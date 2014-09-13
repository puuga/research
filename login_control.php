<?php //login_control.php ?>
<?php session_start(); ?>
<?php

  function redirect($url, $permanent = false) {
    if (headers_sent() === false) {
    	header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }
    exit();
  }

  $current_user_name = "";
  $current_user_admin_level = 10;

  if (empty($_SESSION['current_user_name']) || $_SESSION['current_user_name']=="") {
    //header( 'Location: login.php' );
    //exit();
    $current_user_name = "";
    $current_user_admin_level = 10;
    //echo "check";
  } else {
    $current_user_name = $_SESSION['current_user_name'];
    $current_user_admin_level = $_SESSION['current_user_admin_level'];
    //echo "check2";
  }

  function needAdminLevel($requireLevel) {
    if (empty($_SESSION['current_user_name']) || $_SESSION['current_user_name']=="") {
      redirect('login.php?message=Need admin permission', false);
    }
    if (0+$_SESSION['current_user_admin_level'] > 0+$requireLevel) {
      redirect('login.php?message=Need admin permission'.$_SESSION['current_user_admin_level'].",".$requireLevel, false);
    }
  }

?>
