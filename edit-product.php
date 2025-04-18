<?php
/* --------------------------------------------------
 *  edit-product.php  –  Actualiza un producto
 * -------------------------------------------------- */
include('topbar.php');

if (empty($_SESSION['login_email'])) {
  header("Location: index.php");
  exit;
}

$email     = $_SESSION['login_email'];
$row_user  = $dbh->query("SELECT * FROM users WHERE email='$email'")->fetch();

/* ───────── 1. Traer producto ───────── */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = $dbh->prepare("SELECT * FROM tblproduct WHERE ID = ?");
$product->execute([$id]);
$product = $product->fetch();

if (!$product) {
  $_SESSION['error'] = 'Producto no encontrado';
  header('Location: add-product.php');
  exit;
}

/* ───────── 2. Procesar actualización ───────── */
if (isset($_POST['btnupdate'])) {

  $pname   = trim($_POST['txtproduct_name']);
  $cat     = trim($_POST['cmdcategory']);
  $expiry  = trim($_POST['txtexpirydate']);
  $qty     = trim($_POST['txtqty']);
  $price   = trim($_POST['txtprice']);
  $photo   = $product['photo'];   // valor por defecto

  /* validaciones básicas */
  if ($pname === '' || $cat === '' || $qty === '' || $price === '') {
    $_SESSION['error'] = 'Los campos marcados con * son obligatorios';
  } else {

    /* ---------- Manejar foto (si viene un archivo) ---------- */
    if (!empty($_FILES['avatar']['name'])) {
      $file_type = $_FILES['avatar']['type'];
      $allowed   = ["image/jpeg", "image/jpg", "image/png", "image/webp", "image/gif"];
      if (!in_array($file_type, $allowed)) {
        $_SESSION['error'] = 'Solo se permiten imágenes jpg, png, gif o webp';
        header("Location: edit-product.php?id=$id");
        exit;
      }
      /* subimos el archivo */
      $filename  = time() . '_' . basename($_FILES['avatar']['name']);
      $target    = "uploadImage/" . $filename;
      if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target)) {
        $photo = $target;
      }
    }

    /* ---------- Update ---------- */
    $sql = 'UPDATE tblproduct SET 
                  product_name = :pname,
                  category     = :cat,
                  expirydate   = :exp,
                  qty          = :qty,
                  price        = :price,
                  photo        = :photo
                WHERE ID = :id';
    $ok  = $dbh->prepare($sql)->execute([
      ':pname' => $pname,
      ':cat'   => $cat,
      ':exp'   => $expiry,
      ':qty'   => $qty,
      ':price' => $price,
      ':photo' => $photo,
      ':id'    => $id
    ]);

    if ($ok) {
      $_SESSION['success'] = 'Producto actualizado correctamente';
      header('Location: add-product.php');
      exit;
    }
    $_SESSION['error'] = 'No se pudo actualizar el producto';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Edit Product - Admin Dashboard</title>
  <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon" />
  <!-- plugins/requeridos -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="dist/css/custom.css">
  <link rel="stylesheet" href="popup_style.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <?php @require_once __DIR__ . '/inc/.xloader.php'; ?>
  <div class="wrapper">
    <!-- ===== Sidebar ===== -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image"><img src="<?= $row_user['photo']; ?>" width="140" height="141" class="img-circle elevation-2"></div>
          <div class="info"><a href="#" class="d-block"><?= $row_user['fullname']; ?></a></div>
        </div>
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column"><?php include('sidebar.php'); ?></ul>
        </nav>
      </div>
    </aside>

    <div class="content-wrapper">
      <section class="content-header">
        <h1 class="ml-3 mt-3">Edit Product</h1>
      </section>

      <section class="content">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-md-6">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Update product details</h3>
                </div>
                <form method="POST" enctype="multipart/form-data">
                  <div class="card-body">
                    <div class="form-group">
                      <label>Product Name *</label>
                      <input type="text" name="txtproduct_name" class="form-control" value="<?= htmlspecialchars($product['product_name']); ?>" required>
                    </div>

                    <div class="form-group">
                      <label>Category *</label>
                      <?php
                      $cats = $dbh->query("SELECT * FROM tblcategory")->fetchAll(PDO::FETCH_ASSOC);
                      echo '<select name="cmdcategory" class="form-control" required>';
                      echo '<option value="">Select Category</option>';
                      foreach ($cats as $c) {
                        $sel = ($c['category_name'] == $product['category']) ? 'selected' : '';
                        echo '<option value="' . $c['category_name'] . '" ' . $sel . '>' . $c['category_name'] . '</option>';
                      }
                      echo '</select>';
                      ?>
                    </div>

                    <div class="form-group">
                      <label>Expiry Date</label>
                      <input type="date" name="txtexpirydate" class="form-control" value="<?= $product['expirydate']; ?>">
                    </div>

                    <div class="form-group">
                      <label>Quantity *</label>
                      <input type="number" name="txtqty" class="form-control" value="<?= $product['qty']; ?>" required>
                    </div>

                    <div class="form-group">
                      <label>Unit Price *</label>
                      <input type="number" step="0.01" name="txtprice" class="form-control" value="<?= $product['price']; ?>" required>
                    </div>

                    <div class="form-group">
                      <label>Change Image</label>
                      <input type="file" name="avatar" class="form-control" accept="image/png,image/jpeg,image/jpg,image/webp">
                      <div class="mt-2 text-center">
                        <img src="<?= $product['photo']; ?>" id="preview" width="180" height="150">
                      </div>
                    </div>
                  </div>

                  <div class="card-footer">
                    <button type="submit" name="btnupdate" class="btn btn-primary">Update</button>
                    <a href="add-product.php" class="btn btn-secondary float-right">Back</a>
                  </div>
                </form>
              </div><!-- /.card -->
            </div>
          </div>
        </div>
      </section>
    </div>

    <footer class="main-footer"><strong><?php include '../footer.php'; ?></strong></footer>
  </div>

  <!-- ===== scripts ===== -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="dist/js/adminlte.min.js"></script>
  <script>
    /* vista previa instantánea de imagen */
    $('input[type=file]').on('change', function(e) {
      if (e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = e2 => $('#preview').attr('src', e2.target.result);
        reader.readAsDataURL(e.target.files[0]);
      }
    });
  </script>

  <?php if (!empty($_SESSION['success'])): ?>
    <div class="popup popup--icon -success js_success-popup popup--visible">
      <div class="popup__background"></div>
      <div class="popup__content">
        <h3 class="popup__content__title"><strong>Success</strong></h3>
        <p><?= $_SESSION['success']; ?></p>
        <p><button class="button button--success" data-for="js_success-popup">Close</button></p>
      </div>
    </div>
  <?php unset($_SESSION['success']);
  endif; ?>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="popup popup--icon -error js_error-popup popup--visible">
      <div class="popup__background"></div>
      <div class="popup__content">
        <h3 class="popup__content__title"><strong>Error</strong></h3>
        <p><?= $_SESSION['error']; ?></p>
        <p><button class="button button--error" data-for="js_error-popup">Close</button></p>
      </div>
    </div>
  <?php unset($_SESSION['error']);
  endif; ?>

  <script>
    Array.from(document.querySelectorAll('button[data-for]')).forEach(btn => {
      btn.addEventListener('click', () => document.querySelector('.' + btn.dataset.for).classList.toggle('popup--visible'));
    });
  </script>
</body>

</html>