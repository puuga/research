<?php //db_connect.php ?>
<?php
  // Create connection
  $con = mysqli_connect("localhost","researchdba","123456","research");

  mysqli_set_charset ($con , "UTF8");

  // Check connection
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit;
  }

  // mysqli_close($con);
?>
