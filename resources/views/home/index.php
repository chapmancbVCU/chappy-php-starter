<?php use Core\Lib\Utilities\Env; ?>
<?php $this->start('body'); ?>

<div class="container">
  <div class="text-center">
    <h1 class="display-4">Welcome to</h1>
    <div class="col-12 mx-auto text-center">
      <img class="w-50" src="<?=Env::get('APP_DOMAIN', '/')?>public/logo.png" alt="Framework Logo">
    </div>
    <p class="lead mt-3">
      A lightweight and modern PHP framework built for simplicity, speed, and developer happiness.
    </p>

    <div class="d-flex justify-content-center mt-4 flex-wrap gap-3">
      <a class="btn btn-primary" href="https://chapmancbvcu.github.io/chappy-php/">📘 View Documentation</a>
    </div>
  </div>

  <hr class="my-5">

  <div class="row text-center g-4">
    <div class="col-md-4">
      <h4>🔧 MVC Architecture</h4>
      <p>Familiar routing and controller setup with simple view rendering.</p>
    </div>
    <div class="col-md-4">
      <h4>🛡️ Custom Forms With Validation</h4>
      <p>A FormHelper class with support for many commonly used elements and built-in server-side form validation with error message support.</p>
    </div>
    <div class="col-md-4">
      <h4>⚙️ Project Generator</h4>
      <p>Generate project skeletons and database migrations using console commands.</p>
    </div>
  </div>

  <div class="row text-center g-4 mt-4">
    <div class="col-md-4">
      <h4>🧩 Composer Support</h4>
      <p>Manage your dependencies using Composer just like Laravel or Symfony.</p>
    </div>
    <div class="col-md-4">
      <h4>📁 User Management</h4>
      <p>Includes ACL support and authentication out of the box.</p>
    </div>
    <div class="col-md-4">
      <h4>📄 Simple Documentation</h4>
      <p>Markdown and API documentation included and easy to customize.</p>
    </div>
  </div>

  <hr class="my-5">

  <div class="text-center">
    <h4>⚡ Ajax In Action</h4>
    <p>Test a sample Ajax call to see the framework response.</p>
    <button class="btn btn-success" onclick="ajaxTest();">Run Ajax Test</button>
  </div>

  <script>
    function ajaxTest() {
      $.ajax({
        type: "POST",
        url: "<?= Env::get('APP_DOMAIN', '/') ?>home/testAjax",
        data: { model_id: 45 },
        success: function (resp) {
          if (resp.success) {
            alert("Server says: " + resp.data.name);
          }
          console.log(resp);
        }
      });
    }
  </script>

  <p class="text-center text-muted mt-5">
    To customize this page, edit <code>resources/views/home/index.php</code> and <code>app/Controllers/HomeController.php</code>.
  </p>
</div>

<?php $this->end(); ?>
