<?php //research_edit.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php
  header('Content-Type: application/json');
  needAdminLevel(1);
  $id_to_edit = $_POST["id"];
  $title = mysqli_real_escape_string($con,$_POST["title"]);
  $isStudentGraduation = empty($_POST["isStudentGraduation"])? "0": $_POST["isStudentGraduation"];
  $reference = $_POST["reference"];
  $journal_type = $_POST["journal_type"];
  $journal_title = mysqli_real_escape_string($con,$_POST["journal_title"]);
  $is_journal_international_ISI = empty($_POST["is_journal_international_ISI"])? "0": $_POST["is_journal_international_ISI"];
  $is_journal_international_SCOPUS = empty($_POST["is_journal_international_SCOPUS"])? "0": $_POST["is_journal_international_SCOPUS"];
  $is_journal_international_SJR = empty($_POST["is_journal_international_SJR"])? "0": $_POST["is_journal_international_SJR"];
  $journal_international_group_sjr = $_POST["journal_international_group_sjr"];
  $journal_national_group = $_POST["journal_national_group"];
  $journal_type_progress = $_POST["journal_type_progress"];
  $journal_vol = $_POST["journal_vol"];
  $journal_issue = $_POST["journal_issue"];
  $journal_number = $_POST["journal_number"];
  $journal_page_start = $_POST["journal_page_start"];
  $journal_page_end = $_POST["journal_page_end"];
  $journal_doi_no = $_POST["journal_doi_no"];
  $journal_accepted_date = $_POST["journal_accepted_date"];
  $journal_published_month = $_POST["journal_published_month"];
  $journal_published_year = $_POST["journal_published_year"];
  $conference_name = $_POST["conference_name"];
  $conference_address = $_POST["conference_address"];
  $conference_start_date = $_POST["conference_start_date"];
  $conference_end_date = $_POST["conference_end_date"];
  $conference_page_start = $_POST["conference_page_start"];
  $conference_page_end = $_POST["conference_page_end"];
  $conference_location_type = $_POST["conference_location_type"];
  $conference_type = $_POST["conference_type"];

  $sql = "UPDATE research SET
    title = '$title',
    is_student_grad = $isStudentGraduation,
    reference = '$reference',
    journal_name = '$journal_title',
    journal_type = '$journal_type',
    is_journal_international_ISI = $is_journal_international_ISI,
    is_journal_international_SCOPUS = $is_journal_international_SCOPUS,
    is_journal_international_SJR = $is_journal_international_SJR,
    journal_international_group_sjr = '$journal_international_group_sjr',
    journal_national_group = '$journal_national_group',
    journal_type_progress = '$journal_type_progress',
    journal_vol = '$journal_vol',
    journal_issue = '$journal_issue',
    journal_number = '$journal_number',
    journal_page_start = '$journal_page_start',
    journal_page_end = '$journal_page_end',
    journal_doi_no = '$journal_doi_no',
    journal_accepted_date = '$journal_accepted_date-01',
    journal_published_month = '$journal_published_month',
    journal_published_year = '$journal_published_year',
    conference_name = '$conference_name',
    conference_address = '$conference_address',
    conference_start_date = '$conference_start_date',
    conference_end_date = '$conference_end_date',
    conference_page_start = '$conference_page_start',
    conference_page_end = '$conference_page_end',
    conference_location_type = '$conference_location_type',
    conference_type = '$conference_type'
    WHERE `research`.`id` = $id_to_edit";
  mysqli_query($con,$sql);
  mysqli_close($con);
  //header('Location: research_view.php?message=Delete Paper Completed');

  // set up result
  $result = array();
  $result['success'] = true;
  $result['id_to_edit'] = $id_to_edit;
  $result['title'] = $title;
  $result['isStudentGraduation'] = $isStudentGraduation;
  $result['reference'] = $reference;

  // Return the data result as json
  echo json_encode($result);
?>
