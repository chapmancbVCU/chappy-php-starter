<?php
namespace App\Controllers;
use Core\Lib\Utilities\Arr;
use Core\Lib\Utilities\Env;
use Core\Lib\Pagination\Pagination;
use Core\Controller;
use App\Models\{ACL, ProfileImages, Users};

/**
 * Implements support for our Admindashboard controller.
 */
class AdmindashboardController extends Controller {

    /**
     * Renders add acl view and adds ACL to acl table.
     *
     * @return void
     */
    public function addAclAction(): void {
        $acl = new ACL();
        if($this->request->isPost()) {
            $this->request->csrfCheck();
            $acl->assign($this->request->get());
            if($acl->save()) {
                flashMessage('success', 'ACL added!');
                redirect('admindashboard.manageAcls');
            }
        }

        $this->view->acl = $acl;
        $this->view->displayErrors = $acl->getErrorMessages();
        $this->view->postAction = route('admindashboard.addAcl');
        $this->view->render('admindashboard/add_acl');
    }

    /**
     * Performs delete action on a user.
     *
     * @param integer $id The id for the user we want to delete.
     * @return void
     */
    public function deleteAction(int $id): void {
        $user = Users::findById((int)$id);

        if($this->request->isPost()) {
            $this->request->csrfCheck();
            if($user && $user->acl != '["Admin"]') {
                ProfileImages::deleteImages($id, false);
                $user->delete();
                flashMessage('success', 'User has been deleted.');
            } else {
                flashMessage('danger', 'Cannot delete Admin user!');
            }
        }
        redirect('admindashboard');
    }

    /**
     * Deletes ACL from acl table.
     *
     * @param int $id The id for the ACL we want to delete.
     * @return void
     */
    function deleteAclAction(): void {
        if($this->request->isPost()) {
            $this->request->csrfCheck();
            $acl = ACL::findById((int)$this->request->get('id'));
    
            // Get users so we can get number using acl and update later.
            $users = $acl->isAssignedToUsers();
            if(is_countable($users) > 0) {
                flashMessage('info', "Cannot delete ". $acl->acl. ", assigned to one or more users.");
            }
            if($acl) {
                $acl->delete();
                flashMessage('success', 'ACL has been deleted');
            } else {
                flashMessage('danger', 'You do not have permission to perform this action.');
            }
        }
        redirect('admindashboard.manageAcls');
    }

