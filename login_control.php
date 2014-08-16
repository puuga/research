<?php //login_control.php ?>
<?php session_start(); ?>
<?php

  function redirect($url, $permanent = false) {
    if (headers_sent() === false) {
    	header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }
    exit();
  }

  if (empty($_SESSION['current_user']) || $_SESSION['current_user']=="") {
    //header( 'Location: login.php' );
    //exit();
    redirect('login.php', false);
    //echo "check";
  } else {
    $current_user = $_SESSION['current_user'];
    //echo "check2";
  }

?>
