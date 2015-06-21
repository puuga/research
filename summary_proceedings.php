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
        $link .= "&paper_year=2014";
        $link .= "&item_per_page=25";

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
                  <?php
                    echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Chemistry"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Chemistry"]["international"];
                  ?>
                </td>
                <th>
                  <?php
                    echo $result_conference_arr[$result_conference_year_arr[$i]]["Chemistry"]["national"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Chemistry"]["international"];
                  ?>
                </th>
              </tr>
              <tr>
                <td>Physics</td>
                <td>
                  <?php
                    echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Physics"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Physics"]["international"];
                  ?>
                </td>
                <th>
                  <?php
                    echo $result_conference_arr[$result_conference_year_arr[$i]]["Physics"]["national"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Physics"]["international"];
                  ?>
                </th>
              </tr>
              <tr>
                <td>Biology</td>
                <td>
                  <?php
                    echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Biology"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Biology"]["international"];
                  ?>
                </td>
                <th>
                  <?php
                    echo $result_conference_arr[$result_conference_year_arr[$i]]["Biology"]["national"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Biology"]["international"];
                  ?>
                </th>
              </tr>
              <tr>
                <td>Mathematics</td>
                <td>
                  <?php
                    echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Mathematics"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Mathematics"]["international"];
                  ?>
                </td>
                <th>
                  <?php
                    echo $result_conference_arr[$result_conference_year_arr[$i]]["Mathematics"]["national"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Mathematics"]["international"];
                  ?>
                </th>
              </tr>
              <tr>
                <td>CSIT</td>
                <td>
                  <?php
                    echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Computer Science and Information Technology"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_conference_arr[$result_conference_year_arr[$i]]["Computer Science and Information Technology"]["international"];
                  ?>
                </td>
                <th>
                  <?php
                    echo $result_conference_arr[$result_conference_year_arr[$i]]["Computer Science and Information Technology"]["national"]
                      +$result_conference_arr[$result_conference_year_arr[$i]]["Computer Science and Information Technology"]["international"];
                  ?>
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

    </div> <!-- end container -->


  </body>
</html>
