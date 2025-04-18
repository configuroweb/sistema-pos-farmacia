<?php
include('topbar.php');
if (empty($_SESSION['login_email'])) {
  header("Location: login.php");
}

$email = $_SESSION['login_email'];

$stmt = $dbh->query("SELECT * FROM users where email='$email'");
$row_user = $stmt->fetch();

if (isset($_POST["btnsave"])) {

  $fullname = $_POST['txtfullname'];
  $groupname = $_POST['cmdtype'];

  //edit profile details
  $sql = "UPDATE users SET fullname=?, groupname=? where email=?";
  $stmt = $dbh->prepare($sql);
  $stmt->execute([$fullname, $groupname, $email]);
  if ($stmt) {

    //save activity log details
    $task = $fullname . ' ' . 'Updated his Profile' . ' ' . 'On' . ' ' . $current_date;
    $sql = 'INSERT INTO activity_log(task) VALUES(:task)';
    $statement = $dbh->prepare($sql);
    $statement->execute([
      ':task' => $task
    ]);

    header("refresh:2;url= edit-profile.php");
    $_SESSION['success'] = 'Profile Edited Successfully...';
  } else {
    $error = 'Problem Editing Photo ';
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View/Edit Profile - Admin Dashboard</title>
  <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="dist/css/custom.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <?php @require_once __DIR__ . '/inc/.xloader.php'; ?>
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">Home</a>
        </li>

      </ul>

      <!-- SEARCH FORM -->
      <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
          <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-navbar" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </form>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">


      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index.php" class="brand-link">
        <span class="brand-text font-weight-light"></span> </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="<?php echo $row_user['photo'];    ?>" alt="User Image" width="140" height="141" class="img-circle elevation-2">
          </div>
          <div class="info">
            <a href="#" class="d-block"><?php echo $row_user['fullname'];  ?></a>
          </div>
        </div>



        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

            <?php
            include('sidebar.php');

            ?>


          </ul>
        </nav>

        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Profile</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">User Profile</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-3">

              <!-- Profile Image -->
              <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                  <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                      src="<?php echo $row_user['photo'];    ?>" alt="User profile picture">
                  </div>

                  <h3 class="profile-username text-center"><?php echo $row_user['fullname'];  ?></h3>

                  <p class="text-muted text-center"><?php echo $row_user['groupname'];  ?></p>

                  <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                      <b>Email:</b> <a class="float-right"><?php echo $row_user['email'];  ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>Status:</b> <a class="float-right"><?php if ($row['status'] == "0") { ?>
                          <span class="badge  bg-danger">InActive</span>
                        <?php } else { ?>
                          <span class="badge  bg-success">Active</span>
                        <?php } ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>IP Address:</b> <a class="float-right"><?php echo $row_user['last_ip'];  ?></a>
                    </li>
                  </ul>

                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->


            </div>
            <!-- /.col -->
            <div class="col-md-9">
              <section class="content">
                <div class="container-fluid">
                  <!-- Small boxes (Stat box) -->
                  <div class="row">

                    <!-- general form elements -->
                    <div class="card card-primary">
                      <div class="card-header">
                        <h3 class="card-title">Edit Profile </h3>
                      </div>
                      <!-- /.card-header -->
                      <!-- form start -->
                      <form action="" method="POST" enctype="multipart/form-data">
                        <div class="card-body">

                          <div class="form-group">
                            <label for="exampleInputEmail1">Fullname </label>
                            <input type="text" class="form-control" name="txtfullname" id="exampleInputEmail1" size="77" value="<?php echo $row_user['fullname']; ?>" placeholder="Enter Fullname">
                          </div>

                          <div class="form-group">
                            <label for="exampleInputPassword1">User Type</label>
                            <?php
                            $sql = "select * from tblgroup where groupname !='Super Admin'";
                            $group = $dbh->query($sql);
                            $group->setFetchMode(PDO::FETCH_ASSOC);
                            echo '<select name="cmdtype"  id="cmdtype" class="form-control" >';
                            echo '<option value="' . $row_user['groupname'] . '">' . $row_user['groupname'] . '</option>';
                            while ($row = $group->fetch()) {
                              echo '<option value="' . $row['groupname'] . '">' . $row['groupname'] . '</option>';
                            }

                            echo '</select>';
                            ?>
                          </div>

                          <!-- /.card-body -->

                          <div class="card-footer">
                            <button type="submit" name="btnsave" class="btn btn-primary">Save Changes</button>
                          </div>
                      </form>
                    </div>

                  </div>
                  <!-- /.row -->
                  <!-- Main row -->
                  <div class="row">
                    <!-- Left col --><!-- /.Left col -->
                    <!-- right col (We are only adding the ID to make the widgets sortable)--><!-- right col -->
                  </div>
                  <!-- /.row (main row) -->
                </div><!-- /.container-fluid -->
              </section>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <div class="float-right d-none d-sm-block">

      </div>
      <?php include('footer.php');   ?>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <link rel="stylesheet" href="popup_style.css">
  <?php if (!empty($_SESSION['success'])) {  ?>
    <div class="popup popup--icon -success js_success-popup popup--visible">
      <div class="popup__background"></div>
      <div class="popup__content">
        <h3 class="popup__content__title">
          <strong>Success</strong>
          </h1>
          <p><?php echo $_SESSION['success']; ?></p>
          <p>
            <button class="button button--success" data-for="js_success-popup">Close</button>
          </p>
      </div>
    </div>
  <?php unset($_SESSION["success"]);
  } ?>
  <?php if (!empty($_SESSION['error'])) {  ?>
    <div class="popup popup--icon -error js_error-popup popup--visible">
      <div class="popup__background"></div>
      <div class="popup__content">
        <h3 class="popup__content__title">
          <strong>Error</strong>
          </h1>
          <p><?php echo $_SESSION['error']; ?></p>
          <p>
            <button class="button button--error" data-for="js_error-popup">Close</button>
          </p>
      </div>
    </div>
  <?php unset($_SESSION["error"]);
  } ?>
  <script>
    var addButtonTrigger = function addButtonTrigger(el) {
      el.addEventListener('click', function() {
        var popupEl = document.querySelector('.' + el.dataset.for);
        popupEl.classList.toggle('popup--visible');
      });
    };

    Array.from(document.querySelectorAll('button[data-for]')).
    forEach(addButtonTrigger);
  </script>

</body>

</html>