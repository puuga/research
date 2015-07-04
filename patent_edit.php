<?php //research_edit.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php
  header('Content-Type: application/json');
  needAdminLevel(1);
  $id_to_edit = $_POST["id"];
  $patent_type_id = $_POST["patent_type_id"];
  $name = $_POST["name"];
  $number = $_POST["number"];
  $description = $_POST["description"];
  $belong_to = $_POST["belong_to"];
  $grant_date = $_POST["grant_date"];
  $extra1 = $_POST["extra1"];
  $work_type = $_POST["work_type"];

  if ( $patent_type_id==='1' ) {
    $work_characteristic = $_POST["work_characteristic"];
  } else {
    $request_number = $_POST["request_number"];
    $request_date = $_POST["request_date"];
  }


  $sql = "UPDATE patents SET ";
  $sql .= " name = '$name', ";
  $sql .= " number = '$number', ";
  $sql .= " description = '$description', ";
  $sql .= " belong_to = '$belong_to', ";
  $sql .= " grant_date = '$grant_date', ";
  $sql .= " extra1 = '$extra1', ";
  $sql .= " work_type = '$work_type', ";
  if ( $patent_type_id==='1' ) {
    $sql .= " work_characteristic = '$work_characteristic' ";
  } else {
    $sql .= " request_number = '$request_number', ";
    $sql .= " request_date = '$request_date' ";
  }
  $sql .= " WHERE `patents`.`id` = $id_to_edit";
  mysqli_query($con,$sql);


  mysqli_close($con);

  // set up result
  $result = array();
  $result['success'] = true;
  $result['id_to_edit'] = $id_to_edit;
  $result['sql'] = $sql;

  // Return the data result as json
  echo json_encode($result);
?>
