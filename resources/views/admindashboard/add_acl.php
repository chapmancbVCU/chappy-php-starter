<?php use Core\Helper; ?>
<?php $this->setSiteTitle("Add ACL"); ?>
<?php $this->start('body'); ?>
<h1 class="text-center">Add ACL</h1>

<div class="row align-items-center justify-content-center">
    <div class="col-md-6 bg-light p-3">
        <?php $this->component('acl_form'); ?>
    </div>
</div>

<?php $this->end(); ?>