<?php
  include 'db_connect_oo.php';
  header('Content-Type: application/json');

  $key = isset($_GET["name"]) ? $_GET["name"] : "" ;

  $sql = "SELECT * FROM research";
  if( $key!="" ) {
    $sql .= " where author_name_th like '%$key%' ";
    $sql .= " or author_name_en like '%$key%' ";
  }

  $result = $conn->query($sql);

  $json = array();
  if ($result->num_rows > 0) {
    $json = mysqli_fetch_all($result, MYSQLI_ASSOC);
  }

  $conn->close();

  echo json_encode($json);
?>
