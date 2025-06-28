<?php $this->setSiteTitle($this->header); ?>

<!-- Head content between these two function calls.  Remove if not needed. -->
<?php $this->start('head'); ?>
<script src="<?=env('APP_DOMAIN', '/')?>vendor/tinymce/tinymce/tinymce.min.js?v=<?=config('config.version')?>"></script>
<script src='<?=env('APP_DOMAIN', '/')?>resources/js/TinyMCE.js'></script>
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
                ['class' => 'form-control input-sm', 'placeholder' => 'Describe yourself here...'], 
                ['class' => 'form-group mb-3']
            ) ?>

            <?php if($this->attachment->isNew()): ?>
                <?= input(
                    'file', 
                    $this->uploadMessage, 
                    'attachment_name', 
                    '', 
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
    document.addEventListener("DOMContentLoaded", function() {
        initializeTinyMCE('description');
    });
</script>
<?php $this->end(); ?>
