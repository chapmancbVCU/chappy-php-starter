<?php $this->setSiteTitle($this->header); ?>

<!-- Head content between these two function calls.  Remove if not needed. -->
<?php $this->start('head'); ?>
<?= loadTinyMCE() ?>
<?php $this->end(); ?>


<!-- Body content between these two function calls. -->
<?php $this->start('body'); ?>
<h1 class="text-center mb-4"><?= $this->header ?></h1>
<div class="row align-items-center justify-content-center">
    <div class="col-md-6 bg-light p-3">
        <form class="form" action="" method="POST" enctype="multipart/form-data">
            <?= errorBag($this->errors) ?>
            <?= csrf() ?>
            <?= textarea(
                "Description",
                "description",
                $this->attachment->description,
                ['class' => 'form-control input-sm'], 
                ['class' => 'form-group mb-3']
            ) ?>

            <?php if($this->attachment->isNew()): ?>
                <?= fileSelector(
                    $this->uploadMessage, 
                    'attachment_name', 
                    ['class' => 'form-control', 'accept' => 'image/gif image/jpeg image/png'], 
                    ['class' => 'form-group mb-3']
                ) ?>
            <?php endif; ?>
            <div class="col-md-12 text-end">
                <a href="<?=route('admindashboard.attachments')?>" class="btn btn-default">Cancel</a>
                <?= submit('Save', ['class' => 'btn btn-primary']) ?>
            </div>
        </form>
    </div>
</div>

<!-- Wait until content is loaded before we initialize script -->
<script>
    initializeTinyMCE('description');
</script>
<?php $this->end(); ?>