<?php
use Core\FormHelper;
use Core\Helper;
use Core\Lib\Utilities\Env;
?>
<?php $this->setSiteTitle("Edit Details for ".$this->user->username); ?>
<?php $this->start('head') ?>
    <link rel="stylesheet" href="<?=Env::get('APP_DOMAIN', '/')?>resources/css/profileImage.css?v=<?=Env::get('VERSION')?>" media="screen" title="no title" charset="utf-8">
    <script src='<?=Env::get('APP_DOMAIN', '/')?>vendor/tinymce/tinymce/tinymce.min.js'></script>
    <script src='<?=Env::get('APP_DOMAIN', '/')?>resources/js/profileDescriptionTinyMCE.js'></script>
    <script type="text/javascript" src="<?=Env::get('APP_DOMAIN', '/')?>node_modules/jquery-ui/ui/widgets/sortable.js"></script>
    <script type="text/javascript" src="<?=Env::get('APP_DOMAIN', '/')?>node_modules/jquery-ui/dist/jquery-ui.min.js"></script>
<?php $this->end() ?>

<?php $this->start('body'); ?>
<div class="row align-items-center justify-content-center">
    <div class="col-md-6 bg-light p-3">
        <h1 class="text-center">Edit Details for <?=$this->user->username?></h1>
        <hr>
        <form class="form" action=<?=$this->postAction?> method="post">
            <!-- Primary profile details -->
            <?= $this->component('edit_profile_details'); ?>

            <!-- ACL Management Section -->
            <div class="form-group mb-3">
                <label>Manage ACLs:</label>
                <?php foreach ($this->acls as $aclKey => $aclName): ?>
                    <?= FormHelper::checkboxBlockLabelRight($aclName, "acls[]", $aclName, $this->user->hasAcl($aclName), [], ['class' => 'form-check'], $this->displayErrors, 
                    ); ?>
                <?php endforeach; ?>
            </div>
            
            <!-- Manage profile images section -->
            <?= $this->component('manage_profile_images'); ?>

            <div class="col-md-12 text-end">
                <a href="<?=Env::get('APP_DOMAIN', '/')?>admindashboard/details/<?=$this->user->id?>" class="btn btn-default">Cancel</a>
                <?= FormHelper::submitTag('Update', ['class' => 'btn btn-primary'])  ?>
            </div>
        </form>
    </div>
</div>



<?php $this->end(); ?>