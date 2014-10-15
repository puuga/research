<?php //staff_add_edit_process.php ?>
<?php include 'db_connect.php'; ?>
<?php
  $post_action = $_POST["action"];
  if( $post_action=="edit") {
    $post_id = $_POST["id"];
  }
  $post_name_th = $_POST["name_th"];
  $post_name_en = $_POST["name_en"];
  $post_department = $_POST["department"];

  // echo $post_action."<br/>";
  // echo $post_name_th."<br/>";
  // echo $post_name_en."<br/>";
  // echo $post_department."<br/>";

  switch ($post_department) {
    case 1:
        $department_th = "คณิตศาสตร์";
        $department_en = "Matematics";
        break;
    case 2:
        $department_th = "ชีววิทยา";
        $department_en = "Biology";
        break;
    case 3:
        $department_th = "ฟิสิกส์";
        $department_en = "Physics";
        break;
    case 4:
        $department_th = "วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ";
        $department_en = "Computer Science and Information Technology";
        break;
    case 5:
        $department_th = "เคมี";
        $department_en = "Chemistry";
        break;
}

  if( $post_action=="add") {
    $sql = "INSERT INTO researcher (name_th, name_en, department_th, department_en)
      VALUES ('$post_name_th', '$post_name_en', '$department_th', '$department_en')";
  } else {
    $sql = "UPDATE researcher
      SET name_th = '$post_name_th',
      name_en = '$post_name_en',
      department_th = '$department_th',
      department_en = '$department_en'
      WHERE id = $post_id;";
  }

  // echo $sql;

  $result = mysqli_query($con, $sql);
  if (!$result) {
    die('Error: ' . mysqli_error($con));
  }

  if ($post_action=="add"){
    header('Location: staff_view.php?message=Add New Staff Completed');
  } else {
    header('Location: staff_view.php?message=Edit Staff Completed');
  }

?>
