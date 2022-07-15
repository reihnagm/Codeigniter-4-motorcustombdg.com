<?php 
    use Config\Services;
    $session = Services::session();
?>

<nav>
    <div class="container">
        <div class="d-flex justify-content-between">
            <div class="logo-nav">
                <a href="<?= base_url() ?>">
                    <img src="<?= base_url() ?>/public/assets/imagesv2/logo.png" width="80" alt="motorcustombdg logo">
                </a>
            </div>
            <div class="menu">
                <div class="main_list" id="mainListDiv">
                    <ul>
                    <?php if(uri_string() == "/auth" || strtolower(explode('/', uri_string())[1]) == "products") { ?>
                        <li> <a href="<?= base_url() ?>/#our-product">Our Product</a> </li>
                    <?php } else { ?>
                        <li> <a href="#our-product">Our Product</a> </li>
                    <?php } ?>
                    <?php if(uri_string() == "/auth" || strtolower(explode('/', uri_string())[1]) == "products") { ?>
                        <li> <a href="<?= base_url() ?>/#about-us">About Us</a> </li>
                    <?php } else { ?>
                        <li> <a href="#about-us">About Us</a> </li>
                    <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="media_button">
                <i class="fas fa-bars" id="mediaButton"></i>
            </div>
            <div class="auth-menu d-flex justify-content-around">
                <?php if($session->get("authenticated")) { ?>
                    <ul>
                        <li> <a href="<?= base_url() ?>/admin">Admin</a> </li>
                        <li> <a id="logout-btn" href="javascript:void(0)">Logout</a> </li>
                    </ul>
                <?php } else { ?>
                    <ul>
                        <li> <a href="<?= base_url() ?>/auth">Login / Register</a> </li>
                    </ul>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>