<?php //user_add_edit.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php include "class_import.php"; ?>
<?php
  needAdminLevel(0);
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
          <h2><?php echo $mode=="add"?"Add New":"Edit"; ?> User</h2>
        </div>
        <div class="col-md-2 text-right">
          <p></p>
          <a class="btn btn-danger" href="javascript:history.go(-1)" role="button">
            <span class="glyphicon glyphicon-remove"></span> Cancle
          </a>
        </div>
      </div>

      <form role="form" id="form1" method="post" action="user_add_edit_process.php">

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
              <label for="name_th">Name:</label>
              <input type="text"
                class="form-control"
                name="name"
                id="name"
                placeholder="name"
                <?php echo $mode=="edit"?"value='".$_GET['name']."'":""; ?>
                required/>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="name_en">Username:</label>
              <input type="text"
                class="form-control"
                name="username"
                id="username"
                placeholder="username"
                <?php echo $mode=="edit" ? "value='".$_GET['username']."'" : ""; ?>
                required/>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="name_en">Password:</label>
              <input type="text"
                class="form-control"
                name="password"
                id="password"
                placeholder="password"
                <?php echo $mode=="edit" ? "value='".$_GET['password']."'" : ""; ?>
                required/>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="name_en">Admin Level:</label>
              <input type="number"
                min="0"
                max="1"
                class="form-control"
                name="admin_level"
                id="admin_level"
                <?php echo $mode=="edit" ? "value='".$_GET['admin_level']."'" : ""; ?>
                required/>
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
