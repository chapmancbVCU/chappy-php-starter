<?php
namespace App\Controllers;
use Core\Controller;
use App\Models\Users;
use Core\Models\Login;
use Core\Services\ACLService;
use Core\Models\ProfileImages;
use Core\Services\AuthService;
use Core\Lib\Events\EventManager;
use Core\Lib\Events\UserRegistered;
use Core\Services\NotificationService;
use Core\Services\UserService;

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
                $loginModel = AuthService::login($this->request, $loginModel, $_POST['username']);
            }
        }
        $this->view->login = $loginModel;
        $this->view->displayErrors = $loginModel->getErrorMessages();
        $this->view->render('auth.login');
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
            flashMessage('danger', 'You must logout through menu');
            redirect('home');
        }

        $this->request->csrfCheck();
        AuthService::logout();
        redirect('auth.login');
    }

    /**
     * Runs when the object is constructed.
     *
     * @return void
     */
    public function onConstruct(): void {
        $this->view->setLayout('default');
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
                //UserService::queueWelcomeMailer((int)$user->id);
                if($uploads) {
                    ProfileImages::uploadProfileImage($user->id, $uploads);
                }

                EventManager::dispatcher()->dispatch(new UserRegistered($user, false));
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
    public function resetPasswordAction($id): void {
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