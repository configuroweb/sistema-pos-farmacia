<?php
/* ---------------------------------------------------------
 *  edit-category.php  –  Actualiza el nombre de una categoría
 * --------------------------------------------------------- */
include('topbar.php');

if (empty($_SESSION['login_email'])) {
    header("Location: index.php");
    exit;
}

$email     = $_SESSION['login_email'];
$row_user  = $dbh->query("SELECT * FROM users WHERE email='$email'")->fetch();

/* ──────────────── 1. Recuperar categoría ──────────────── */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$category = $dbh->prepare("SELECT * FROM tblcategory WHERE ID = ?");
$category->execute([$id]);
$category = $category->fetch();

if (!$category) {
    $_SESSION['error'] = 'Categoría no encontrada';
    header('Location: add-category.php');
    exit;
}

/* ──────────────── 2. Procesar actualización ───────────── */
if (isset($_POST['btnupdate'])) {
    $category_name = trim($_POST['txtcategory_name']);

    if ($category_name === '') {
        $_SESSION['error'] = 'El campo Nombre de categoría es obligatorio';
    } else {
        /* verificar duplicados (ignorando la misma fila) */
        $dup = $dbh->prepare("SELECT ID FROM tblcategory WHERE category_name = ? AND ID <> ?");
        $dup->execute([$category_name, $id]);

        if ($dup->fetch()) {
            $_SESSION['error'] = 'Ya existe otra categoría con ese nombre';
        } else {
            $sql = 'UPDATE tblcategory SET category_name = :name WHERE ID = :id';
            $ok  = $dbh->prepare($sql)->execute([
                ':name' => $category_name,
                ':id'   => $id
            ]);
            if ($ok) {
                $_SESSION['success'] = 'Categoría actualizada correctamente';
                header('Location: add-category.php'); // vuelve al listado
                exit;
            }
            $_SESSION['error'] = 'No se pudo actualizar la categoría';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Edit Category - Admin Dashboard</title>
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon" />
    <!-- plugins (los mismos que en add-category) -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="dist/css/custom.css">
    <link rel="stylesheet" href="popup_style.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- ===== Sidebar y Topbar ===== -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image"><img src="<?= $row_user['photo']; ?>" class="img-circle elevation-2" width="140" height="141"></div>
                    <div class="info"><a href="#" class="d-block"><?= $row_user['fullname']; ?></a></div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview"><?php include('sidebar.php'); ?></ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <section class="content-header">
                <h1 class="ml-3 mt-3">Edit Category</h1>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-5">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Update category name</h3>
                                </div>
                                <form method="POST">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Category Name *</label>
                                            <input type="text" name="txtcategory_name" class="form-control" value="<?= htmlspecialchars($category['category_name']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" name="btnupdate" class="btn btn-primary">Update</button>
                                        <a href="add-category.php" class="btn btn-secondary float-right">Back</a>
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