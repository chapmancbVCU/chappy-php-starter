<?php use Core\Helper; ?>
<?php use Core\FormHelper; ?>
<?php $this->setSiteTitle("Edit ACL - " . $this->acl->acl); ?>
<?php $this->start('body'); ?>
<h1 class="text-center">Edit ACL - <?= $this->acl->acl ?></h1>

<div class="row align-items-center justify-content-center">
    <div class="col-md-6 bg-light p-3">
        <?php $this->component('acl_form'); ?>
    </div>
</div>
<?php $this->end(); ?>