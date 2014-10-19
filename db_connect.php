<?php //db_connect.php ?>
<?php
  // Create connection
  $con = mysqli_connect("localhost","researchdba","123456","research");

  //mysqli_query("SET NAMES 'UTF-8'");
  mysqli_set_charset($con , "UTF8");
  //mysqli_query("SET character_set_results=UTF-8");
  //mysqli_query("SET character_set_client=UTF-8");
  //mysqli_query("SET character_set_connection=UTF-8");

  // Check connection
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit;
  }

  // mysqli_close($con);
?>
