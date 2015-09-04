<?php //input_form_process.php ?>
<?php include 'db_connect.php'; ?>
<?php
  // set sql
  $sql = "";

  $research_name = isset($_POST["research_name"]) ? $_POST["research_name"] : $_POST["research_name"] ;
  $research_name = mysqli_real_escape_string($con,$research_name);
  //echo "research_name".$research_name."<br/>";
  $isStudentProduct = isset($_POST["isStudentProduct"]) ? $_POST["isStudentProduct"] : "false" ;
  //echo "isStudentProduct".$isStudentProduct."<br/>";


  $researchers = array();
  $researcher_amount = isset($_POST["researcher_amount"]) ? $_POST["researcher_amount"] : 0 ;
  for( $i=0; $i<=$researcher_amount; $i++) {
    //echo "i:".$i."<br/>";
    //echo "i:".$_POST["researcher".($i+1)]."<br/>";

    $researchers[($i)]["researcher_name"] = isset($_POST["researcher".($i)]) ? $_POST["researcher".($i)] : "" ;
    $researchers[($i)]["researcher_name_th"] = isset($_POST["researcher".($i)."_th"]) ? $_POST["researcher".($i)."_th"] : "" ;
    $researchers[($i)]["researcher_name_en"] = isset($_POST["researcher".($i)."_en"]) ? $_POST["researcher".($i)."_en"] : "" ;
    $researchers[($i)]["departments"] = isset($_POST["department".($i)]) ? $_POST["department".($i)] : "" ;

    // set sql
    if ($i == 0) {
      $author_name_th = $researchers[0]["researcher_name_th"];
      $author_name_en = $researchers[0]["researcher_name_en"];
      $firstname = $researchers[0]["researcher_name_th"].", ".$researchers[0]["researcher_name_en"];
      $department = $researchers[0]["departments"];
    } else {
      $author_name_th .= ", ".$researchers[($i)]["researcher_name_th"];
      $author_name_en .= ", ".$researchers[($i)]["researcher_name_en"];
      $department .= ", ".$researchers[($i)]["departments"];
    }

    if ( $_POST["corresponding"] == "".$i) {
      $corresponding = $researchers[$i]["researcher_name_th"].", ".$researchers[$i]["researcher_name_en"];
    }

  }

  //print_r($researchers);
  //echo "<hr/>";


  // type
  $type = isset($_POST["type"]) ?  $_POST["type"] : "" ;
  if ($type == "journal") {
    $journal_name = isset($_POST["journal_name"]) ? $_POST["journal_name"] : "" ;
    $journal_name = mysqli_real_escape_string($con,$journal_name);
    $journal_type = isset($_POST["journal_type"]) ? $_POST["journal_type"] : "" ;

    if ($journal_type == "national") {
      $journal_national_group = isset($_POST["journal_national_group"]) ? $_POST["journal_national_group"] : "" ;
    } else if ($journal_type == "international") {
      $is_journal_international_ISI = isset($_POST["is_journal_international_ISI"]) && !empty($_POST["is_journal_international_ISI"]) ? $_POST["is_journal_international_ISI"] : "false" ;
      $is_journal_international_ISI = $is_journal_international_ISI == "false" ? 0 : 1 ;
      $is_journal_international_SCOPUS = isset($_POST["is_journal_international_SCOPUS"]) && !empty($_POST["is_journal_international_SCOPUS"]) ? $_POST["is_journal_international_SCOPUS"] : "false" ;
      $is_journal_international_SCOPUS = $is_journal_international_SCOPUS == "false" ? 0 : 1 ;
      $is_journal_international_SJR = isset($_POST["is_journal_international_SJR"]) && !empty($_POST["is_journal_international_SJR"]) ? $_POST["is_journal_international_SJR"] : "false" ;
      $is_journal_international_SJR = $is_journal_international_SJR == "false" ? 0 : 1 ;
      if ($is_journal_international_SJR != "false") {
        $journal_international_group_sjr = isset($_POST["journal_international_group_sjr"]) ? $_POST["journal_international_group_sjr"] : "" ;
      }
    }

    $journal_type_progress = isset($_POST["journal_type_progress"]) ? $_POST["journal_type_progress"] : "" ;
    if ($journal_type_progress == "published") {
      $journal_vol = isset($_POST["journal_vol"]) ? $_POST["journal_vol"] : "" ;
      $journal_issue = isset($_POST["journal_issue"]) ? $_POST["journal_issue"] : "" ;
      $journal_number = isset($_POST["journal_number"]) ? $_POST["journal_number"] : "" ;
      $journal_page_start = isset($_POST["journal_page_start"]) ? $_POST["journal_page_start"] : "" ;
      $journal_page_end = isset($_POST["journal_page_end"]) ? $_POST["journal_page_end"] : "" ;
      $journal_doi_no = isset($_POST["journal_doi_no"]) ? $_POST["journal_doi_no"] : "" ;
      $journal_accepted_date = isset($_POST["journal_accepted_date"]) ? $_POST["journal_accepted_date"]."-01" : "" ;
      // if($journal_accepted_date != "") {
      //   $journal_accepted_date = $journal_accepted_date."-01";
      // }
      $journal_published_month = isset($_POST["journal_published_month"]) ? $_POST["journal_published_month"] : "" ;
      $journal_published_year = isset($_POST["journal_published_year"]) ? $_POST["journal_published_year"] : "" ;
    } else if ($journal_type_progress == "inpress") {

    }



  } else if ($type == "conference") {
    $conference_name = isset($_POST["conference_name"]) ?  $_POST["conference_name"] : "" ;
    $conference_name = mysqli_real_escape_string($con,$conference_name);
    $conference_address = isset($_POST["conference_address"]) ?  $_POST["conference_address"] : "" ;
    $conference_address = mysqli_real_escape_string($con,$conference_address);
    $conference_start_date = isset($_POST["conference_start_date"]) ?  $_POST["conference_start_date"] : "" ;
    $conference_end_date = isset($_POST["conference_end_date"]) ?  $_POST["conference_end_date"] : "" ;
    $conference_page_start = isset($_POST["conference_page_start"]) ?  $_POST["conference_page_start"] : "" ;
    $conference_page_end = isset($_POST["conference_page_end"]) ?  $_POST["conference_page_end"] : "" ;
    $conference_location_type = isset($_POST["conference_location_type"]) ?  $_POST["conference_location_type"] : "false" ;
    $conference_type = isset($_POST["conference_type"]) ?  $_POST["conference_type"] : "" ;
  }

  // upload file
  // In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
  // of $_FILES.
  if ($_FILES['att_file']) {
    $uploaddir = 'upload_file/';
    $uploadfile = $uploaddir . date("l_d_m_Y_H_i_s") . "_". basename($_FILES['att_file']['name']);

    //echo '<hr>';
    //echo date("l_d_m_Y_H_i_s") . "<br>";

    if (move_uploaded_file($_FILES['att_file']['tmp_name'], $uploadfile)) {
        //echo "File is valid, and was successfully uploaded.\n";
    } else {
        //echo "Can not upload file!\n";
        //exit();
        $uploadfile = "";
    }
    //echo '<hr>';
  }
  $att_file = $uploadfile;
  $reference = isset($_POST["reference"]) ? $_POST["reference"] : "" ;
  $reference = mysqli_real_escape_string($con,$reference);
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
  if (!$result) {
    die('Error: ' . mysqli_error($con));
  }
  header('Location: research_view.php?message=Add New Paper Completed');

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
