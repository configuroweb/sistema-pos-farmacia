<?php
include('topbar.php');

if (isset($_POST['btnlogin'])) {
  $status = "1";
  //login
  $sql = "SELECT * FROM `users` WHERE `email`=? AND `password`=? AND `status`=?";
  $query = $dbh->prepare($sql);
  $query->execute(array($_POST['txtemail'], $_POST['txtpassword'], $status));
  $row = $query->rowCount();
  $fetch = $query->fetch();
  if ($row > 0) {
    $_SESSION['login_email'] = $fetch['email'];
    $_SESSION['login_groupname'] = $fetch['groupname'];
    $_SESSION['login_fullname'] = $fetch['fullname'];
    $_SESSION['logged'] = time();

    //save activity log details
    $fullname = $fetch['fullname'];
    $task = $fullname . ' ' . 'Logged In' . ' ' . 'On' . ' ' . $current_date;
    $sql = 'INSERT INTO activity_log(task) VALUES(:task)';
    $statement = $dbh->prepare($sql);
    $statement->execute([
      ':task' => $task
    ]);

    header("Location: index.php");
  } else {
    $_SESSION['error'] = ' Wrong Email and Password';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Form | JoshAnn Pharmacy</title>
  <link rel="icon" type="image/png" sizes="16x16" href="../assets/logo.png">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="dist/css/custom.css">
  <style type="text/css">
    <!--
    .style2 {
      color: #000099;
      font-weight: bold;
    }

    .style3 {
      font-size: 12px
    }

    .style4 {
      color: #000000;
      font-size: medium;
    }
    -->
  </style>
</head>

<body class="hold-transition login-page">
  <?php @require_once __DIR__ . '/inc/.xloader.php'; ?>
  <div class="login-box">

    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg style2"> LOGIN FORM </p>

        <form action="" method="post">
          <div class="input-group mb-3">
            <input type="text" class="form-control" name="txtemail" placeholder="Enter Email Address">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" name="txtpassword" placeholder="Enter Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary style3">
                <input type="checkbox" id="remember">
                <label for="remember">
                  Remember Me </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" name="btnlogin" class="btn btn-primary btn-block">Sign In</button>
            </div>
            <!-- /.col -->
            <a href="#" class="style3">Do You Forgot Password?</a>
          </div>
        </form>

        <!-- /.social-auth-links -->
        <p class="mb-1">&nbsp;</p>
      </div>
      <!-- /.login-card-body -->
    </div>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
  </div>
  <!-- /.login-box -->

  <?php //include ('../footer.php');  
  ?>

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>

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