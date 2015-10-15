<?php //staff_view.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php include "class_import.php"; ?>
<?php
  needAdminLevel(1);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Science Research</title>

    <?php include 'head_tag.php'; ?>


    <script>
      function deleteStaff(id,name_th,name_en,department_th,department_en) {
        var r = confirm("Delete ?\nName: "+name_th+" / "+name_en+"\nDepartment: "+department_th+" / "+department_en);
        if (r == true) {
            window.location.assign("staff_delete.php?id="+id);
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

        <div class="col-md-4">
          <h2>Staff View</h2>
        </div>

        <div class="col-md-6">
          <p>
            <form class="form-inline" method="get">
              <div class="form-group">
                <input type="text" class="form-control"
                  name="key" id="key" placeholder="Key Search"
                  value="<?php echo isset($_GET["key"])?$_GET["key"]:'';?>">
              </div>
              <button type="submit" class="btn btn-default">Search</button>
            </form>
          </p>
        </div>

        <div class="col-md-2 text-right">
          <p></p>
          <p>
            <a class="btn btn-success" href="staff_add_edit.php" role="button">
              <span class="glyphicon glyphicon-plus"></span> New Staff
            </a>
          </p>
        </div>

      </div>

      <?php
        if (!empty($_GET["message"])) {
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
      ?>

      <br/>
      <!--data row-->
      <div class="row">
        <div class="col-md-12">
          <table class="table table-hover table-striped">
            <thead>
              <tr class="info">
                <th>#</th>
                <th>Thai name</th>
                <th>English name</th>
                <th>Department</th>
                <th>Status</th>
                <th>Edit / Delete</th>
              </tr>
            </thead>
            <tbody>
              <?php
                // set sql
                if ( isset($_GET["key"])) {
                  $key = $_GET["key"];
                  $sql = "SELECT * FROM researcher
                    WHERE name_th like '%$key%' or name_en like '%$key%'
                    ORDER BY department_th,name_th ASC";
                } else {
                  $sql = "SELECT * FROM researcher ORDER BY department_th,name_th ASC";
                }

                $result_for_json = array();
                $result = mysqli_query($con, $sql);
                if (!$result) {
                  die('Error: ' . mysqli_error($con));
                } else {
                  $ii = 1;
                  while($row = mysqli_fetch_array($result)) {
                    $result_for_json[] = $row;
                    ?>
                    <tr>
                      <td>
                        <?php echo $ii++; ?>
                      </td>
                      <td>
                        <?php echo $row['name_th']; ?>
                      </td>
                      <td>
                        <?php echo $row['name_en']; ?>
                      </td>
                      <td>
                        <?php echo $row['department_th']; ?><br/>
                        <?php echo $row['department_en']; ?>
                        <!-- <?php
                          if ($row['department_en']==='Computer Science and Information Technology') {
                            echo 'CSIT';
                          } else {
                            echo $row['department_en'];
                          }
                        ?> -->
                      </td>
                      <td>
                        <?php
                        if ( $row['status']==1) {
                          echo "ปกติ";
                        } else {
                          echo "ย้ายสังกัด";
                        }
                        ?>
                      </td>
                      <td>
                        <a class='btn btn-warning' href='staff_add_edit.php?id=<?php echo $row['id']; ?>&name_th=<?php echo $row['name_th']; ?>&name_en=<?php echo $row['name_en']; ?>&department_en=<?php echo $row['department_en']; ?>&status=<?php echo $row['status']; ?>'>
                          <span class='glyphicon glyphicon-pencil'></span> edit
                        </a>
                        <a class='btn btn-danger' href='javascript:deleteStaff("<?php echo $row['id']; ?>","<?php echo $row['name_th']; ?>","<?php echo $row['name_en']; ?>","<?php echo $row['department_th']; ?>","<?php echo $row['department_en']; ?>")'>
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
