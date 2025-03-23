<?php
use Core\FormHelper;
use Core\Helper;
?>
<?php $this->setSiteTitle('Reset Password'); ?>
<?php $this->start('body'); ?>

<div class="row align-items-center justify-content-center">
    <div class="col-md-6 bg-light p-3">
        <h3 class="text-center">Reset Password</h3>
        <?php $this->component('password_complexity_requirements'); ?>
        <form class="form" action="" method="post">
            <?= FormHelper::csrfInput() ?>
            <?= FormHelper::displayErrors($this->displayErrors) ?>
            <?= FormHelper::inputBlock('password', "Password", 'password', $this->user->password, ['class' => 'form-control input-sm'], ['class' => 'form-group']) ?>
            <?= FormHelper::inputBlock('password', "Confirm Password", 'confirm', $this->user->confirm, ['class' => 'form-control input-sm'], ['class' => 'form-group']) ?>

            <?= FormHelper::submitTag('Set Password',['class'=>'btn btn-primary']) ?>
        </form>
    </div>
</div>
<?php $this->end(); ?>