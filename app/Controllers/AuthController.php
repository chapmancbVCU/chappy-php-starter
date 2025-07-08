<?php
namespace App\Controllers;
use Core\Controller;
use Core\Models\Login;
use core\Auth\AuthService;
use Core\Lib\Utilities\Env;
use Core\Lib\Logging\Logger;
use Core\Lib\FileSystem\Uploads;
use App\Models\{ProfileImages, Users};
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
            $uploads = Uploads::handleUpload(
                $_FILES['profileImage'],
                ProfileImages::class,
                ROOT . DS,
                "5mb",
                $user,
                'profileImage'
            );

            $user->assign($this->request->get());
            $user->confirm = $this->request->get('confirm');
            $user->acl = Users::setAclAtRegistration();
            $user->save();
            if($user->validationPassed()) {
                if($uploads) {
                    ProfileImages::uploadProfileImage($user->id, $uploads);
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
    public function resetPasswordAction($id): void {
        $user = Users::findById((int)$id);
        $user->password = "";
        
        if(!$user) redirect('');
        if($this->request->isPost()) {
            $this->request->csrfCheck();
            $user->assign($this->request->get(), Users::blackListedFormKeys);
            
            // PW mode on for correct validation.
            $user->setChangePassword(true);
            
            // Allows password matching confirmation.
            $user->confirm = $this->request->get('confirm');
            
            if($user->save()) {
                // PW change mode off.
                $user->reset_password = 0;
                $user->setChangePassword(false);    
                redirect('auth.login');
            }
        }

        $user->setChangePassword(false);
        $this->view->displayErrors = $user->getErrorMessages();
        $this->view->user = $user;
        $this->view->postAction = route('auth.resetPassword', [$user->id]);
        $this->view->render('auth.reset_password');
    }
}