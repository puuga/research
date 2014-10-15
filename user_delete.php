<?php //user_delete.php ?>
<?php include 'db_connect.php'; ?>
<?php

  $post_id = $_GET["id"];

  $sql = "DELETE FROM user WHERE id = $post_id";
  // echo $sql."<br/>";

  $result = mysqli_query($con, $sql);
  if (!$result) {
    die('Error: ' . mysqli_error($con));
  }


  header('Location: staff_view.php?message=Delete Staff Completed');

?>
