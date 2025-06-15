<form class="form" action=<?=$this->postAction?> method="post">
    <?= csrf() ?>
    <?= input('text', "ACL", 'acl', $this->acl->acl, ['class' => 'form-control input-sm'], ['class' => 'form-group'], $this->displayErrors) ?>
    <div class="col-md-12 text-end pt-3">
        <a href="<?=route('admindashboard.manageACLs')?>" class="btn btn-default">Cancel</a>
        <?= submit('Update',['class'=>'btn btn-primary']) ?>
    </div>
</form>