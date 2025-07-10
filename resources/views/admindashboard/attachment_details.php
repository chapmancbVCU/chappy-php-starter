<?php use Core\Models\EmailAttachments; ?>
<?php use Core\Lib\Utilities\DateTime; ?>
<?php $this->setSiteTitle("Details for " . $this->attachment->attachment_name); ?>

<!-- Head content between these two function calls.  Remove if not needed. -->
<?php $this->start('head'); ?>

<?php $this->end(); ?>


<!-- Body content between these two function calls. -->
<?php $this->start('body'); ?>
<div class="row align-items-center justify-content-center">
    <div class="col-md-6 bg-light p-3">
        <a href="<?=route('admindashboard.attachments')?>" class="btn btn-sm btn-secondary">Back</a>
        <h2 class="text-center"><?=$this->attachment->attachment_name?></h2>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Created at: </strong><?= DateTime::timeAgo($this->attachment->created_at) ?></p>
                <p><strong>Updated at: </strong><?= DateTime::timeAgo($this->attachment->updated_at) ?></p>
                <p><strong>Size: </strong><?= EmailAttachments::formatBytes($this->attachment->size) ?></p>
                <p><strong>MIME Type: </strong><?= $this->attachment->mime_type ?></p>
                <p><strong>Uploader: </strong><?= $this->uploader->username ?></p>
                <p><strong>Description: </strong><?= htmlspecialchars_decode(stripslashes($this->attachment->description)) ?></p>
                <p><strong>Preview: </strong><a href="<?= route('admindashboard.preview', [$this->attachment->id]) ?>" target="_blank"><?= $this->attachment->attachment_name ?></a></p>
            </div>
        </div>
    </div>
</div>
<?php $this->end(); ?>
