<?php $this->setSiteTitle($this->contact->displayName()); ?>
<?php $this->start('body'); ?>
<div class="row align-items-center justify-content-center">
    <div class="col-md-6 bg-light p-3">
        <a href="<?APP_DOMAIN?>contacts" class="btn btn-sm btn-secondary">Back</a>
        <h2 class="text-center"><?=$this->contact->displayName()?></h2>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Email: </strong><?=$this->contact->email?></p>
                <p><strong>Cell Phone: </strong><?=$this->contact->cell_phone?></p>
                <p><strong>Home Phone: </strong><?=$this->contact->home_phone?></p>
                <p><strong>Work Phone: </strong><?=$this->contact->work_phone?></p>
            </div>
            <div class="col-md-6">
                <?=$this->contact->displayAddressLabel()?>
            </div>
        </div>    
    </div>
    
</div>
<?php $this->end(); ?>