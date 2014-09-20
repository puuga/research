<?php //staff_add_edit.php ?>
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
    <?php
      if ( !isset($_GET["id"]) || empty($_GET["id"]) ) {
        $mode = "add";
      } else {
        $mode = "edit";
      }
    ?>
    <div class="container">

      <!--title row-->
      <div class="row bg-info">
        <div class="col-md-10">
          <h2><?php echo $mode=="add"?"Add New":"Edit"; ?> Staff</h2>
        </div>
        <div class="col-md-2 text-right">
          <p></p>
          <a class="btn btn-danger" href="javascript:history.go(-1)" role="button">
            <span class="glyphicon glyphicon-remove"></span> Cancle
          </a>
        </div>
      </div>

      <form role="form" id="form1" method="post" action="staff_add_edit_process.php">

        <?php
          if ( $mode == "add" ) {
            echo "<input type='hidden' name='action' value='add'>";
          } else {
            echo "<input type='hidden' name='action' value='edit'>";
            echo "<input type='hidden' name='id' value='".$_GET['id']."'>";
          }
        ?>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="name_th">Thai name:</label>
              <input type="text"
                class="form-control"
                name="name_th"
                id="name_th"
                placeholder="Thai name"
                <?php echo $mode=="edit"?"value='".$_GET['name_th']."'":""; ?>
                required/>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="name_en">English name:</label>
              <input type="text"
                class="form-control"
                name="name_en"
                id="name_en"
                placeholder="English name"
                <?php echo $mode=="edit" ? "value='".$_GET['name_en']."'" : ""; ?>
                required/>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <br/>
            Department:
            <div class="radio">
              <label>
                <input type="radio" name="department" value="1" required
                  <?php echo $mode=="edit"&&$_GET['department_en']=="Matematics" ? "checked" : ""; ?>
                  />
                คณิตศาสตร์<br/>Matematics
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="department" value="2"
                  <?php echo $mode=="edit"&&$_GET['department_en']=="Biology" ? "checked" : ""; ?>
                  />
                ชีววิทยา<br/>Biology
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="department" value="3"
                  <?php echo $mode=="edit"&&$_GET['department_en']=="Physics" ? "checked" : ""; ?>
                  />
                ฟิสิกส์<br/>Physics
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="department" value="4"
                  <?php echo $mode=="edit"&&$_GET['department_en']=="Computer Science and Information Technology" ? "checked" : ""; ?>
                  />
                วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ<br/>Computer Science and Information Technology
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="department" value="5"
                  <?php echo $mode=="edit"&&$_GET['department_en']=="Chemistry" ? "checked" : ""; ?>
                  />
                เคมี<br/>Chemistry
              </label>
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
      </script>

    </div>
  </body>
</html>
