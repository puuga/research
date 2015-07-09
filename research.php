<?php //research.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php include "class_import.php"; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Science Research</title>

    <?php include 'head_tag.php'; ?>

    <!-- start google chart -->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">

      <?php
        $sql = "SELECT * FROM research.paper_year";
        $result_paper_year_arr = array();
        $result = mysqli_query($con, $sql);
        if (!$result) {
          die('Error: ' . mysqli_error($con));
        } else {
          while($row = mysqli_fetch_array($result)) {
            $result_paper_year_arr[] = $row["paper_year"];
          }
        }

        $sql = "SELECT
                    py.paper_year,
                    'journal' as name,
                    count(id) as count
                FROM
                    paper_year as py,
                    research.graph_data_view as gv
                where
                    research_type = 'journal'
                        and journal_type_progress = 'published'
                        and year(journal_accepted_date) = py.paper_year
                group by paper_year
                union SELECT
                    py.paper_year,
                    'conference' as name,
                    count(id) as count
                FROM
                    paper_year as py,
                    research.graph_data_view as gv
                where
                    research_type = 'conference'
                        and year(conference_start_date) = py.paper_year
                group by paper_year";
        //$result_conference_arr = array();
        $result = mysqli_query($con, $sql);
        if (!$result) {
          die('Error: ' . mysqli_error($con));
        } else {
          while($row = mysqli_fetch_array($result)) {
            $result_paper_arr[$row["paper_year"]][$row["name"]] = $row["count"];
          }
        }

        // result journal
        $sqlJ = "SELECT
                    py.paper_year,
                    'journal' as name,
                    gv.journal_type,
                    count(id) as count

                FROM
                    paper_year as py,
                    research.graph_data_view as gv
                where
                    research_type = 'journal'
                        and journal_type_progress = 'published'
                        and year(journal_accepted_date) = py.paper_year
                group by paper_year,journal_type";
        $result = mysqli_query($con, $sqlJ);
        if (!$result) {
          die('Error: ' . mysqli_error($con));
        } else {
          while($row = mysqli_fetch_array($result)) {
            $result_journal_arr[$row["paper_year"]][$row["journal_type"]] = $row["count"];
          }
        }

        // result conference
        $sqlC = "SELECT
                    py.paper_year,
                    'conference' as name,
                    gv.conference_location_type,
                    count(id) as count
                FROM
                    paper_year as py,
                    research.graph_data_view as gv
                where
                    research_type = 'conference'
                        and year(conference_start_date) = py.paper_year
                group by paper_year,conference_location_type";
        $result = mysqli_query($con, $sqlC);
        if (!$result) {
          die('Error: ' . mysqli_error($con));
        } else {
          while($row = mysqli_fetch_array($result)) {
            $result_conference_arr[$row["paper_year"]][$row["conference_location_type"]] = $row["count"];
          }
        }
      ?>

      // Google chart
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Year', 'Journal', 'Proceedings'],
          <?php
            $i = count($result_paper_year_arr)>3 ? 2 : count($result_paper_year_arr)-1;
            for($i=$i; $i>=0; $i--) {
          ?>
          ['<?php echo $result_paper_year_arr[$i];?>',
            <?php echo 0+$result_paper_arr[$result_paper_year_arr[$i]]["journal"]; ?>,
            <?php echo 0+$result_paper_arr[$result_paper_year_arr[$i]]["conference"]; ?>
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
          vAxis: {title: 'Number of paper', titleTextStyle: {color: 'red'}}
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
        <h2>Summary Paper Chart</h2>
        <div id="chart_div" style="width: 100%; height: 400px;"></div>
      </div>
    </div>


    <div class="container">

      <!--title row-->
      <div class="row bg-primary">
        <div class="col-md-12 ">
          <h2>Summary Paper Data</h2>
        </div>
      </div>


      <br/>
      <!--data row-->
      <div class="row">
        <div class="col-md-12">
          <table class="table table-hover table-striped">
            <thead>
              <tr class="info">
                <th rowspan="2">Year</th>
                <th colspan="2">Journal</th>
                <th rowspan="2">Total</th>
                <th colspan="2">Proceedings</th>
                <th rowspan="2">Total</th>
                <th rowspan="2">Grand Total</th>
              </tr>
              <tr class="info">
                <th>National</th>
                <th>International</th>
                <th>National</th>
                <th>International</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $sum_journal = 0;
                $sum_journal_national = 0;
                $sum_journal_international = 0;
                $sum_conference = 0;
                $sum_conference_national = 0;
                $sum_conference_international = 0;
                $sum_total = 0;
                for($i=0; $i<count($result_paper_year_arr); $i++) {
              ?>
              <tr>
                <td>
                  <?php
                    echo $result_paper_year_arr[$i];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_paper_year_arr[$i]]["national"];
                    $sum_journal_national += 0+$result_journal_arr[$result_paper_year_arr[$i]]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_paper_year_arr[$i]]["international"];
                    $sum_journal_international += 0+$result_journal_arr[$result_paper_year_arr[$i]]["international"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_paper_arr[$result_paper_year_arr[$i]]["journal"];
                    $sum_journal += 0+$result_paper_arr[$result_paper_year_arr[$i]]["journal"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_conference_arr[$result_paper_year_arr[$i]]["national"];
                    $sum_conference_national += 0+$result_conference_arr[$result_paper_year_arr[$i]]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_conference_arr[$result_paper_year_arr[$i]]["international"];
                    $sum_conference_international += 0+$result_conference_arr[$result_paper_year_arr[$i]]["international"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_paper_arr[$result_paper_year_arr[$i]]["conference"];
                    $sum_conference += 0+$result_paper_arr[$result_paper_year_arr[$i]]["conference"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_paper_arr[$result_paper_year_arr[$i]]["conference"]
                      +$result_paper_arr[$result_paper_year_arr[$i]]["journal"];
                    $sum_total += 0+$result_paper_arr[$result_paper_year_arr[$i]]["conference"];
                    $sum_total += 0+$result_paper_arr[$result_paper_year_arr[$i]]["journal"];
                  ?>
                </td>
              </tr>
              <?php
                }
              ?>
            </tbody>
            <tfoot>
              <tr>
                <th>Total</th>
                <th><?php echo $sum_journal_national; ?></th>
                <th><?php echo $sum_journal_international; ?></th>
                <th><?php echo $sum_journal; ?></th>
                <th><?php echo $sum_conference_national; ?></th>
                <th><?php echo $sum_conference_international; ?></th>
                <th><?php echo $sum_conference; ?></th>
                <th><?php echo $sum_total; ?></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <div class="row bg-primary">
        <div class="col-md-12">
          <h2>Range Summary Papers</h2>
        </div>

        <?php
          $rangeFrom = isset($_GET['rangeFrom']) ? $_GET['rangeFrom'] : date("Y").'-01-01' ;
          $rangeTo = isset($_GET['rangeTo']) ? $_GET['rangeTo'] : date("Y").'-12-31' ;
        ?>

        <div class="col-md-12">
          <form class="form-inline"></form>

          <form class="form-inline" action="research.php" method="get">
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
        // summary
        $sql2 = "SELECT
                    'journal' as name,
                    count(id) as count
                FROM
                    research.graph_data_view as gv
                where
                    research_type = 'journal'
                        and journal_type_progress = 'published'
                        and (journal_accepted_date between '$rangeFrom' and '$rangeTo')
                union SELECT
                    'conference' as name,
                    count(id) as count
                FROM
                    research.graph_data_view as gv
                where
                    research_type = 'conference'
                        and (conference_start_date BETWEEN '$rangeFrom' and '$rangeTo')";

        //$result_conference_arr = array();
        $result2 = mysqli_query($con, $sql2);
        if (!$result2) {
          die('Error: ' . mysqli_error($con));
        } else {
          while($row = mysqli_fetch_array($result2)) {
            $result_paper_range[$row["name"]] = $row["count"];
          }
        }

        // result journal
        $sqlJ2 = "SELECT
                    'journal' as name,
                    gv.journal_type,
                    count(id) as count

                FROM
                    research.graph_data_view as gv
                where
                    research_type = 'journal'
                        and journal_type_progress = 'published'
                        and (journal_accepted_date BETWEEN '$rangeFrom' and '$rangeTo')
                group by journal_type";
        $resultJ2 = mysqli_query($con, $sqlJ2);
        if (!$resultJ2) {
          die('Error: ' . mysqli_error($con));
        } else {
          while($row = mysqli_fetch_array($resultJ2)) {
            $result_journal_range[$row["journal_type"]] = $row["count"];
          }
        }

        // result conference
        $sqlC2 = "SELECT
                    'conference' as name,
                    gv.conference_location_type,
                    count(id) as count
                FROM
                    research.graph_data_view as gv
                where
                    research_type = 'conference'
                        and (conference_start_date BETWEEN '$rangeFrom' and '$rangeTo')
                group by conference_location_type";
        $resultC2 = mysqli_query($con, $sqlC2);
        if (!$resultC2) {
          die('Error: ' . mysqli_error($con));
        } else {
          while($row = mysqli_fetch_array($resultC2)) {
            $result_conference_range[$row["conference_location_type"]] = $row["count"];
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
                <th rowspan="2">Range</th>
                <th colspan="2">Journal</th>
                <th rowspan="2">Total</th>
                <th colspan="2">Proceedings</th>
                <th rowspan="2">Total</th>
                <th rowspan="2">Grand Total</th>
              </tr>
              <tr class="info">
                <th>National</th>
                <th>International</th>
                <th>National</th>
                <th>International</th>
              </tr>
            </thead>
            <tbody>

              <tr>
                <td>
                  <?php
                    echo "From ".$rangeFrom." To ".$rangeTo;
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["international"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_paper_range["journal"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_conference_range["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_conference_range["international"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_paper_range["conference"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_paper_range["conference"]
                      +$result_paper_range["journal"];
                  ?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div><!--end data row-->

    </div> <!-- end container -->


  </body>
</html>
