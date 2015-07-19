<?php //staff_ranking.php ?>
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

  </head>
  <body>
    <?php include 'navbar.php'; ?>


    <div class="container">

      <!--title row-->
      <div class="row bg-info">

        <div class="col-md-4">
          <h2>Staff Ranking</h2>
        </div>

      </div>

      <br/>
      <!--data row-->
      <div class="row">
        <div class="col-md-12">
          <table class="table table-hover table-striped">
            <thead>
              <tr class="info">
                <th>Thai name</th>
                <th>English name</th>
                <th>Department</th>
                <th>Ranking</th>
              </tr>
            </thead>
            <tbody>
              <?php
                // set sql
                $sql = "SELECT
                            r.id,
                            r.name_th,
                            r.name_en,
                            r.department_th,
                            r.department_en,
                            COUNT(gdv.id) gdv_count
                        FROM
                            researcher r
                                LEFT JOIN
                            graph_data_view gdv ON corresponding LIKE CONCAT('%', r.name_th, '%')
                                OR corresponding LIKE CONCAT('%', r.name_en, '%')
                        GROUP BY r.id
                        order by gdv_count desc";

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
                        <?php echo $row['name_th']; ?>
                      </td>
                      <td>
                        <?php echo $row['name_en']; ?>
                      </td>
                      <td>
                        <?php echo $row['department_en']; ?>
                      </td>
                      <td>
                        <?php echo $row['gdv_count']; ?>
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
