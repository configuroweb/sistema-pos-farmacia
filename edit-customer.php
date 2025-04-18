<?php
/* ------------------------------------------------------------------
 *  edit-customer.php  –  Actualiza los datos de un cliente existente
 * ------------------------------------------------------------------ */
include('topbar.php');

if (empty($_SESSION['login_email'])) {
    header("Location: index.php");
    exit;
}

$email     = $_SESSION['login_email'];
$row_user  = $dbh->query("SELECT * FROM users WHERE email='$email'")->fetch();

/* ────────────── Traemos al cliente ────────────── */
$customerID = isset($_GET['id']) ? intval($_GET['id']) : 0;
$customer   = $dbh->prepare("SELECT * FROM customer WHERE customerID = ?");
$customer->execute([$customerID]);
$customer = $customer->fetch();

if (!$customer) {
    $_SESSION['error'] = 'Cliente no encontrado.';
    header('Location: add-customer.php');
    exit;
}

/* ────────────── Procesar actualización ────────────── */
if (isset($_POST['btnupdate'])) {

    $fullName  = trim($_POST['txtfullname']);
    $emailC    = trim($_POST['txtemail']);
    $mobile    = trim($_POST['txtmobile']);
    $phone2    = trim($_POST['txtphone2']);
    $address   = trim($_POST['txtaddress']);
    $address2  = trim($_POST['txtaddress2']);
    $city      = trim($_POST['txtcity']);
    $district  = trim($_POST['txtdistrict']);
    $status    = trim($_POST['txtstatus']);

    if ($fullName === '' || $mobile === '' || $address === '' || $district === '') {
        $_SESSION['error'] = 'Los campos marcados con * son obligatorios.';
    } else {
        $sql = 'UPDATE customer SET
                fullName  = :fullName,
                email     = :email,
                mobile    = :mobile,
                phone2    = :phone2,
                address   = :address,
                address2  = :address2,
                city      = :city,
                district  = :district,
                status    = :status
                WHERE customerID = :customerID';
        $ok = $dbh->prepare($sql)->execute([
            ':fullName'   => $fullName,
            ':email'      => $emailC ?: null,
            ':mobile'     => $mobile,
            ':phone2'     => $phone2 ?: null,
            ':address'    => $address,
            ':address2'   => $address2 ?: null,
            ':city'       => $city ?: null,
            ':district'   => $district,
            ':status'     => $status,
            ':customerID' => $customerID
        ]);

        if ($ok) {
            $_SESSION['success'] = 'Cliente actualizado correctamente';
            header('Location: add-customer.php'); // vuelve al listado
            exit;
        }
        $_SESSION['error'] = 'No se pudo actualizar el cliente';
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Edit Customer - Admin Dashboard</title>
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="dist/css/custom.css">
    <link rel="stylesheet" href="popup_style.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <?php @require_once __DIR__ . '/inc/.xloader.php'; ?>
    <div class="wrapper">
        <!-- =======  Sidebar y topbar  ======= -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image"><img src="<?= $row_user['photo'] ?>" class="img-circle elevation-2" width="140" height="141"></div>
                    <div class="info"><a href="#" class="d-block"><?= $row_user['fullname'] ?></a></div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">
                        <?php include('sidebar.php'); ?>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <section class="content-header">
                <h1 class="ml-3 mt-3">Edit Customer</h1>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Update information</h3>
                                </div>
                                <form method="POST">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Full Name *</label>
                                            <input type="text" name="txtfullname" class="form-control" value="<?= htmlspecialchars($customer['fullName']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="txtemail" class="form-control" value="<?= htmlspecialchars($customer['email']) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Mobile *</label>
                                            <input type="number" name="txtmobile" class="form-control" value="<?= $customer['mobile'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Phone 2</label>
                                            <input type="number" name="txtphone2" class="form-control" value="<?= $customer['phone2'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Address *</label>
                                            <textarea name="txtaddress" class="form-control" required><?= htmlspecialchars($customer['address']) ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Address 2</label>
                                            <textarea name="txtaddress2" class="form-control"><?= htmlspecialchars($customer['address2']) ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>City</label>
                                            <input type="text" name="txtcity" class="form-control" value="<?= htmlspecialchars($customer['city']) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>District *</label>
                                            <input type="text" name="txtdistrict" class="form-control" value="<?= htmlspecialchars($customer['district']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Status *</label>
                                            <select name="txtstatus" class="form-control" required>
                                                <option value="Active" <?= $customer['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                                                <option value="Inactive" <?= $customer['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" name="btnupdate" class="btn btn-primary">Update</button>
                                        <a href="add-customer.php" class="btn btn-secondary float-right">Back</a>
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

    <!-- ========= scripts ========= -->
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