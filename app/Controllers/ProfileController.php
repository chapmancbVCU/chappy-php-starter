<?php
namespace App\Controllers;
use Core\Controller;
use App\Models\Users;
use Core\Models\ProfileImages;
use core\Services\AuthService;
use core\Services\UserService;

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
            $resp = UserService::deleteProfileImage($this->request);
        }
        $this->jsonResponse($resp);
    }

    /**
     * Renders edit profile page and handles database updates.
     *
     * @return void
     */
    public function editAction(): void {
        $user = AuthService::currentUser();
        UserService::ensureAuthenticatedUser($user);

        $profileImages = ProfileImages::findByUserId($user->id);
        if($this->request->isPost()) {
            $this->request->csrfCheck();
            $uploads = AuthService::profileImageUpload($user);
            $user->assign($this->request->get(), Users::blackListedFormKeys);
            $user->save();
            if($user->validationPassed()){
                UserService::handleProfileImages($user, $uploads, $_POST['images_sorted']);
                redirect('profile.index');
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
        $user = AuthService::currentUser();
        UserService::ensureAuthenticatedUser($user);
        $profileImages = ProfileImages::findByUserId($user->id);
        
        $this->view->profileImages = $profileImages;
        $this->view->user = $user;
        $this->view->render('profile.index');
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
        $user = AuthService::currentUser();
        UserService::ensureAuthenticatedUser($user);
        
        if($this->request->isPost()) {
            $this->request->csrfCheck();

            // Verify password and display message if incorrect.
            if(!UserService::updatePassword($user, $this->request)) {
                flashMessage('danger', 'There was an error when entering your current password');
                redirect('profile.updatePassword', [$user->id]);
            }

            flashMessage('success', 'Password updated!'); 
            redirect('profile.index');
        }

        // PW change mode off and final page setup.
        $user->setChangePassword(false);
        $this->view->displayErrors = $user->getErrorMessages();
        $this->view->user = $user;
        $this->view->postAction = route('profile.updatePassword', [$user->id]);
        $this->view->render('profile.update_password');
    }
}