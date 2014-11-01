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
      ?>

      // Google chart
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Year', 'Proceedings', 'Journal'],
          <?php
            $i = count($result_paper_year_arr)>3 ? 2 : count($result_paper_year_arr)-1;
            for($i=$i; $i>=0; $i--) {
          ?>
          ['<?php echo $result_paper_year_arr[$i];?>',
            <?php echo 0+$result_paper_arr[$result_paper_year_arr[$i]]["conference"]; ?>,
            <?php echo 0+$result_paper_arr[$result_paper_year_arr[$i]]["journal"]; ?>
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
                <th>Year</th>
                <th>Journal</th>
                <th>Proceedings</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $sum_conference = 0;
                $sum_journal = 0;
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
                    echo 0+$result_paper_arr[$result_paper_year_arr[$i]]["conference"];
                    $sum_conference += 0+$result_paper_arr[$result_paper_year_arr[$i]]["conference"];
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
                <th><?php echo $sum_conference; ?></th>
                <th><?php echo $sum_journal; ?></th>
                <th><?php echo $sum_total; ?></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

    </div> <!-- end container -->


  </body>
</html>
