<?php 
  use Config\Services;
  $request = Services::request();
  $session = Services::session();
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index3.html" class="brand-link">
    <img src="<?= base_url() ?>/public/assets/admin/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="<?= base_url() ?>/public/assets/admin/img/admin.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
        <a href="#" class="d-block"><?= $session->get("username") ?></a>
        </div>
    </div>

    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="<?= base_url() ?>/admin" class="nav-link <?= uri_string() == "/admin" ? "active" : ""?>">
                <i class="nav-icon fa-solid fa-gauge"></i>
                    <p> Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url() ?>/admin/products" class="nav-link <?= uri_string() == "/admin/products" ? "active" : ""?>">
                <i class="nav-icon fa-solid fa-cart-shopping"></i>
                    <p>Products</p>
                </a>
            </li>
        </ul>
    </nav>
    </div>
</aside>