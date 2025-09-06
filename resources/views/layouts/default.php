<?php use Core\Session; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?=$this->siteTitle()?></title>
    <link rel="icon" href="<?= env('APP_DOMAIN', '/')?>public/noun-mvc-5340614.png">
    <?php if (env('APP_ENV', 'production') === 'local'): ?>
        <script type="module" src="http://localhost:5173/@vite/client"></script>
        <script type="module" src="<?= vite('resources/js/app.js') ?>"></script>
    <?php else: ?>
      <!-- Production: Include compiled assets -->
      <link rel="stylesheet" href="<?= vite('resources/css/app.css') ?>">
      <script type="module" src="<?= vite('resources/js/app.js') ?>"></script>
    <?php endif; ?>
    <link rel="stylesheet" href="<?=env('APP_DOMAIN', '/')?>node_modules/bootstrap/dist/css/bootstrap.min.css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="<?=env('APP_DOMAIN', '/')?>resources/css/alerts/alertMsg.min.css?v=<?=config('config.version')?>" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="<?=env('APP_DOMAIN', '/')?>node_modules/@fortawesome/fontawesome-free/css/all.min.css" media="screen" title="no title" charset="utf-8">
    <script src="<?=env('APP_DOMAIN', '/')?>node_modules/jquery/dist/jquery.min.js"></script>
    <script src="<?=env('APP_DOMAIN', '/')?>node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="<?=env('APP_DOMAIN', '/')?>node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?=env('APP_DOMAIN', '/')?>resources/js/alerts/alertMsg.min.js?v=<?=config('config.version')?>"></script>
    <?= $this->content('head'); ?>

  </head>
  <body class="d-flex flex-column min-vh-100">
    <?php $this->component('main_menu') ?>
    <div class="container-fluid" style="min-height:calc(100% - 125px);">
      <?= Session::displayMessage() ?>
      <?= $this->content('body'); ?>
    </div> 
  </body>
</html>