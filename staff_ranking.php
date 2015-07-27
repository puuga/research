<?php //staff_ranking.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php include "class_import.php"; ?>
<?php
  // needAdminLevel(1);

  function makeLink($name) {
    $out = "main_menu.php?advance_search=true&paper_title=&paper_author=$name&options_journal=true&options_conference=true&options_international=true&options_national=true&options_department_1=true&options_department_2=true&options_department_3=true&options_department_4=true&options_department_5=true&paper_year=all";
    return $out;
  }
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
          <h2>
            Staff Ranking
            <?php
              if ( isset($_GET['dep']) ) {
                switch ( $_GET['dep'] ) {
                  case 'chemistry':
                    $dep = 'Chemistry';
                    break;
                  case 'mathematics':
                    $dep = 'Mathematics';
                    break;
                  case 'csit':
                    $dep = 'Computer Science and Information Technology';
                    break;
                  case 'biology':
                    $dep = 'Biology';
                    break;
                  case 'physics':
                    $dep = 'Physics';
                    break;
                }
                echo ": ".$dep;
              } else {
                $dep = '';
              }
            ?>
          </h2>
        </div>

      </div>

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
                <th>Ranking</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $ii = 1;
                // set sql
                if ( isset($_GET['dep'] )) {

                  $sql = "SELECT
                              r.id,
                              r.name_th,
                              r.name_en,
                              r.department_th,
                              r.department_en,
                              COUNT(rs.id) gdv_count
                          FROM
                              researcher r
                                  LEFT JOIN
                              research rs ON
                                  rs.author_name_th LIKE CONCAT('%', r.name_th, '%')
                                  OR rs.author_name_en LIKE CONCAT('%', r.name_en, '%')
                          WHERE r.department_en = '$dep'
                          GROUP BY r.id
                          ORDER BY gdv_count DESC";
                } else {
                  $sql = "SELECT
                              r.id,
                              r.name_th,
                              r.name_en,
                              r.department_th,
                              r.department_en,
                              COUNT(rs.id) gdv_count
                          FROM
                              researcher r
                                  LEFT JOIN
                              research rs ON
                                  rs.author_name_th LIKE CONCAT('%', r.name_th, '%')
                                  OR rs.author_name_en LIKE CONCAT('%', r.name_en, '%')
                          GROUP BY r.id
                          ORDER BY gdv_count DESC";
                }


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
                        <?php echo $ii++; ?>
                      </td>
                      <td>
                        <?php echo $row['name_th']; ?>
                      </td>
                      <td>
                        <?php echo $row['name_en']; ?>
                      </td>
                      <td>
                        <?php
                          if ($row['department_en']==='Computer Science and Information Technology') {
                            echo 'CSIT';
                          } else {
                            echo $row['department_en'];
                          }
                        ?>
                      </td>
                      <td>
                        <a href="<?php echo makeLink($row['name_th'])?>">
                          <?php echo $row['gdv_count']; ?>
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
