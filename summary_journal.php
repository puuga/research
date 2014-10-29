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

      <?php
        // select journal_year
        $sql = "SELECT * FROM research.journal_year";
        $result_journal_year_arr = array();
        $result = mysqli_query($con, $sql);
        if (!$result) {
          die('Error: ' . mysqli_error($con));
        } else {
          while($row = mysqli_fetch_array($result)) {
            $result_journal_year_arr[] = $row["journal_year"];
          }
        }

        // select journal_national_international_count
        $sql = "SELECT
                    jy.journal_year,
                    'journal' as name,
                    dv.department_en,
                    sc.scope as scope,
                    count(id) as count
                FROM
                    journal_year as jy,
                    research.graph_data_view as gv,
                    department_view as dv,
                    scope as sc

                	where
                    research_type = 'journal'
                        and journal_type_progress = 'published'
                        and year(journal_accepted_date) = jy.journal_year
                        and gv.department_en = dv.department_en
                and gv.journal_type = sc.scope COLLATE utf8_unicode_ci

                group by journal_year , department_en , scope";
        //$result_conference_arr = array();
        $result = mysqli_query($con, $sql);
        if (!$result) {
          die('Error: ' . mysqli_error($con));
        } else {
          while($row = mysqli_fetch_array($result)) {
            $result_journal_arr[$row["journal_year"]][$row["department_en"]][$row["scope"]] = $row["count"];
          }
        }

        // select journal_international_isi_count
        $sql = "SELECT
                    jy.journal_year,
                    'journal' as name,
                    dv.department_en,
                    'international' as scope,
                    'isi' as type,
                    count(id) as count
                FROM
                    journal_year as jy,
                    research.graph_data_view as gv,
                    department_view as dv

                	where
                    research_type = 'journal'
                        and journal_type_progress = 'published'
                        and year(journal_accepted_date) = jy.journal_year
                        and gv.department_en = dv.department_en
                and gv.journal_type = 'international'
                and is_journal_international_ISI = 1

                group by journal_year , department_en , scope";
        //$result_conference_arr = array();
        $result = mysqli_query($con, $sql);
        if (!$result) {
          die('Error: ' . mysqli_error($con));
        } else {
          while($row = mysqli_fetch_array($result)) {
            $result_journal_arr[$row["journal_year"]][$row["department_en"]][$row["scope"]][$row["type"]] = $row["count"];
          }
        }

        // select journal_international_scopus_count
        $sql = "SELECT
                    jy.journal_year,
                    'journal' as name,
                    dv.department_en,
                    'international' as scope,
                    'scopus' as type,
                    count(id) as count
                FROM
                    journal_year as jy,
                    research.graph_data_view as gv,
                    department_view as dv

                	where
                    research_type = 'journal'
                        and journal_type_progress = 'published'
                        and year(journal_accepted_date) = jy.journal_year
                        and gv.department_en = dv.department_en
                and gv.journal_type = 'international'
                and is_journal_international_SCOPUS = 1

                group by journal_year , department_en , scope";
        //$result_conference_arr = array();
        $result = mysqli_query($con, $sql);
        if (!$result) {
          die('Error: ' . mysqli_error($con));
        } else {
          while($row = mysqli_fetch_array($result)) {
            $result_journal_arr[$row["journal_year"]][$row["department_en"]][$row["scope"]][$row["type"]] = $row["count"];
          }
        }

        // select journal_international_sjr_count
        $sql = "SELECT
                    jy.journal_year,
                    'journal' as name,
                    dv.department_en,
                    'international' as scope,
                    'sjr' as type,
                    count(id) as count
                FROM
                    journal_year as jy,
                    research.graph_data_view as gv,
                    department_view as dv

                	where
                    research_type = 'journal'
                        and journal_type_progress = 'published'
                        and year(journal_accepted_date) = jy.journal_year
                        and gv.department_en = dv.department_en
                and gv.journal_type = 'international'
                and is_journal_international_SJR = 1

                group by journal_year , department_en , scope";
        //$result_conference_arr = array();
        $result = mysqli_query($con, $sql);
        if (!$result) {
          die('Error: ' . mysqli_error($con));
        } else {
          while($row = mysqli_fetch_array($result)) {
            $result_journal_arr[$row["journal_year"]][$row["department_en"]][$row["scope"]][$row["type"]] = $row["count"];
          }
        }

        // select journal_international_other_count
        $sql = "SELECT
                    jy.journal_year,
                    'journal' as name,
                    dv.department_en,
                    'international' as scope,
                    'other' as type,
                    count(id) as count
                FROM
                    journal_year as jy,
                    research.graph_data_view as gv,
                    department_view as dv

                	where
                    research_type = 'journal'
                        and journal_type_progress = 'published'
                        and year(journal_accepted_date) = jy.journal_year
                        and gv.department_en = dv.department_en
                and gv.journal_type = 'international'
                and is_journal_international_ISI = 0
                and is_journal_international_SCOPUS = 0
                and is_journal_international_SJR = 0

                group by journal_year , department_en , scope";
        //$result_conference_arr = array();
        $result = mysqli_query($con, $sql);
        if (!$result) {
          die('Error: ' . mysqli_error($con));
        } else {
          while($row = mysqli_fetch_array($result)) {
            $result_journal_arr[$row["journal_year"]][$row["department_en"]][$row["scope"]][$row["type"]] = $row["count"];
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
            for($i=0; $i<2&&$i<count($result_journal_year_arr); $i++) {
          ?>
          ['<?php echo $result_journal_year_arr[$i];?>',
            <?php
              echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["national"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["international"]["isi"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["international"]["scopus"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["international"]["sjr"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["international"]["other"];
            ?>,
            <?php
              echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["national"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["international"]["isi"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["international"]["scopus"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["international"]["sjr"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["international"]["other"];
            ?>,
            <?php
              echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["national"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["international"]["isi"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["international"]["scopus"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["international"]["sjr"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["international"]["other"];
            ?>,
            <?php
              echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["national"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["international"]["isi"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["international"]["scopus"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["international"]["sjr"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["international"]["other"];
            ?>,
            <?php
              echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["national"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["international"]["isi"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["international"]["scopus"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["international"]["sjr"]
                +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["international"]["other"];
            ?>
          ]
          <?php
              if($i != count($result_journal_year_arr)-1) {
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
        <h2>Summary Journal Chart</h2>
        <div id="chart_div" style="width: 100%; height: 400px;"></div>
      </div>
    </div>


    <div class="container">

      <!--title row-->
      <div class="row bg-primary">
        <div class="col-md-10 ">
          <h2>Summary Journal Data</h2>
        </div>

        <div class="col-md-2 ">
          <p><br/>
            <form action="summary_proceedings.php" method="get" role="form">
            <select class="form-control" name="year" onchange="this.form.submit()">
              <?php
                for($i=0; $i<count($result_journal_year_arr); $i++) {
                  ?>
                    <option <?php echo $_GET['year']==$result_journal_year_arr[$i]?"selected":""; ?>>
                      <?php echo $result_journal_year_arr[$i]; ?></option>
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
        for($i=0; $i<count($result_journal_year_arr); $i++) {
          if($result_journal_year_arr[$i]==$_GET["year"]||empty($_GET["year"])) {
      ?>
      <!--data row-->
      <div class="row">
        <div class="col-md-12">
          <table class="table table-hover table-striped">
            <thead>
              <tr class="info">
                <th rowspan="2">Department</th>
                <th rowspan="2">National Journal</th>
                <th colspan="4">International Journal</th>
                <th rowspan="2">Total</th>
              </tr>
              <tr class="info">
                <th>ISI</th>
                <th>Scopus</th>
                <th>SJR</th>
                <th>Others</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Chemistry</td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["international"]["isi"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["international"]["scopus"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["international"]["sjr"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["international"]["other"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["international"]["isi"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["international"]["scopus"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["international"]["sjr"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["international"]["other"];
                  ?>
                </td>
              </tr>
              <tr>
                <td>Physics</td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["international"]["isi"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["international"]["scopus"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["international"]["sjr"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["international"]["other"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["international"]["isi"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["international"]["scopus"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["international"]["sjr"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["international"]["other"];
                  ?>
                </td>
              </tr>
              <tr>
                <td>Biology</td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["international"]["isi"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["international"]["scopus"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["international"]["sjr"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["international"]["other"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["international"]["isi"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["international"]["scopus"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["international"]["sjr"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["international"]["other"];
                  ?>
                </td>
              </tr>
              <tr>
                <td>Mathematics</td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["international"]["isi"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["international"]["scopus"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["international"]["sjr"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["international"]["other"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["international"]["isi"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["international"]["scopus"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["international"]["sjr"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["international"]["other"];
                  ?>
                </td>
              </tr>
              <tr>
                <td>CSIT</td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["international"]["isi"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["international"]["scopus"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["international"]["sjr"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["international"]["other"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["international"]["isi"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["international"]["scopus"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["international"]["sjr"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["international"]["other"];
                  ?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <?php
          } // end if
        } // end for
      ?>

    </div> <!-- end container -->


  </body>
</html>
