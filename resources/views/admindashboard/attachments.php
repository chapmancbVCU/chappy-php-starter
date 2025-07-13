<?php use Core\Models\EmailAttachments; ?>
<?php $this->setSiteTitle("Attachments"); ?>

<!-- Head content between these two function calls.  Remove if not needed. -->
<?php $this->start('head'); ?>

<?php $this->end(); ?>


<!-- Body content between these two function calls. -->
<?php $this->start('body'); ?>
<h1 class="text-center mb-4">Attachments
    <a href="<?=route('admindashboard.editAttachments', ['new'])?>" class="btn btn-primary btn-sm me-5">
        <i class="fa fa-plus"></i> Add Attachment
    </a>
</h1>
<div class="d-flex justify-content-center">
    <table class="w-75 table table-striped table-bordered table-hover table-striped table-sm">
        <thead>
            <th>Original Name</th>
            <th>Uploader</th>
            <th>Size</th>
            <th></th>
        </thead>
        <tbody>
            <?php foreach($this->attachments as $attachment): ?>
                <tr>
                    <td>
                        <a href="<?=route('admindashboard.attachmentDetails', [$attachment->id])?>"><?=$attachment->attachment_name?></a>
                    </td>
                    <td><?=EmailAttachments::uploadUsername($attachment->user_id)?></td>
                    <td><?=EmailAttachments::formatBytes($attachment->size)?></td>
                    <td class="text-end">
                        <a href="<?=route('admindashboard.editAttachments', [$attachment->id])?>"
                            class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i> Edit
                        </a>
                        <form method="POST"
                            action="<?=route('admindashboard.deleteAttachment', [$attachment->id])?>"
                            class="d-inline-block"
                            onsubmit="return confirm('Are you sure you want to delete this attachment?');">
                            <?=hidden('id', $attachment->id)?>
                            <?=csrf()?>
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php $this->end(); ?>
