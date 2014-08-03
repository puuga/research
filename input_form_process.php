<?php //input_form_process.php ?>

<?php include 'db_connect.php'; ?>

<!DOCTYPE html>

<html>
  <head>
    <meta charset="UTF-8">

    <title>Science Research System</title>

    <link rel="stylesheet" type="text/css" href="css/main_style.css">

    <script src="script/jquery-2.1.1.min.js"></script>
    <script type="text/javascript">

    </script>
  </head>
  <body>
    <?php
      // set sql
      $sql = "";

      $research_name = isset($_GET["research_name"]) ? $_GET["research_name"] : $_GET["research_name"] ;
      //echo "research_name".$research_name."<br/>";
      $isStudentProduct = isset($_GET["isStudentProduct"]) ? $_GET["isStudentProduct"] : "false" ;
      //echo "isStudentProduct".$isStudentProduct."<br/>";
      $researchers = array();
      $researchers[0]["researcher_name"] = isset($_GET["researcher0"]) ? $_GET["researcher0"] : "" ;
      $researchers[0]["researcher_name_th"] = isset($_GET["researcher0_th"]) ? $_GET["researcher0_th"] : "" ;
      $researchers[0]["researcher_name_en"] = isset($_GET["researcher0_en"]) ? $_GET["researcher0_en"] : "" ;
      $researchers[0]["isFirstResearcher"] = isset($_GET["isFirstResearcher0"]) && !empty($_GET["isFirstResearcher0"]) ? $_GET["isFirstResearcher0"] : "false" ;
      $researchers[0]["isCorresponding"] = isset($_GET["isCorresponding0"]) && !empty($_GET["isCorresponding0"]) ? $_GET["isCorresponding0"] : "false" ;
      $researchers[0]["departments"] = isset($_GET["department0"]) ? $_GET["department0"] : "" ;

      $researcher_amount = isset($_GET["researcher_amount"]) ? $_GET["researcher_amount"] : 0 ;
      //print_r($researchers);
      //echo "<br/>";
      //echo "researcher_amount".$researcher_amount."<br/>";

      // set sql
      $author_name_th = $researchers[0]["researcher_name_th"];
      $author_name_en = $researchers[0]["researcher_name_en"];
      $firstname = $researchers[0]["isFirstResearcher"] == "true" ? $researchers[0]["researcher_name"] : "";
      $corresponding = $researchers[0]["isCorresponding"] == "true" ? $researchers[0]["researcher_name"] : "";
      $department = $researchers[0]["departments"];


      for( $i=0; $i<$researcher_amount; $i++) {
        //echo "i:".$i."<br/>";
        //echo "i:".$_GET["researcher".($i+1)]."<br/>";

        $researchers[($i+1)]["researcher_name"] = isset($_GET["researcher".($i+1)]) ? $_GET["researcher".($i+1)] : "" ;
        $researchers[($i+1)]["researcher_name_th"] = isset($_GET["researcher".($i+1)."_th"]) ? $_GET["researcher".($i+1)."_th"] : "" ;
        $researchers[($i+1)]["researcher_name_en"] = isset($_GET["researcher".($i+1)."_en"]) ? $_GET["researcher".($i+1)."_en"] : "" ;
        $researchers[($i+1)]["isFirstResearcher"] = isset($_GET["isFirstResearcher".($i+1)]) && !empty($_GET["isFirstResearcher".($i+1)]) ? $_GET["isFirstResearcher".($i+1)] : "false" ;
        $researchers[($i+1)]["isCorresponding"] = isset($_GET["isCorresponding".($i+1)]) && !empty($_GET["isCorresponding".($i+1)]) ? $_GET["isCorresponding".($i+1)] : "false" ;
        $researchers[($i+1)]["departments"] = isset($_GET["department".($i+1)]) ? $_GET["department".($i+1)] : "" ;

        // set sql
        $author_name_th .= ", ".$researchers[($i+1)]["researcher_name_th"];
        $author_name_en .= ", ".$researchers[($i+1)]["researcher_name_en"];
        $firstname = $researchers[($i+1)]["isFirstResearcher"] == "true" ? $researchers[($i+1)]["researcher_name"] : $firstname;
        $corresponding = $researchers[($i+1)]["isCorresponding"] == "true" ? $researchers[($i+1)]["researcher_name"] : $corresponding;
        $department .= ", ".$researchers[($i+1)]["departments"];
      }

      //print_r($researchers);
      //echo "<hr/>";


      // type
      $type = isset($_GET["type"]) ?  $_GET["type"] : "" ;
      if ($type == "journal") {
        $journal_name = isset($_GET["journal_name"]) ? $_GET["journal_name"] : "" ;
        $journal_type = isset($_GET["journal_type"]) ? $_GET["journal_type"] : "" ;

        if ($journal_type == "national") {
          $journal_national_group = isset($_GET["journal_national_group"]) ? $_GET["journal_national_group"] : "" ;
        } else if ($journal_type == "international") {
          $is_journal_international_ISI = isset($_GET["is_journal_international_ISI"]) && !empty($_GET["is_journal_international_ISI"]) ? $_GET["is_journal_international_ISI"] : "false" ;
          //echo "is_journal_international_ISI".$is_journal_international_ISI."<br/>";
          $is_journal_international_SCOPUS = isset($_GET["is_journal_international_SCOPUS"]) && !empty($_GET["is_journal_international_SCOPUS"]) ? $_GET["is_journal_international_SCOPUS"] : "false" ;
          //echo "is_journal_international_SCOPUS".$is_journal_international_SCOPUS."<br/>";
          $is_journal_international_SJR = isset($_GET["is_journal_international_SJR"]) && !empty($_GET["is_journal_international_SJR"]) ? $_GET["is_journal_international_SJR"] : "false" ;
          //echo "is_journal_international_SJR".$is_journal_international_SJR."<br/>";
          if ($is_journal_international_SJR != "false") {
            $journal_international_group_sjr = isset($_GET["journal_international_group_sjr"]) ? $_GET["journal_international_group_sjr"] : "" ;
          }
        }

        $journal_type_progress = isset($_GET["journal_type_progress"]) ? $_GET["journal_type_progress"] : "" ;
        if ($journal_type_progress == "public") {
          $journal_vol = isset($_GET["journal_vol"]) ? $_GET["journal_vol"] : "" ;
          $journal_issue = isset($_GET["journal_issue"]) ? $_GET["journal_issue"] : "" ;
          $journal_number = isset($_GET["journal_number"]) ? $_GET["journal_number"] : "" ;
          $journal_page_start = isset($_GET["journal_page_start"]) ? $_GET["journal_page_start"] : "" ;
          $journal_page_end = isset($_GET["journal_page_end"]) ? $_GET["journal_page_end"] : "" ;
          $journal_doi_no = isset($_GET["journal_doi_no"]) ? $_GET["journal_doi_no"] : "" ;
          $journal_accepted_date = isset($_GET["journal_accepted_date"]) ? $_GET["journal_accepted_date"] : "" ;
          $journal_published_month = isset($_GET["journal_published_month"]) ? $_GET["journal_published_month"] : "" ;
          $journal_published_year = isset($_GET["journal_published_year"]) ? $_GET["journal_published_year"] : "" ;
        } else if ($journal_type_progress == "inpress") {

        }



      } else if ($type == "conference") {
        $conference_name = isset($_GET["conference_name"]) ?  $_GET["conference_name"] : "" ;
        $conference_address = isset($_GET["conference_address"]) ?  $_GET["conference_address"] : "" ;
        $conference_start_date = isset($_GET["conference_start_date"]) ?  $_GET["conference_start_date"] : "" ;
        $conference_end_date = isset($_GET["conference_end_date"]) ?  $_GET["conference_end_date"] : "" ;
        $conference_page_start = isset($_GET["conference_page_start"]) ?  $_GET["conference_page_start"] : "" ;
        $conference_page_end = isset($_GET["conference_page_end"]) ?  $_GET["conference_page_end"] : "" ;
        $conference_location_type = isset($_GET["conference_location_type"]) ?  $_GET["conference_location_type"] : "false" ;
        $conference_type = isset($_GET["conference_type"]) ?  $_GET["conference_type"] : "" ;
      }


      $att_file = "";
      $reference = isset($_GET["reference"]) ? $_GET["reference"] : "" ;
      //echo "reference".$reference."<br/>";

      $sql .= "INSERT INTO research (
        title,
        is_student_grad,
        author_name_th,
        author_name_en,
        firstname,
        corresponding,
        department,
        research_type,
        journal_name,
        journal_type,
        journal_national_group,
        is_journal_international_ISI,
        is_journal_international_SCOPUS,
        is_journal_international_SJR,
        journal_international_group_sjr,
        journal_type_progress,
        journal_vol,
        journal_issue,
        journal_number,
        journal_page_start,
        journal_page_end,
        journal_doi_no,
        journal_accepted_date,
        journal_published_month,
        journal_published_year,
        conference_name,
        conference_address,
        conference_start_date,
        conference_end_date,
        conference_page_start,
        conference_page_end,
        conference_location_type,
        conference_type,
        att_file,
        reference
        ) VALUES ";
      $sql .= "(
        '$research_name',
        $isStudentProduct,
        '$author_name_th',
        '$author_name_en',
        '$firstname',
        '$corresponding',
        '$department',
        '$type',
        '$journal_name',
        '$journal_type',
        '$journal_national_group',
        '$is_journal_international_ISI',
        '$is_journal_international_SCOPUS',
        '$is_journal_international_SJR',
        '$journal_international_group_sjr',
        '$journal_type_progress',
        '$journal_vol',
        '$journal_issue',
        '$journal_number',
        '$journal_page_start',
        '$journal_page_end',
        '$journal_doi_no',
        '$journal_accepted_date',
        '$journal_published_month',
        '$journal_published_year',
        '$conference_name',
        '$conference_address',
        '$conference_start_date',
        '$conference_end_date',
        '$conference_page_start',
        '$conference_page_end',
        '$conference_location_type',
        '$conference_type',
        '$att_file',
        '$reference'
        );";

      $result = mysqli_query($con, $sql);
      if (!mysqli_query($con,$sql)) {
        die('Error: ' . mysqli_error($con));
      }
      echo "1 record added";
      mysqli_close($con);



      //echo "<hr/>";
      $arr = GET_defined_vars();
      echo "<hr/>";
      // print all the available keys for the arrays of variables
      print_r($arr);
      echo "<hr/>";
      print_r(array_keys(GET_defined_vars()));
      echo "<hr/>";
      echo $sql;
    ?>
  </body>
</html>
