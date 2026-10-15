<?php $this->setSiteTitle($this->header); ?>
<?php $this->start('body'); ?>
<h1 class="text-center"><?= $this->header ?></h1>

<div class="row align-items-center justify-content-center">
    <div class="col-md-6 bg-light p-3">
        <form class="form" action="" method="post">
            <?= csrf() ?>
            <?= errorBag($this->displayErrors) ?>
            <?= text("ACL", 'acl', $this->acl->acl, ['class' => 'form-control input-sm'], ['class' => 'form-group'], $this->displayErrors) ?>
            <div class="col-md-12 text-end pt-3">
                <a href="<?=route('admindashboard.manageACLs')?>" class="btn btn-default">Cancel</a>
                <?= submit('Save',['class'=>'btn btn-primary']) ?>
            </div>
        </form>
    </div>
</div>

<?php $this->end(); ?>