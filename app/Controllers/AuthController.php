<?php
declare(strict_types=1);
namespace App\Controllers;
use Core\Controller;
use App\Models\Users;
use Core\Lib\Auth\LoginResult;
use Core\Models\Login;
use Core\Services\ACLService;
use Core\Models\ProfileImages;
use Core\Services\AuthService;
use Core\Services\LoginService;
use Core\Session;

/**
 * Implements support for our Auth controller.  Functions found in this 
 * class will support tasks related to the user registration and 
 * authentication.
 */
class AuthController extends Controller {
    
    /**
     * Manages login action processes.
     *
     * @return void
     */
    public function loginAction(): void {
        $loginModel = new Login();
        if($this->request->isPost()) {
            $this->request->csrfCheck();
            $loginModel->assign($this->request->get());
            $loginModel->validator();
            if($loginModel->validationPassed()){
                $result = LoginService::attempt($this->request, $loginModel, $_POST['username']);
                self::loginRedirects($result);
            }
        }

        $this->view->login = $loginModel;
        $this->view->displayErrors = $loginModel->getErrorMessages();
        $this->view->render('auth.login');
    }

    /**
     * Handles messaging and redirects for loginAction based on status of LoginResult.
     *
     * @param LoginResult $result The resulting status of a login attempt.
     * @return void
     */
    private function loginRedirects(LoginResult $result) {
        switch($result->status) {
                case LoginResult::SUCCESS:
                    redirect(env('DEFAULT_CONTROLLER'));
                    break;
                case LoginResult::NEEDS_RESET:
                    redirect('auth.resetPassword', [$result->user->id]);
                    break;
                case LoginResult::INACTIVE:
                    flashMessage(Session::DANGER, 'Account is currently inactive');
                    redirect('auth.login');
                    break;
                case LoginResult::LOCKED:
                    flashMessage(Session::DANGER, 'Your account has been locked due to too many failed login attempts.');
                    redirect('auth.login');
                    break;
                case LoginResult::INVALID:
                    // Error message already added to $loginModel by the service.
                    break;
            }
    }

    /**
     * Manages logout action for a user.  It checks if a user is currently 
     * logged.  No matter of the result, the user gets redirected to the 
     * login screen.
     *
     * @return void
     */
    public function logoutAction(): void {
        if(!$this->request->isPost()) {
            flashMessage(Session::DANGER, 'You must logout through menu');
            redirect('home');
        }

        $this->request->csrfCheck();
        AuthService::logout();
        redirect('auth.login');
    }

    /**
     * Manages actions related to user registration.
     *
     * @return void
     */
    public function registerAction(): void {
        $user = new Users();
        if($this->request->isPost()) {
            $this->request->csrfCheck();
            
            // Handle file upload
            $uploads = AuthService::profileImageUpload($user);

            $user->assign($this->request->get());
            $user->confirm = AuthService::confirm($this->request);
            $user->acl = ACLService::setAclAtRegistration();
            $user->save();
            if($user->validationPassed()) {
                if($uploads) {
                    ProfileImages::uploadProfileImage((int)$user->id, $uploads);
                }
                redirect('auth.login');
            }
        }

        $this->view->user = $user;
        $this->view->displayErrors = $user->getErrorMessages();
        $this->view->render('auth.register');
    }

    /**
     * Supports ability to reset passwords when a user attempts to 
     * login when account is locked.
     *
     * @param int $id The id of the user whose password we want to reset.
     * @return void
     */
    public function resetPasswordAction(int $id): void {
        $user = Users::findById((int)$id);
        $user->password = "";
        
        if(!$user) redirect('');
        if($this->request->isPost()) {
            $this->request->csrfCheck();
            AuthService::passwordReset($this->request, $user);
        }

        $user->setChangePassword(false);
        $this->view->displayErrors = $user->getErrorMessages();
        $this->view->user = $user;
        $this->view->postAction = route('auth.resetPassword', [$user->id]);
        $this->view->render('auth.reset_password');
    }
}