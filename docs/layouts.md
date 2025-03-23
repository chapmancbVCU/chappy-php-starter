<h1 style="font-size: 50px; text-align: center;">Layouts</h1>

## Table of contents
1. [Overview](#overview)
2. [Layouts](#layouts)
3. [Building Your Own Layout](#build-layout)
4. [Menus](#menus)
5. [Menu ACLs](#menu-acls)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The layouts feature supports the ability to present a consistent user experience across views in the framework.  We natively support Bootstrap 5 for the styling.

Layouts are supported by layout files that are located at `resources/views/layouts`, menus that can be found at `resources/views/components`, and menu_acl json files within the `app` directory.
<br>

## 2. Layouts <a id="layouts"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Let's look at the default layout.

```php
<?php 
use Core\Session;
use Core\FormHelper;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?=$this->siteTitle()?></title>
    <link rel="icon" href="<?=Env::get('APP_DOMAIN', '/')?>public/noun-mvc-5340614.png">
    <?php if (Env::get('APP_ENV', 'production')): ?>
        <script type="module" src="http://localhost:5173/@vite/client"></script>
        <script type="module" src="<?= vite('resources/js/app.js') ?>"></script>
    <?php else: ?>
      <!-- Production: Include compiled assets -->
      <link rel="stylesheet" href="<?= vite('resources/css/app.css') ?>">
      <script type="module" src="<?= vite('resources/js/app.js') ?>"></script>
    <?php endif; ?>
    <link rel="stylesheet" href="<?=Env::get('APP_DOMAIN', '/')?>node_modules/bootstrap/dist/css/bootstrap.min.css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="<?=Env::get('APP_DOMAIN', '/')?>resources/css/alerts/alertMsg.min.css?v=<?=Env::get('VERSION')?>" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="<?=Env::get('APP_DOMAIN', '/')?>resources/css/font-awesome-4.7.0/font-awesome.min.css" media="screen" title="no title" charset="utf-8">
    <script src="<?=Env::get('APP_DOMAIN', '/')?>resources/js/jQuery-3.7.1/jQuery-3.7.1.min.js"></script>
    <script src="<?=Env::get('APP_DOMAIN', '/')?>node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="<?=Env::get('APP_DOMAIN', '/')?>node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?=Env::get('APP_DOMAIN', '/')?>resources/js/alerts/alertMsg.min.js?v=<?=Env::get('VERSION')?>"></script>
    <?= $this->content('head'); ?>

  </head>
  <body class="d-flex flex-column min-vh-100">
    <?php $this->component('main_menu') ?>
    <div class="container-fluid" style="min-height:calc(100% - 125px);">
      <?= Session::displayMessage() ?>
      <?= $this->content('body'); ?>
    </div>
    <?php //$this->component(footer.php'); ?>
    
  </body>
</html>
```

Within the `head` element the first thing after the required meta tags you will see a call to the siteTitle() function.  This sets the title of the current page on a browser tab.  

The if statement that is in this section is where Vite is used for asset bundling.  During development, when you save a view file, any changes are automatically presented to the user without have to refresh the page.

The last function call, `$this->content('head)`  is where additional information for `head` element is injected into your view.  More information about the `content` function can be found in the [View](view) page.

The `body` element contains a call to the `component` function for rendering menus.  For the admin layout we use the `admin_menu` instead and the framework is smart enough to display this menu for pages that uses the admin layout.

The next function call displays session or sometimes called flash messages depending on the framework.  Finally, we have a call to the content function for displaying `body` content.
<br>

## 3. Building Your Own Layout <a id="build-layout"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
User have the ability to create their own layout.  You can either create just a layout.  We will create a layout called Foo.

```sh
php console make:layout Foo
```

This tells the framework to create a new layout using the default main_menu  The `make:layout` also accepts `--menu` and `-menu-acl` as arguments for generating the menu file and the menu_acl json file.  Using these arguments will create new menu and menu_acl files.  More about this in the next two sections.
<br>

## 4. Menus <a id="menu"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
You can create custom menus to be used with your layouts.  There are two ways to accomplish this task.  You can create a menu using the following command:

```sh
php console make:menu Foo
```

This will create a new menu called foo that you can customize.  You can also create a menu using the `make:layout` commands as shown below:

```sh
php console make:layout Foo --menu
```

This will create a new layout where the menu name is set when the component function as called.  Note, this operation will fail if you type anything after `--menu` such as `--menu=afafaf`.  This is demonstrated below:

```php
$this->component('foo_menu')
```

No matter what method you use the same menu will be created as shown below:

```php
use Core\Router;
use Core\Helper;
$profileImage = Helper::getProfileImage();
$menu = Router::getMenu('foo_menu_acl');
$userMenu = Router::getMenu('user_menu');
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top mb-5">
  <!-- Brand and toggle get grouped for better mobile display -->
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_menu" aria-controls="main_menu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="<?=Env::get('APP_DOMAIN', '/')?>home"><?=Env::get('MENU_BRAND', 'My Brand')?></a>

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse" id="main_menu">
    <ul class="navbar-nav me-auto">
      <?= Helper::buildMenuListItems($menu); ?>
    </ul>
    <ul class="navbar-nav me-2 align-items-center"> <!-- Align items vertically -->
      <?= Helper::buildMenuListItems($userMenu, "dropdown-menu-end"); ?>
      <li class="nav-item">
          <a class="nav-link p-0" href="<?=Env::get('APP_DOMAIN', '/')?>profile">
              <?php if ($profileImage != null): ?>
                  <img class="rounded-circle profile-img ms-2"
                      style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #ddd; transition: opacity 0.3s;"
                      src="<?=Env::get('APP_DOMAIN', '/') . $profileImage->url?>"
                      alt="Profile Picture">
              <?php endif; ?>
          </a>
      </li>
    </ul>
  </div><!-- /.navbar-collapse -->
</nav>
```

Notice that the parameter near the top for the `getMenu` function call is set to `foo_menu_acl`.  That is the name of the menu_acl file that is used to configure your menu.  You can also edit other parts of the menu.  If you inspect the admin_menu you will notice there are slight differences from the main_menu file.
<br>

## 5. Menu ACLs <a id="menu-acls"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The menu_acl json file is tied to the menu file created above.  The menu_acl json file is used to configure the contents of the navigation bar.  With this file you can define links and menus.  Let's take a look at the `admin_menu_acl.json` file.

```json
{
    "Home" : "home",
    "Admin" : {
        "Admin Dashboard" : "admindashboard/index",
        "Manage ACLS" : "admindashboard/manageACLs"
    }
}
```

Anything on the left side of a colon is the label for your link or menu.  Home is simple.  The right side text, `home`, is the name of the controller.  The admin section is a little more complicated.  `Admin` is the name of the menu and anything inside this json object are link labels, just like Home, along with the name of the view directory and the name of the action after the forward slash.  If you write `home/index` the same result will occur when clicking on the link since index actions are always the default action.  To summarize, the right side looks the text in the call to the render function inside your controller.c

You have two options for creating menu_acl json files.  The first is by using the following command:

```sh
php console make:acl admin
```

This is the command we used to generate the `admin_menu_acl.json` file shown above.  You can also use the `make:layout` command as well which is shown below:

```sh
php console make:layout Admin --menu-acl
```

Running the command above will create the Admin layout along with your acl file.