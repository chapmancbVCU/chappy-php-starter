<?php use Core\FormHelper; ?>
<form class="form" action=<?=$this->postAction?> method="post">
    <?= FormHelper::csrfInput() ?>
    <?= FormHelper::inputBlock('text', "ACL", 'acl', $this->acl->acl, ['class' => 'form-control input-sm'], ['class' => 'form-group'], $this->displayErrors) ?>
    <div class="col-md-12 text-end pt-3">
        <a href="<?=env('APP_DOMAIN', '/')?>admindashboard/manageACLs" class="btn btn-default">Cancel</a>
        <?= FormHelper::submitTag('Update',['class'=>'btn btn-primary']) ?>
    </div>
</form>