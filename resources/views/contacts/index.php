<?php use Core\Lib\Utilities\Env; ?>
<?php $this->setSiteTitle("My Contacts"); ?>

<?php $this->start('body'); ?>
<h2 class="text-center">My Contacts</h2>
<table class="table table-striped  table-bordered table-hover">
    <thead>
        <th>Name</th>
        <th>Email</th>
        <th>Cell Phone</th>
        <th>Home Phone</th>
        <th>Work Phone</th>
        <th></th>
    </thead>
    <tbody>
        <?php foreach($this->contacts as $contact): ?>
            <tr>
                <td>
                    <a href="<?=Env::get('APP_DOMAIN', '/')?>contacts/details/<?=$contact->id?>">
                        <?= $contact->displayName(); ?>
                    </a>
                </td>
                <td><?= $contact->email ?></td>
                <td><?= $contact->cell_phone ?></td>
                <td><?= $contact->home_phone ?></td>
                <td><?= $contact->work_phone ?></td>
                <td class="text-center">
                    <a href="<?=Env::get('APP_DOMAIN', '/')?>contacts/edit/<?=$contact->id?>" class="btn btn-info btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <a href="<?=Env::get('APP_DOMAIN', '/')?>contacts/delete/<?=$contact->id?>" class="btn btn-danger btn-sm" onclick="if(!confirm('Are you sure?')){return false;}">
                        <i class="fa fa-trash"></i> Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->pagination ?>
<?php $this->end(); ?>