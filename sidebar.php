<li class="nav-item menu-open">
  <a href="index.php" class="nav-link active">
    <i class="nav-icon fas fa-tachometer-alt"></i>
    <p>
      Dashboard
    </p>
  </a>
</li>

<li class="nav-item">
  <a href="#" class="nav-link">
    <p>
      User Management
      <i class="fas fa-angle-left right"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <?php if ($_SESSION['login_groupname'] == "Super Admin") { ?>

      <li class="nav-item">
        <a href="add-admin.php" class="nav-link">
          <i class="far fa-circle nav-icon"></i>
          <p>Add User</p>
        </a>
      </li>
    <?php } ?>


    <li class="nav-item">
      <a href="edit-profile.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Edit Profile</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="edit-photo.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Edit Photo</p>
      </a>
    </li>
    <?php if ($_SESSION['login_groupname'] == "Super Admin") { ?>

      <li class="nav-item">
        <a href="user-record.php" class="nav-link">
          <i class="far fa-circle nav-icon"></i>
          <p>Admin Record</p>
        </a>
      </li>
    <?php } ?>

  </ul>
</li>



<?php if ($_SESSION['login_groupname'] == "Super Admin") { ?>

  <li class="nav-item">
    <a href="add-category.php" class="nav-link">
      <p>Category Management </p>
    </a>
  </li>
<?php } ?>

<?php if ($_SESSION['login_groupname'] == "Super Admin") { ?>

  <li class="nav-item">
    <a href="add-supplier.php" class="nav-link">
      <p>Supplier Management </p>
    </a>
  </li>
<?php } ?>

<?php if ($_SESSION['login_groupname'] == "Super Admin") { ?>

  <li class="nav-item">
    <a href="add-product.php" class="nav-link">
      <p>Product Management </p>
    </a>
  </li>
<?php } ?>

<li class="nav-item">
  <a href="#" class="nav-link">
    <p>
      Sales Management
      <i class="fas fa-angle-left right"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">

    <li class="nav-item">
      <a href="add-sales.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Add Sales</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="sales-record.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Sales Records</p>
      </a>
    </li>
  </ul>
</li>


<li class="nav-item">
  <a href="#" class="nav-link">
    <p>
      Stock In Management
      <i class="fas fa-angle-left right"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">

    <li class="nav-item">
      <a href="add-stock.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Add Stock</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="stock-record.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Stock Records</p>
      </a>
    </li>
  </ul>
</li>

<li class="nav-item">
  <a href="add-customer.php" class="nav-link">
    <i class="nav-icon fas fa-user-plus"></i>
    <p>Customers</p>
  </a>
</li>



<li class="nav-item">
  <a href="changepassword.php" class="nav-link">
    <p>Change Password </p>
  </a>
</li>

<?php if ($_SESSION['login_groupname'] == "Super Admin") { ?>

  <li class="nav-item">
    <a href="backup_db.php" class="nav-link">
      <p>Backup Database </p>
    </a>
  </li>
<?php } ?>


<li class="nav-item">
  <a href="logout.php" class="nav-link">
    <i class="fa fa-sign-out-alt"></i>
    <p>
      Logout
    </p>
  </a>
</li>

<p class="text"></p>

</li>


<p class="text"></p>
<p class="text"></p>
<p class="text"></p>
<p class="text"></p>
<p class="text"></p>
<p class="text"></p>
<p class="text"></p>
<p class="text"></p>

</li>