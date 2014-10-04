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












        $("#researchIdToEdit").attr("href", "javascript:editData("+ research.id +")");
      }

      function editData(id) {
        $.ajax({
          url: 'research_edit.php',
          data: {
            id: id,
            title: $("#researchTitleToEdit").val(),
            isStudentGraduation: $("#researchTitleDetailForStudentGraduationToEdit").is(':checked'),
            reference: $("#researchTitleDetailReferenceToEdit").val()
            },
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
                            <span class='glyphicon glyphicon-remove'></span> Delete
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

            </form>

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
            <strong>Title:</strong> <span id="researchTitleToDelete"></span>
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
