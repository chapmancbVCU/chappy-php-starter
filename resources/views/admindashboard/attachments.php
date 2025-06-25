<?php $this->setSiteTitle("Attachments"); ?>

<!-- Head content between these two function calls.  Remove if not needed. -->
<?php $this->start('head'); ?>

<?php $this->end(); ?>


<!-- Body content between these two function calls. -->
<?php $this->start('body'); ?>
<h1 class="text-center mb-4">Attachments</h1>
<table class="table table-striped table-bordered table-hover table-striped table-sm">
    <thead>
        <th>Original Name</th>
        <th>MIME Type</th>
        <th>Size</th>
        <th></th>
    </thead>
    <tbody>
        <?php foreach($this->attachments as $attachment): ?>
            <tr>
                <td><?=$attachment->attachment_name?></td>
                <td><?=$attachment->mime_type?></td>
                <td><?=$attachment->size?></td>
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
<?php $this->end(); ?>
