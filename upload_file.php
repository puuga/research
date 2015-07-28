<?php // upload_file.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php
needAdminLevel(1);

// upload file
// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.
if ($_FILES['att_file']) {
  $uploaddir = 'upload_file/';
  $uploadfile = $uploaddir . date("l_d_m_Y_H_i_s") . "_". basename($_FILES['att_file']['name']);

  //echo '<hr>';
  //echo date("l_d_m_Y_H_i_s") . "<br>";

  if (move_uploaded_file($_FILES['att_file']['tmp_name'], $uploadfile)) {
      //echo "File is valid, and was successfully uploaded.\n";
  } else {
      //echo "Can not upload file!\n";
      //exit();
      $uploadfile = "";
  }
  //echo '<hr>';
}
$att_file = $uploadfile;
$research_id = $_POST['research_id'];

// update to db
$sql = "UPDATE research SET att_file='$att_file' WHERE id=$research_id";
mysqli_query($con,$sql);
mysqli_close($con);

$output["result"] = "success";
$output["file"] = $att_file;
$output["research_id"] = $research_id;

header('Content-Type: application/json');
echo json_encode($output);
?>
