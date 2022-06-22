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
                        <li><a href="<?= base_url() ?>">HOME</a></li>
                        <li><a href="javascript:void(0)">OUR PRODUCT</a></li>
                        <li><a href="javascript:void(0)">CONTACT US</a></li>
                        <li><a href="javascript:void(0)">OUR LOCATION</a></li>
                        <li><a href="javascript:void(0)">ABOUT US</a></li>
                        <li>
                            <?php if(!empty($session->get("authenticated"))) { ?>
                                <a id="logout-btn" href="javascript:void(0)">LOGOUT</a>
                            <?php } else { ?>
                                <a href="<?= base_url() ?>/auth">ACCOUNT</a>
                            <?php } ?>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php if($segment != "auth") { ?>
                <?= view('layouts/banner') ?>
            <?php } else { ?> <?php } ?> 
        </div>
    </div>
</section>