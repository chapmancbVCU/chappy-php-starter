<?php $this->setSiteTitle("Attachments"); ?>

<!-- Head content between these two function calls.  Remove if not needed. -->
<?php $this->start('head'); ?>

<?php $this->end(); ?>


<!-- Body content between these two function calls. -->
<?php $this->start('body'); ?>
<h1 class="text-center mb-4">Attachments</h1>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <th>Original Name</th>
        <th>MIME Type</th>
        <th>Size</th>
        <th></th>
    </thead>
    <tbody>
        <?php foreach($this->attachments as $attachment): ?>
            <td><?$attachment->attachment_name?></td>
            <td><?$attachment->mime_type?></td>
            <td><?$attachment->size?></td>
            <td></td>
        <?php endforeach; ?>
    </tbody>
</table>
<?php $this->end(); ?>
