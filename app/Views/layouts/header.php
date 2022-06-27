<?php 
    use Config\Services;

    $request = Services::request();
    $session = Services::session();

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
                        <li><a href="<?= base_url() ?>" class="text-white">HOME</a></li>
                        <li><a href="javascript:void(0)" class="text-white">OUR PRODUCT</a></li>
                        <li><a href="javascript:void(0)" class="text-white">ABOUT US</a></li>
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
            <?php if(uri_string() != "/auth") { ?>
                <?= view('layouts/banner') ?>
            <?php } else { ?> <?php } ?> 
        </div>
    </div>
</section>