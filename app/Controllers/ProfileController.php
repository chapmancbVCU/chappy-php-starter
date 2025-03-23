<?php
namespace App\Controllers;
use Core\Lib\Utilities\Env;
use Core\Lib\FileSystem\Uploads;
use App\Models\{ProfileImages, Users};
use Core\{Controller, Helper, Router, Session};

/**
 * Supports ability to use user profile features and render relevant views.
 */
class ProfileController extends Controller {
    /**
     * Deletes an image associated with a user's profile.
     *
     * @return void
     */
    function deleteImageAction(): void {
        $resp = ['success' => false];
        if($this->request->isPost()) {
            $user = Users::currentUser();
            $id = $this->request->get('image_id');
            $image = ProfileImages::findById($id);
            if($user) {
                ProfileImages::deleteById($image->id);
                $resp = ['success' => true, 'model_id' => $image->id];
            }
        }
        $this->jsonResponse($resp);
    }

    /**
     * Renders edit profile page and handles database updates.
     *
     * @return void
     */
    public function editAction(): void {
        $user = Users::currentUser();
        if(!$user) {
            Session::addMessage('danger', 'You do not have permission to edit this user.');
            Router::redirect('');
        }

        $profileImages = ProfileImages::findByUserId($user->id);
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

            $user->assign($this->request->get(), Users::blackListedFormKeys);
            $user->save();
            if($user->validationPassed()){
                if($uploads) {
                    // Upload Image
                    ProfileImages::uploadProfileImage($user->id, $uploads);
                }
                ProfileImages::updateSortByUserId($user->id, json_decode($_POST['images_sorted']));

                // Redirect
                Router::redirect('profile/index');
            }
        }

        $this->view->profileImages = $profileImages;
        $this->view->displayErrors = $user->getErrorMessages();
        $this->view->user = $user;
        $this->view->render('profile/edit');
    }

    /**
     * Renders profile view for current logged in user.
     *
     * @return void
     */
    public function indexAction(): void {
        $user = Users::currentUser();
        $profileImages = ProfileImages::findByUserId($user->id);
        if(!$user) { Router::redirect(''); }
        $this->view->profileImages = $profileImages;
        $this->view->user = $user;
        $this->view->render('profile/index');
    }

    /**
     * Runs when the object is constructed.
     *
     * @return void
     */
    public function onConstruct(): void{
        $this->view->setLayout('default');
    }
    
    /**
     * Renders change password page for current logged in user.
     *
     * @return void
     */
    public function updatePasswordAction(): void {
        $user = Users::currentUser();

        if(!$user) Router::redirect('');
        if($this->request->isPost()) {
            $this->request->csrfCheck();

            // Verify password and display message if incorrect.
            if($user && !password_verify($this->request->get('current_password'), $user->password)) {
                Session::addMessage('danger', 'There was an error when entering your current password');
                Router::redirect('profile/updatePassword/'.$user->id);
            }
            $user->assign($this->request->get(), Users::blackListedFormKeys);

            // PW mode on for correct validation.
            $user->setChangePassword(true);

            // Allows password matching confirmation.
            $user->confirm = $this->request->get('confirm');
            
            if($user->save()) {
                // PW change mode off.
                $user->setChangePassword(false);
                Session::addMessage('success', 'Password updated!'); 
                Router::redirect('profile/index');
            }
        }

        // PW change mode off and final page setup.
        $user->setChangePassword(false);
        $this->view->displayErrors = $user->getErrorMessages();
        $this->view->user = $user;
        $this->view->postAction = Env::get('APP_DOMAIN', '/') . 'profile' . DS . 'update_password' . DS . $user->id;
        $this->view->render('profile/update_password');
    }
}