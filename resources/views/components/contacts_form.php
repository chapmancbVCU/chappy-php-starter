<?php use Core\FormHelper; ?>
<?php use Core\Lib\Utilities\Env; ?>
<form class="form mb-2" action=<?=$this->postAction?> method="post">
    <?= FormHelper::displayErrors($this->displayErrors) ?>
    <?= FormHelper::csrfInput() ?>
    <div class="row d-flex">
        <?= FormHelper::inputBlock('text', 
            'First Name', 
            'fname', 
            $this->contact->fname, 
            ['class' => 'form-control'], 
            ['class' => 'form-group col-md-6']
        );?>
        <?= FormHelper::inputBlock('text', 
            'Last Name', 
            'lname', 
            $this->contact->lname, 
            ['class' => 'form-control'], 
            ['class' => 'form-group col-md-6']
        );?>
    </div>

    <div class="row">
        <?= FormHelper::inputBlock('text', 
            'Address', 
            'address', 
            $this->contact->address, 
            ['class' => 'form-control'], 
            ['class' => 'form-group col-md-6']
        );?>
        <?= FormHelper::inputBlock('text', 
            'Address 2', 
            'address2', 
            $this->contact->address2, 
            ['class' => 'form-control'], 
            ['class' => 'form-group col-md-6']
        );?>
    </div>

    <div class="row">
        <?= FormHelper::inputBlock('text', 
            'City', 
            'city', 
            $this->contact->city, 
            ['class' => 'form-control'], 
            ['class' => 'form-group col-md-5']
        );?>
        <?= FormHelper::inputBlock('text', 
            'State', 
            'state', 
            $this->contact->state, 
            ['class' => 'form-control', 'pattern' => '[A-Z]*', 'placeholder' => 'ex: VA'], 
            ['class' => 'form-group col-md-3']
        );?>
        <?= FormHelper::inputBlock('text', 
            'Zip', 
            'zip', 
            $this->contact->zip, 
            ['class' => 'form-control', 'pattern' => '[0-9]*', 'placeholder' => 'ex: 90210'], 
            ['class' => 'form-group col-md-4']
        );?>
    </div>

    <div class="row">
        <?= FormHelper::emailBlock('Email', 
            'email', 
            $this->contact->email, 
            ['class' => 'form-control'], 
            ['class' => 'form-group col-md-6']
        );?>
        <?= FormHelper::telBlock('cell', 
            'Cell Phone', 
            'cell_phone', 
            $this->contact->cell_phone, 
            ['class' => 'form-control'], 
            ['class' => 'form-group col-md-6'],
            "phe"
        );?>
    </div>

    <div class="row">
        <?= FormHelper::telBlock('home', 
            'Home Phone', 
            'home_phone', 
            $this->contact->home_phone,
            ['class' => 'form-control', 'placeholder' => 'EX: 123-456-7890'], 
            ['class' => 'form-group col-md-6'],
            "ep"
        );?>
        <?= FormHelper::telBlock('work', 
            'Work Phone', 
            'work_phone', 
            $this->contact->work_phone, 
            ['class' => 'form-control'], 
            ['class' => 'form-group col-md-6'],
            "a"
        );?>
    </div>

    <div class="row">
        <?= FormHelper::inputBlock('text', 
                'Country', 
                'country', 
                $this->contact->country, 
                ['class' => 'form-control'], 
                ['class' => 'form-group col-md-12']
        );?>
    </div>

    <div class="col-md-12 text-end mt-3">
        <a href="<?=Env::get('APP_DOMAIN', '/')?>contacts" class="btn btn-default">Cancel</a>
        <?= FormHelper::submitTag('Save', ['class' => 'btn btn-primary']) ?>
    </div>
    <script src="<?=Env::get('APP_DOMAIN', '/')?>resources/js/frontEndPhoneNumberValidate.js"></script>
</form>