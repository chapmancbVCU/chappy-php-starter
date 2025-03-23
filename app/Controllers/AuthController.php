<?php
namespace App\Controllers;
use Core\Lib\Utilities\Env;
use Core\Lib\Logging\Logger;
use Core\Lib\FileSystem\Uploads;
use Core\Lib\FileSystem\UploadProfileImage;
use App\Models\{Login, ProfileImages, Users};
use Core\{Controller, Helper, Router, Session};
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
            // form validation
            $this->request->csrfCheck();
            $loginModel->assign($this->request->get());
            $loginModel->validator();
            if($loginModel->validationPassed()){
                $user = Users::findByUsername($_POST['username']);
                if($user && password_verify($this->request->get('password'), $user->password)) {
                    if($user->reset_password == 1) {
                        Router::redirect('auth/resetPassword/'.$user->id);
                    }
                    if($user->inactive == 1) {
                        Session::addMessage('danger', 'Account is currently inactive');
                        Router::redirect('auth/login');
                    } 
                    $remember = $loginModel->getRememberMeChecked();
                    $user->login_attempts = 0;
                    $user->save();
                    $user->login($remember);
                    Router::redirect('home');
                }  else {
                    if($user) {
                        $loginModel = Users::loginAttempts($user, $loginModel);
                    }
                    else {
                        $loginModel->addErrorMessage('username','There is an error with your username or password');
                        Logger::log('User failed to log in', 'warning');
                    }
                }
            }
        }
        $this->view->login = $loginModel;
        $this->view->displayErrors = $loginModel->getErrorMessages();
        $this->view->render('auth/login');
    }

    /**
     * Manages logout action for a user.  It checks if a user is currently 
     * logged.  No matter of the result, the user gets redirected to the 
     * login screen.
     *
     * @return void
     */
    public function logoutAction(): void {
        if(Users::currentUser()) {
            Users::currentUser()->logout();
        }
        Router::redirect(('auth/login'));
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
        $newUser = new Users();
        if($this->request->isPost()) {
            $this->request->csrfCheck();
            
            // Handle file upload
            $uploads = Uploads::handleUpload(
                $_FILES['profileImage'],
                ProfileImages::class,
                ROOT . DS,
                "5mb",
                $newUser,
                'profileImage'
            );

            $newUser->assign($this->request->get());
            $newUser->confirm = $this->request->get('confirm');
            $newUser->acl = Users::setAclAtRegistration();
            $newUser->save();
            if($newUser->validationPassed()) {
                if($uploads) {
                    ProfileImages::uploadProfileImage($newUser->id, $uploads);
                }
                Router::redirect('auth/login');
            }
        }

        $this->view->newUser = $newUser;
        $this->view->displayErrors = $newUser->getErrorMessages();
        $this->view->render('auth/register');
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
        
        if(!$user) Router::redirect('');
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
                Router::redirect('auth/login');
            }
        }

        $user->setChangePassword(false);
        $this->view->displayErrors = $user->getErrorMessages();
        $this->view->user = $user;
        $this->view->postAction = Env::get('APP_DOMAIN', '/') . 'auth' . DS . 'reset_password' . DS . $user->id;
        $this->view->render('auth/reset_password');
    }
}