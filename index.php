<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

include('topbar.php');
if (empty($_SESSION['login_email'])) {
  header("Location: login.php");
}

$email = $_SESSION["login_email"];

//fetch user data
$stmt = $dbh->query("SELECT * FROM users where email='$email'");
$row_user = $stmt->fetch();
$phone = $row_user['phone'];
$fullname = $row_user['fullname'];


//fetch expired product
$today = date('Y-m-d');
$new_today = date('Y-m-d', strtotime($today . ' + 3 days'));

$sql = "SELECT * FROM tblproduct where expirydate <= '$new_today' ;";
$statement = $dbh->query($sql);
$row_expired = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($row_expired as $Expired) {

  $expiring_drug = $Expired['product_name'];
  $expiring_stock = $Expired['qty'];
  $expiring_productID = $Expired['productID'];

  //send sms alert

  //Create the message data
  $data = array('username' => $username, 'password' => $password, 'sender' => $sender, 'message' => $message, 'mobiles' => $phone);
  //URL encode the message data
  $data = http_build_query($data);
  //Send the message
  $request = $api_url . '?' . $data;
  $result  = file_get_contents($request);
  $result  = json_decode($result);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Welcome to Admin Dashboard</title>
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
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <!-- Custom style - must be loaded last -->
  <link rel="stylesheet" href="dist/css/custom.css">
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
        <!-- User profile dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-user-circle"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <a href="edit-profile.php" class="dropdown-item">
              <i class="fas fa-user mr-2"></i> Profile
            </a>
            <div class="dropdown-divider"></div>
            <a href="logout.php" class="dropdown-item">
              <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index.php" class="brand-link">
        <span class="brand-text font-weight-light">Pharmacy System</span> </a>

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
    <div class="content-wrapper modern-dashboard">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Info boxes -->
          <div class="row">

            <?php
            $stmt = $dbh->prepare("SELECT count(*) FROM tblproduct");
            $stmt->execute([]);
            $count_drug = $stmt->fetchColumn();
            ?>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-primary elevation-1"><i class="fa fa-pills" id="icon"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">No. Of Drug(s) </span>
                  <span class="info-box-number">
                    <?php echo $count_drug;   ?>
                    <small></small>
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>

            <?php
            $stmt = $dbh->prepare("SELECT count(*) FROM tblcategory");
            $stmt->execute([]);
            $count_Category = $stmt->fetchColumn();
            ?>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-accent elevation-1"><i class="fa fa-list" id="icon"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">No. Of Category(s) </span>
                  <span class="info-box-number">
                    <?php echo $count_Category;   ?>
                    <small></small>
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>


            <?php
            $stmt = $dbh->prepare("SELECT count(*) FROM tblsupplier ");
            $stmt->execute([]);
            $count_supplier = $stmt->fetchColumn();
            ?>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-warning elevation-1"><i class="fa fa-truck" id="icon"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">No. Of Supplier(s) </span>
                  <span class="info-box-number">
                    <?php echo $count_supplier;   ?>
                    <small></small>
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>

            <?php
            $stmt = $dbh->prepare("SELECT count(*) FROM users");
            $stmt->execute([]);
            $count_users = $stmt->fetchColumn();
            ?>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-success elevation-1"><i class="fa fa-user" id="icon"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">No. Of User(s) </span>
                  <span class="info-box-number">
                    <?php echo $count_users;   ?>
                    <small></small>
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fa fa-desktop" id="icon"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">System IP Address </span>
                  <span class="info-box-number">
                    <?php echo $row_user['last_ip'];  ?>
                    <small></small>
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box">
                <span class="info-box-icon bg-danger elevation-1"><i class="fa fa-clock" id="icon"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Last Login</span>
                  <span class="info-box-number">
                    <?php echo $row_user['lastaccess'];  ?>
                    <small></small>
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>

            <!-- /.col -->
          </div>
          <!-- /.row -->

          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <div class="col-md-8">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Stock Overview</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="info-box bg-gradient-primary">
                        <span class="info-box-icon"><i class="fas fa-box-open"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Products in Stock</span>
                          <span class="info-box-number"><?php echo $count_drug; ?></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="info-box bg-gradient-danger">
                        <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Expiring Soon</span>
                          <span class="info-box-number"><?php echo count($row_expired); ?></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card -->

              <!-- Latest Products -->
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Latest Products</h3>
                  <div class="card-tools">
                    <span class="badge badge-primary"><?php echo $count_drug; ?> Total Products</span>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body modern-card">
                  <div class="row">
                    <?php
                    $sql = 'SELECT * FROM tblproduct ORDER BY ID DESC LIMIT 8;';
                    $statement = $dbh->query($sql);
                    $rowdrugs = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($rowdrugs as $rowdrug) {
                      $drug = $rowdrug['product_name'];
                      $productID = $rowdrug['productID'];
                      $photo = $rowdrug['photo'];
                    ?>
                      <div class="col-md-3 col-sm-6">
                        <div class="product-item">
                          <img src="<?php echo $photo; ?>" alt="<?php echo $drug; ?>" class="img-fluid">
                          <div class="product-info">
                            <h5><?php echo $drug; ?></h5>
                            <small class="text-muted"><?php echo $productID; ?></small>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                  </div>
                </div>
                <div class="card-footer text-center">
                  <a href="add-product.php" class="btn btn-primary">View All Products</a>
                </div>
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->

            <!-- Right column -->
            <div class="col-md-4">
              <!-- User profile card -->
              <div class="card card-widget widget-user">
                <div class="widget-user-header bg-primary">
                  <h3 class="widget-user-username"><?php echo $row_user['fullname']; ?></h3>
                  <h5 class="widget-user-desc">Administrator</h5>
                </div>
                <div class="widget-user-image">
                  <img class="img-circle elevation-2" src="<?php echo $row_user['photo']; ?>" alt="User Avatar">
                </div>
                <div class="card-footer">
                  <div class="row">
                    <div class="col-sm-6 border-right">
                      <div class="description-block">
                        <h5 class="description-header">Email</h5>
                        <span class="description-text"><?php echo $email; ?></span>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="description-block">
                        <h5 class="description-header">Phone</h5>
                        <span class="description-text"><?php echo $phone; ?></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card -->

              <!-- Quick links -->
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body p-0">
                  <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                      <a href="add-product.php" class="nav-link">
                        <i class="fas fa-plus mr-2"></i> Add New Product
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="add-supplier.php" class="nav-link">
                        <i class="fas fa-truck mr-2"></i> Add New Supplier
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="stock-record.php" class="nav-link">
                        <i class="fas fa-boxes mr-2"></i> Manage Inventory
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="sales-record.php" class="nav-link">
                        <i class="fas fa-chart-bar mr-2"></i> View Reports
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div><!--/. container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <strong><?php include('footer.php');  ?></strong>
      <div class="float-right d-none d-sm-inline-block">
      </div>
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.js"></script>

  <!-- PAGE PLUGINS -->
  <!-- jQuery Mapael -->
  <script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
  <script src="plugins/raphael/raphael.min.js"></script>
  <script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
  <script src="plugins/jquery-mapael/maps/usa_states.min.js"></script>
  <!-- ChartJS -->
  <script src="plugins/chart.js/Chart.min.js"></script>

  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="dist/js/pages/dashboard2.js"></script>

  <!-- Additional custom script -->
  <script>
    $(document).ready(function() {
      // Add hover effects
      $('.info-box').hover(
        function() {
          $(this).find('#icon').addClass('fa-spin');
        },
        function() {
          $(this).find('#icon').removeClass('fa-spin');
        }
      );

      // Add animation to cards
      $('.card').addClass('animate__animated animate__fadeIn');
    });
  </script>
</body>

</html>