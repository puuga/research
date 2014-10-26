<?php //summary_journal.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php include "class_import.php"; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Summary Journal</title>

    <?php include 'head_tag.php'; ?>

    

    <!-- start google chart -->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">


      // Google chart
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Year', 'Chemistry', 'Physics', 'Biology', 'Mathematics', 'CSIT'],
          ['2012', 90, 100, 110, 105, 110],
          ['2013', 100, 110, 120, 115, 120],
          ['2014', 101, 111, 121, 116, 121]
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
                <th>Department</th>
                <th>year</th>
                <th>Journal</th>
                <th>Proceedings</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td rowspan="2">Chemistry</td>
                <td>2013</td>
                <td>50</td>
                <td>50</td>
              </tr>
              <tr>
                <td>2014</td>
                <td>60</td>
                <td>60</td>
              </tr>
              <tr>
                <td rowspan="2">Physics</td>
                <td>2013</td>
                <td>50</td>
                <td>50</td>
              </tr>
              <tr>
                <td>2014</td>
                <td>60</td>
                <td>60</td>
              </tr>
              <tr>
                <td rowspan="2">Biology</td>
                <td>2013</td>
                <td>50</td>
                <td>50</td>
              </tr>
              <tr>
                <td>2014</td>
                <td>60</td>
                <td>60</td>
              </tr>
              <tr>
                <td rowspan="2">Mathematics</td>
                <td>2013</td>
                <td>50</td>
                <td>50</td>
              </tr>
              <tr>
                <td>2014</td>
                <td>60</td>
                <td>60</td>
              </tr>
              <tr>
                <td rowspan="2">CSIT</td>
                <td>2013</td>
                <td>50</td>
                <td>50</td>
              </tr>
              <tr>
                <td>2014</td>
                <td>60</td>
                <td>60</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div> <!-- end container -->


  </body>
</html>
