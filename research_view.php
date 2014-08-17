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
              <span class="glyphicon glyphicon-pencil"></span> New Research
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
                <th>Department</th>
                <th>year</th>
                <th>Journal</th>
                <th>Proceedings</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td rowspan="2">Chemistry</td>
                <td>2013</td>
                <td>50</td>
                <td>50</td>
              </tr>
              <tr>
                <td>2014</td>
                <td>60</td>
                <td>60</td>
              </tr>
              <tr>
                <td rowspan="2">Physics</td>
                <td>2013</td>
                <td>50</td>
                <td>50</td>
              </tr>
              <tr>
                <td>2014</td>
                <td>60</td>
                <td>60</td>
              </tr>
              <tr>
                <td rowspan="2">Biology</td>
                <td>2013</td>
                <td>50</td>
                <td>50</td>
              </tr>
              <tr>
                <td>2014</td>
                <td>60</td>
                <td>60</td>
              </tr>
              <tr>
                <td rowspan="2">Mathematics</td>
                <td>2013</td>
                <td>50</td>
                <td>50</td>
              </tr>
              <tr>
                <td>2014</td>
                <td>60</td>
                <td>60</td>
              </tr>
              <tr>
                <td rowspan="2">CSIT</td>
                <td>2013</td>
                <td>50</td>
                <td>50</td>
              </tr>
              <tr>
                <td>2014</td>
                <td>60</td>
                <td>60</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div> <!-- end container -->


  </body>
</html>
