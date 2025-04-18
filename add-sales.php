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

  if (empty($_POST['txtconfirm_password']) && empty($_POST['txtprice'])) {

    $_SESSION['error'] = 'One of the textbox is empty ';
  } else {
    //Add stock in details
    $sql = 'INSERT INTO sales(customerName,drugName,saleDate,quantity,unitPrice) VALUES(:customerName,:drugName,:saleDate,:quantity,:unitPrice)';
    $statement = $dbh->prepare($sql);
    $statement->execute([
      ':customerName' => $_POST['cmdcustomer'],
      ':drugName' => $_POST['cmddrug'],
      ':saleDate' => $_POST['txtsalesDate'],
      ':quantity' => $_POST['txtqty'],
      ':unitPrice' => $_POST['txtprice']

    ]);
    if ($statement) {

      //update stock summary of drug
      $newQty =   $_POST['txtstock'] - $_POST['txtqty'];
      $sql = "UPDATE tblproduct SET qty=? where product_name=?";
      $stmt = $dbh->prepare($sql);
      $stmt->execute([$newQty, $_POST['cmddrug']]);

      // header( "refresh:2;url= add-purchase.php" );
      $_SESSION['success'] = 'Sales Added Successfully';
    } else {
      $_SESSION['error'] = 'Problem Saving Sales';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POS - Admin Dashboard</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="plugins/bs-stepper/css/bs-stepper.min.css">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="plugins/dropzone/min/dropzone.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="dist/css/custom.css">

  <script type="text/javascript">
    function calculation() {
      var price = document.getElementById("txtprice");
      var qty = document.getElementById("txtqty");

      var price = parseFloat(txtprice.value);
      if (isNaN(price)) price = 0;
      var qty = parseFloat(txtqty.value);
      if (isNaN(qty)) qty = 0;

      document.getElementById("txttotalcost").value = qty * price;

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
                <li class="breadcrumb-item active">Stock In</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- SELECT2 EXAMPLE -->
          <div class="card card-default">
            <div class="card-header">
              <h3 class="card-title">Stock In</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <form action="" method="POST">

              <div class="card-body">
                <div class="row">
                  <div class="col-md-3">

                    <div class="form-group">
                      <label>Choose Customer</label>
                      <?php
                      $sql = "select * from customer";
                      $drug = $dbh->query($sql);
                      $drug->setFetchMode(PDO::FETCH_ASSOC);
                      echo '<select name="cmdcustomer"  id="cmdcustomer" class="form-control select2" style="width: 100%;" >';
                      echo '<option value="">Select Customer</option>';
                      while ($row = $drug->fetch()) {
                        echo '<option value="' . $row['fullName'] . '">' . $row['fullName'] . '</option>';
                      }

                      echo '</select>';
                      ?>



                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                      <label for="exampleInputEmail1">Sales Date</label>
                      <input type="date" class="form-control" name="txtsalesDate" id="txtsalesDate" size="77" value="<?php if (isset($_POST['txtsalesDate'])) ?><?php echo $_POST['txtsalesDate']; ?>">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Product ID</label>
                      <input type="text" class="form-control" name="txtproductID" id="txtproductID" size="77" value="<?php if (isset($_POST['txtproductID'])) ?><?php echo $_POST['txtproductID']; ?>" readonly>
                    </div>



                  </div>
                  <div class="col-md-3">

                    <div class="form-group">
                      <label>Select Drug</label>
                      <?php
                      $sql = "select * from tblproduct";
                      $drug = $dbh->query($sql);
                      $drug->setFetchMode(PDO::FETCH_ASSOC);
                      echo '<select name="cmddrug"  id="cmddrug" class="form-control select2" style="width: 100%;" onchange="GetDrugDetail1(this.value)">';
                      echo '<option value="">Select Drug</option>';
                      while ($row = $drug->fetch()) {
                        echo '<option value="' . $row['product_name'] . '">' . $row['product_name'] . '</option>';
                      }

                      echo '</select>';
                      ?>



                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Current Stock </label>
                      <input type="text" class="form-control" name="txtstock" id="txtstock" size="77" value="<?php if (isset($_POST['txtstock'])) ?><?php echo $_POST['txtstock']; ?>" readonly>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Total Cost</label>
                      <input type="text" class="form-control" name="txttotalcost" id="txttotalcost" size="77" value="<?php if (isset($_POST['txttotalcost'])) ?><?php echo $_POST['txttotalcost']; ?>" readonly>
                    </div>

                  </div>
                  <!-- /.col -->

                  <!-- /.col -->
                  <div class="col-md-3">


                    <div class="form-group">
                      <label for="exampleInputEmail1">Category</label>
                      <input type="text" class="form-control" name="txtcategory" id="txtcategory" size="77" value="<?php if (isset($_POST['txtcategory'])) ?><?php echo $_POST['txtcategory']; ?>" readonly>
                    </div>

                    <!-- /.form-group -->
                    <div class="form-group">
                      <label for="exampleInputEmail1">Quantity </label>
                      <input type="number" class="form-control" name="txtqty" id="txtqty" size="77" value="0" onKeyUp="calculation(this.value)">
                    </div>

                  </div>
                  <!-- /.col -->
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Expiry Date</label>
                      <input type="text" class="form-control" name="txtexpirydate" id="txtexpirydate" size="77" value="<?php if (isset($_POST['txtexpirydate'])) ?><?php echo $_POST['txtexpirydate']; ?>" readonly>
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                      <label for="exampleInputEmail1">Unit Price</label>
                      <input type="text" class="form-control" name="txtprice" id="txtprice" size="77" value="<?php if (isset($_POST['txtprice'])) ?><?php echo $_POST['txtprice']; ?>" readonly>
                    </div>

                  </div>



                </div>
                <!-- /.row -->

              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <button type="submit" name="btnsave" class="btn btn-primary">Save</button>

              </div>
            </form>
          </div>
          <!-- /.card -->







        </div>
        <!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <?php include('footer.php');  ?>
      <div class="float-right d-none d-sm-inline-block">

      </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->
  <script>
    function GetDrugDetail1(str) {
      if (str.length == 0) {
        document.getElementById("txtexpirydate").value = "";
        document.getElementById("txtstock").value = "";
        document.getElementById("txtcategory").value = "";
        document.getElementById("txtproductID").value = "";
        document.getElementById("txtprice").value = "";

        return;
      } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            var myObj = JSON.parse(this.responseText);
            document.getElementById("txtexpirydate").value = myObj[0];
            document.getElementById("txtstock").value = myObj[1];
            document.getElementById("txtcategory").value = myObj[2];
            document.getElementById("txtproductID").value = myObj[3];
            document.getElementById("txtprice").value = myObj[4];

          }
        };
        xmlhttp.open("GET", "search/search_sales.php?name=" + str, true);
        xmlhttp.send();
      }
    }
  </script>
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Select2 -->
  <script src="plugins/select2/js/select2.full.min.js"></script>
  <!-- Bootstrap4 Duallistbox -->
  <script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
  <!-- InputMask -->
  <script src="plugins/moment/moment.min.js"></script>
  <script src="plugins/inputmask/jquery.inputmask.min.js"></script>
  <!-- date-range-picker -->
  <script src="plugins/daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap color picker -->
  <script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Bootstrap Switch -->
  <script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
  <!-- BS-Stepper -->
  <script src="plugins/bs-stepper/js/bs-stepper.min.js"></script>
  <!-- dropzonejs -->
  <script src="plugins/dropzone/min/dropzone.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <!-- Page specific script -->
  <script>
    $(function() {
      //Initialize Select2 Elements
      $('.select2').select2()

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })

      //Datemask dd/mm/yyyy
      $('#datemask').inputmask('dd/mm/yyyy', {
        'placeholder': 'dd/mm/yyyy'
      })
      //Datemask2 mm/dd/yyyy
      $('#datemask2').inputmask('mm/dd/yyyy', {
        'placeholder': 'mm/dd/yyyy'
      })
      //Money Euro
      $('[data-mask]').inputmask()

      //Date range picker
      $('#reservationdate').datetimepicker({
        format: 'L'
      });
      //Date range picker
      $('#reservation').daterangepicker()
      //Date range picker with time picker
      $('#reservationtime').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
          format: 'MM/DD/YYYY hh:mm A'
        }
      })
      //Date range as a button
      $('#daterange-btn').daterangepicker({
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function(start, end) {
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        }
      )

      //Timepicker
      $('#timepicker').datetimepicker({
        format: 'LT'
      })

      //Bootstrap Duallistbox
      $('.duallistbox').bootstrapDualListbox()

      //Colorpicker
      $('.my-colorpicker1').colorpicker()
      //color picker with addon
      $('.my-colorpicker2').colorpicker()

      $('.my-colorpicker2').on('colorpickerChange', function(event) {
        $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
      });

      $("input[data-bootstrap-switch]").each(function() {
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
      });

    })
    // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function() {
      window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    });

    // DropzoneJS Demo Code Start
    Dropzone.autoDiscover = false;

    // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
    var previewNode = document.querySelector("#template");
    previewNode.id = "";
    var previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);

    var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
      url: "/target-url", // Set the url
      thumbnailWidth: 80,
      thumbnailHeight: 80,
      parallelUploads: 20,
      previewTemplate: previewTemplate,
      autoQueue: false, // Make sure the files aren't queued until manually added
      previewsContainer: "#previews", // Define the container to display the previews
      clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
    });

    myDropzone.on("addedfile", function(file) {
      // Hookup the start button
      file.previewElement.querySelector(".start").onclick = function() {
        myDropzone.enqueueFile(file);
      };
    });

    // Update the total progress bar
    myDropzone.on("totaluploadprogress", function(progress) {
      document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
    });

    myDropzone.on("sending", function(file) {
      // Show the total progress bar when upload starts
      document.querySelector("#total-progress").style.opacity = "1";
      // And disable the start button
      file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
    });

    // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("queuecomplete", function(progress) {
      document.querySelector("#total-progress").style.opacity = "0";
    });

    // Setup the buttons for all transfers
    // The "add files" button doesn't need to be setup because the config
    // `clickable` has already been specified.
    document.querySelector("#actions .start").onclick = function() {
      myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
    };
    document.querySelector("#actions .cancel").onclick = function() {
      myDropzone.removeAllFiles(true);
    };
    // DropzoneJS Demo Code End
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

</body>

</html>