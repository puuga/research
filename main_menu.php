<?php //research.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<?php include "class_import.php"; ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Science Research</title>

    <?php include 'head_tag.php'; ?>

  </head>

  <body>
    <?php include 'navbar.php'; ?>

    <div class="container">

      <div class="row">

        <div class="col-md-4">
          <div class="list-group">
            <a href="research_view.php" class="list-group-item">
              <span class="glyphicon glyphicon-list-alt"></span> Paper Management System
            </a>
            <a href="#" class="list-group-item">
              <span class="glyphicon glyphicon-tag"></span> Patent Management System
            </a>
            <a href="#" class="list-group-item">
              <span class="glyphicon glyphicon-user"></span> Staff Management System
            </a>
          </div>
        </div>

      </div>

    </div>




  </body>
</html>
