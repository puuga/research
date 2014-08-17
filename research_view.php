<?php //research_view.php ?>
<?php include 'login_control.php'; ?>
<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Science Research</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap mod -->
    <link href="css/bootstrap-mod.css" rel="stylesheet">
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="script/jquery-2.1.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script>

      function setDataForModal(title) {
        $("#researchTitle").html(title);
      }

      function setDataToDelete(title) {
        $("#researchTitleToDelete").html(title);
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
            <a class="btn btn-danger" href="input_form.php" role="button">
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
                <th>File</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </thead>
            <tbody>
              <?php
                // set sql
                $sql = "SELECT * FROM research";
                $result = mysqli_query($con, $sql);
                if (!$result) {
                  die('Error: ' . mysqli_error($con));
                } else {
                  while($row = mysqli_fetch_array($result)) {
                    ?>
                    <tr>
                      <td>
                        <?php echo $row['reference']; ?>
                      </td>
                      <td>
                        <a href='".$row['att_file']."'><span class='glyphicon glyphicon-download'></span> Download
                      </td>

                      <td>
                        <!-- Button trigger modal -->
                        <button class='btn btn-warning' data-toggle='modal' data-target='#myModal' onclick='setDataForModal("<?php echo $row['title']; ?>")'>
                          <span class='glyphicon glyphicon-pencil'></span> edit
                        </button>
                      </td>
                      <td>
                        <!-- Button trigger modal alert -->
                        <button class='btn btn-danger' data-toggle='modal' data-target='#alertToDelete' onclick='setDataToDelete("<?php echo $row['title']; ?>")'>
                          <span class='glyphicon glyphicon-remove'></span> Delete
                        </button>
                      </td>
                    </tr>
                    <?php
                  }
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>

    </div> <!-- end container -->


    <!-- Modal -->
    <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Modal title</h4>
          </div>
          <div class="modal-body" id="mySmallModalBody">
            <div>
              <strong>Title:</strong> <span id="researchTitle"></span>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div> <!-- end Modal -->

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
