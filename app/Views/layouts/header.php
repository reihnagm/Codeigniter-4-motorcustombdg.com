<?php 
    use Config\Services;
    $request = Services::request();
    $segment = $request->uri->getSegment(1);
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
                        <li><a href="<?= base_url() ?>">Home</a></li>
                        <li><a href="javascript:void(0)">Products</a></li>
                        <li><a href="javascript:void(0)">About</a></li>
                        <li><a href="javascript:void(0)">Contact</a></li>
                        <li><a href="<?= base_url() ?>/auth">Account</a></li>
                    </ul>
                </nav>
                <a href="javascript:void(0)"><img src="<?= base_url() ?>/public/assets/images/cart.png" width="30px" height="30px"></a>
                <img src="<?= base_url() ?>/public/assets/images/menu.png" class="menu-icon" onclick="menutoggle()">
            </div>
            <?php if($segment != "auth") { ?>
                <?= view('layouts/banner') ?>
            <?php } else { ?> <?php } ?> 
        </div>
    </div>
</section>