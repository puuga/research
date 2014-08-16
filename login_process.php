<?php //login_process.php ?>
<?php session_start(); ?>
<?php
  if ($_POST["username"]=="test" && $_POST["password"]=="test") {
    // store session data
    $_SESSION['current_user'] = $_POST["username"];
    //echo $_POST["username"].$_POST["password"];
    header( 'Location: research.php' );
  } else {
    header( 'Location: login.php?message=Invalid username or password' );
  }

?>
