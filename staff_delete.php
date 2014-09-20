<?php //staff_add_edit.php ?>
<?php include 'db_connect.php'; ?>
<?php

  $post_id = $_GET["id"];

  $sql = "DELETE FROM `research`.`researcher` WHERE `researcher`.`id` = $post_id";
  // echo $sql."<br/>";

  $result = mysqli_query($con, $sql);
  if (!$result) {
    die('Error: ' . mysqli_error($con));
  }


  header('Location: staff_view.php?message=Delete Staff Completed');

?>
