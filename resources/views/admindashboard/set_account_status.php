<?php $this->setSiteTitle("Set Status for ".$this->user->username); ?>
<?php $this->start('body') ?>

<h1 class="text-center">Set Status for <?=$this->user->username?></h1>

<div class="row align-items-center justify-content-center">
    <div class="col-md-3 bg-light p-3">
        <form class="form" action=<?=$this->postAction?> method="post">
            <?= csrf() ?>
            <?= checkboxLabelLeft('Select to activate/deactivate account', 'inactive', "on", $this->user->isInactiveChecked(), [], ['class' => 'form-group mb-3'], $this->displayErrors); ?>
            <div class="col-md-12 text-end">
                <a href="<?=route('admindashboard.details', [$this->user->id])?>" class="btn btn-default">Cancel</a>
                <?= submit('Activate / Deactivate',['class'=>'btn btn-primary']) ?>
            </div>
        </form>
    </div>
</div>
<?php $this->end(); ?>