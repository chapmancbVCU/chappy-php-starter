<?php use Core\FormHelper; ?>

<?= FormHelper::csrfInput() ?>
<?= FormHelper::hidden('images_sorted', '') ?>
<?= FormHelper::displayErrors($this->displayErrors) ?>
<?= FormHelper::inputBlock('text', "First Name", 'fname', $this->user->fname, ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3']) ?>
<?= FormHelper::inputBlock('text', "Last Name", 'lname', $this->user->lname, ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3']) ?>
<?= FormHelper::emailBlock("Email", 'email', $this->user->email, ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3']) ?>

<?= FormHelper::textAreaBlock("Description", 
    'description', 
    $this->user->description, 
    ['class' => 'form-control input-sm', 'placeholder' => 'User description'], 
    ['class' => 'form-group mb-3']); 
?>