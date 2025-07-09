<?php
namespace App\Controllers;
use Core\Controller;
use Core\Models\ACL;
use core\Auth\ACLService;
use core\Auth\AuthService;
use Core\Lib\Utilities\Arr;
use Core\Lib\Mail\Attachments;
use Core\Lib\FileSystem\Uploads;
use Core\Models\EmailAttachments;
use Core\Lib\Pagination\Pagination;
use App\Models\{ProfileImages, Users};
use Core\Lib\Mail\Services\AttachmentService;

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
        $this->view->render('admindashboard.add_acl');
    }

    /**
     * Displays list of attachments.
     *
     * @return void
     */
    public function attachmentsAction(): void {
        $attachments = EmailAttachments::find();
        $this->view->attachments = $attachments;
        $this->view->render('admindashboard.attachments');
    }

    /**
     * Displays details for a particular E-mail attachment.
     *
     * @param int $id Primary key for attachment record.
     * @return void
     */
    public function attachmentDetailsAction(int $id): void {
        $attachment = EmailAttachments::findById((int)$id);
        $this->view->uploader = Users::findById($attachment->user_id);
        $this->view->attachment = $attachment;
        $this->view->render('admindashboard.attachment_details');
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
    public function deleteAclAction(): void {
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
     * Deletes an attachment and sets deleted field in table to 1.
     *
     * @param int $id The primary key for the attachment's database 
     * record.
     * @return void
     */
    public function deleteAttachmentAction(int $id): void {
        $attachment = EmailAttachments::findById($id);
        AttachmentService::deleteAttachment($attachment);
        redirect('admindashboard.attachments');
    }

    /**
     * Deletes an image associated with a user's profile.
     *
     * @return void
     */
    public function deleteImageAction(): void {
        $resp = ['success' => false];
        if($this->request->isPost()) {
            $user = AuthService::currentUser();
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
        $this->view->render('admindashboard.details');
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
        $this->view->render('admindashboard.edit_acl');
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
            ACLService::manageAcls($acls, $user, $newAcls, $userAcls);
            
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
        $this->view->render('admindashboard.edit');
    }

    /**
     * Creates or edits the details of an existing E-mail attachment.
     *
     * @param int|string $id The primary key for the record associated with an 
     * E-mail attachment.
     * @return void
     */
    public function editAttachmentsAction(int|string $id): void {
        $attachment = ($id == 'new') ? new EmailAttachments() : 
            EmailAttachments::findById((int)$id);

        if($this->request->isPost()) {
            $this->request->csrfCheck();
            
            if($attachment->isNew()) {
                $uploads = Uploads::handleUpload(
                    $_FILES['attachment_name'],
                    EmailAttachments::class,
                    ROOT.DS,
                    '15mb',
                    $attachment,
                    'attachment_name'
                );
            }

            $attachment->description = $this->request->get('description');
            $attachment->attachment_name = ($attachment->isNew()) ? htmlspecialchars($_FILES['attachment_name']['name']) :
                $attachment->attachment_name;
            $attachment->user_id = AuthService::currentUser()->id;
            $attachment->save();
            if($attachment->validationPassed()) {
                if($uploads) {
                    $file = $uploads->getFiles();
                    $path = EmailAttachments::$_uploadPath . DS;
                    $uploadName = $uploads->generateUploadFilename($file[0]['name']);
                    $attachment->name =$uploadName;
                    $attachment->path = $path . $uploadName;
                    $attachment->size = $file[0]['size'];
                    $attachment->mime_type = Attachments::mime(pathinfo($file[0]['name'], PATHINFO_EXTENSION));
                    $uploads->upload($path, $uploadName, $file[0]['tmp_name']);
                    $attachment->save();
                }
                redirect('admindashboard.attachments');
            }
        }

        $this->view->attachment = $attachment;
        $this->view->errors = $attachment->getErrorMessages();
        $this->view->uploadMessage = $attachment->isNew() ? "Upload file" : "Update Attachment";
        $this->view->header = $attachment->isNew() ? "Added Attachment" : "Edit Attachment";
        $this->view->render('admindashboard/attachments_form');
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
            'bind' => [AuthService::currentUser()->id]
        ]);

        $pagination = new Pagination($page, 10, $totalUsers);
        $users = Users::find($pagination->paginationParams(
            'id != ?',
            [AuthService::currentUser()->id],
            'created_at DESC'
        ));

        $this->view->pagination = Pagination::pagination($page, $pagination->totalPages());
        $this->view->users = $users;
        $this->view->render('admindashboard.index');
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
        $this->view->render('admindashboard.manage_acls');
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
     * Previews an attachment
     *
     * @param int $id The primary key for the record of the attachment.
     * @return void
     */
    public function previewAction(int $id): void {
        $attachment = EmailAttachments::findById($id);
        if (!$attachment || !file_exists(CHAPPY_BASE_PATH . DS . $attachment->path)) {
            http_response_code(404);
            exit('File not found.');
        }

        header('Content-Type: ' . $attachment->mime_type);
        header('Content-Disposition: inline; filename="' . $attachment->attachment_name . '"');
        readfile($attachment->path);
        exit;
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
        $this->view->render('admindashboard.set_reset_password');
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
        $this->view->render('admindashboard.set_account_status');
    }
}