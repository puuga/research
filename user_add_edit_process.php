<?php //user_add_edit_process.php ?>
<?php include 'db_connect.php'; ?>
<?php
  $post_action = $_POST["action"];
  if( $post_action=="edit") {
    $post_id = $_POST["id"];
  }
  $name = $_POST["name"];
  $username = $_POST["username"];
  $password = $_POST["password"];
  $admin_level = $_POST["admin_level"];

  // echo $post_action."<br/>";
  // echo $post_name_th."<br/>";
  // echo $post_name_en."<br/>";
  // echo $post_department."<br/>";

  if( $post_action=="add") {
    $sql = "INSERT INTO user (name, username, password, admin_level)
      VALUES ('$name', '$username', '$password', '$admin_level')";
  } else {
    $sql = "UPDATE user
      SET name = '$name',
      username = '$username',
      password = '$password',
      admin_level = $admin_level
      WHERE id = $post_id;";
  }

  // echo $sql;

  $result = mysqli_query($con, $sql);
  if (!$result) {
    header('Location: user_view.php?message=Cannot add or edit User because of duplicate username&message_type=error');

    die('Error: ' . mysqli_error($con));

  }

  if ($post_action=="add"){
    header('Location: user_view.php?message=Add New User Completed');
  } else {
    header('Location: user_view.php?message=Edit User Completed');
  }

?>
