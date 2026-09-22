
<!-- <?= radio("Email", 'contact', 'email', false, ['class' => 'form-group mb-3']) ?>
<?= radio("Phone", 'contact', 'phone', false, ['class' => 'form-group mb-3']) ?> -->

<!-- <?= radioGroup('users', ['User1', 'User2'], $this->users, 'username', [''], ['class' => 'form-group mb-3']) ?> -->


<?= dataListDate('Choose a browser from the list', 'browser', 'browsers', '', ['2026-09-21', '2026-09-22', '2026-09-23'], ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3']) ?>



<?= text("First Name", 'fname', $this->user->fname, ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3']) ?>
<?= text("Last Name", 'lname', $this->user->lname, ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3']) ?>
<?= email("Email", 'email', $this->user->email, ['class' => 'form-control input-sm'], ['class' => 'form-group mb-3']) ?>

<?= textarea("Description", 
    'description', 
    $this->user->description, 
    ['class' => 'form-control input-sm', 'placeholder' => 'Describe yourself here...'], 
    ['class' => 'form-group mb-3']); 
?>