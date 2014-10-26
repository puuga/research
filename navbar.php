<!-- top nav bar -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="main_menu.php"><?php echo String::system_title; ?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="research.php"><span class="glyphicon glyphicon-stats"></span> Summary Paper</a></li>
        <li><a href="summary_journal.php"><span class="glyphicon glyphicon-stats"></span> Summary Journal</a></li>
        <li><a href="summary_proceedings.php"><span class="glyphicon glyphicon-stats"></span> Summary Proceedings</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-th"></span>
            System <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="research_view.php"><span class="glyphicon glyphicon-list-alt"></span> Paper Management System</a></li>
            <li class="divider"></li>
            <li><a href="#"><span class="glyphicon glyphicon-tag"></span> Patent Management System</a></li>
            <li class="divider"></li>
            <li><a href="staff_view.php"><span class="glyphicon glyphicon-user"></span> Staff Management System</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-user"></span>
            <?php echo $current_user_name; ?> <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
            <?php
              if ($current_user_name != "") {
                if ($current_user_admin_level == 0) {
                  echo '<li><a href="user_view.php"><span class="glyphicon glyphicon-wrench"></span> Admin Manager</a></li>';
                }
                echo '<li><a href="logout_process.php"><span class="glyphicon glyphicon-log-out"></span> Sign out</a></li>';
              } else {
                echo '<li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Sign in</a></li>';
              }
            ?>


          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<!-- buttom nav bar -->
<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="main_menu.php"><?php echo String::system_title; ?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="http://www.sci.nu.ac.th/science/">Sci Web</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Department <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="http://www.sci.nu.ac.th/chemistry">Chemistry</a></li>
            <li><a href="http://www.sci.nu.ac.th/physics">Physics</a></li>
            <li><a href="http://www.sci.nu.ac.th/biology">Biology</a></li>
            <li><a href="http://www.sci.nu.ac.th/mathematics/math">Mathematics</a></li>
            <li><a href="http://www.sci.nu.ac.th/csit">CSIT</a></li>
          </ul>
        </li>
      </ul>

    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
