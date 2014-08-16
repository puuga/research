<?php //logout_process.php ?>
<?php session_start(); ?>
<?php
  if(!empty($_SESSION['current_user'])) {
		unset($_SESSION['current_user']);
	}
  header( 'Location: login.php' );
?>
