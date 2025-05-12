<?php
use Core\FormHelper;
use Core\Lib\Utilities\Env;
use Core\Lib\Utilities\Config;
?>
<?php $this->setSiteTitle("Edit Details for ".$this->user->username); ?>
<?php $this->start('head') ?>
<link rel="stylesheet" href="<?=Env::get('APP_DOMAIN', '/')?>resources/css/profileImage.css?v=<?=Config::get('config.version')?>" media="screen" title="no title" charset="utf-8">
<script src="<?=Env::get('APP_DOMAIN', '/')?>vendor/tinymce/tinymce/tinymce.min.js?v=<?=Config::get('config.version')?>"></script>
<script src='<?=Env::get('APP_DOMAIN', '/')?>resources/js/TinyMCE.js'></script>
<script type="text/javascript" src="<?=Env::get('APP_DOMAIN', '/')?>node_modules/jquery-ui/dist/jquery-ui.min.js"></script>
<?php $this->end() ?>

<?php $this->start('body'); ?>
<div class="row align-items-center justify-content-center mb-5">
    <div class="col-md-6 bg-light p-3">
        <h1 class="text-center">Edit Details for <?=$this->user->username?></h1>
        <hr>
        <form class="form" action="" method="POST" enctype="multipart/form-data">
            <?= FormHelper::csrfInput() ?>
            <?= FormHelper::hidden('images_sorted', '') ?>
            <?= FormHelper::displayErrors($this->displayErrors) ?>
            
            <!-- Primary profile details -->
            <?= $this->component('edit_profile_details'); ?>
            
            <!-- Manage profile images section -->
            <?= FormHelper::inputBlock('file', "Upload Profile Image (Optional)", 'profileImage', '', ['class' => 'form-control', 'accept' => 'image/gif image/jpeg image/png'], ['class' => 'form-group mb-3']) ?>
            <?= $this->component('manage_profile_images') ?>

            <div class="col-md-12 text-end">
                <a href="<?=Env::get('APP_DOMAIN', '/')?>profile" class="btn btn-default">Cancel</a>
                <?= FormHelper::submitTag('Update', ['class' => 'btn btn-primary'])  ?>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        initializeTinyMCE('description');
    });
</script>
<?php $this->end(); ?>