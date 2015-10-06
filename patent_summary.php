<?php // patent_summary.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php include "class_import.php"; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Science Research</title>

    <?php include 'head_tag.php'; ?>
    <?php
    // chart data
    $sql = "SELECT year(grant_date) year_grant_date, patent_type_id, count(id) count_id
            from patents
            group by patent_type_id, year_grant_date
            order by year_grant_date, patent_type_id";
    $result = mysqli_query($con, $sql);
    if (!$result) {
      die('Error: ' . mysqli_error($con));
    } else {
      while($row = mysqli_fetch_array($result)) {
        $patents_count[$row["year_grant_date"]][$row["patent_type_id"]] = $row["count_id"];

        // define min, max
        $year_max = !isset($year_max) ? $row["year_grant_date"] : $year_max;
        $year_min = !isset($year_min) ? $row["year_grant_date"] : $year_min;

        // find min, max
        $year_max = $row["year_grant_date"] > $year_max ? $row["year_grant_date"] : $year_max;
        $year_min = $row["year_grant_date"] < $year_min ? $row["year_grant_date"] : $year_min;
      }
    }
    $text_copyright = 'ลิขสิทธิ์';
    $text_patent = 'สิทธิบัตร';
    $text_subpatent = 'อนุสิทธิบัตร';
    ?>

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      var text_copyright = '<?php echo $text_copyright; ?>';
      var text_patent = '<?php echo $text_patent; ?>';
      var text_subpatent = '<?php echo $text_subpatent; ?>';
      google.load("visualization", "1.1", {packages:["bar"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', text_copyright, text_patent, text_subpatent],
          <?php
          for ($i=$year_min; $i <= $year_max; $i++) {
            $x1 = isset($patents_count[$i][1]) ? $patents_count[$i][1] : 0;
            $x2 = isset($patents_count[$i][2]) ? $patents_count[$i][2] : 0;
            $x3 = isset($patents_count[$i][3]) ? $patents_count[$i][3] : 0;
            echo "['$i',$x1,$x2,$x3],";
          }
          ?>
        ]);

        var options = {
          chart: {
            title: 'Patent Summary',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, options);
      }
    </script>


  </head>
  <body>
    <?php include 'navbar.php'; ?>

    <?php printLogo(); ?>
    
    <div class="container">

    <!-- <?php
    echo $year_max."<br/>";
    echo $year_min."<br/>";
    print_r($patents_count);
    ?> -->

    <div class="panel panel-default">
      <div class="panel-body">
        <div id="columnchart_material" style="width: 1000px; height: 500px;"></div>
      </div>
    </div>

    <?php
      function makeLink($year, $patent_type, $number_of_patents) {
        if ( $year == 0 ) {
          switch ($patent_type) {
            case 0:
              return '<a href="patent.php">'.$number_of_patents.'</a>';
            case 1:
              return '<a href="patent.php?view=copyright">'.$number_of_patents.'</a>';
            case 2:
            case 3:
              return '<a href="patent.php?view=patent">'.$number_of_patents.'</a>';
            default:
              return "";
          }
        }
        switch ($patent_type) {
          case 0:
            return '<a href="patent.php?year='.$year.'">'.$number_of_patents.'</a>';
          case 1:
            return '<a href="patent.php?year='.$year.'&view=copyright">'.$number_of_patents.'</a>';
          case 2:
          case 3:
            return '<a href="patent.php?year='.$year.'&view=patent">'.$number_of_patents.'</a>';
          default:
            return "";
        }
      }
    ?>

    <table class="table table-striped table-bordered table-hover">
      <thead>
        <tr>
          <th>Year</th>
          <th><?php echo $text_copyright; ?></th>
          <th><?php echo $text_patent; ?></th>
          <th><?php echo $text_subpatent; ?></th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sum_x1 = 0;
        $sum_x2 = 0;
        $sum_x3 = 0;
        for ($i=$year_min; $i <= $year_max; $i++) {
          $x1 = isset($patents_count[$i][1]) ? $patents_count[$i][1] : 0;
          $x2 = isset($patents_count[$i][2]) ? $patents_count[$i][2] : 0;
          $x3 = isset($patents_count[$i][3]) ? $patents_count[$i][3] : 0;
          $sum_x1 += $x1;
          $sum_x2 += $x2;
          $sum_x3 += $x3;
          echo "<tr>";
          echo "<td>$i</td>";
          // echo "<td>$x1</td>";
          echo "<td>".makeLink($i, 1, $x1)."</td>";
          // echo "<td>$x2</td>";
          echo "<td>".makeLink($i, 2, $x2)."</td>";
          // echo "<td>$x3</td>";
          echo "<td>".makeLink($i, 3, $x3)."</td>";
          // echo "<td>".($x1+$x2+$x3)."</td>";
          echo "<td>".makeLink($i, 0, $x1+$x2+$x3)."</td>";
          echo "</tr>";
        }
        ?>
      </tbody>
      <tfoot>
        <tr class="info">
          <th>Total</th>
          <!-- <th><?php echo $sum_x1; ?></th> -->
          <th><?php echo makeLink(0, 1, $sum_x1); ?></th>
          <!-- <th><?php echo $sum_x2; ?></th> -->
          <th><?php echo makeLink(0, 2, $sum_x2); ?></th>
          <!-- <th><?php echo $sum_x3; ?></th> -->
          <th><?php echo makeLink(0, 2, $sum_x3); ?></th>
          <!-- <th><?php echo $sum_x1+$sum_x2+$sum_x3; ?></th> -->
          <th><?php echo makeLink(0, 0, $sum_x1+$sum_x2+$sum_x3); ?></th>
        </tr>
      </tfoot>
    </table>

    </div> <!-- end container -->

  </body>
</html>
