<?php
use Core\FormHelper;
use Core\Lib\Utilities\Env;
use Core\Lib\Utilities\Config;
?>
<?php $this->setSiteTitle("Register Here!"); ?>
<?php $this->start('head') ?>
<script src="<?=Env::get('APP_DOMAIN', '/')?>vendor/tinymce/tinymce/tinymce.min.js?v=<?=Config::get('config.version')?>"></script>
<script src='<?=Env::get('APP_DOMAIN', '/')?>resources/js/descriptionTinyMCE.js'></script>
<?php $this->end() ?>

<?php $this->start('body'); ?>
<div class="row align-items-center justify-content-center">
    <div class="col-md-6 bg-light p-3">
        <h3 class="text-center">Register Here!</h3>
        <hr>
        <form class="form" action="" method="post" enctype="multipart/form-data">
            <?= FormHelper::csrfInput() ?>
            <?= FormHelper::displayErrors($this->displayErrors) ?>
            <?= FormHelper::inputBlock('text', "User name", 'username', $this->user->username, ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3']) ?>
            
            <!-- Primary profile details -->
            <?= $this->component('edit_profile_details') ?>
            
            <?= FormHelper::inputBlock('file', "Upload Profile Image (Optional)", 'profileImage', '', ['class' => 'form-control', 'accept' => 'image/png image/jpeg image/png'], ['class' => 'form-group mb-3']) ?>
            
            <?= $this->component('password_complexity_requirements'); ?>

            <?= FormHelper::inputBlock('password', "Password", 'password', $this->user->password, ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3']) ?>
            <?= FormHelper::inputBlock('password', "Confirm Password", 'confirm', $this->user->confirm, ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3']) ?>
            <?= FormHelper::submitBlock('Register', ['class' => 'btn btn-large btn-primary'], ['class' => 'text-end'])  ?>
        </form>
    </div>
</div>
<?php $this->end(); ?>