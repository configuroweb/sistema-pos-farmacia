<?php
include('topbar.php');
if (empty($_SESSION['login_email'])) {
  header("Location: index.php");
} else {
}

$email = $_SESSION["login_email"];

$stmt = $dbh->query("SELECT * FROM users where email='$email'");
$row_user = $stmt->fetch();


if (isset($_POST["btnsave"])) {

  $product_name = $_POST['txtproduct_name'];
  $category = $_POST['cmdcategory'];
  $expirydate = $_POST['txtexpirydate'];
  $qty = $_POST['txtqty'];
  $price = $_POST['txtprice'];


  //generate random productID
  function GenerateproductID()
  {
    $alphabet = "0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 5; $i++) {
      $n = rand(0, $alphaLength);
      $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
  }
  $productID = GenerateproductID();


  $file_type = $_FILES['avatar']['type']; //returns the mimetype
  $allowed = array("image/jpg", "image/jpeg", "image/png");
  if (!in_array($file_type, $allowed)) {
    $_SESSION['error'] = 'Only jpg,jpeg and png files are allowed. ';

    // exit();

  } else {
    $image = addslashes(file_get_contents($_FILES['avatar']['tmp_name']));
    $image_name = addslashes($_FILES['avatar']['name']);
    $image_size = getimagesize($_FILES['avatar']['tmp_name']);
    move_uploaded_file($_FILES["avatar"]["tmp_name"], "uploadImage/" . $_FILES["avatar"]["name"]);
    $location = "uploadImage/" . $_FILES["avatar"]["name"];


    ///check if product already exist
    $stmt = $dbh->prepare("SELECT * FROM tblproduct WHERE product_name=? and category=?");
    $stmt->execute([$product_name, $category]);
    $row_product = $stmt->fetch();


    if ($row_product) {
      $_SESSION['error'] = 'product Already Exist in our Database ';
    } else {
      //Add course details
      $sql = 'INSERT INTO tblproduct(productID,product_name,category,expirydate,qty,price,photo) VALUES(:productID,:product_name,:category,:expirydate,:qty,:price,:photo)';
      $statement = $dbh->prepare($sql);
      $statement->execute([
        ':productID' => $productID,
        ':product_name' => $product_name,
        ':category' => $category,
        ':expirydate' => $expirydate,
        ':qty' => $qty,
        ':price' => $price,
        ':photo' => $location
      ]);
      if ($statement) {
        $_SESSION['success'] = 'Product Added Successfully';
      } else {
        $_SESSION['error'] = 'Problem Adding Product';
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Product - Admin Dashboard</title>
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
  <script type="text/javascript">
    function deldata() {
      if (confirm("ARE YOU SURE YOU WISH TO DELETE THIS PRODUCT FROM THE DATABASE ?")) {
        return true;
      } else {
        return false;
      }

    }
  </script>
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
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Add Product</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <!-- left column -->
            <div class="col-md-4">
              <!-- general form elements -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Add New Product</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="" method="POST" enctype="multipart/form-data">
                  <div class="card-body">

                    <div class="form-group">
                      <label for="exampleInputEmail1">Product Name </label>
                      <input type="text" class="form-control" name="txtproduct_name" id="exampleInputEmail1" size="77" value="<?php if (isset($_POST['txtproduct_name'])) ?><?php echo $_POST['txtproduct_name']; ?>">
                    </div>


                    <div class="form-group">
                      <label for="exampleInputEmail1">Category </label>


                      <?php
                      $sql = "select * from tblcategory";
                      $group = $dbh->query($sql);
                      $group->setFetchMode(PDO::FETCH_ASSOC);
                      echo '<select name="cmdcategory"  id="cmdcategory" class="form-control" >';
                      echo '<option value="">Select Category Name</option>';
                      while ($row = $group->fetch()) {
                        echo '<option value="' . $row['category_name'] . '">' . $row['category_name'] . '</option>';
                      }

                      echo '</select>';
                      ?>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Expiry Date </label>
                      <input type="date" class="form-control" name="txtexpirydate" id="txtexpirydate" size="77" value="<?php if (isset($_POST['txtexpirydate'])) ?><?php echo $_POST['txtexpirydate']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Quantity </label>
                      <input type="number" class="form-control" name="txtqty" id="txtqty" size="77" value="<?php if (isset($_POST['txtqty'])) ?><?php echo $_POST['txtqty']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Unit Price </label>
                      <input type="number" class="form-control" name="txtprice" id="txtprice" size="77" value="<?php if (isset($_POST['txtprice'])) ?><?php echo $_POST['txtprice']; ?>">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputPassword1">Image</label>
                      <p class="text-center">
                        <input type="file" name="avatar" id="avatar" required class="form-control form-control-sm rounded-0" accept="image/png,image/jpeg,image/jpg" onChange="display_img(this)">
                      </p>

                      <p class="text-center">
                        <img src="uploadImage/drug.jpeg" alt="user image" width="178" height="154" id="logo-img">
                      </p>
                    </div>



                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <button type="submit" name="btnsave" class="btn btn-primary">Save</button>
                  </div>
                </form>
              </div>
              <!-- /.card -->


            </div>
            <!--/.col (left) -->
            <!-- right column -->
            <div class="col-md-8">
              <!-- general form elements disabled -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Product Record</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table width="106%" align="center" class="table table-bordered table-striped" id="example1">
                    <thead>
                      <tr>
                        <th width="3%">
                          <div align="center">#</div>
                        </th>
                        <th width="13%">
                          <div align="center">Image</div>
                        </th>
                        <th width="13%">
                          <div align="center">Product Name</div>
                        </th>
                        <th width="13%">
                          <div align="center">Category</div>
                        </th>
                        <th width="13%">
                          <div align="center">Expiry Date</div>
                        </th>
                        <th width="13%">
                          <div align="center">Quantity</div>
                        </th>
                        <th width="13%">
                          <div align="center">Price</div>
                        </th>
                        <th width="6%">
                          <div align="center">Action</div>
                        </th>

                      </tr>
                    </thead>
                    <div align="center"></div>

                    <tbody>

                      <?php
                      $data = $dbh->query("SELECT * FROM tblproduct ORDER BY product_name DESC")->fetchAll();
                      $cnt = 1;
                      foreach ($data as $row) {
                      ?>

                        <tr class="gradeX">
                          <td height="47">
                            <div align="center"><?php echo $cnt; ?></div>
                          </td>
                          <td>
                            <div align="center" class="style2"><span class="controls"><img src="<?php echo $row['photo']; ?>" width="50" height="43" border="2" /></span></div>
                          </td>
                          <td>
                            <div align="center"><?php echo $row['product_name']; ?></div>
                          </td>
                          <td>
                            <div align="center"><?php echo $row['category']; ?></div>
                          </td>
                          <td>
                            <div align="center"><?php echo $row['expirydate']; ?></div>
                          </td>
                          <td>
                            <div align="center"><?php echo $row['qty']; ?></div>
                          </td>
                          <td>
                            <div align="center"><?php echo $row['price']; ?></div>
                          </td>

                          <td>
                            <div class="btn-group">
                              <button type="button" class="btn btn-danger btn-flat">Action</button>
                              <button type="button" class="btn btn-danger btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <div class="dropdown-menu" role="menu">
                                <a class="dropdown-item" href="edit-product.php?id=<?= $row['ID']; ?>">Edit</a>
                                <a class="dropdown-item" href="delete-product.php?id=<?php echo $row['ID']; ?>" onClick="return deldata();">Delete</a>

                              </div>
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
                  <td width="386"></td>
                </tr>
              </table>
              <p>&nbsp;</p>
              </td>
              </tr>

              </table>
              <p>
                <!-- /.card -->
                <!-- /.card -->
            </div>
            <!--/.col (right) -->
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
      <strong><?php include '../footer.php' ?>
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
  <!-- bs-custom-file-input -->
  <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <!-- Page specific script -->
  <script>
    $(function() {
      bsCustomFileInput.init();
    });
  </script>

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


  <script>
    function display_img(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          $('#logo-img').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
</body>

</html>