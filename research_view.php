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

      function setDataForDetail(id) {
        console.log("researchs"+researchs.length);
        research = researchs[id];
        $("#researchTitleDetail").html(research.title);
        $("#researchTitleDetailForStudentGraduation").html(research.is_student_grad);
        $("#researchTitleDetailAuther").html(research.author_name_th+"<br/>"+research.author_name_en);
        $("#researchTitleDetailCorresponding").html(research.corresponding);
        $("#researchTitleDetailReference").html(research.reference);
        $("#researchTitleDetailAttFile").attr("href", research.att_file);
      }

      function setDataForModal(id) {
        research = researchs[id];
        $("#researchTitleToEdit").html(research.title);
      }

      function setDataToDelete(id) {
        research = researchs[id];
        $("#researchTitleToDelete").html(research.title);
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

      <br/>
      <!--data row-->
      <div class="row">
        <div class="col-md-12">
          <table class="table table-hover table-striped">
            <thead>
              <tr class="info">
                <th>Data</th>
                <th>Detail</th>
                <th>Edit</th>
                <th>Delete</th>
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
                        <?php echo $row['reference']; ?>
                      </td>
                      <td>
                        <!-- Button trigger modal -->
                        <button class='btn btn-info' data-toggle='modal' data-target='#myModalDetail' onclick='setDataForDetail("<?php echo $row['id']; ?>")'>
                          <span class='glyphicon glyphicon-th-list'></span> Detail
                        </button>
                      </td>
                      <td>
                        <!-- Button trigger modal -->
                        <button class='btn btn-warning' data-toggle='modal' data-target='#myModal' onclick='setDataForModal("<?php echo $row['id']; ?>")'>
                          <span class='glyphicon glyphicon-pencil'></span> edit
                        </button>
                      </td>
                      <td>
                        <!-- Button trigger modal alert -->
                        <button class='btn btn-danger' data-toggle='modal' data-target='#alertToDelete' onclick='setDataToDelete("<?php echo $row['id']; ?>")'>
                          <span class='glyphicon glyphicon-remove'></span> Delete
                        </button>
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
            <h4 class="modal-title" id="myModalLabelDetail">Modal title</h4>
          </div>
          <div class="modal-body" id="mySmallModalBodyDetail">
            <div>
              <strong>Title:</strong> <span id="researchTitleDetail"></span>
            </div>
            <div>
              <strong>For Student Graduation:</strong> <span id="researchTitleDetailForStudentGraduation"></span>
            </div>
            <div>
              <strong>Authur:</strong> <span id="researchTitleDetailAuther"></span>
            </div>
            <div>
              <strong>Corresponding:</strong> <span id="researchTitleDetailCorresponding"></span>
            </div>
            <div>
              <strong>Reference:</strong> <span id="researchTitleDetailReference"></span>
            </div>
            <div>
              <a id="researchTitleDetailAttFile" href="#" class='btn btn-primary' target="_blank">
                <span class='glyphicon glyphicon-download'></span> Download
              </a>
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
            <h4 class="modal-title" id="myModalLabel">Modal title</h4>
          </div>
          <div class="modal-body" id="mySmallModalBody">
            <div>
              <strong>Title:</strong> <span id="researchTitleToEdit"></span>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
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
            <button type="button" class="btn btn-danger">Delete</button>
          </div>
        </div>

      </div>
    </div> <!-- end Alert -->

  </body>
</html>
