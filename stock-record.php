<?php
include('topbar.php');
if (empty($_SESSION['login_email'])) {
  header("Location: login.php");
} else {
}

$email = $_SESSION["login_email"];
//fetch user data
$stmt = $dbh->query("SELECT * FROM users where email='$email'");
$row_user = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Stock In Record|Dashboard</title>
  <link rel="icon" type="image/png" sizes="16x16" href="../<?php echo $favicon; ?>">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->

  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

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


  <script type="text/javascript">
    function deldata(drugName) {
      if (confirm("ARE YOU SURE YOU WISH TO DELETE " + " " + drugName + " " + " FROM THE DATABASE?")) {
        return true;
      } else {
        return false;
      }

    }
  </script>
  <style type="text/css">
    <!--
    .style7 {
      vertical-align: middle;
      cursor: pointer;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
      border: 1px solid transparent;
      padding: .375rem .75rem;
      line-height: 1.5;
      border-radius: .25rem;
      transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
      display: inline-block;
      color: #212529;
      text-align: center;
    }
    -->
  </style>
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
      <a href="#" class="brand-link">
        <span class="brand-text font-weight-light"> </span>
      </a>

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

        <!-- SidebarSearch Form -->
        <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
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
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">&nbsp;</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Stock Record</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <p>&nbsp;</p>
            <table width="1204" height="227" border="0" align="center">
              <tr>
                <td width="1090" height="184">
                  <div class="card">
                    <div class="card-header">
                      <div class="card-footer">
                        <a class="btn btn-primary" href="add-stock.php">Add Stock</a>

                      </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table width="85%" align="center" class="table table-bordered table-striped" id="example1">
                        <thead>
                          <th>
                            <div align="center"><span class="style1">#</span></div>
                          </th>
                          <th>
                            <div align="center"><span class="style1">Photo</span></div>
                          </th>
                          <th>
                            <div align="center"><span class="style1">Product ID</span></div>
                          </th>
                          <th>
                            <div align="center"><span class="style1">Purchase ID</span></div>
                          </th>
                          <th>
                            <div align="center"><span class="style1">Stock Date</span></div>
                          </th>
                          <th>
                            <div align="center"><span class="style1">Drug </span></div>
                          </th>
                          <th>
                            <div align="center"><span class="style1">Price </span></div>
                          </th>
                          <th>
                            <div align="center"><span class="style1">Quantity</span></div>
                          </th>
                          <th>
                            <div align="center"><span class="style1">Current Stock</span></div>
                          </th>
                          <th>
                            <div align="center"><span class="style1">Expiry Date</span></div>
                          </th>
                          <th>
                            <div align="center"><span class="style1">Action</span></div>
                          </th>

              </tr>
              </thead>
              <div align="center"></div>

              <tbody>
                <?php
                $data = $dbh->query("SELECT *  FROM tblstock inner join tblproduct on tblproduct.product_name = tblstock.drugName order by tblstock.stockDate DESC")->fetchAll();
                $cnt = 1;
                foreach ($data as $row) {
                ?>
                  <tr class="gradeX">
                    <td>
                      <div align="center" class="style2"><?php echo $cnt;  ?></div>
                    </td>
                    <td>
                      <div align="center" class="style2"><span class="controls"><img src="<?php echo $row['photo']; ?>" width="50" height="43" border="2" /></span></div>
                    </td>
                    <td>
                      <div align="center" class="style2"><?php echo $row['productID'];  ?></div>
                    </td>
                    <td>
                      <div align="center" class="style2"><?php echo $row['purchaseID'];  ?></div>
                    </td>
                    <td class="center">
                      <div align="center" class="style2"><?php echo $row['stockDate'];  ?></div>
                    </td>
                    <td>
                      <div align="center" class="style2"><?php echo $row['drugName'];  ?> </div>
                    </td>
                    <td class="center">
                      <div align="center" class="style2">$<?php echo number_format((float) $row['unitPrice'], 2); ?></div>
                    </td>
                    <td>
                      <div align="center" class="style2"><?php echo $row['quantity'];  ?></div>
                    </td>
                    <td>
                      <div align="center" class="style2"><?php echo $row['qty'];  ?></div>
                    </td>
                    <td>
                      <div align="center" class="style2"><?php echo $row['expirydate'];  ?></div>
                    </td>
                    <td>
                      <div align="center">
                        <?php if ($row_user['groupname'] !== 'User'): ?>
                          <a href="delete-stock.php?id=<?php echo $row['productID']; ?>&qty_new=<?php echo $row['quantity']; ?>&qty_old=<?php echo $row['qty']; ?>&pid=<?php echo $row['purchaseID']; ?>" onClick="return deldata('<?php echo $row['drugName']; ?>');">Delete </a>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php $cnt = $cnt + 1;
                } ?>
              </tbody>
              <tfoot>
              </tfoot>
            </table>

          </div>
          <!-- /.card-body -->
        </div>
        <table width="392" border="0" align="right">
          <tr>
            <td width="386">
              <div class="card-footer">
              </div>
            </td>
          </tr>
        </table>
        <p>&nbsp;</p>

        </td>
        </tr>

        </table>
        <p>
          <!-- /.card -->
        </p>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
  </div>
  <!-- /.container-fluid --><!-- /.content -->
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
  <!-- DataTables -->
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <!-- Page specific script -->
  <script>
    $(function() {
      $("#example1").DataTable({
        "responsive": true,
        "autoWidth": false,
      });
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });
  </script>
</body>

</html>