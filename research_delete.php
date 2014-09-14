<?php //research_delete.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php
  needAdminLevel(1);
  $id_to_delete = $_GET["id"];
  $sql = "DELETE FROM research WHERE id = $id_to_delete";
  mysqli_query($con,$sql);
  mysqli_close($con);
  header('Location: research_view.php?message=Delete Paper Completed');
?>
