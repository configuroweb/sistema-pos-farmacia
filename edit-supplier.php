<?php
/* --------------------------------------------------------
 *  edit-supplier.php  –  Actualiza un proveedor
 * -------------------------------------------------------- */
include('topbar.php');

if (empty($_SESSION['login_email'])) {
    header("Location: index.php");
    exit;
}

$email     = $_SESSION['login_email'];
$row_user  = $dbh->query("SELECT * FROM users WHERE email='$email'")->fetch();

/* ─────── 1. Traer proveedor ─────── */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$supplier = $dbh->prepare("SELECT * FROM tblsupplier WHERE ID = ?");
$supplier->execute([$id]);
$supplier = $supplier->fetch();

if (!$supplier) {
    $_SESSION['error'] = 'Proveedor no encontrado';
    header('Location: add-supplier.php');
    exit;
}

/* ─────── 2. Procesar actualización ─────── */
if (isset($_POST['btnupdate'])) {
    $name    = trim($_POST['txtsupplier_name']);
    $address = trim($_POST['txtaddress']);

    if ($name === '' || $address === '') {
        $_SESSION['error'] = 'Los campos marcados con * son obligatorios';
    } else {
        /* comprobar duplicado salvo la fila actual */
        $dup = $dbh->prepare("SELECT ID FROM tblsupplier WHERE supplier_name = ? AND ID <> ?");
        $dup->execute([$name, $id]);

        if ($dup->fetch()) {
            $_SESSION['error'] = 'Ya existe otro proveedor con ese nombre';
        } else {
            $ok = $dbh->prepare("UPDATE tblsupplier SET supplier_name = ?, address = ? WHERE ID = ?")
                ->execute([$name, $address, $id]);

            if ($ok) {
                $_SESSION['success'] = 'Proveedor actualizado correctamente';
                header('Location: add-supplier.php');
                exit;
            }
            $_SESSION['error'] = 'No se pudo actualizar el proveedor';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Edit Supplier - Admin Dashboard</title>
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon" />
    <!-- plugins usados -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
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
                <h1 class="ml-3 mt-3">Edit Supplier</h1>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-5">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Update supplier information</h3>
                                </div>
                                <form method="POST">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Supplier Name *</label>
                                            <input type="text" name="txtsupplier_name" class="form-control" value="<?= htmlspecialchars($supplier['supplier_name']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Address *</label>
                                            <input type="text" name="txtaddress" class="form-control" value="<?= htmlspecialchars($supplier['address']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" name="btnupdate" class="btn btn-primary">Update</button>
                                        <a href="add-supplier.php" class="btn btn-secondary float-right">Back</a>
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