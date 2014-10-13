<?php //research_view.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php include "class_import.php"; ?>
<?php
  needAdminLevel(1);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Science Research</title>

    <?php include 'head_tag.php'; ?>


    <script>
      // globol var
      var researchs = [];
      var idToDelete;

      function setDataForDetail(id) {
        console.log("researchs.length:"+researchs.length);
        console.log("id:"+id);

        var research;
        for (var i=0; i<researchs.length; i++) {
          if (researchs[i].id == id) {
            research = researchs[i];
            break;
          }
        }

        $("#researchTitleDetail").html(research.title);
        $("#researchTitleDetailForStudentGraduation").html(research.is_student_grad);
        $("#researchTitleDetailAuthor").html(research.author_name_th+"<br/>"+research.author_name_en);
        $("#researchTitleDetailCorresponding").html(research.corresponding);
        $("#researchTitleDetailReference").html(research.reference);

        if (research.research_type == "journal") {
          $("#researchTitleDetailForJournal").show();
          $("#researchTitleDetailForProceeding").hide();

          $("#researchTitleDetailJournalTitle").html(research.journal_name);


          $("#researchTitleDetailJournalNationalInternational").html(research.journal_type);
          var message = "";
          if(research.journal_type == "national") {
            message += research.journal_national_group + " ";
          } else {
            console.log(research.is_journal_international_ISI);
            if(research.is_journal_international_ISI == "1") {
              message += "ISI ";
            }
            if(research.is_journal_international_SCOPUS == "1") {
              message += "SCOPUS ";
            }
            if(research.is_journal_international_SJR == "1") {
              message += "SJR " + research.journal_international_group_sjr;
            }
          }
          $("#researchTitleDetailJournalNationalInternationalDetail").html(message);

          $("#researchTitleDetailJournalPublishedInpress").html(research.journal_type_progress);
          message = "";
          if(research.journal_type_progress == "public") {
            message += "Vol. " + research.journal_vol + "<br/>";
            message += "Issue No. " + research.journal_issue + "<br/>";
            message += "Number. " + research.journal_number + "<br/>";
            message += "From Page. " + research.journal_page_start + "<br/>";
            message += "To Page. " + research.journal_page_end + "<br/>";
            message += "DOI no. " + research.journal_doi_no + "<br/>";
            message += "Accepted date. " + research.journal_accepted_date + "<br/>";
            message += "Published month. " + research.journal_published_month + "<br/>";
            message += "Published year. " + research.journal_published_year + "<br/>";

          }
          $("#researchTitleDetailJournalPublishedInpressDetail").html(message);

        } else if (research.research_type == "conference") {
          $("#researchTitleDetailForProceeding").show();
          $("#researchTitleDetailForJournal").hide();

          $("#researchTitleDetailConferenceName").html(research.conference_name);
          $("#researchTitleDetailVenue").html(research.conference_address);

          $("#researchTitleDetailStartDate").html(research.conference_start_date);
          $("#researchTitleDetailEndDate").html(research.conference_end_date);
          $("#researchTitleDetailPageFrom").html(research.conference_page_start);
          $("#researchTitleDetailPageTo").html(research.conference_page_end);
          $("#researchTitleDetailNationalInternational").html(research.conference_location_type);
          $("#researchTitleDetailOralPoster").html(research.conference_type);

        }

        $("#researchTitleDetailAttFile").attr("href", research.att_file);
      }

      function setDataForModal(id) {
        resetEditForm();
        var research;
        for (var i=0; i<researchs.length; i++) {
          if (researchs[i].id == id) {
            research = researchs[i];
            break;
          }
        }

        $("#researchTitleToEdit").val(research.title);
        if(research.is_student_grad != "0") {
          $("#researchTitleDetailForStudentGraduationToEdit").prop('checked', true);
        }

        $("#researchTitleDetailReferenceToEdit").val(research.reference);

        // ---------------------------------------------------------------------------------------------------------
        if (research.research_type == "journal") {
          $("#researchEditJournal").show();
          $("#researchEditProceeding").hide();

          $("#researchEditJournalTitle").val(research.journal_name);

          if (research.journal_type == "international") {
            $("#radio_journal_type_international").prop("checked", true);
            $("#journal_national").hide();
            $("#journal_international").show();

            if (research.is_journal_international_ISI == "1") {
              $("#is_journal_international_ISI").prop("checked", true);
            }

            if (research.is_journal_international_SCOPUS == "1") {
              $("#is_journal_international_SCOPUS").prop("checked", true);
            }

            if (research.is_journal_international_SJR == "1") {
              $("#is_journal_international_SJR").prop("checked", true);
              $("#journal_international_group_sjr").show();
            }

            if (research.journal_international_group_sjr == "Q1") {
              $("#journal_international_group_sjr_q1").prop("checked", true);
            } else if (research.journal_international_group_sjr == "Q2") {
              $("#journal_international_group_sjr_q2").prop("checked", true);
            }else if (research.journal_international_group_sjr == "Q3") {
              $("#journal_international_group_sjr_q3").prop("checked", true);
            }else if (research.journal_international_group_sjr == "Q4") {
              $("#journal_international_group_sjr_q4").prop("checked", true);
            }
          } else if (research.journal_type == "national") {
            $("#radio_journal_type_national").prop("checked", true);
            $("#journal_national").show();
            $("#journal_international").hide();

            if (research.journal_national_group == "TCI group 1") {
              $("#journal_national_group_tci_group_1").prop("checked", true);
            } else if (research.journal_national_group == "TCI group 2") {
              $("#journal_national_group_tci_group_2").prop("checked", true);
            }else if (research.journal_national_group == "none") {
              $("#journal_national_group_none").prop("checked", true);
            }
          }

          // ---------------------------------------------------------------------------------------------
          if (research.journal_type_progress == "inpress") {
            $("#radio_journal_type_inpress").prop("checked", true);
            $("#journal_type_public").hide();
            $("#journal_type_inpress").show();

          } else if(research.journal_type_progress == "public") {
            $("#radio_journal_type_public").prop("checked", true);
            $("#journal_type_public").show();
            $("#journal_type_inpress").hide();

            $("#journal_vol").val(research.journal_vol);
            $("#journal_issue").val(research.journal_issue);
            $("#journal_number").val(research.journal_number);
            $("#journal_page_start").val(research.journal_page_start);
            $("#journal_page_end").val(research.journal_page_end);
            $("#journal_doi_no").val(research.journal_doi_no);
            $("#journal_accepted_date").val(research.journal_accepted_date.substring(0, 7));
            $('#journal_published_month option[value="'+research.journal_published_month+'"]').prop('selected', true);
            $('#journal_published_year option[value="'+research.journal_published_year+'"]').prop('selected', true);
          }

        } else if (research.research_type == "conference") {
          //---------------------------------------------------------------------------------------------------------
          $("#researchEditProceeding").show();
          $("#researchEditJournal").hide();

          $("#conference_name").val(research.conference_name);

          $("#conference_address").val(research.conference_address);

          $("#conference_start_date").val(research.conference_start_date);

          $("#conference_end_date").val(research.conference_end_date);

          $("#conference_page_start").val(research.conference_page_start);

          $("#conference_page_end").val(research.conference_page_end);

          if (research.conference_location_type == "national") {
            $("#conference_location_type_national").prop("checked", true);
          } else if (research.conference_location_type == "international") {
            $("#conference_location_type_international").prop("checked", true);
          }

          if (research.conference_type == "oral") {
            $("#conference_type_oral").prop("checked", true);
          } else if (research.conference_type == "poster") {
            $("#conference_type_poster").prop("checked", true);
          }
        }

        $("#researchIdToEdit").attr("href", "javascript:editData("+ research.id +")");
      }

      function editData(id) {
        $.ajax({
          url: 'research_edit.php',
          data: {
            id: id,
            title: $("#researchTitleToEdit").val(),
              isStudentGraduation: $("#researchTitleDetailForStudentGraduationToEdit").is(':checked'),
              reference: $("#researchTitleDetailReferenceToEdit").val(),
              journal_type: $("input:radio[name*='journal_type']:checked").val(),
              is_journal_international_ISI: $("#is_journal_international_ISI").is(':checked'),
              is_journal_international_SCOPUS: $("#is_journal_international_SCOPUS").is(':checked'),
              is_journal_international_SJR: $("#is_journal_international_SJR").is(':checked'),
              journal_international_group_sjr: $("input:radio[name*='journal_international_group_sjr']:checked").val(),
              journal_national_group: $("input:radio[name*='journal_national_group']:checked").val(),
              journal_type_progress: $("input:radio[name*='journal_type_progress']:checked").val(),
              journal_vol: $("#journal_vol").val(),
              journal_issue: $("#journal_issue").val(),
              journal_number: $("#journal_number").val(),
              journal_page_start: $("#journal_page_start").val(),
              journal_page_end: $("#journal_page_end").val(),
              journal_doi_no: $("#journal_doi_no").val(),
              journal_accepted_date: $("#journal_accepted_date").val(),
              journal_published_month: $("#journal_published_month").val(),
              journal_published_year: $("#journal_published_year").val(),
              conference_name: $("#conference_name").val(),
              conference_address: $("#conference_address").val(),
              conference_start_date: $("#conference_start_date").val(),
              conference_end_date: $("#conference_end_date").val(),
              conference_page_start: $("#conference_page_start").val(),
              conference_page_end: $("#conference_page_end").val(),
              conference_location_type: $("input:radio[name*='conference_location_type']:checked").val(),
              conference_type: $("input:radio[name*='conference_type']:checked").val()
            },
          type: 'post',
          dataType: 'json',
          success: function(output) {
            // alert(output);
            if (!output.success) { //If fails
              //error
            } else {
              //succ
              // reload page
              location.reload();
            }
          }
        });
      }

      function setDataToDelete(id) {
        idToDelete = id;
        var research;
        for (var i=0; i<researchs.length; i++) {
          if (researchs[i].id == id) {
            research = researchs[i];
            break;
          }
        }

        $("#researchTitleToDelete").html(research.title);
        $("#researchTitleToDeleteReference").html(research.reference);

        $("#researchIdToDelete").attr("href", "javascript:deleteData("+research.id+")");
      }

      function deleteData(id) {
        $.ajax({
          url: 'research_delete.php',
          data: {
            id: id},
          type: 'post',
          dataType: 'json',
          success: function(output) {
            // alert(output);
            if (!output.success) { //If fails
              //alert("error");
            } else {
              //alert("succ");
              location.reload();
            }
          }
        });
      }

      function resetEditForm() {
        //$("#formEdit").reset();
        $("#formEdit").trigger('reset')
      }



    </script>
  </head>
  <body>
    <?php include 'navbar.php'; ?>


    <div class="container">

      <!--title row-->
      <div class="row bg-info">
        <div class="col-md-10">
          <h2>Research View</h2>
          <h4>Result</h4>
        </div>

        <div class="col-md-2 text-right">
          <p></p>
          <p>
            <a class="btn btn-success" href="input_form.php" role="button">
              <span class="glyphicon glyphicon-plus"></span> New Research
            </a>
          </p>
        </div>
      </div>

      <?php
        if (!empty($_GET["message"])) {
          ?>
          <div class="row bg-info">

            <div class="col-md-4">
            </div>

            <div class="col-md-4">
              <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert">
                  <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <strong><?php echo $_GET["message"]; ?></strong>
              </div>
            </div>

          </div>
          <?php
        }
      ?>

      <br/>
      <!--data row-->
      <div class="row">
        <div class="col-md-12">
          <table class="table table-hover table-striped">
            <thead>
              <tr class="info">
                <th>Paper Title</th>
                <th>Author</th>
                <th>Type</th>
                <th>Detail</th>
                <th>Edit / Delete</th>
              </tr>
            </thead>
            <tbody>
              <?php
                // set sql
                $sql = "SELECT * FROM research";
                $result_for_json = array();
                $result = mysqli_query($con, $sql);
                if (!$result) {
                  die('Error: ' . mysqli_error($con));
                } else {
                  while($row = mysqli_fetch_array($result)) {
                    $result_for_json[] = $row;
                    ?>
                    <tr>
                      <td>
                        <?php echo $row['title']; ?>
                      </td>
                      <td>
                        <?php echo $row['author_name_th']; ?><br/>
                        <?php echo $row['author_name_en']; ?>
                      </td>
                      <td>
                        <?php
                          if($row['research_type']=="journal") {
                            echo "Journal";
                          } else {
                            echo "Conference";
                          }
                        ?>
                      </td>
                      <td>
                        <!-- Button trigger modal -->
                        <button class='btn btn-xs btn-info' data-toggle='modal' data-target='#myModalDetail' onclick='setDataForDetail("<?php echo $row['id']; ?>")'>
                          <span class='glyphicon glyphicon-th-list'></span> Detail
                        </button>
                      </td>
                      <td>
                        <p>
                          <!-- Button trigger modal -->
                          <button class='btn btn-xs btn-warning' data-toggle='modal' data-target='#myModal' onclick='setDataForModal("<?php echo $row['id']; ?>")'>
                            <span class='glyphicon glyphicon-pencil'></span> Edit
                          </button>
                        <p>
                        <p>
                          <!-- Button trigger modal alert -->
                          <button class='btn btn-xs btn-danger' data-toggle='modal' data-target='#alertToDelete' onclick='setDataToDelete("<?php echo $row['id']; ?>")'>
                            <span class='glyphicon glyphicon-trash'></span> Delete
                          </button>
                        </p>
                      </td>
                    </tr>
                    <?php
                  }
                }

                ?>
                <script>
                  researchs = <?php echo json_encode($result_for_json) ?>;
                  //console.log("count" + research.length);
                </script>
                <?php
              ?>
            </tbody>
          </table>
        </div>
      </div>

    </div> <!-- end container -->


    <!-- Detail Modal -->
    <div class="modal fade bs-example-modal-lg" id="myModalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabelDetail" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabelDetail">Detail</h4>
          </div>
          <div class="modal-body" id="mySmallModalBodyDetail">
            <div>
              <p>
                <strong>Title:</strong><br/>
                <span id="researchTitleDetail"></span>
              </p>
            </div>

            <div>
              <p>
                <strong>For Student Graduation:</strong><br/>
                <span id="researchTitleDetailForStudentGraduation"></span>
              </p>
            </div>

            <div>
              <p>
                <strong>Author:</strong><br/>
                <span id="researchTitleDetailAuthor"></span>
              </p>
            </div>

            <div>
              <p>
                <strong>Corresponding:</strong><br/>
                <span id="researchTitleDetailCorresponding"></span>
              </p>
            </div>

            <div>
              <p>
                <strong>Reference:</strong><br/>
                <span id="researchTitleDetailReference"></span>
              </p>
            </div>

            <div id="researchTitleDetailForJournal">

              <div>
                <p>
                  <strong>Journal Title:</strong><br/>
                  <span id="researchTitleDetailJournalTitle"></span>
                </p>
              </div>

              <div>
                <p>
                  <strong>National / International:</strong><br/>
                  <span id="researchTitleDetailJournalNationalInternational"></span>
                  <span id="researchTitleDetailJournalNationalInternationalDetail"></span>
                </p>
              </div>

              <div>
                <p>
                  <strong>Published / Inpress:</strong><br/>
                  <span id="researchTitleDetailJournalPublishedInpress"></span>
                  <span id="researchTitleDetailJournalPublishedInpressDetail"></span>
                </p>
              </div>

            </div>

            <div id="researchTitleDetailForProceeding">


              <div>
                <p>
                  <strong>Conference Name:</strong><br/>
                  <span id="researchTitleDetailConferenceName"></span>
                </p>
              </div>

              <div>
                <p>
                  <strong>Venue:</strong><br/>
                  <span id="researchTitleDetailVenue"></span>
                </p>
              </div>

              <div>
                <p>
                  <strong>Conference Start Date:</strong><br/>
                  <span id="researchTitleDetailStartDate"></span>
                </p>
              </div>

              <div>
                <p>
                  <strong>Conference End Date:</strong><br/>
                  <span id="researchTitleDetailEndDate"></span>
                </p>
              </div>

              <div>
                <p>
                  <strong>Page From:</strong><br/>
                  <span id="researchTitleDetailPageFrom"></span>
                </p>
              </div>

              <div>
                <p>
                  <strong>Page To:</strong><br/>
                  <span id="researchTitleDetailPageTo"></span>
                </p>
              </div>

              <div>
                <p>
                  <strong>National / International:</strong><br/>
                  <span id="researchTitleDetailNationalInternational"></span>
                </p>
              </div>

              <div>
                <p>
                  <strong>Oral / Poster:</strong><br/>
                  <span id="researchTitleDetailOralPoster"></span>
                </p>
              </div>

            </div>

            <div>
              <p>
                <a id="researchTitleDetailAttFile" href="#" class='btn btn-primary' target="_blank">
                  <span class='glyphicon glyphicon-download'></span> Download
                </a>
              </p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div> <!-- end Detail Modal -->


    <!-- Edit Modal -->
    <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Edit</h4>
          </div>
          <div class="modal-body" id="mySmallModalBody">

            <form role="form" id="formEdit" method="post" action="input_form_process.php">

              <div class="form-group">
                <label for="researchTitleToEdit">Title:</label>
                <input type="text" class="form-control" name="researchTitleToEdit" id="researchTitleToEdit"/>
              </div>

              <div>
                <div class="checkbox">
                  <label>
                    <input
                      type="checkbox"
                      name="researchTitleDetailForStudentGraduationToEdit"
                      id="researchTitleDetailForStudentGraduationToEdit"
                      value="true">For Student Graduation:
                  </label>
                </div>
              </div>

              <div>
                <div class="form-group">
                  <label for="reference">Reference:</label>
                  <textarea name="researchTitleDetailReferenceToEdit" id="researchTitleDetailReferenceToEdit" rows="5" class="form-control"></textarea>
                </div>
              </div>

              <!--  -->
              <div id="researchEditJournal">

                <div>
                  <div class="form-group">
                    <label for="researchEditJournalTitle">Title:</label>
                    <input type="text" class="form-control" name="researchEditJournalTitle" id="researchEditJournalTitle"/>
                  </div>
                </div>

                <div>
                  <p>
                    <strong>National / International:</strong><br/>
                    <div class="row">

                      <div class="col-md-2">
                        <div class="radio">
                          <label>
                            <input id="radio_journal_type_national" type="radio" name="journal_type" value="national" />
                            National
                          </label>
                        </div>
                        <div class="radio">
                          <label>
                            <input id="radio_journal_type_international" type="radio" name="journal_type" value="international" />
                            International
                          </label>
                        </div>
                      </div>

                      <div class="col-md-10">

                        <div id="journal_national" style="display: none;">
                          <div class="row">
                            <div class="col-md-2">
                              <div class="radio">
                                <label>
                                  <input type="radio" name="journal_national_group"
                                    id="journal_national_group_tci_group_1" value="TCI group 1" />
                                  TCI group 1
                                </label>
                              </div>
                              <div class="radio">
                                <label>
                                  <input type="radio" name="journal_national_group"
                                    id="journal_national_group_tci_group_2" value="TCI group 2" />
                                  TCI group 2
                                </label>
                              </div>
                              <div class="radio">
                                <label>
                                  <input type="radio" name="journal_national_group"
                                    id="journal_national_group_none" value="none" />none
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div id="journal_international" style="display: none;">
                          <div class="row">

                            <div class="col-md-2">
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox" name="is_journal_international_ISI" id="is_journal_international_ISI" value="ISI">
                                  ISI
                                </label>
                              </div>
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox" name="is_journal_international_SCOPUS" id="is_journal_international_SCOPUS" value="SCOPUS">
                                  SCOPUS
                                </label>
                              </div>
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox" name="is_journal_international_SJR" id="is_journal_international_SJR" value="SJR">
                                  SJR
                                </label>
                              </div>
                            </div>

                            <div class="col-md-2">
                              <div id="journal_international_group_sjr" style="display: none;">
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="journal_international_group_sjr" id="journal_international_group_sjr_q1" value="Q1" />Q1
                                  </label>
                                </div>
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="journal_international_group_sjr" id="journal_international_group_sjr_q2" value="Q2" />Q2
                                  </label>
                                </div>
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="journal_international_group_sjr" id="journal_international_group_sjr_q3" value="Q3" />Q3
                                  </label>
                                </div>
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="journal_international_group_sjr" id="journal_international_group_sjr_q4" value="Q4" />Q4
                                  </label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                      </div>

                    </div>

                  </p>
                </div>




                <div>
                  <p>
                    <strong>Published / Inpress:</strong><br/>

                    <div class="row">

                      <div class="col-md-2">
                        <div class="radio">
                          <label>
                            <input id="radio_journal_type_public" type="radio" name="journal_type_progress" value="public" />
                            Published
                          </label>
                        </div>
                        <div class="radio">
                          <label>
                            <input id="radio_journal_type_inpress" type="radio" name="journal_type_progress" value="inpress" />
                            Inpress
                          </label>
                        </div>
                      </div>

                      <div class="col-md-6">

                        <div id="journal_type_public" class="form-horizontal" style="display: none;">

                          <div class="form-group">
                            <label for="journal_vol" class="col-sm-4 control-label">
                              Vol.:
                            </label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" name="journal_vol" id="journal_vol"/>
                            </div>
                            <br/>
                          </div>

                          <div class="form-group">
                            <label for="journal_issue" class="col-sm-4 control-label">
                              Issue No.:
                            </label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" name="journal_issue" id="journal_issue"/>
                            </div>
                            <br/>
                          </div>

                          <div class="form-group">
                            <label for="journal_number" class="col-sm-4 control-label">
                              Number:
                            </label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" name="journal_number" id="journal_number"/>
                            </div>
                            <br/>
                          </div>

                          <div class="form-group">
                            <label for="journal_page_start" class="col-sm-4 control-label">
                              From Page:
                            </label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" name="journal_page_start" id="journal_page_start"/>
                            </div>
                            <br/>
                          </div>

                          <div class="form-group">
                            <label for="journal_page_end" class="col-sm-4 control-label">
                              To Page:
                            </label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" name="journal_page_end" id="journal_page_end"/>
                            </div>
                            <br/>
                          </div>

                          <div class="form-group">
                            <label for="journal_doi_no" class="col-sm-4 control-label">
                              DOI no:
                            </label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" name="journal_doi_no" id="journal_doi_no"/>
                            </div>
                            <br/>
                          </div>

                          <div class="form-group">
                            <label for="journal_accepted_date" class="col-sm-4 control-label">
                              Accepted date:
                            </label>
                            <div class="col-sm-6">
                              <input type="month" class="form-control" name="journal_accepted_date" id="journal_accepted_date"/>
                            </div>
                            <br/>
                          </div>

                          <div class="form-group">
                            <label for="journal_published_month" class="col-sm-4 control-label">
                              Published month:
                            </label>
                            <div class="col-sm-4">
                              <select class="form-control" name="journal_published_month" id="journal_published_month">
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                              </select>
                            </div>
                            <br/>
                          </div>

                          <div class="form-group">
                            <label for="journal_published_year" class="col-sm-4 control-label">
                              Published year:
                            </label>
                            <div class="col-sm-4">
                              <select class="form-control" id="journal_published_year" name="journal_published_year">
                              </select>
                              <script>
                                // setup year
                                var d = new Date();
                                var n = d.getFullYear();
                                var nMin = n-5;
                                var nMax = n+5;
                                for ( i=nMin; i<=nMax; i++ ) {
                                  var temp = "<option value='"+i+"'>"+i+"</option>";
                                  if ( i==n ) {
                                    temp = "<option value='"+i+"' selected>"+i+"</option>";
                                  }
                                  $("#journal_published_year").append(temp);
                                }
                              </script>
                            </div>
                            <br/>
                          </div>

                        </div>

                        <div id="journal_type_inpress" style="display: none;">
                        </div>

                      </div>

                    </div>
                  </p>
                </div>



              </div>

              <div id="researchEditProceeding">


                <div>
                  <p>
                    <div class="form-group">
                      <label for="conference_name">Conference Name:</label>
                      <input type="text" name="conference_name" id="conference_name" class="form-control"/>
                    </div>
                  </p>
                </div>

                <div>
                  <p>
                    <span id="researchEditVenue"></span>
                    <div class="form-group">
                      <label for="conference_address">Venue:</label>
                      <input type="text" name="conference_address" id="conference_address" class="form-control"/>
                    </div>
                  </p>
                </div>

                <div class="row form-horizontal">

                  <div class="col-md-6">
                    Conference date:
                    <div class="form-group">
                      <label for="conference_start_date" class="col-sm-3 control-label">Start Date:</label>
                      <div class="col-sm-6">
                        <input type="date" class="form-control" id="conference_start_date" name="conference_start_date" oninput="setConferenceEndDateFromConferenceStartDate()"/>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="conference_end_date" class="col-sm-3 control-label">End Date:</label>
                      <div class="col-sm-6">
                        <input type="date" class="form-control" id="conference_end_date" name="conference_end_date"/>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    Page:
                    <div class="form-group">
                      <label for="conference_page_start" class="col-sm-3 control-label">From:</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" name="conference_page_start" id="conference_page_start"/>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="conference_page_end" class="col-sm-3 control-label">To:</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" name="conference_page_end" id="conference_page_end"/>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="row form-horizontal">
                  <div class="col-md-2">
                    <div class="radio">
                      <label>
                        <input type="radio" name="conference_location_type"
                          id="conference_location_type_national" value="national" />
                        National
                      </label>
                    </div>
                    <div class="radio">
                      <label>
                        <input type="radio" name="conference_location_type"
                          id="conference_location_type_international" value="international" />
                        International
                      </label>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="radio">
                      <label>
                        <input type="radio" name="conference_type"
                          id="conference_type_oral" value="oral" />
                        Oral
                      </label>
                    </div>
                    <div class="radio">
                      <label>
                        <input type="radio" name="conference_type"
                          id="conference_type_poster" value="poster" />
                        Poster
                      </label>
                    </div>
                  </div>
                </div>

              </div><!--  -->

            </form>

            <script>
              $("#radio_journal_type_national").click( function() {
                $("#journal_international").hide();
                $("#journal_national").show();
              });

              $("#radio_journal_type_international").click( function() {
                $("#journal_national").hide();
                $("#journal_international").show();
              });

              $("input[value='SJR']").click( function() {
                $("#journal_international_group_sjr").toggle();
              });

              $("#radio_journal_type_public").click( function() {
                $("#journal_type_inpress").hide();
                $("#journal_type_public").show();
              });

              $("#radio_journal_type_inpress").click( function() {
                $("#journal_type_public").hide();
                $("#journal_type_inpress").show();
              });
            </script>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancle</button>
            <a id="researchIdToEdit" href="#" class="btn btn-primary">
              <span class='glyphicon glyphicon-floppy-save'></span> Save changes
            </a>
          </div>
        </div>
      </div>
    </div> <!-- end Edit Modal -->


    <!-- Alert delete title -->
    <!-- Small modal -->
    <div class="modal fade bs-example-modal-sm" id="alertToDelete" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="mySmallModalLabel">Delete</h4>
          </div>
          <div class="modal-body" id="mySmallModalBody">
            <p><strong>Title:</strong><br/> <span id="researchTitleToDelete"></span></p>
            <p><strong>Reference:</strong><br/> <span id="researchTitleToDeleteReference"></span></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancle</button>
            <a id="researchIdToDelete" href="#" class='btn btn-danger'>
              <span class='glyphicon glyphicon-trash'></span> Delete
            </a>
          </div>
        </div>

      </div>
    </div> <!-- end Alert -->

  </body>
</html>
