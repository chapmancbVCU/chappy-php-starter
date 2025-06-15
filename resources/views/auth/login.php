<?php $this->start('head'); ?>
<?php $this->end(); ?>

<?php $this->start('body'); ?>
<div class="row align-items-center justify-content-center">
    <div class="col-md-6 bg-light p-3">
        <h3 class="text-center">Log In</h3>
        <form class="form" action="<?=route('auth.login')?>" method="post">
            <?= csrf() ?>
            <?= errorBag($this->displayErrors) ?>
            <?= input('text', 'Username', 'username', $this->login->username, ['class' => 'form-control'], ['class' => 'form-group mb-3']); ?>
            <?= input('password', 'Password', 'password', $this->login->password,['class' => 'form-control'], ['class' => 'form-group mb-3']); ?>
            <?= checkboxLabelLeft('Remember Me', 'remember_me', "on", $this->login->getRememberMeChecked(), [], ['class' => 'form-group mb-3']); ?>
            
            <div class="d-flex justify-content-end">
                <div class="flex-grow-1 text-body">Don't have an account? <a href="<?=route('auth.register')?>">Sign Up</a></div>
                <?= submit('Login',['class'=>'btn btn-primary']) ?>
            </div>
        </form>
    </div>
</div>
<?php $this->end(); ?>