    /**
     * Deletes an image associated with a user's profile.
     *
     * @return void
     */
    public function deleteImageAction(): void {
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
     * Presents information about a particular user's profile.
     *
     * @param int $id The id of the user whose details we want to view.
     * @return void
     */
    public function detailsAction($id): void {
        $user = Users::findById((int)$id);
        $profileImage = ProfileImages::findCurrentProfileImage($user->id);
        $this->view->profileImage = $profileImage;
        $this->view->user = $user;
        $this->view->render('admindashboard/details');
    }

    /**
     * Supports ability to edit ACLs not assigned to a user through a web form.
     *
     * @param int $id The id of the ACL we want to modify.
     * @return void
     */
    public function editAclAction($id): void {
        $acl = ACL::findById((int)$id);
        if (!$acl) {
            flashMessage('danger', "ACL not found.");
            redirect('admindashboard.manageAcls');
        }
    
        
        // Check if ACL is assigned to any users and restrict access
        if ($acl->isAssignedToUsers()) {
            flashMessage('danger', "Access denied. '{$acl->acl}' is assigned to one or more users and cannot be edited.");
            redirect('admindashboard.manageAcls');
        }
    
        if ($this->request->isPost()) {
            $this->request->csrfCheck();
            $acl->assign($this->request->get(), ACL::blackList);
    
            if ($acl->save()) {
                flashMessage('info', "ACL Name updated.");
                redirect('admindashboard.manageAcls');
            } else {
                flashMessage('danger', implode(" ", $acl->getErrorMessages()));
            }
        }
    
        $this->view->displayErrors = $acl->getErrorMessages();
        $this->view->acl = $acl;
        $this->view->postAction = route('admindashboard.editAcl', [$acl->id]);
        $this->view->render('admindashboard/edit_acl');
    }
    

    /**
     * Supports ability for administrators to edit user profiles.
     *
     * @param int $id The id of the user whose profile we want to modify.
     * @return void
     */
    public function editAction($id): void {
        $user = Users::findById((int)$id);
    
        if (!$user) {
            flashMessage('danger', 'You do not have permission to edit this user.');
            redirect('');
        }
    
        $this->view->user = $user;
    
        // Fetch all available ACLs
        $acls = ACL::getOptionsForForm();
        $this->view->acls = $acls;
    
        // Decode stored ACL JSON string into an array
        $userAcls = json_decode($user->acl, true);
        $userAcls = Users::aclToArray($userAcls);

        $this->view->userAcls = Arr::map($userAcls, 'strval'); // Ensure values are strings
        $profileImages = ProfileImages::findByUserId($user->id);
    
        if ($this->request->isPost()) {
            $this->request->csrfCheck();
            $user->assign($this->request->get(), Users::blackListedFormKeys);
    
            // Handle ACL updates from checkboxes
            $newAcls = $_POST['acls'] ?? [];
            $newAcls = Users::aclToArray($newAcls);
            Users::manageAcls($acls, $user, $newAcls, $userAcls);
            
            // Save updated ACLs
            $user->acl = json_encode($newAcls);
            
            if ($user->save()) {
                $sortOrder = json_decode($_POST['images_sorted']);
                ProfileImages::updateSortByUserId($user->id, $sortOrder);
                redirect('admindashboard.details', [$user->id]);
            }
        }
    
        $this->view->profileImages = $profileImages;
        $this->view->displayErrors = $user->getErrorMessages();
        $this->view->postAction = route('admindashboard.edit', [$user->id]);
        $this->view->render('admindashboard/edit');
    }

    /** 
     * The default action for this controller.  It performs rendering of this 
     * site's admin dashboard page.
     * 
     * @return void
     */
    public function indexAction(): void {
        // Determine current page
        $page = Pagination::currentPage($this->request);
        $totalUsers = Users::findTotal([
            'conditions' => 'id != ?',
            'bind' => [Users::currentUser()->id]
        ]);

        $pagination = new Pagination($page, 10, $totalUsers);
        $users = Users::find($pagination->paginationParams(
            'id != ?',
            [Users::currentUser()->id],
            'created_at DESC'
        ));

        $this->view->pagination = Pagination::pagination($page, $pagination->totalPages());
        $this->view->users = $users;
        $this->view->render('admindashboard/index');
    }

    public function manageACLsAction(): void {
        $acls = ACL::getACLs();
        $usedAcls = [];
        $unUsedAcls = [];
        foreach($acls as $acl) {
            if($acl->isAssignedToUsers()) {
                Arr::push($usedAcls, $acl);
            } else {
                Arr::push($unUsedAcls, $acl);
            }
        }

        $this->view->usedAcls = $usedAcls;
        $this->view->unUsedAcls = $unUsedAcls;
        $this->view->render('admindashboard/manage_acls');
    }

    /**
     * Runs when the object is constructed.
     *
     * @return void
     */
    public function onConstruct(): void {
        $this->view->setLayout('admin');
    }

    /**
     * Support ability to toggle on or off the reset password flag for a 
     * particular user.
     *
     * @param int $id The id of the user whose reset password flag we want to 
     * modify.
     * @return void
     */
    public function setResetPasswordAction($id) {
        $user = Users::findById((int)$id);
        
        if($this->request->isPost()) {
            $this->request->csrfCheck();
            $user->assign($this->request->get(), Users::blackListedFormKeys);
            $user->reset_password = ($this->request->get('reset_password') == 'on') ? 1 : 0;
            if($user->save()) {
                redirect('admindashboard.details', [$user->id]);
            }
        }

        $this->view->user = $user;
        $this->view->displayErrors = $user->getErrorMessages();
        $this->view->postAction = route('admindashboard.setResetPassword', [$user->id]);
        $this->view->render('admindashboard/set_reset_password');
    }

    /**
     * Sets active status for a particular user.  The administrator can 
     * toggle the setting on or off using a web form.
     *
     * @param int $id The id of the user we want to activate or inactivate.
     * @return void
     */
    public function setStatusAction($id) {
        $user = Users::findById((int)$id);

        if($this->request->isPost()) {
            $this->request->csrfCheck();
            $user->assign($this->request->get(), Users::blackListedFormKeys);
            $user->inactive = ($this->request->get('inactive') == 'on') ? 1 : 0;
            $user->login_attempts = ($user->inactive == 0) ? 0 : $user->login_attempts;
            if($user->save()) {
                redirect('admindashboard.details', [$user->id]);
            }
        }

        $this->view->user = $user;
        $this->view->displayErrors = $user->getErrorMessages();
        $this->view->postAction = route('admindashboard.setStatus', [$user->id]);
        $this->view->render('admindashboard/set_account_status');
    }
}