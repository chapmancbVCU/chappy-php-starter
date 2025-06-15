<?php $this->setSiteTitle("Reset Password for ".$this->user->username); ?>
<?php $this->start('body') ?>

<h1 class="text-center">Reset Password for <?=$this->user->username?></h1>

<div class="row align-items-center justify-content-center">
    <div class="col-md-3 bg-light p-3">
        <form class="form" action=<?=$this->postAction?> method="post">
            <?= csrf() ?>
            <?= checkboxLabelLeft('Select to confirm reset password', 'reset_password', "on", $this->user->isResetPWChecked(), [], ['class' => 'form-group mb-3'], $this->displayErrors); ?>
            
            <div class="col-md-12 text-end">
                <a href="<?=route('admindashboard.details', [$this->user->id])?>" class="btn btn-default">Cancel</a>
                <?= submit('Reset Password',['class'=>'btn btn-primary']) ?>
            </div>
        </form>
    </div>
</div>
<?php $this->end(); ?>