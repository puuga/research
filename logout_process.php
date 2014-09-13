<?php //logout_process.php ?>
<?php session_start(); ?>
<?php
  session_destroy();
  header( 'Location: main_menu.php' );
?>
