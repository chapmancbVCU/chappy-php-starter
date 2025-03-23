<?php use Core\Lib\Utilities\Env; ?>
<?php $this->setSiteTitle("Profile Details for ".$this->user->username); ?>
<?php $this->start('body'); ?>
<h1 class="text-center">Profile Details for <?=$this->user->username?></h1>

<div class="col align-items-center justify-content-center mx-auto my-3 w-50">
    <?php if($this->profileImages != null):?>
        <img src="<?=Env::get('APP_DOMAIN', '/').$this->profileImages[0]->url?>"
            class="img-thumbnail mx-auto my-5 d-block w-50 rounded border border-primary shadow-lg">
        </img>
    <?php endif; ?>
    <table class="table table-striped  table-bordered table-hover bg-light my-5">
        <tbody>
            <tr>
                <th class="text-center">First Name</th>
                <td class="text-center"><?=$this->user->fname?></td>
            </tr>
            <tr>
                <th class="text-center">Last Name</th>
                <td class="text-center"><?=$this->user->lname?></td>
            </tr>
            <tr>
                <th class="text-center">E-mail</th>
                <td class="text-center"><?=$this->user->email?></td>
            </tr>
            <tr>
                <th class="text-center">ACL</th>
                <td class="text-center"><?=$this->user->acl?></td>
            </tr>
            <?php if($this->user->description): ?>
                <tr>
                    <th class="text-center" colspan="2">Description</th>
                </tr>
                <tr>
                    <td class="p-4" colspan="2"><?=htmlspecialchars_decode(stripslashes($this->user->description));?></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td class="text-center" colspan="2">No description</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="mb-5 d-flex justify-content-around">
        <a href="<?=Env::get('APP_DOMAIN', '/')?>profile/edit/<?=$this->user->id?>" class="btn btn-info btn-sm mx-2 mb-3">
            <i class="fa fa-edit"></i> Edit User Profile
        </a>
        <a href="<?=Env::get('APP_DOMAIN', '/')?>profile/updatePassword/<?=$this->user->id?>" class="btn btn-danger btn-sm mx-2 mb-3">
            <i class="fa fa-key"></i> Update Password
        </a>
    </div>
    
</div>
<?php $this->end(); ?>