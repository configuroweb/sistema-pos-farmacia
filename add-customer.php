<?php
/* add-customer.php
 * Alta y listado de clientes
 * Requiere: topbar.php, sidebar.php, conexión $dbh ya iniciada
 */
include('topbar.php');

if (empty($_SESSION['login_email'])) {
    header("Location: index.php");
    exit;
}

$email = $_SESSION["login_email"];
$stmt  = $dbh->query("SELECT * FROM users WHERE email='$email'");
$row_user = $stmt->fetch();

/* ─────────────────────────────
   Guardar nuevo cliente
   ───────────────────────────── */
if (isset($_POST["btnsave"])) {

    // Normalizamos entradas
    $fullName  = trim($_POST['txtfullname']);
    $emailC    = trim($_POST['txtemail']);
    $mobile    = trim($_POST['txtmobile']);
    $phone2    = trim($_POST['txtphone2']);
    $address   = trim($_POST['txtaddress']);
    $address2  = trim($_POST['txtaddress2']);
    $city      = trim($_POST['txtcity']);
    $district  = trim($_POST['txtdistrict']);
    $status    = 'Active';

    /* Validaciones básicas */
    if ($fullName === '' || $mobile === '' || $address === '' || $district === '') {
        $_SESSION['error'] = 'Por favor completa los campos obligatorios (*)';
    } else {

        // Evitamos duplicados por nombre + teléfono
        $dup = $dbh->prepare("SELECT customerID FROM customer WHERE fullName=? AND mobile=?");
        $dup->execute([$fullName, $mobile]);

        if ($dup->fetch()) {
            $_SESSION['error'] = 'El cliente ya existe en la base de datos';
        } else {
            $sql = 'INSERT INTO customer (fullName,email,mobile,phone2,address,address2,city,district,status)
                    VALUES (:fullName,:email,:mobile,:phone2,:address,:address2,:city,:district,:status)';
            $ins = $dbh->prepare($sql)->execute([
                ':fullName'  => $fullName,
                ':email'     => $emailC ?: null,
                ':mobile'    => $mobile,
                ':phone2'    => $phone2 ?: null,
                ':address'   => $address,
                ':address2'  => $address2 ?: null,
                ':city'      => $city ?: null,
                ':district'  => $district,
                ':status'    => $status
            ]);

            if ($ins) {
                $_SESSION['success'] = 'Cliente agregado exitosamente';
            } else {
                $_SESSION['error']   = 'Problema al guardar el cliente';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Add Customer - Admin Dashboard</title>
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon" />
    <!-- Estilos y plugins (idénticos a los módulos existentes) -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="dist/css/custom.css">
    <link rel="stylesheet" href="popup_style.css">
    <script type="text/javascript">
        function deldata() {
            return confirm("¿Eliminar este cliente de la base de datos?");
        }
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <?php @require_once __DIR__ . '/inc/.xloader.php'; ?>
    <div class="wrapper">
        <?php /* Navbar, Sidebar y resto generados igual que en otros módulos */ ?>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="index.php" class="brand-link"></a>
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?php echo $row_user['photo']; ?>" class="img-circle elevation-2" width="140" height="141">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?php echo $row_user['fullname']; ?></a>
                    </div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">
                        <?php include('sidebar.php'); ?>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <section class="content-header"></section>

            <!-- =============================== FORMULARIO =============================== -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Formulario -->
                        <div class="col-md-4">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Add New Customer</h3>
                                </div>
                                <form action="" method="POST">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Full Name *</label>
                                            <input type="text" name="txtfullname" class="form-control"
                                                value="<?php echo $_POST['txtfullname'] ?? ''; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="txtemail" class="form-control"
                                                value="<?php echo $_POST['txtemail'] ?? ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Mobile *</label>
                                            <input type="number" name="txtmobile" class="form-control"
                                                value="<?php echo $_POST['txtmobile'] ?? ''; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Phone 2</label>
                                            <input type="number" name="txtphone2" class="form-control"
                                                value="<?php echo $_POST['txtphone2'] ?? ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Address *</label>
                                            <textarea name="txtaddress" class="form-control"
                                                required><?php echo $_POST['txtaddress'] ?? ''; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Address 2</label>
                                            <textarea name="txtaddress2" class="form-control"><?php echo $_POST['txtaddress2'] ?? ''; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>City</label>
                                            <input type="text" name="txtcity" class="form-control"
                                                value="<?php echo $_POST['txtcity'] ?? ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>District *</label>
                                            <input type="text" name="txtdistrict" class="form-control"
                                                value="<?php echo $_POST['txtdistrict'] ?? ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" name="btnsave" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- =============================== LISTADO =============================== -->
                        <div class="col-md-8">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Customer Record</h3>
                                </div>
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Full Name</th>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                                <th>City</th>
                                                <th>District</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $data = $dbh->query("SELECT * FROM customer ORDER BY createdOn DESC")->fetchAll();
                                            $cnt  = 1;
                                            foreach ($data as $row) { ?>
                                                <tr>
                                                    <td><?php echo $cnt++; ?></td>
                                                    <td><?php echo htmlspecialchars($row['fullName']); ?></td>
                                                    <td><?php echo $row['mobile']; ?></td>
                                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['city']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['district']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-danger btn-flat">Action</button>
                                                            <button type="button" class="btn btn-danger btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                                <span class="sr-only">Toggle Dropdown</span>
                                                            </button>
                                                            <div class="dropdown-menu" role="menu">
                                                                <?php if ($row_user['groupname'] !== 'User'): ?>
                                                                    <a class="dropdown-item" href="delete-customer.php?id=<?php echo $row['customerID']; ?>" onclick="return deldata();">Delete</a>
                                                                <?php endif; ?>
                                                                <a class="dropdown-item" href="edit-customer.php?id=<?= $row['customerID']; ?>">Edit</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- /.col-md-8 -->
                    </div>
                </div>
            </section>
        </div>

        <footer class="main-footer"><strong><?php include '../footer.php'; ?></footer>
    </div>

    <!-- ========== Scripts ========= -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>

    <!-- Mensajes emergentes -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="popup popup--icon -success js_success-popup popup--visible">
            <div class="popup__background"></div>
            <div class="popup__content">
                <h3 class="popup__content__title"><strong>Success</strong></h3>
                <p><?php echo $_SESSION['success']; ?></p>
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
                <p><?php echo $_SESSION['error']; ?></p>
                <p><button class="button button--error" data-for="js_error-popup">Close</button></p>
            </div>
        </div>
    <?php unset($_SESSION['error']);
    endif; ?>

    <script>
        var addButtonTrigger = function(el) {
            el.addEventListener('click', function() {
                document.querySelector('.' + el.dataset.for).classList.toggle('popup--visible');
            });
        };
        Array.from(document.querySelectorAll('button[data-for]')).forEach(addButtonTrigger);
    </script>
</body>

</html>