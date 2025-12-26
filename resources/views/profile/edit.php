<?php $this->setSiteTitle("Edit Details for ".$this->user->username); ?>

<?php $this->start('head') ?>
<link rel="stylesheet" href="<?=env('APP_DOMAIN', '/')?>resources/css/profileImage.css?v=<?=config('config.version')?>" media="screen" title="no title" charset="utf-8">
<script src="<?=env('APP_DOMAIN', '/')?>node_modules/jquery/dist/jquery.min.js"></script>
<script src="<?=env('APP_DOMAIN', '/')?>vendor/tinymce/tinymce/tinymce.min.js?v=<?=config('config.version')?>"></script>
<script src='<?=env('APP_DOMAIN', '/')?>resources/js/TinyMCE.js'></script>
<script type="text/javascript" src="<?=env('APP_DOMAIN', '/')?>node_modules/jquery-ui/dist/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=env('APP_DOMAIN', '/')?>node_modules/jquery-ui/ui/widgets/sortable.js"></script>
<?php $this->end() ?>

<?php $this->start('body'); ?>
<div class="row align-items-center justify-content-center mb-5">
    <div class="col-md-6 bg-light p-3">
        <h1 class="text-center">Edit Details for <?=$this->user->username?></h1>
        <hr>
        <form class="form" action="" method="POST" enctype="multipart/form-data">
            <?= csrf() ?>
            <?= hidden('images_sorted', '') ?>
            <?= errorBag($this->displayErrors) ?>
            
            <!-- Primary profile details -->
            <?= $this->component('edit_profile_details'); ?>
            
            <!-- Manage profile images section -->
            <?= input('file', "Upload Profile Image (Optional)", 'profileImage', '', ['class' => 'form-control', 'accept' => 'image/gif image/jpeg image/png'], ['class' => 'form-group mb-3']) ?>
            <?= $this->component('manage_profile_images') ?>

            <div class="col-md-12 text-end">
                <a href="<?=route('profile')?>" class="btn btn-default">Cancel</a>
                <?= submit('Update', ['class' => 'btn btn-primary'])  ?>
            </div>
        </form>
    </div>
</div>

<!-- Wait until content is loaded before we initialize script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        initializeTinyMCE('description');
    });
</script>
<?php $this->end(); ?>