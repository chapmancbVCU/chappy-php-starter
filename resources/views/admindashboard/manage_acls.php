<?php use Core\Lib\Utilities\DateTime; ?>
<?php use Core\Lib\Utilities\Env; ?>
<?php $this->setSiteTitle("Manage ACLs"); ?>
<?php $this->start('body'); ?>
<h1 class="text-center">Manage ACLs
    <a href="<?=Env::get('APP_DOMAIN', '/')?>admindashboard/addAcl/" class="btn btn-primary btn-sm ml-5">
        <i class="fa fa-plus"></i> Add ACL
    </a>
</h1>
<table class="table table-striped  table-bordered table-hover mb-5">
    <thead>
        <th class="text-center">ACL</th>
        <th class="text-center">Created</th>
        <th class="text-center">Updated</th>
        <th></th>
    </thead>
    <tbody>
        <?php foreach($this->unUsedAcls as $acl): ?>
            <tr>
                <?php if($acl->acl !== "Admin"): ?>
                    <td class="text-center w-50"><?= $acl->acl ?></td>
                    <td class="text-center"><?= DateTime::timeAgo($acl->created_at) ?></td>
                    <td class="text-center"><?= DateTime::timeAgo($acl->updated_at) ?></td>
                    <td class="text-center">
                        <a href="<?=Env::get('APP_DOMAIN', '/')?>admindashboard/editAcl/<?=$acl->id?>" class="btn btn-info btn-sm">
                            <i class="fa fa-edit"></i> Edit ACL
                        </a>
                        <a href="<?=Env::get('APP_DOMAIN', '/')?>admindashboard/deleteAcl/<?=$acl->id?>" class="btn btn-danger btn-sm" onclick="if(!confirm('Are you sure?')){return false;}">
                            <i class="fa fa-trash"></i> Delete
                        </a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2 class="text-center">ACLs In Use</h2>
<table class="table table-striped  table-bordered table-hover">
    <thead>
        <th class="text-center">ACL</th>
        <th class="text-center">Created</th>
        <th class="text-center">Updated</th>
    </thead>
    <tbody>
        <?php foreach($this->usedAcls as $acl): ?>
            <tr>
                <td class="text-center w-50"><?= $acl->acl ?></td>
                <td class="text-center"><?= DateTime::timeAgo($acl->created_at) ?></td>
                <td class="text-center"><?= DateTime::timeAgo($acl->updated_at) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php $this->end(); ?>