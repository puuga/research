<?php //db_connect.php ?>
<?php
  include "env.php";
  // Create connection
  $con = mysqli_connect($servername, $username, $password, $dbname);

  // set charset to connection
  mysqli_set_charset($con , "UTF8");

  // Check connection
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit;
  }

  // mysqli_close($con);
?>
