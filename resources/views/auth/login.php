<?php
use Core\FormHelper;
use Core\Lib\Utilities\Env;
?>
<?php $this->start('head'); ?>
<?php $this->end(); ?>

<?php $this->start('body'); ?>
<div class="row align-items-center justify-content-center">
    <div class="col-md-6 bg-light p-3">
        <h3 class="text-center">Log In</h3>
        <form class="form" action="<?=Env::get('APP_DOMAIN', '/')?>auth/login" method="post">
            <?= FormHelper::csrfInput() ?>
            <?= FormHelper::displayErrors($this->displayErrors) ?>
            <?= FormHelper::inputBlock('text', 'Username', 'username', $this->login->username, ['class' => 'form-control'], ['class' => 'form-group mb-3']); ?>
            <?= FormHelper::inputBlock('password', 'Password', 'password', $this->login->password,['class' => 'form-control'], ['class' => 'form-group mb-3']); ?>
            <?= FormHelper::checkboxBlockLabelLeft('Remember Me', 'remember_me', "on", $this->login->getRememberMeChecked(), [], ['class' => 'form-group mb-3']); ?>
            
            <div class="d-flex justify-content-end">
                <div class="flex-grow-1 text-body">Don't have an account? <a href="<?=Env::get('APP_DOMAIN', '/')?>auth/register">Sign Up</a></div>
                <?= FormHelper::submitTag('Login',['class'=>'btn btn-primary']) ?>
            </div>
        </form>
    </div>
</div>
<?php $this->end(); ?>