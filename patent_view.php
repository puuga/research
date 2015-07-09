<?php // patent_view.php ?>
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
      var patents = [];
      var idToDelete;

      function setDataForDetail(id) {
        var patent;
        for (var i=0; i<patents.length; i++) {
          if (patents[i].id == id) {
            patent = patents[i];
            break;
          }
        }
        $("#patentTitleDetail").html(patent.name);
        $("#patentTypeDetail").html(patent.patent_type_name);
        $("#patentOwnerDetail").html(patent.belong_to);
        $("#patentGrantDateDetail").html(patent.grant_date);
        $("#patentRequestDateDetail").html(patent.request_date);
        $("#patentNumberDetail").html(patent.number);
        $("#patentRequestNumberDetail").html(patent.request_number);
        $("#patentWorkTypeDetail").html(patent.work_type);
        $("#patentWorkCharacteristicDetail").html(patent.work_characteristic);
        $("#patentExtra1Detail").html(patent.extra1);
        $("#patentDescriptionDetail").html(patent.description);

        if ( patent.patent_type_id==="1" ) {
          $("#patentRequestDateDetailBlog").hide();
          $("#patentRequestNumberDetailBlog").hide();
          $("#patentWorkCharacteristicDetailBlog").show();
        } else {
          $("#patentRequestDateDetailBlog").show();
          $("#patentRequestNumberDetailBlog").show();
          $("#patentWorkCharacteristicDetailBlog").hide();
        }
      }

      function resetEditForm() {
        $("#formEdit").trigger('reset');
      }

      function setDataForEditModal(id) {
        resetEditForm();
        var patent;
        for (var i=0; i<patents.length; i++) {
          if (patents[i].id == id) {
            patent = patents[i];
            break;
          }
        }

        // set date to form
        $("#patentTitleEdit").val(patent.name);
        $("#patentOwnerEdit").val(patent.belong_to);
        $("#patentGrantDateEdit").val(patent.grant_date);
        $("#patentRequestDateEdit").val(patent.request_date);
        $("#patentNumberEdit").val(patent.number);
        $("#patentRequestNumberEdit").val(patent.request_number);
        $("#patentWorkTypeEdit").val(patent.work_type);
        $("#patentWorkCharacteristicEdit").val(patent.work_characteristic);
        $("#patentExtra1Edit").val(patent.extra1);
        $("#patentDescriptionEdit").val(patent.description);

        // show input by type
        if ( patent.patent_type_id==="1" ) {
          $("#patentRequestDateEditBlog").hide();
          $("#patentRequestNumberEditBlog").hide();
          $("#patentWorkCharacteristicEditBlog").show();
        } else {
          $("#patentRequestDateEditBlog").show();
          $("#patentRequestNumberEditBlog").show();
          $("#patentWorkCharacteristicEditBlog").hide();
        }

        $("#patentIdToEdit").attr("href", "javascript:editData("+ patent.id +")");
      }

      function editData(id) {
        var patent;
        for (var i=0; i<patents.length; i++) {
          if (patents[i].id == id) {
            patent = patents[i];
            break;
          }
        }

        var data = {};
        if ( patent.patent_type_id==="1" ) {
          data = {
            id: id,
            patent_type_id: patent.patent_type_id,
            name: $("#patentTitleEdit").val(),
            number: $("#patentNumberEdit").val(),
            description: $("#patentDescriptionEdit").val(),
            belong_to: $("#patentOwnerEdit").val(),
            grant_date: $("#patentGrantDateEdit").val(),
            extra1: $("#patentExtra1Edit").val(),
            work_type: $("#patentWorkTypeEdit").val(),
            work_characteristic: $("#patentWorkCharacteristicEdit").val()
          }
        } else {
          data = {
            id: id,
            patent_type_id: patent.patent_type_id,
            name: $("#patentTitleEdit").val(),
            number: $("#patentNumberEdit").val(),
            request_number: $("#patentRequestNumberEdit").val(),
            description: $("#patentDescriptionEdit").val(),
            belong_to: $("#patentOwnerEdit").val(),
            request_date: $("#patentRequestDateEdit").val(),
            grant_date: $("#patentGrantDateEdit").val(),
            extra1: $("#patentExtra1Edit").val(),
            work_type: $("#patentWorkTypeEdit").val()
          }
        }

        $.ajax({
          url: 'patent_edit.php',
          data: data,
          type: 'post',
          dataType: 'json',
          success: function(output) {
            // alert(output);
            if (!output.success) { //If fails
              //alert("error");
              console.log(output);
            } else {
              //alert("succ");
              console.log(output);
              location.reload();
            }
          }
        });
      }

      function setDataToDelete(id) {
        idToDelete = id;
        var patent;
        for (var i=0; i<patents.length; i++) {
          if (patents[i].id == id) {
            patent = patents[i];
            break;
          }
        }

        $("#patentTitleToDelete").html(patent.name);
        $("#patentDescriptionToDelete").html(patent.description);

        $("#patentIdToDelete").attr("href", "javascript:deleteData("+patent.id+")");
      }

      function deleteData(id) {
        $.ajax({
          url: 'patent_delete.php',
          data: {
            id: id},
          type: 'post',
          dataType: 'json',
          success: function(output) {
            // alert(output);
            if (!output.success) { //If fails
              //alert("error");
              console.log(output);
            } else {
              //alert("succ");
              console.log(output);
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
          <?php
            $text_copyright = "ลิขสิทธิ์";
            $text_patent = "สิทธิบัตร, อนุลิขสิทธิ์";

            if ( isset($_GET["view"]) ) {
              if ( $_GET["view"]==="patent" ) {
                $viewgroup="patent";
              } elseif ( $_GET["view"]==="copyright" ) {
                $viewgroup="copyright";
              } else {
                $viewgroup="all";
              }
            } else {
              $viewgroup="all";
            }

          ?>
          <h2>Patent View [<?php echo $viewgroup; ?>]</h2>
          <?php

          ?>
          <h4>
            Result
            [
            <a href="patent_view.php">
              all
            </a>
            /
            <a href="patent_view.php?view=patent">
              <?php echo $text_patent; ?>
            </a>
            /
            <a href="patent_view.php?view=copyright">
              <?php echo $text_copyright; ?>
            </a>
            ]
          </h4>
        </div>

        <div class="col-md-2 text-right">
          <p></p>
          <p>
            <a class="btn btn-success" href="patent_add_form.php" role="button">
              <span class="glyphicon glyphicon-plus"></span> New Patent
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
                <th>Patent Title</th>
                <th>Type</th>
                <th>Owner</th>
                <th>Detail</th>
                <th>Edit / Delete</th>
              </tr>
            </thead>
            <tbody>
              <?php
                // set sql
                $sql = "SELECT
                p.id,
                p.patent_type_id,
                pt.name as patent_type_name,
                p.name,
                p.number,
                p.request_number,
                p.description,
                p.belong_to,
                p.request_date,
                p.grant_date,
                p.extra1,
                p.extra2,
                p.work_type,
                p.work_characteristic
                FROM patents p
                inner join patent_types pt on p.patent_type_id = pt.id ";
                if ( $viewgroup==="patent" ) {
                  $sql .= " where patent_type_id = 2 or patent_type_id = 3 ";
                } elseif ( $viewgroup==="copyright" ) {
                  $sql .= " where patent_type_id = 1 ";
                }
                $sql .= " order by p.id desc";
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
                        <?php echo $row['name']; ?>
                      </td>
                      <td>
                        <?php echo $row['patent_type_name']; ?>
                      </td>
                      <td>
                        <?php echo $row['belong_to']; ?>
                      </td>
                      <td>
                        <!-- Button trigger detail modal -->
                        <button
                        class='btn btn-xs btn-info'
                        data-toggle='modal'
                        data-target='#myModalDetail'
                        onclick='setDataForDetail("<?php echo $row['id']; ?>")'>
                          <span class='glyphicon glyphicon-th-list'></span> Detail
                        </button>
                      </td>
                      <td>
                        <p>
                          <!-- Button trigger edit modal -->
                          <button
                          class='btn btn-xs btn-warning'
                          data-toggle='modal'
                          data-target='#myModal'
                          onclick='setDataForEditModal("<?php echo $row['id']; ?>")'>
                            <span class='glyphicon glyphicon-pencil'></span> Edit
                          </button>
                        <p>
                        <p>
                          <!-- Button trigger delete modal alert -->
                          <button
                          class='btn btn-xs btn-danger'
                          data-toggle='modal'
                          data-target='#alertToDelete'
                          onclick='setDataToDelete("<?php echo $row['id']; ?>")'>
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
                  patents = <?php echo json_encode($result_for_json) ?>;
                  console.log(patents);
                </script>
                <?php
              ?>
            </tbody>
          </table>
        </div>
      </div>

    </div> <!-- end container -->


    <!-- Detail Modal -->
    <div
    class="modal fade bs-example-modal-lg"
    id="myModalDetail"
    tabindex="-1"
    role="dialog"
    aria-labelledby="myModalLabelDetail"
    aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button
            type="button"
            class="close"
            data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="myModalLabelDetail">Detail</h4>
          </div>
          <div class="modal-body" id="mySmallModalBodyDetail">
            <p>
              <strong>Title:</strong><br/>
              <span id="patentTitleDetail"></span>
            </p>

            <p>
              <strong>Type:</strong><br/>
              <span id="patentTypeDetail"></span>
            </p>

            <p>
              <strong>Owner:</strong><br/>
              <span id="patentOwnerDetail"></span>
            </p>

            <p>
              <strong>grant_date:</strong><br/>
              <span id="patentGrantDateDetail"></span>
            </p>

            <p id="patentRequestDateDetailBlog">
              <strong>request_date:</strong><br/>
              <span id="patentRequestDateDetail"></span>
            </p>

            <p>
              <strong>number:</strong><br/>
              <span id="patentNumberDetail"></span>
            </p>

            <p id="patentRequestNumberDetailBlog">
              <strong>request_number:</strong><br/>
              <span id="patentRequestNumberDetail"></span>
            </p>

            <p>
              <strong>work_type:</strong><br/>
              <span id="patentWorkTypeDetail"></span>
            </p>

            <p id="patentWorkCharacteristicDetailBlog">
              <strong>work_characteristic:</strong><br/>
              <span id="patentWorkCharacteristicDetail"></span>
            </p>

            <p>
              <strong>extra1:</strong><br/>
              <span id="patentExtra1Detail"></span>
            </p>

            <p>
              <strong>description:</strong><br/>
              <span id="patentDescriptionDetail"></span>
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div> <!-- end Detail Modal -->


    <!-- Edit Modal -->
    <div
    class="modal fade bs-example-modal-lg"
    id="myModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="myModalLabel"
    aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Edit</h4>
          </div>
          <div class="modal-body" id="mySmallModalBody">

            <form role="form" id="formEdit">

              <div class="form-group" id="patentTitleEditBlog">
                <label for="patentTitleEdit">Title:</label>
                <input type="text" class="form-control"
                name="patentTitleEdit" id="patentTitleEdit"/>
              </div>

              <div class="form-group" id="patentOwnerEditBlog">
                <label for="patentOwnerEdit">Owner:</label>
                <input type="text" class="form-control"
                name="patentOwnerEdit" id="patentOwnerEdit"/>
              </div>

              <div class="form-group" id="patentGrantDateEditBlog">
                <label for="patentGrantDateEdit">GrantDate:</label>
                <input type="date" class="form-control"
                name="patentGrantDateEdit" id="patentGrantDateEdit"/>
              </div>

              <div class="form-group" id="patentRequestDateEditBlog">
                <label for="patentRequestDateEdit">RequestDate:</label>
                <input type="date" class="form-control"
                name="patentRequestDateEdit" id="patentRequestDateEdit"/>
              </div>

              <div class="form-group" id="patentNumberEditBlog">
                <label for="patentNumberEdit">Number:</label>
                <input type="text" class="form-control"
                name="patentNumberEdit" id="patentNumberEdit"/>
              </div>

              <div class="form-group" id="patentRequestNumberEditBlog">
                <label for="patentRequestNumberEdit">Request:</label>
                <input type="text" class="form-control"
                name="patentRequestNumberEdit" id="patentRequestNumberEdit"/>
              </div>

              <div class="form-group" id="patentWorkTypeEditBlog">
                <label for="patentWorkTypeEdit">WorkType:</label>
                <input type="text" class="form-control"
                name="patentWorkTypeEdit" id="patentWorkTypeEdit"/>
              </div>

              <div class="form-group" id="patentWorkCharacteristicEditBlog">
                <label for="patentWorkCharacteristicEdit">WorkCharacteristic:</label>
                <input type="text" class="form-control"
                name="patentWorkCharacteristicEdit" id="patentWorkCharacteristicEdit"/>
              </div>

              <div class="form-group" id="patentExtra1EditBlog">
                <label for="patentExtra1Edit">Extra1:</label>
                <input type="text" class="form-control"
                name="patentExtra1Edit" id="patentExtra1Edit"/>
              </div>

              <div class="form-group" id="patentDescriptionEditBlog">
                <label for="patentDescriptionEdit">Description:</label>
                <input type="text" class="form-control"
                name="patentDescriptionEdit" id="patentDescriptionEdit"/>
              </div>

            </form>

            <script>
            </script>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancle</button>
            <a id="patentIdToEdit" href="#" class="btn btn-primary">
              <span class='glyphicon glyphicon-floppy-save'></span> Save changes
            </a>
          </div>
        </div>
      </div>
    </div> <!-- end Edit Modal -->

    <!-- Alert delete title -->
    <!-- Small modal -->
    <div
    class="modal fade bs-example-modal-sm"
    id="alertToDelete"
    tabindex="-1"
    role="dialog"
    aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="mySmallModalLabel">Delete</h4>
          </div>
          <div class="modal-body" id="mySmallModalBody">
            <p><strong>Title:</strong><br/> <span id="patentTitleToDelete"></span></p>
            <p><strong>Description:</strong><br/> <span id="patentDescriptionToDelete"></span></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancle</button>
            <a id="patentIdToDelete" href="#" class='btn btn-danger'>
              <span class='glyphicon glyphicon-trash'></span> Delete
            </a>
          </div>
        </div>

      </div>
    </div> <!-- end Alert -->

  </body>
</html>
