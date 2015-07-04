<?php //research_delete.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php
  header('Content-Type: application/json');
  needAdminLevel(1);
  $id_to_delete = $_POST["id"];
  $sql = "DELETE FROM patents WHERE id = $id_to_delete";
  mysqli_query($con,$sql);
  mysqli_close($con);
  //header('Location: research_view.php?message=Delete Paper Completed');

  // set up result
  $result = array();
  $result['success'] = true;
  $result['message'] = "Patent[$id_to_delete] Deleted";

  // Return the data result as json
  echo json_encode($result);
?>
