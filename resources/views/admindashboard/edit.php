<?php $this->setSiteTitle("Edit Details for ".$this->user->username); ?>
<?php $this->start('head') ?>
<?= profileImageSort() ?>
<?= loadTinyMCE() ?>
<?php $this->end() ?>

<?php $this->start('body'); ?>
<div class="row align-items-center justify-content-center">
    <div class="col-md-6 bg-light p-3">
        <h1 class="text-center">Edit Details for <?=$this->user->username?></h1>
        <hr>
        <form class="form" action=<?=$this->postAction?> method="post">
            <?= csrf() ?>
            <?= hidden('images_sorted', '') ?>
            <?= errorBag($this->displayErrors) ?>
            
            <!-- Primary profile details -->
            <?= $this->component('edit_profile_details', true); ?>

            <!-- ACL Management Section -->
            <div class="form-group mb-3">
                <label>Manage ACLs:</label>
                <?= checkboxGroup(
                    'acls',
                    $this->acls,              // [value => label] map of all ACLs
                    $this->user->getAcls(),   // the set of ACLs this user currently has
                    [],
                    ['class' => 'form-check'],
                    $this->displayErrors
                ); ?>
            </div>
            
            <!-- Manage profile images section -->
            <?= $this->component('manage_profile_images', true); ?>

            <div class="col-md-12 text-end">
                <a href="<?=route('admindashboard.details', [$this->user->id])?>" class="btn btn-default">Cancel</a>
                <?= submit('Update', ['class' => 'btn btn-primary'])  ?>
            </div>
        </form>
    </div>
</div>

<!-- Wait until content is loaded before we initialize script -->
<script>
    initializeTinyMCE('description');
</script>

<?php $this->end(); ?>