<h1 style="font-size: 50px; text-align: center;">Pagination</h1>

## Table of contents
1. [Overview](#overview)
2. [Setup](#controller)
    * A. [Import Pagination Class](#import-class)
    * B. [Get Current Page](#current-page)
    * C. [Get Total Records](#total-records)
    * D. [Retrieve Paginated Records](#paginated-records)
    * E. [Configure The View](#configure-view)
    * F. [Adding Pagination To View](#view)
3. [Final View](#final-view)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Pagination is supported with the use of a Pagination class and built in Bootstrap 5 support.  An example of this is shown below in Figure 1.

<div style="text-align: center;">
  <img src="assets/seeded-contacts.png" alt="Pagination example">
  <p style="font-style: italic;">Figure 1 - Pagination example</p>
</div>
<br>

## 2. Setup <a id="setup"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The following are instructions for setting up Pagination within your controller.  We will use the `indexAction` of the `ContactsController`.

#### A. Import Pagination Class <a id="import-class">

```php
use Core\Lib\Pagination\Pagination;
```
<br>

#### B. Get Current Page <a id="current-page">

```php
$page = Pagination::currentPage($this->request);
```
<br>

#### C. Get Total Records <a id="total-records">
We want to obtain the list of all of the records.  In this case the number of contacts for this user when creating instance of Pagination class.  The condition is the `user_id` and we bind with the current user's id since we want only contacts associated with this user.

```php
$pagination = new Pagination($page, 10, Contacts::findTotal([
    'conditions' => 'user_id = ?',
    'bind'       => [$this->currentUser->id]
]));
```
<br>

#### D. Retrieve Paginated Records <a id="paginated-records">
We will now proceed to get paginated records using base model's find method.  In this step we use the paginationParams function from the Pagination class to build our query.

```php
$contacts = Contacts::find($pagination->paginationParams(
    'user_id = ?',                  // Conditions
    [$this->currentUser->id],       // Bind
    'lname, fname')                 // Order
);
```
<br>

#### E. Configure The View <a id="configure-view">
Configure the view by setting the results of the pagination function to the `$this->view->pagination` variable.  This variable contains the data for rendering the page links.
```php
$this->view->pagination = Pagination::pagination($page, $pagination->totalPages());
```

Putting everything together here is the complete indexAction function:

```php
public function indexAction(): void {
    // Determine current page
    $page = Pagination::currentPage($this->request);

    // Get the total number of contacts for the user
    $pagination = new Pagination($page, 10, Contacts::findTotal([
        'conditions' => 'user_id = ?',
        'bind'       => [$this->currentUser->id]
    ]));
    
    // Retrieve paginated contacts using the base model’s find method
    $contacts = Contacts::find($pagination->paginationParams(
        'user_id = ?', 
        [$this->currentUser->id], 
        'lname, fname')
    );

    // Configure the view
    $this->view->contacts = $contacts;
    $this->view->pagination = Pagination::pagination($page, $pagination->totalPages());
    $this->view->render('contacts/index');
}
```
<br>

#### F. Adding Pagination To View <a id="view">
Within your view add the following line, in this cases, right after the closing tag for the table element:

```php
<?= $this->pagination ?>
```
<br>

## 3. Final View <a id="final-view"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Putting everything together we can demonstrate what the final view looks like.  By placing the `$this->pagination` variable below the closing tag for the table the links are located where they need to be.

```php
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
```