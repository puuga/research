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
            $result_journal_arr[$row["journal_year"]][$row["department_en"]]["isi"] = $row["count"];
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
            $result_journal_arr[$row["journal_year"]][$row["department_en"]]["scopus"] = $row["count"];
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
            $result_journal_arr[$row["journal_year"]][$row["department_en"]]["sjr"] = $row["count"];
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
            $result_journal_arr[$row["journal_year"]][$row["department_en"]]["other"] = $row["count"];
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
            $i = count($result_journal_year_arr)>3 ? 2 : count($result_journal_year_arr)-1;
            for($i=$i; $i>=0; $i--) {
          ?>
          ['<?php echo $result_journal_year_arr[$i];?>',
            <?php
              echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["national"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["isi"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["scopus"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["sjr"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["other"];
                +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["international"];
            ?>,
            <?php
              echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["national"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["isi"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["scopus"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["sjr"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["other"];
                +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["international"];
            ?>,
            <?php
              echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["national"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["isi"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["scopus"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["sjr"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["other"];
                +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["international"];
            ?>,
            <?php
              echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["national"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["isi"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["scopus"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["sjr"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["other"];
                +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["international"];
            ?>,
            <?php
              echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["national"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["isi"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["scopus"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["sjr"]
                // +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["other"];
                +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["international"];
            ?>
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
          vAxis: {title: 'Number of journal', titleTextStyle: {color: 'red'}}
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
    ?>


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
                <th>Year <?php echo $result_journal_year_arr[$i];?></th>
                <th rowspan="2">National Journal</th>
                <th colspan="5">International Journal</th>
                <th rowspan="2">All Total</th>
              </tr>
              <tr class="info">
                <th>Department</th>
                <th>ISI</th>
                <th>Scopus</th>
                <th>SJR</th>
                <th>Others</th>
                <th>Total</th>
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
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["isi"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["scopus"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["sjr"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["other"];
                  ?>
                </td>
                <td>
                  <?php
                    // echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["national"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["isi"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["scopus"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["sjr"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["other"];
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["international"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["international"];
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
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["isi"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["scopus"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["sjr"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["other"];
                  ?>
                </td>
                <td>
                  <?php
                    // echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["national"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["isi"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["scopus"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["sjr"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["other"];
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["international"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["international"];
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
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["isi"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["scopus"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["sjr"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["other"];
                  ?>
                </td>
                <td>
                  <?php
                    // echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["national"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["isi"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["scopus"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["sjr"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["other"];
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["international"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["international"];
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
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["isi"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["scopus"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["sjr"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["other"];
                  ?>
                </td>
                <td>
                  <?php
                    // echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["national"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["isi"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["scopus"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["sjr"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["other"];
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["international"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["international"];
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
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["isi"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["scopus"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["sjr"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["other"];
                  ?>
                </td>
                <td>
                  <?php
                    // echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["national"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["isi"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["scopus"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["sjr"]
                    //   +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["other"];
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["international"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["international"];
                  ?>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr class="warning">
                <th>Total</th>
                <th>
                  <a href="<?php echo makeLink(true,false,true,false,$result_journal_year_arr[$i]); ?>">
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["national"];
                  ?>
                  </a>
                </th>
                <th>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["isi"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["isi"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["isi"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["isi"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["isi"];
                  ?>
                </th>
                <th>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["scopus"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["scopus"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["scopus"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["scopus"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["scopus"];
                  ?>
                </th>
                <th>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["sjr"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["sjr"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["sjr"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["sjr"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["sjr"];
                  ?>
                </th>
                <th>
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["other"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["other"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["other"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["other"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["other"];
                  ?>
                </th>
                <th>
                  <a href="<?php echo makeLink(true,false,false,true,$result_journal_year_arr[$i]); ?>">
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["international"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["international"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["international"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["international"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["international"];
                  ?>
                  </a>
                </th>
                <th>
                  <a href="<?php echo makeLink(true,false,true,true,$result_journal_year_arr[$i]); ?>">
                  <?php
                    echo 0+$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["national"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Chemistry"]["international"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Physics"]["international"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Biology"]["international"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Mathematics"]["international"]
                      +$result_journal_arr[$result_journal_year_arr[$i]]["Computer Science and Information Technology"]["international"];
                  ?>
                  </a>
                </th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <?php
          } // end if
        } // end for
      ?>
      <?php
        // print_r($result_journal_arr);
      ?>

      <div class="row bg-primary">
        <div class="col-md-12">
          <h2>Range Summary Proceedings</h2>
        </div>

        <?php
          $rangeFrom = isset($_GET['rangeFrom']) ? $_GET['rangeFrom'] : date("Y").'-01-01' ;
          $rangeTo = isset($_GET['rangeTo']) ? $_GET['rangeTo'] : date("Y").'-12-31' ;
        ?>

        <div class="col-md-12">
          <form class="form-inline"></form>

          <form class="form-inline" action="summary_journal.php" method="get">
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
        // select journal_national_international_count
        $sql2 = "SELECT
                    'journal' AS name,
                    dv.department_en,
                    sc.scope AS scope,
                    COUNT(id) AS count
                FROM
                    research.graph_data_view AS gv,
                    department_view AS dv,
                    scope AS sc
                WHERE
                    research_type = 'journal'
                        AND journal_type_progress = 'published'
                        AND (journal_accepted_date BETWEEN '$rangeFrom' AND '$rangeTo')
                        AND gv.department_en = dv.department_en
                        AND gv.journal_type = sc.scope COLLATE utf8_unicode_ci
                GROUP BY department_en , scope";

        $result2 = mysqli_query($con, $sql2);
        if (!$result2) {
          die('Error: ' . mysqli_error($con));
        } else {
          while( $row = mysqli_fetch_array($result2) ) {
            $result_journal_range[$row["department_en"]][$row["scope"]] = $row["count"];
          }
        }

        // select journal_international_isi_count
        $sql3 = "SELECT
                    'journal' AS name,
                    dv.department_en,
                    'international' AS scope,
                    'isi' AS type,
                    COUNT(id) AS count
                FROM
                    research.graph_data_view AS gv,
                    department_view AS dv
                WHERE
                    research_type = 'journal'
                        AND journal_type_progress = 'published'
                        AND (journal_accepted_date BETWEEN '$rangeFrom' AND '$rangeTo')
                        AND gv.department_en = dv.department_en
                        AND gv.journal_type = 'international'
                        AND is_journal_international_ISI = 1
                GROUP BY department_en , scope";

        $result3 = mysqli_query($con, $sql3);
        if (!$result3) {
          die('Error: ' . mysqli_error($con));
        } else {
          while( $row = mysqli_fetch_array($result3) ) {
            $result_journal_range[$row["department_en"]]["isi"] = $row["count"];
          }
        }

        // select journal_international_scopus_count
        $sql4 = "SELECT
                    'journal' AS name,
                    dv.department_en,
                    'international' AS scope,
                    'scopus' AS type,
                    COUNT(id) AS count
                FROM
                    research.graph_data_view AS gv,
                    department_view AS dv
                WHERE
                    research_type = 'journal'
                        AND journal_type_progress = 'published'
                        AND (journal_accepted_date BETWEEN '$rangeFrom' AND '$rangeTo')
                        AND gv.department_en = dv.department_en
                        AND gv.journal_type = 'international'
                        AND is_journal_international_SCOPUS = 1
                GROUP BY department_en , scope";

        $result4 = mysqli_query($con, $sql4);
        if (!$result4) {
          die('Error: ' . mysqli_error($con));
        } else {
          while( $row = mysqli_fetch_array($result4) ) {
            $result_journal_range[$row["department_en"]]["scopus"] = $row["count"];
          }
        }

        // select journal_international_sjr_count
        $sql5 = "SELECT
                    'journal' AS name,
                    dv.department_en,
                    'international' AS scope,
                    'sjr' AS type,
                    COUNT(id) AS count
                FROM
                    research.graph_data_view AS gv,
                    department_view AS dv
                WHERE
                    research_type = 'journal'
                        AND journal_type_progress = 'published'
                        AND (journal_accepted_date BETWEEN '$rangeFrom' AND '$rangeTo')
                        AND gv.department_en = dv.department_en
                        AND gv.journal_type = 'international'
                        AND is_journal_international_SJR = 1
                GROUP BY department_en , scope";

        $result5 = mysqli_query($con, $sql5);
        if (!$result5) {
          die('Error: ' . mysqli_error($con));
        } else {
          while( $row = mysqli_fetch_array($result5) ) {
            $result_journal_range[$row["department_en"]]["sjr"] = $row["count"];
          }
        }

        // select journal_international_other_count
        $sql6 = "SELECT
                    'journal' AS name,
                    dv.department_en,
                    'international' AS scope,
                    'other' AS type,
                    COUNT(id) AS count
                FROM
                    research.graph_data_view AS gv,
                    department_view AS dv
                WHERE
                    research_type = 'journal'
                        AND journal_type_progress = 'published'
                        AND (journal_accepted_date BETWEEN '$rangeFrom' AND '$rangeTo')
                        AND gv.department_en = dv.department_en
                        AND gv.journal_type = 'international'
                        AND is_journal_international_ISI = 0
                        AND is_journal_international_SCOPUS = 0
                        AND is_journal_international_SJR = 0
                GROUP BY department_en , scope";

        $result6 = mysqli_query($con, $sql6);
        if (!$result6) {
          die('Error: ' . mysqli_error($con));
        } else {
          while( $row = mysqli_fetch_array($result6) ) {
            $result_journal_range[$row["department_en"]]["other"] = $row["count"];
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
                <th>Year <?php echo $result_journal_year_arr[$i];?></th>
                <th rowspan="2">National Journal</th>
                <th colspan="5">International Journal</th>
                <th rowspan="2">All Total</th>
              </tr>
              <tr class="info">
                <th>Department</th>
                <th>ISI</th>
                <th>Scopus</th>
                <th>SJR</th>
                <th>Others</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Chemistry</td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Chemistry"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Chemistry"]["isi"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Chemistry"]["scopus"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Chemistry"]["sjr"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Chemistry"]["other"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Chemistry"]["international"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Chemistry"]["national"]
                      +$result_journal_range["Chemistry"]["international"];
                  ?>
                </td>
              </tr>
              <tr>
                <td>Physics</td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Physics"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Physics"]["isi"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Physics"]["scopus"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Physics"]["sjr"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Physics"]["other"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Physics"]["international"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Physics"]["national"]
                      +$result_journal_range["Physics"]["international"];
                  ?>
                </td>
              </tr>
              <tr>
                <td>Biology</td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Biology"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Biology"]["isi"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Biology"]["scopus"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Biology"]["sjr"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Biology"]["other"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Biology"]["international"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Biology"]["national"]
                      +$result_journal_range["Biology"]["international"];
                  ?>
                </td>
              </tr>
              <tr>
                <td>Mathematics</td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Mathematics"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Mathematics"]["isi"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Mathematics"]["scopus"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Mathematics"]["sjr"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Mathematics"]["other"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Mathematics"]["international"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Mathematics"]["national"]
                      +$result_journal_range["Mathematics"]["international"];
                  ?>
                </td>
              </tr>
              <tr>
                <td>CSIT</td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Computer Science and Information Technology"]["national"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Computer Science and Information Technology"]["isi"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Computer Science and Information Technology"]["scopus"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Computer Science and Information Technology"]["sjr"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Computer Science and Information Technology"]["other"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Computer Science and Information Technology"]["international"];
                  ?>
                </td>
                <td>
                  <?php
                    echo 0+$result_journal_range["Computer Science and Information Technology"]["national"]
                      +$result_journal_range["Computer Science and Information Technology"]["international"];
                  ?>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr class="warning">
                <th>Total</th>
                <th>
                  <?php
                    echo 0+$result_journal_range["Chemistry"]["national"]
                      +$result_journal_range["Physics"]["national"]
                      +$result_journal_range["Biology"]["national"]
                      +$result_journal_range["Mathematics"]["national"]
                      +$result_journal_range["Computer Science and Information Technology"]["national"];
                  ?>
                </th>
                <th>
                  <?php
                    echo 0+$result_journal_range["Chemistry"]["isi"]
                      +$result_journal_range["Physics"]["isi"]
                      +$result_journal_range["Biology"]["isi"]
                      +$result_journal_range["Mathematics"]["isi"]
                      +$result_journal_range["Computer Science and Information Technology"]["isi"];
                  ?>
                </th>
                <th>
                  <?php
                    echo 0+$result_journal_range["Chemistry"]["scopus"]
                      +$result_journal_range["Physics"]["scopus"]
                      +$result_journal_range["Biology"]["scopus"]
                      +$result_journal_range["Mathematics"]["scopus"]
                      +$result_journal_range["Computer Science and Information Technology"]["scopus"];
                  ?>
                </th>
                <th>
                  <?php
                    echo 0+$result_journal_range["Chemistry"]["sjr"]
                      +$result_journal_range["Physics"]["sjr"]
                      +$result_journal_range["Biology"]["sjr"]
                      +$result_journal_range["Mathematics"]["sjr"]
                      +$result_journal_range["Computer Science and Information Technology"]["sjr"];
                  ?>
                </th>
                <th>
                  <?php
                    echo 0+$result_journal_range["Chemistry"]["other"]
                      +$result_journal_range["Physics"]["other"]
                      +$result_journal_range["Biology"]["other"]
                      +$result_journal_range["Mathematics"]["other"]
                      +$result_journal_range["Computer Science and Information Technology"]["other"];
                  ?>
                </th>
                <th>
                  <?php
                    echo 0+$result_journal_range["Chemistry"]["international"]
                      +$result_journal_range["Physics"]["international"]
                      +$result_journal_range["Biology"]["international"]
                      +$result_journal_range["Mathematics"]["international"]
                      +$result_journal_range["Computer Science and Information Technology"]["international"];
                  ?>
                </th>
                <th>
                  <?php
                    echo 0+$result_journal_range["Chemistry"]["national"]
                      +$result_journal_range["Physics"]["national"]
                      +$result_journal_range["Biology"]["national"]
                      +$result_journal_range["Mathematics"]["national"]
                      +$result_journal_range["Computer Science and Information Technology"]["national"]
                      +$result_journal_range["Chemistry"]["international"]
                      +$result_journal_range["Physics"]["international"]
                      +$result_journal_range["Biology"]["international"]
                      +$result_journal_range["Mathematics"]["international"]
                      +$result_journal_range["Computer Science and Information Technology"]["international"];
                  ?>
                </th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div><!--end data row-->

      <div>
        <?php // print_r($result_journal_range); ?>
      </div>

    </div> <!-- end container -->


  </body>
</html>
