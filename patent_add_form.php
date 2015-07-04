<?php //patent_add_form.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php include "class_import.php"; ?>
<?php
  needAdminLevel(1);
?>
<!DOCTYPE html>
<!--
code by siwawes wongcharoen
-->
<html>
  <head>
    <meta charset="UTF-8">

    <title>Science Research System</title>

    <?php include 'head_tag.php'; ?>

    <!--<link rel="stylesheet" type="text/css" href="css/main_style.css">-->

    <style>
      body {
        padding-top: 10px;
        margin-bottom: 10px;
      }
    </style>

  </head>

  <body>

    <div class="container">

      <!--title row-->
      <div class="row bg-info">
        <div class="col-md-10">
          <h2>New Patent</h2>
        </div>
        <div class="col-md-2 text-right">
          <p></p>
          <a class="btn btn-danger" href="javascript:history.go(-1)" role="button">
            <span class="glyphicon glyphicon-remove"></span> Cancle
          </a>
        </div>
      </div>

      <form role="form" id="form1" method="post" action="patent_add_process.php">

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="name">Title:</label>
              <input type="text"
                class="form-control"
                name="name"
                id="name"
                placeholder="Title"
                required/>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="belong_to">Owner:</label>
              <input type="text"
                class="form-control"
                name="belong_to"
                id="belong_to"
                placeholder="Owner"
                required />
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <br/>
            Type:
            <div class="radio">
              <label>
                <input type="radio" id="patent_type_id2"
                name="patent_type_id" value="2" required />
                สิทธิบัตร
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" id="patent_type_id3"
                name="patent_type_id" value="3" />
                อนุสิทธิบัตร
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" id="patent_type_id1"
                name="patent_type_id" value="1" />
                ลิขสิทธิ์
              </label>
            </div>

          </div>
        </div>

        <div class="row" id="grant_date_field">
          <div class="col-md-12">
            <div class="form-group">
              <label for="grant_date">GrantDate:</label>
              <input type="date"
                class="form-control"
                name="grant_date"
                id="grant_date"
                placeholder="GrantDate" />
            </div>
          </div>
        </div>

        <div class="row" id="request_date_field">
          <div class="col-md-12">
            <div class="form-group">
              <label for="request_date">RequestDate:</label>
              <input type="date"
                class="form-control"
                name="request_date"
                id="request_date"
                placeholder="RequestDate" />
            </div>
          </div>
        </div>

        <div class="row" id="number_field">
          <div class="col-md-12">
            <div class="form-group">
              <label for="number">Number:</label>
              <input type="text"
                class="form-control"
                name="number"
                id="number"
                placeholder="Number" />
            </div>
          </div>
        </div>

        <div class="row" id="request_number_field">
          <div class="col-md-12">
            <div class="form-group">
              <label for="request_number">Request Number:</label>
              <input type="text"
                class="form-control"
                name="request_number"
                id="request_number"
                placeholder="Request Number" />
            </div>
          </div>
        </div>

        <div class="row" id="work_type_field">
          <div class="col-md-12">
            <div class="form-group">
              <label for="work_type">Work Type:</label>
              <input type="text"
                class="form-control"
                name="work_type"
                id="work_type"
                placeholder="Work Type" />
            </div>
          </div>
        </div>

        <div class="row" id="work_characteristic_field">
          <div class="col-md-12">
            <div class="form-group">
              <label for="work_characteristic">Work Characteristic:</label>
              <input type="text"
                class="form-control"
                name="work_characteristic"
                id="work_characteristic"
                placeholder="Work Characteristic" />
            </div>
          </div>
        </div>

        <div class="row" id="extra1_field">
          <div class="col-md-12">
            <div class="form-group">
              <label for="extra1">Extra1: สังกัด</label>
              <input type="text"
                class="form-control"
                name="extra1"
                id="extra1"
                placeholder="extra1" />
            </div>
          </div>
        </div>

        <div class="row" id="description_field">
          <div class="col-md-12">
            <div class="form-group">
              <label for="description">Description:</label>
              <input type="text"
                class="form-control"
                name="description"
                id="description"
                placeholder="Description" />
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
              <input type="submit" class="btn btn-primary" >
              <input type="reset" class="btn btn-warning" />
          </div>
        </div>

      </form>

      <script type="text/javascript">
        // disable submit button
        // btnSubmit
        $( "#form1" ).submit(function( event ) {
          //alert( "Handler for .submit() called." );
          //event.preventDefault();
          $( "#btnSubmit" ).attr("disabled", "disabled");
          //event.preventDefault();
        });

        $("#patent_type_id1").click( function() {
          hideField();
          showFieldCopyRight();
        });

        $("#patent_type_id2").click( function() {
          hideField();
          showFieldPatent();
        });

        $("#patent_type_id3").click( function() {
          hideField();
          showFieldPatent();
        });

        // 1
        function showFieldCopyRight() {
          $("#grant_date_field").show();
          $("#number_field").show();
          $("#work_type_field").show();
          $("#work_characteristic_field").show();
          $("#extra1_field").show();
          $("#description_field").show();
        }

        // 2,3
        function showFieldPatent() {
          $("#grant_date_field").show();
          $("#request_date_field").show();
          $("#number_field").show();
          $("#request_number_field").show();
          $("#work_type_field").show();
          $("#extra1_field").show();
          $("#description_field").show();
        }

        function hideField() {
          $("#grant_date_field").hide();
          $("#request_date_field").hide();
          $("#number_field").hide();
          $("#request_number_field").hide();
          $("#work_type_field").hide();
          $("#work_characteristic_field").hide();
          $("#extra1_field").hide();
          $("#description_field").hide();
        }

        hideField();
      </script>

    </div>
  </body>
</html>
