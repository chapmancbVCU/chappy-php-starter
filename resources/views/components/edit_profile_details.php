<?php use Core\FormHelper; ?>

<?= FormHelper::inputBlock('text', "First Name", 'fname', $this->user->fname, ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3']) ?>
<?= FormHelper::inputBlock('text', "Last Name", 'lname', $this->user->lname, ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3']) ?>
<?= email("Email", 'email', $this->user->email, ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3']) ?>

<?= FormHelper::textAreaBlock("Description", 
    'description', 
    $this->user->description, 
    ['class' => 'form-control input-sm', 'placeholder' => 'Describe yourself here...'], 
    ['class' => 'form-group mb-3']); 
?>