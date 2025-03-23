<h1 style="font-size: 50px; text-align: center;">Views</h1>

## Table of contents
1. [Overview](#overview)
2. [Getting Data From Controller](#controller)
3. [Creating Views](#make-views)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
View files are responsible for displaying what the user interacts with.  The view files in this framework are plain php files and at this moment does not require a template engine such as Twig or Blade.  A template view file is shown below:

```php
<?php $this->setSiteTitle("My title here"); ?>

<!-- Head content between these two function calls.  Remove if not needed. -->
<?php $this->start('head'); ?>

<?php $this->end(); ?>


<!-- Body content between these two function calls. -->
<?php $this->start('body'); ?>

<?php $this->end(); ?>
```

This template is separated into 3 sections:
1. Set Title Section - What is displayed in the browser tab.
2. Head - You can add additional information to the head element here.
3. Body - Anything in this section gets displayed to the user.
<br>

## 2. Getting Data From Controller <a id="controller"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
You can pass values assigned to the View object in the controller to any view.  Let's look at the configure the view section of the ContactsController's indexAction function.

```php
// Configure the view
$this->view->contacts = $contacts;
$this->view->pagination = Pagination::pagination($page, $pagination->totalPages());
$this->view->render('contacts/index');
```

After setting up $contacts and Pagination we can assign them to variables for the View object as shown in the first two lines.  To get Pagination to work within our view we just use the following statement in our view as shown below:

```php
<?= $this->pagination ?>
```

Since each view file has access to an instance of the View class all the user has to do is type `$this->${variable_name}` to access any variables we setup in the controller's action functions.
<br>

## 3. Creating Views <a id="make-views"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Views can be created by two different methods.  You can right click on the directory under `resources/views/` and create a view this way.  You will be responsible for adding anything needed to setup content between the opening and closing tags for the head and body elements.

You can also create a view by running the `make:view` command.  An example is shown below:

```sh
php console make:view foo.foo
```

The argument format assumes the following, 

```
<directory_name>.<view_name>,
``` 

such that `directory_name` is the location of the view file and `view_name` is the name of the actual file without the .php extension.  If the directory does not exist the user will be asked if they want it to be created before continuing.