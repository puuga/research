<?php //login.php ?>
<?php include "class_import.php" ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Login</title>

    <?php include 'head_tag.php'; ?>

    <link href="css/signin.css" rel="stylesheet">
    <link href="css/starter-template.css" rel="stylesheet">

  </head>
  <body>

    <div class="container">
      <div class="starter-template">
        <h1><?php echo String::system_title ?></h1>
      </div>

      <form class="form-signin" role="form" method="post" action="login_process.php">
        <h2 class="form-signin-heading">Please sign in</h2>
        <div class="text-danger">
          <?php
            if (!empty($_GET["message"])) {
              echo $_GET["message"];
            }
          ?>
        </div>
        <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>

    </div> <!-- /container -->

  </body>
</html>
