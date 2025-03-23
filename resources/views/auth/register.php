<?php
use Core\FormHelper;
use Core\Lib\Utilities\Env;
?>
<?php $this->setSiteTitle("Register Here!"); ?>
<?php $this->start('head') ?>
  <script src="<?=Env::get('APP_DOMAIN', '/')?>vendor/tinymce/tinymce/tinymce.min.js"></script>
  <script src="<?=Env::get('APP_DOMAIN', '/')?>resources/js/profileDescriptionTinyMCE.js"></script>
<?php $this->end() ?>

<?php $this->start('body'); ?>
<div class="row align-items-center justify-content-center">
    <div class="col-md-6 bg-light p-3">
        <h3 class="text-center">Register Here!</h3>
        <hr>
        <form class="form" action="" method="post" enctype="multipart/form-data">
            <?= FormHelper::csrfInput() ?>
            <?= FormHelper::inputBlock('text', "First Name", 'fname', $this->newUser->fname, ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3'], $this->displayErrors) ?>
            <?= FormHelper::inputBlock('text', "Last Name", 'lname', $this->newUser->lname, ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3'], $this->displayErrors) ?>
            <?= FormHelper::inputBlock('text', "Email", 'email', $this->newUser->email, ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3'], $this->displayErrors) ?>
            <?= FormHelper::inputBlock('text', "User name", 'username', $this->newUser->username, ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3'], $this->displayErrors) ?>
            <?= FormHelper::textAreaBlock("About Me", 
                'description', 
                $this->newUser->description, 
                ['class' => 'form-control input-sm', 'placeholder' => 'Describe yourself here...', 'rows' => '4'], 
                ['class' => 'form-group mb-3'], $this->displayErrors); 
            ?>
            <?= FormHelper::inputBlock('file', "Upload Profile Image (Optional)", 'profileImage', '', ['class' => 'form-control', 'accept' => 'image/png image/jpeg image/png'], ['class' => 'form-group mb-3'], $this->displayErrors) ?>
            <?php $this->component('password_complexity_requirements'); ?>
            <?= FormHelper::inputBlock('password', "Password", 'password', $this->newUser->password, ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3'], $this->displayErrors) ?>
            <?= FormHelper::inputBlock('password', "Confirm Password", 'confirm', $this->newUser->confirm, ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3'], $this->displayErrors) ?>
            <?= FormHelper::submitBlock('Register', ['class' => 'btn btn-large btn-primary'], ['class' => 'text-end'])  ?>
        </form>
    </div>
</div>
<?php $this->end(); ?>