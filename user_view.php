<?php //user_view.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php include "class_import.php"; ?>
<?php
  needAdminLevel(0);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Science Research</title>

    <?php include 'head_tag.php'; ?>


    <script>
      function deleteUser(id,name) {
        var r = confirm("Delete ?\nName: "+name);
        if (r == true) {
            window.location.assign("user_delete.php?id="+id);
        } else {
        }
      }


    </script>
  </head>
  <body>
    <?php include 'navbar.php'; ?>


    <div class="container">

      <!--title row-->
      <div class="row bg-info">

        <div class="col-md-10">
          <h2>Staff View</h2>
        </div>

        <div class="col-md-2 text-right">
          <p></p>
          <p>
            <a class="btn btn-success" href="user_add_edit.php" role="button">
              <span class="glyphicon glyphicon-plus"></span> New User
            </a>
          </p>
        </div>

      </div>

      <?php
        if (!empty($_GET["message"])) {
          if (!empty($_GET["message_type"])) {
            ?>
            <div class="row bg-info">

              <div class="col-md-4">
              </div>

              <div class="col-md-4">
                <div class="alert alert-danger alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                  </button>
                  <strong><?php echo $_GET["message"]; ?></strong>
                </div>
              </div>

            </div>
            <?php
          } else {
            ?>
            <div class="row bg-info">

              <div class="col-md-4">
              </div>

              <div class="col-md-4">
                <div class="alert alert-success alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                  </button>
                  <strong><?php echo $_GET["message"]; ?></strong>
                </div>
              </div>

            </div>
            <?php
          }
        }
      ?>

      <br/>
      <!--data row-->
      <div class="row">
        <div class="col-md-12">
          <table class="table table-hover table-striped">
            <thead>
              <tr class="info">
                <th>Name</th>
                <th>Username</th>
                <th>Password</th>
                <th>Level</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </thead>
            <tbody>
              <?php
                // set sql
                $sql = "SELECT * FROM user";
                $result_for_json = array();
                $result = mysqli_query($con, $sql);
                if (!$result) {
                  die('Error: ' . mysqli_error($con));
                } else {
                  while($row = mysqli_fetch_array($result)) {
                    $result_for_json[] = $row;
                    ?>
                    <tr>
                      <td>
                        <?php echo $row['name']; ?>
                      </td>
                      <td>
                        <?php echo $row['username']; ?>
                      </td>
                      <td>
                        <?php echo $row['password']; ?>
                      </td>
                      <td>
                        <?php echo $row['admin_level']; ?>
                      </td>
                      <td>
                        <a class='btn btn-warning' href='user_add_edit.php?id=<?php echo $row['id']; ?>&name=<?php echo $row['name']; ?>&username=<?php echo $row['username']; ?>&password=<?php echo $row['password']; ?>&admin_level=<?php echo $row['admin_level']; ?>' >
                          <span class='glyphicon glyphicon-pencil'></span> edit
                        </a>
                      </td>
                      <td>
                        <a class='btn btn-danger' href='javascript:deleteUser("<?php echo $row['id']; ?>","<?php echo $row['name']; ?>")'>
                          <span class='glyphicon glyphicon-remove'></span> Delete
                        </a>
                      </td>
                    </tr>
                    <?php
                  }
                }

              ?>
            </tbody>
          </table>
        </div>
      </div>


    </div> <!-- end container -->




  </body>
</html>
