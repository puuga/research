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

  $sql = "SELECT * FROM research.paper_year";
  $paper_year = array();
  $result = mysqli_query($con, $sql);
  if (!$result) {
    die('Error: ' . mysqli_error($con));
  } else {
    while($row = mysqli_fetch_array($result)) {
      $paper_year[] = $row["paper_year"];
    }
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

        <div class="col-md-9">
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

              $year = isset($_GET['year']) ? $_GET['year'] : 'all';


            ?>
          </h2>
        </div>
        <div class="col-md-3">
          <form action="staff_ranking.php" method="get" role="form">
            <?php
            if ( isset($_GET['dep']) ) {
              echo '<input type="hidden" name="dep" value="'.$_GET['dep'].'">';
            }
            ?>

            <div class="form-group">
              <div class="col-lg-10">
                <select class="form-control" id="select" name="year" onchange="this.form.submit()">
                  <?php
                  echo "<option>all</option>";

                  for ($i=0; $i < count($paper_year); $i++) {
                    $is_selected = $year===$paper_year[$i]? ' selected': '';
                    echo "<option$is_selected>$paper_year[$i]</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
          </form>
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
                if ( $dep!='' ) {
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
                          WHERE r.department_en = '$dep'";
                  if ($year!=='all') {
                    $sql .= " AND (YEAR(rs.journal_accepted_date) = $year
                                  OR YEAR(rs.conference_start_date) = $year)";
                  }
                  $sql .= "
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
                                  OR rs.author_name_en LIKE CONCAT('%', r.name_en, '%')";
                  if ($year!=='all') {
                    $sql .= " WHERE (YEAR(rs.journal_accepted_date) = $year
                                  OR YEAR(rs.conference_start_date) = $year)";
                  }
                  $sql.="
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

    <script type="text/javascript">
    </script>




  </body>
</html>
