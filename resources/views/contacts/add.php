<?php $this->setSiteTitle("Add A Contact"); ?>
<?php $this->start('body'); ?>
<div class="row align-items-center justify-content-center">
    <div class="col-md-6 bg-light p-3">
        <h2 class="text-center">Add A Contact</h2>
        <hr>
        <?php $this->component('contacts_form'); ?>
    </div>
</div>
<?php $this->end(); ?>