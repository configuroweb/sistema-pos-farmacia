<?php
/* ------------- cabecera idéntica a tu archivo ------------- */
include('topbar.php');
if (empty($_SESSION['login_email'])) {
  header("Location: index.php");
  exit;
}

$email = $_SESSION["login_email"];
$row_user = $dbh->query("SELECT * FROM users WHERE email='$email'")->fetch();
$current_date = date('Y-m-d');

/* ───────────── Guardar ───────────── */
if (isset($_POST["btnsave"])) {

  if (empty($_POST['txtprice']) || empty($_POST['txtqty']) || empty($_POST['supplier_id'])) {
    $_SESSION['error'] = 'Completa todos los campos obligatorios';
  } else {
    $sql = 'INSERT INTO tblstock(productID,supplier_id,stockDate,drugName,
                                   unitPrice,quantity)
              VALUES(:productID,:supplier,:stockDate,:drugName,:unitPrice,:quantity)';
    $ok = $dbh->prepare($sql)->execute([
      ':productID' => $_POST['txtproductID'],
      ':supplier'  => $_POST['supplier_id'],
      ':stockDate' => $_POST['txtstockinDate'],
      ':drugName'  => $_POST['cmddrug'],
      ':unitPrice' => $_POST['txtprice'],
      ':quantity'  => $_POST['txtqty']
    ]);

    if ($ok) {
      /* actualizar stock y precio del producto */
      $newQty = $_POST['txtstock'] + $_POST['txtqty'];
      $dbh->prepare("UPDATE tblproduct SET qty=?, price=? WHERE product_name=?")
        ->execute([$newQty, $_POST['txtprice'], $_POST['cmddrug']]);

      $_SESSION['success'] = 'Stock agregado correctamente';
    } else {
      $_SESSION['error'] = 'Problema al guardar el stock';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- … todos tus <meta>, css de AdminLTE, Select2, etc. sin cambios … -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="dist/css/custom.css">
  <script>
    /* función de cálculo total idéntica */
    function calculation() {
      let p = parseFloat(txtprice.value) || 0,
        q = parseFloat(txtqty.value) || 0;
      txttotalcost.value = (p * q).toFixed(2);
    }
  </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <?php @require_once __DIR__ . '/inc/.xloader.php'; ?>
  <div class="wrapper">
    <!-- ========== NAVBAR + SIDEBAR sin cambios ========== -->

    <div class="content-wrapper">
      <section class="content-header">
        <h1 class="ml-3">Stock In</h1>
      </section>

      <section class="content">
        <div class="container-fluid">
          <div class="card card-default">
            <div class="card-header">
              <h3 class="card-title">Entrada de inventario</h3>
            </div>

            <form method="POST">
              <div class="card-body">
                <div class="row">
                  <!-- === Col‑1 producto === -->
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Select Drug *</label>
                      <?php
                      $drug = $dbh->query("SELECT * FROM tblproduct");
                      echo '<select name="cmddrug" id="cmddrug" class="form-control select2" style="width:100%;" onchange="GetDrugDetail1(this.value)" required>';
                      echo '<option value="">Select Drug</option>';
                      foreach ($drug as $row) {
                        echo '<option value="' . $row['product_name'] . '">' . $row['product_name'] . '</option>';
                      }
                      echo '</select>';
                      ?>
                    </div>

                    <div class="form-group">
                      <label>Stock‑in Date *</label>
                      <input type="date" name="txtstockinDate" id="txtstockinDate"
                        class="form-control" value="<?= $current_date; ?>" required>
                    </div>

                    <div class="form-group">
                      <label>Current Stock</label>
                      <input type="text" name="txtstock" id="txtstock" class="form-control" readonly>
                    </div>
                  </div>

                  <!-- === Col‑2 categoría y cantidad === -->
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Category</label>
                      <input type="text" name="txtcategory" id="txtcategory" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                      <label>Quantity *</label>
                      <input type="number" name="txtqty" id="txtqty" class="form-control" value="0"
                        oninput="calculation()" required>
                    </div>
                  </div>

                  <!-- === Col‑3 expiración y precio === -->
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Expiry Date</label>
                      <input type="date" name="txtexpirydate" id="txtexpirydate" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                      <label>Unit Price *</label>
                      <input type="number" step="0.01" name="txtprice" id="txtprice"
                        class="form-control" value="0" oninput="calculation()" required>
                    </div>
                  </div>

                  <!-- === Col‑4 proveedor, IDs y total === -->
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Supplier *</label>
                      <?php
                      $sup = $dbh->query("SELECT * FROM tblsupplier");
                      echo '<select name="supplier_id" id="supplier_id" class="form-control select2" required>';
                      echo '<option value="">Select Supplier</option>';
                      foreach ($sup as $s) {
                        echo '<option value="' . $s['ID'] . '">' . $s['supplier_name'] . '</option>';
                      }
                      echo '</select>';
                      ?>
                    </div>

                    <div class="form-group">
                      <label>Product ID</label>
                      <input type="text" name="txtproductID" id="txtproductID" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                      <label>Total Cost</label>
                      <input type="text" name="txttotalcost" id="txttotalcost" class="form-control" readonly>
                    </div>
                  </div>
                </div>
              </div><!-- /.card-body -->

              <div class="card-footer"><button name="btnsave" class="btn btn-primary">Save</button></div>
            </form>
          </div>
        </div>
      </section>
    </div>

    <footer class="main-footer"><?php include('footer.php'); ?></footer>
  </div><!-- /.wrapper -->

  <!-- ===== scripts ===== -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="plugins/select2/js/select2.full.min.js"></script>
  <script src="dist/js/adminlte.min.js"></script>
  <script>
    $('.select2').select2();

    /* ---------- AJAX igual que antes ---------- */
    function GetDrugDetail1(str) {
      if (!str) {
        limpiar();
        return;
      }
      const x = new XMLHttpRequest();
      x.onreadystatechange = function() {
        if (x.readyState == 4 && x.status == 200) {
          const r = JSON.parse(x.responseText); // [expiry, stock, category, productID]
          $('#txtexpirydate').val(r[0]);
          $('#txtstock').val(r[1]);
          $('#txtcategory').val(r[2]);
          $('#txtproductID').val(r[3]);
          calculation();
        }
      };
      x.open("GET", "search/search_stock.php?name=" + encodeURIComponent(str), true);
      x.send();
    }

    function limpiar() {
      $('#txtexpirydate,#txtstock,#txtcategory,#txtproductID,#txttotalcost').val('');
    }
  </script>

  <!-- pop‑ups de éxito / error idénticos -->
  <?php foreach (['success' => '-success', 'error' => '-error'] as $k => $ic):
    if (!empty($_SESSION[$k])): ?>
      <div class="popup popup--icon <?= $ic; ?> js_<?= $k; ?> popup--visible">
        <div class="popup__background"></div>
        <div class="popup__content">
          <h3 class="popup__content__title"><strong><?= ucfirst($k); ?></strong></h3>
          <p><?= $_SESSION[$k]; ?></p>
          <p><button class="button button<?= $ic; ?>" data-for="js_<?= $k; ?>">Close</button></p>
        </div>
      </div>
  <?php unset($_SESSION[$k]);
    endif;
  endforeach; ?>
  <script>
    document.querySelectorAll('button[data-for]').forEach(btn => {
      btn.addEventListener('click', () => document.querySelector('.' + btn.dataset.for)
        .classList.toggle('popup--visible'));
    });
  </script>
</body>

</html>