<?php //research_edit.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php
  header('Content-Type: application/json');
  needAdminLevel(1);
  $id_to_edit = $_POST["id"];
  $title = $_POST["title"];
  $isStudentGraduation = empty($_POST["isStudentGraduation"])? "0": $_POST["isStudentGraduation"];
  $reference = $_POST["reference"];

  $sql = "UPDATE research SET
    title = '$title',
    is_student_grad = $isStudentGraduation,
    reference = '$reference'
    WHERE `research`.`id` = $id_to_edit";
  mysqli_query($con,$sql);
  mysqli_close($con);
  //header('Location: research_view.php?message=Delete Paper Completed');

  // set up result
  $result = array();
  $result['success'] = true;
  $result['id_to_edit'] = $id_to_edit;
  $result['title'] = $title;
  $result['isStudentGraduation'] = $isStudentGraduation;
  $result['reference'] = $reference;

  // Return the data result as json
  echo json_encode($result);
?>
