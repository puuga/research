<?php //summary_proceedings.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php include "class_import.php"; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Summary Proceedings</title>

    <?php include 'head_tag.php'; ?>

    <!-- start google chart -->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">

      <?php
        $sql = "SELECT * FROM research.conference_year";
        $result_conference_year_arr = array();
        $result = mysqli_query($con, $sql);
        if (!$result) {
          die('Error: ' . mysqli_error($con));
        } else {
          while($row = mysqli_fetch_array($result)) {
            $result_conference_year_arr[] = $row["conference_year"];
          }
        }

        $sql = "SELECT
                    cy.conference_year,
                    'conference' as name,
                    dv.department_en,
                    sc.scope as scope,
                    count(id) as count
                FROM
                    conference_year as cy,
                    research.graph_data_view as gv,
                    department_view as dv,
                    scope as sc
                where
                    research_type = 'conference'
                        and year(conference_start_date) = cy.conference_year
                        and gv.department_en = dv.department_en
                        and gv.conference_location_type = sc.scope COLLATE utf8_unicode_ci
                group by conference_year , department_en , scope";
        //$result_conference_arr = array();
        $result = mysqli_query($con, $sql);
        if (!$result) {
          die('Error: ' . mysqli_error($con));
        } else {
          while($row = mysqli_fetch_array($result)) {
            $result_conference_arr[$row["conference_year"]][$row["department_en"]][$row["scope"]] = $row["count"];
          }
        }
      ?>

      // Google chart
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Year', 'Chemistry', 'Physics', 'Biology', 'Mathematics', 'CSIT'],
          <?php
            $i = count($result_conference_year_arr)>3 ? 2 : count($result_conference_year_arr)-1;
            for($i=$i; $i>=0; $i--) {
          ?>
          ['<?php echo $result_conference_year_arr[$i];?>',
            <?php echo $result_conference_arr[$result_conference_year_arr[$i]]["Chemistry"]["national"]
              +$result_conference_arr[$result_conference_year_arr[$i]]["Chemistry"]["international"]; ?>,
            <?php echo $result_conference_arr[$result_conference_year_arr[$i]]["Physics"]["national"]
              +$result_conference_arr[$result_conference_year_arr[$i]]["Physics"]["international"];?>,
            <?php echo $result_conference_arr[$result_conference_year_arr[$i]]["Biology"]["national"]
              +$result_conference_arr[$result_conference_year_arr[$i]]["Biology"]["international"];?>,
            <?php echo $result_conference_arr[$result_conference_year_arr[$i]]["Mathematics"]["national"]
                +$result_conference_arr[$result_conference_year_arr[$i]]["Mathematics"]["international"];?>,
            <?php echo $result_conference_arr[$result_conference_year_arr[$i]]["Computer Science and Information Technology"]["national"]
              +$result_conference_arr[$result_conference_year_arr[$i]]["Computer Science and Information Technology"]["international"];?>
          ]
          <?php
              if($i != 0) {
                echo ",";
              }
            }
          ?>
        ]);

        var options = {
          title: 'Summary Chart',
          colors: ['#9E9E9E','#2196F3','#4CAF50','#F44336','#FF9800'],
          hAxis: {title: 'Year', titleTextStyle: {color: 'red'}},
          vAxis: {title: 'Number of proceedings', titleTextStyle: {color: 'red'}}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

        chart.draw(data, options);

      }

    </script>
    <!-- end google chart -->
  </head>
  <body>
    <?php include 'navbar.php'; ?>

    <?php printLogo(); ?>

    <div class="jumbotron">
      <div class="container">
        <h2>Summary Proceedings Chart</h2>
        <div id="chart_div" style="width: 100%; height: 400px;"></div>
      </div>
    </div>

    <?php
      function makeLink($isJournal, $isConference, $isNationnal, $isInternational, $year) {
        $link = "main_menu.php?advance_search=true&paper_title=&paper_author=";
        if ($isJournal) {
          $link .= "&options_journal=true";
        }
        if ($isConference) {
          $link .= "&options_conference=true";
        }
        if ($isNationnal) {
          $link .= "&options_national=true";
        }
        if ($isInternational) {
          $link .= "&options_international=true";
        }
        $link .= "&paper_year=".$year;
        $link .= "&item_per_page=25";

        return $link;
      }

      function makeLinkWithDept($isJournal, $isConference, $isNationnal, $isInternational, $year, $dept) {
        $link = makeLink($isJournal, $isConference, $isNationnal, $isInternational, $year);

        switch ($dept) {
          case 1:
            $link .= "&options_department_1=true";
            break;
          case 2:
            $link .= "&options_department_2=true";
            break;
          case 3:
            $link .= "&options_department_3=true";
            break;
          case 4:
            $link .= "&options_department_4=true";
            break;
          case 5:
            $link .= "&options_department_5=true";
            break;
          default:
            # code...
            break;
        }

        return $link;
      }
    ?>


    <div class="container">

      <!--title row-->
      <div class="row bg-primary">
        <div class="col-md-10 ">
          <h2>Summary Proceedings Data</h2>
        </div>

        <div class="col-md-2 ">
          <p><br/>
            <form action="summary_proceedings.php" method="get" role="form">
            <select class="form-control" name="year" onchange="this.form.submit()">
              <?php
                for($i=0; $i<count($result_conference_year_arr); $i++) {
                  ?>
                    <option <?php echo $_GET['year']==$result_conference_year_arr[$i]?"selected":""; ?>>
                      <?php echo $result_conference_year_arr[$i]; ?></option>
                  <?php
                }
              ?>
            </select>
            <form>
          </p>
        </div>

      </div>

      <br/>
      <?php
        for($i=0; $i<count($result_conference_year_arr); $i++) {
          if($result_conference_year_arr[$i]==$_GET["year"]||empty($_GET["year"])) {
      ?>
      <!--data row-->
      <div class="row">
        <div class="col-md-12">
          <table class="table table-hover table-striped">
            <thead>
              <tr class="info">
                <th>Year <?php echo $result_conference_year_arr[$i];?></th>
                <th colspan="2">Proceedings</th>
                <th rowspan="2">Total</th>
              </tr>
              <tr class="info">
                <th>Department</th>
                <th>National</th>
                <th>International</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Chemistry</td>
                <td>
                  <a href="<?php echo makeLinkWithDept(false,true,true,false,$result_conference_year_arr[$i],4); ?>">
                    <?php
                      echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Chemistry"]["national"];
                    ?>
                  </a>
                </td>
                <td>
                  <a href="<?php echo makeLinkWithDept(false,true,false,true,$result_conference_year_arr[$i],4); ?>">
                    <?php
                      echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Chemistry"]["international"];
                    ?>
                  </a>
                </td>
                <th>
                  <a href="<?php echo makeLinkWithDept(false,true,true,true,$result_conference_year_arr[$i],4); ?>">
                    <?php
                      echo $result_conference_arr[$result_conference_year_arr[$i]]["Chemistry"]["national"]
                        +$result_conference_arr[$result_conference_year_arr[$i]]["Chemistry"]["international"];
                    ?>
                  </a>
                </th>
              </tr>
              <tr>
                <td>Physics</td>
                <td>
                  <a href="<?php echo makeLinkWithDept(false,true,true,false,$result_conference_year_arr[$i],3); ?>">
                    <?php
                      echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Physics"]["national"];
                    ?>
                  </a>
                </td>
                <td>
                  <a href="<?php echo makeLinkWithDept(false,true,false,true,$result_conference_year_arr[$i],3); ?>">
                    <?php
                      echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Physics"]["international"];
                    ?>
                  </a>
                </td>
                <th>
                  <a href="<?php echo makeLinkWithDept(false,true,true,true,$result_conference_year_arr[$i],3); ?>">
                    <?php
                      echo $result_conference_arr[$result_conference_year_arr[$i]]["Physics"]["national"]
                        +$result_conference_arr[$result_conference_year_arr[$i]]["Physics"]["international"];
                    ?>
                  </a>
                </th>
              </tr>
              <tr>
                <td>Biology</td>
                <td>
                  <a href="<?php echo makeLinkWithDept(false,true,true,false,$result_conference_year_arr[$i],5); ?>">
                    <?php
                      echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Biology"]["national"];
                    ?>
                  </a>
                </td>
                <td>
                  <a href="<?php echo makeLinkWithDept(false,true,false,true,$result_conference_year_arr[$i],5); ?>">
                    <?php
                      echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Biology"]["international"];
                    ?>
                  </a>
                </td>
                <th>
                  <a href="<?php echo makeLinkWithDept(false,true,true,true,$result_conference_year_arr[$i],5); ?>">
                    <?php
                      echo $result_conference_arr[$result_conference_year_arr[$i]]["Biology"]["national"]
                        +$result_conference_arr[$result_conference_year_arr[$i]]["Biology"]["international"];
                    ?>
                  </a>
                </th>
              </tr>
              <tr>
                <td>Mathematics</td>
                <td>
                  <a href="<?php echo makeLinkWithDept(false,true,true,false,$result_conference_year_arr[$i],1); ?>">
                    <?php
                      echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Mathematics"]["national"];
                    ?>
                  </a>
                </td>
                <td>
                  <a href="<?php echo makeLinkWithDept(false,true,false,true,$result_conference_year_arr[$i],1); ?>">
                    <?php
                      echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Mathematics"]["international"];
                    ?>
                  </a>
                </td>
                <th>
                  <a href="<?php echo makeLinkWithDept(false,true,true,true,$result_conference_year_arr[$i],1); ?>">
                    <?php
                      echo $result_conference_arr[$result_conference_year_arr[$i]]["Mathematics"]["national"]
                        +$result_conference_arr[$result_conference_year_arr[$i]]["Mathematics"]["international"];
                    ?>
                  </a>
                </th>
              </tr>
              <tr>
                <td>CSIT</td>
                <td>
                  <a href="<?php echo makeLinkWithDept(false,true,true,false,$result_conference_year_arr[$i],2); ?>">
                    <?php
                      echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Computer Science and Information Technology"]["national"];
                    ?>
                  </a>
                </td>
                <td>
                  <a href="<?php echo makeLinkWithDept(false,true,false,true,$result_conference_year_arr[$i],2); ?>">
                    <?php
                      echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Computer Science and Information Technology"]["international"];
                    ?>
                  </a>
                </td>
                <th>
                  <a href="<?php echo makeLinkWithDept(false,true,true,true,$result_conference_year_arr[$i],2); ?>">
                    <?php
                      echo $result_conference_arr[$result_conference_year_arr[$i]]["Computer Science and Information Technology"]["national"]
                        +$result_conference_arr[$result_conference_year_arr[$i]]["Computer Science and Information Technology"]["international"];
                    ?>
                  </a>
                </th>
              </tr>
            </tbody>
            <tfoot>
              <tr class="warning">
                <th>Total</th>
                <th>
                  <a href="<?php echo makeLink(false,true,true,false,$result_conference_year_arr[$i]); ?>">
                  <?php
                    echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Chemistry"]["national"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Physics"]["national"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Biology"]["national"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Mathematics"]["national"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Computer Science and Information Technology"]["national"];
                  ?>
                  </a>
                </th>
                <th>
                  <a href="<?php echo makeLink(false,true,false,true,$result_conference_year_arr[$i]); ?>">
                  <?php
                    echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Chemistry"]["international"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Physics"]["international"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Biology"]["international"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Mathematics"]["international"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Computer Science and Information Technology"]["international"];
                  ?>
                  </a>
                </th>
                <th>
                  <a href="<?php echo makeLink(false,true,true,true,$result_conference_year_arr[$i]); ?>">
                  <?php
                    echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Chemistry"]["national"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Physics"]["national"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Biology"]["national"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Mathematics"]["national"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Computer Science and Information Technology"]["national"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Chemistry"]["international"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Physics"]["international"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Biology"]["international"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Mathematics"]["international"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Computer Science and Information Technology"]["international"];
                  ?>
                  </a>
                </th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div><!--end data row-->
      <?php
          } // end if
        } // end for
      ?>

      <div>
        <?php
          //print_r($result_conference_arr);
        ?>
      </div>
      <div>
        <?php
          //print_r($result_conference_year_arr);
        ?>
      </div>

      <div class="row bg-primary">
        <div class="col-md-12">
          <h2>Search on Range of Publication (Proceedings)</h2>
        </div>

        <?php
          $rangeFrom = isset($_GET['rangeFrom']) ? $_GET['rangeFrom'] : date("Y").'-01-01' ;
          $rangeTo = isset($_GET['rangeTo']) ? $_GET['rangeTo'] : date("Y").'-12-31' ;
        ?>

        <div class="col-md-12">
          <form class="form-inline"></form>

          <form class="form-inline" action="summary_proceedings.php" method="get">
            <div class="form-group">
              <label for="rangeFrom">From</label>
              <input
                type="date"
                class="form-control"
                id="rangeFrom"
                name="rangeFrom"
                value="<?php echo $rangeFrom;?>"
                required>
            </div>
            <div class="form-group">
              <label for="rangeTo">To</label>
              <input
                type="date"
                class="form-control"
                id="rangeTo"
                name="rangeTo"
                value="<?php echo $rangeTo;?>"
                required>
            </div>
            <button type="submit" class="btn btn-default">Search</button>
          </form>
          <br/>
        </div>
      </div>

      <?php
        $sql2="SELECT
                  dv.department_en, sc.scope AS scope, COUNT(id) AS count
              FROM
                  research.graph_data_view AS gv,
                  department_view AS dv,
                  scope AS sc
              WHERE
                  research_type = 'conference'
                      AND (conference_start_date BETWEEN '$rangeFrom' AND '$rangeTo')
                      AND gv.department_en = dv.department_en
                      AND gv.conference_location_type = sc.scope COLLATE utf8_unicode_ci
              GROUP BY department_en , scope";

        $result2 = mysqli_query($con, $sql2);
        if (!$result2) {
          die('Error: ' . mysqli_error($con));
        } else {
          while( $row = mysqli_fetch_array($result2) ) {
            $result_conference_range[$row["department_en"]][$row["scope"]] = $row["count"];
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
                <th>Range</th>
                <th colspan="2">Proceedings</th>
                <th rowspan="2">Total</th>
              </tr>
              <tr class="info">
                <th>Department</th>
                <th>National</th>
                <th>International</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Chemistry</td>
                <td>
                  <?php
                    echo 0+$result_conference_range["Chemistry"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_conference_range["Chemistry"]["international"];
                  ?>
                </td>
                <th>
                  <?php
                    echo 0+$result_conference_range["Chemistry"]["national"]
                      +$result_conference_range["Chemistry"]["international"];
                  ?>
                </th>
              </tr>
              <tr>
                <td>Physics</td>
                <td>
                  <?php
                    echo 0+$result_conference_range["Physics"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_conference_range["Physics"]["international"];
                  ?>
                </td>
                <th>
                  <?php
                    echo 0+$result_conference_range["Physics"]["national"]
                      +$result_conference_range["Physics"]["international"];
                  ?>
                </th>
              </tr>
              <tr>
                <td>Biology</td>
                <td>
                  <?php
                    echo 0+$result_conference_range["Biology"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_conference_range["Biology"]["international"];
                  ?>
                </td>
                <th>
                  <?php
                    echo 0+$result_conference_range["Biology"]["national"]
                      +$result_conference_range["Biology"]["international"];
                  ?>
                </th>
              </tr>
              <tr>
                <td>Mathematics</td>
                <td>
                  <?php
                    echo 0+$result_conference_range["Mathematics"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_conference_range["Mathematics"]["international"];
                  ?>
                </td>
                <th>
                  <?php
                    echo 0+$result_conference_range["Mathematics"]["national"]
                      +$result_conference_range["Mathematics"]["international"];
                  ?>
                </th>
              </tr>
              <tr>
                <td>CSIT</td>
                <td>
                  <?php
                    echo 0+$result_conference_range["Computer Science and Information Technology"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_conference_range["Computer Science and Information Technology"]["international"];
                  ?>
                </td>
                <th>
                  <?php
                    echo 0+$result_conference_range["Computer Science and Information Technology"]["national"]
                      +$result_conference_range["Computer Science and Information Technology"]["international"];
                  ?>
                </th>
              </tr>
            </tbody>
            <tfoot>
              <tr class="warning">
                <th>Total</th>
                <th>
                  <?php
                    echo 0+$result_conference_range["Chemistry"]["national"]
                      +$result_conference_range["Physics"]["national"]
                      +$result_conference_range["Biology"]["national"]
                      +$result_conference_range["Mathematics"]["national"]
                      +$result_conference_range["Computer Science and Information Technology"]["national"];
                  ?>
                </th>
                <th>
                  <?php
                    echo 0+$result_conference_range["Chemistry"]["international"]
                      +$result_conference_range["Physics"]["international"]
                      +$result_conference_range["Biology"]["international"]
                      +$result_conference_range["Mathematics"]["international"]
                      +$result_conference_range["Computer Science and Information Technology"]["international"];
                  ?>
                </th>
                <th>
                  <?php
                    echo 0+$result_conference_range["Chemistry"]["national"]
                      +$result_conference_range["Physics"]["national"]
                      +$result_conference_range["Biology"]["national"]
                      +$result_conference_range["Mathematics"]["national"]
                      +$result_conference_range["Computer Science and Information Technology"]["national"]
                      +$result_conference_range["Chemistry"]["international"]
                      +$result_conference_range["Physics"]["international"]
                      +$result_conference_range["Biology"]["international"]
                      +$result_conference_range["Mathematics"]["international"]
                      +$result_conference_range["Computer Science and Information Technology"]["international"];
                  ?>
                </th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div><!--end data row-->

    </div> <!-- end container -->


  </body>
</html>
