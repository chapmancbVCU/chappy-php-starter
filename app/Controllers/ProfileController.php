<?php
namespace App\Controllers;
use Core\Controller;
use App\Models\Users;
use Core\Models\ProfileImages;
use Core\Services\AuthService;
use Core\Services\UserService;
use Core\Lib\Http\JsonResponse;

/**
 * Supports ability to use user profile features and render relevant views.
 */
class ProfileController extends Controller {
    use JsonResponse;
    
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

        // Toggle comments to use React.js
        $this->view->profileImages = $profileImages;
        $this->view->displayErrors = $user->getErrorMessages();
        $this->view->user = $user;
        $this->view->render('profile.edit');

        // $props = [
        //     'user' => $user,
        //     'errors' => $user->getErrorMessages(),
        //     'profileImages' => $profileImages,
        // ];
        // $this->view->renderJsx('profile.Edit', $props);
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
        
        // Toggle comments to use React.js
        $this->view->profileImages = $profileImages;
        $this->view->user = $user;
        $this->view->render('profile.index');

        // $this->view->props = [
        //     'user' => $user, 
        //     'profileImage' => $profileImages[0]
        // ];
        // $this->view->renderJSX('profile.Index');
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
            if(UserService::updatePassword($user, $this->request)) {
                flashMessage('success', 'Password updated!'); 
                redirect('profile.index');
            }
        }

        // PW change mode off and final page setup.
        $user->setChangePassword(false);

        // Toggle comments to use React.js
        $this->view->displayErrors = $user->getErrorMessages();
        $this->view->user = $user;
        $this->view->render('profile.update_password');

        // $props = [
        //     'user' => $user,
        //     'errors' => $user->getErrorMessages()
        // ];
        // $this->view->renderJsx('profile.UpdatePassword', $props);
    }
}