<?php 
    use Config\Services;
    $session = Services::session();
?>

<section class="header">
    <div>
        <div class="container">
            <div class="navbar">
                <div class="logo">
                    <a href="javascript:void(0)">
                        <img src="<?= base_url() ?>/public/assets/images/logo.png" alt="logo" width="125px">
                    </a>
                </div>
                <nav>
                    <ul id="MenuItems">
                        <li><a href="<?= base_url() ?>" class="text-white">HOME</a></li>
                        <?php if(uri_string() != "/about") { ?>
                            <li><a href="<?= base_url() ?>/#products" class="text-white">OUR PRODUCT</a></li>
                        <?php } else { ?>
                            <li><a href="<?= base_url() ?>/#products" class="text-white">OUR PRODUCT</a></li>    
                        <?php } ?>
                        <li><a href="<?= base_url() ?>/about" class="text-white">ABOUT US</a></li>
                        <?php if($session->get("role") === "admin") { ?>
                            <li><a href="<?= base_url() ?>/admin"class="text-white">ADMIN</a></li>
                        <?php } ?>
                        <li>
                            <?php if(!empty($session->get("authenticated"))) { ?>
                                <a id="logout-btn" href="javascript:void(0)" class="text-white">LOGOUT</a>
                            <?php } else { ?>
                                <a href="<?= base_url() ?>/auth" class="text-white">ACCOUNT</a>
                            <?php } ?>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php if(uri_string() != "/auth" && uri_string() != "/about") { ?>
                <?= view('layouts/banner') ?>
            <?php } ?> 
        </div>
    </div>
</section>