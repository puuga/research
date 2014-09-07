<?php //login_process.php ?>
<?php session_start(); ?>
<?php include "db_connect.php"; ?>
<?php

  $username = $_POST["username"];
  $password = $_POST["password"];
  $sql = "SELECT * FROM user WHERE username like '$username' and password like '$password'" ;
  $result = mysqli_query($con, $sql);
  if (!$result) {
    die('Error: ' . mysqli_error($con));
  } else {
    if ( mysqli_num_rows($result) == 0 ) {
      header( 'Location: login.php?message=Invalid username or password' );
      //http_redirect("login.php", array("message" => "Invalid username or password"));
    }

    while($row = mysqli_fetch_array($result)) {
      $_SESSION['current_user'] = $row["username"];
      $_SESSION['current_user_name'] = $row["name"];
    }

    header( 'Location: index.php' );
    //http_redirect('Location: index.php');

  }


?>
