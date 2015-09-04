<?php //input_form_process.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php include "class_import.php"; ?>
<?php
  needAdminLevel(1);

  $patent_type_id = $_POST["patent_type_id"];
  $name = $_POST["name"];
  $name = mysql_real_escape_string($name);
  $number = $_POST["number"];
  $description = $_POST["description"];
  $description = mysql_real_escape_string($description);
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

  // set sql
  $sql = "";

  if ( $patent_type_id==='1' ) {
    $sql .= "INSERT INTO patents ";
    $sql .= "(
    name,
    patent_type_id,
    number,
    description,
    belong_to,
    grant_date,
    extra1,
    work_type,
    work_characteristic
    )";
    $sql .= " VALUES ";
    $sql .= "(
    '$name',
    $patent_type_id,
    '$number',
    '$description',
    '$belong_to',
    '$grant_date',
    '$extra1',
    '$work_type',
    '$work_characteristic') ";
  } else {
    $sql .= "INSERT INTO patents ";
    $sql .= "(
    name,
    patent_type_id,
    number,
    description,
    belong_to,
    grant_date,
    extra1,
    work_type,
    request_number,
    request_date
    )";
    $sql .= " VALUES ";
    $sql .= "(
    '$name',
    $patent_type_id,
    '$number',
    '$description',
    '$belong_to',
    '$grant_date',
    '$extra1',
    '$work_type',
    '$request_number',
    '$request_date') ";
  }


  $result = mysqli_query($con, $sql);
  if (!$result) {
    die('Error: ' . mysqli_error($con));
  }
  header('Location: patent_view.php?message=Add New Patent Completed');

  echo "1 record added";
  mysqli_close($con);





  //echo "<hr/>";
  $arr = GET_defined_vars();
  echo "<hr/>";
  // print all the available keys for the arrays of variables
  print_r($arr);
  echo "<hr/>";
  print_r(array_keys(GET_defined_vars()));
  echo "<hr/>";
  echo $sql;
?>
