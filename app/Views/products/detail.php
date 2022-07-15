<?php 
    use Config\Services;
    $session = Services::session();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Motor Custom Bandung - <?= $title ?></title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   <!-- Google Font Poppins -->
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="<?= base_url() ?>/public/assets/css/style.css">
    <link rel="stylesheet" href="<?= base_url() ?>/public/assets/css/detail.css">

    <style>
        @media only screen and (max-width: 768px) {
            .auth-menu {
                display: none !important; 
            }
        }
    </style>
</head>
<body>

    <?= view('layouts/nav') ?>

    <div class="content-body my-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-3 col-lg-6 col-md-6 col-xxl-5">
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade show active" id="first">
                                            <?php if($files[0]["type"] == "image") { ?>
                                                <img class="img-fluid" src="<?= base_url() .'/'. $files[0]['url'] ?>">
                                            <?php } else { ?>
                                                <video id="display-video" src="<?= base_url() .'/'. $files[0]['url'] ?>" controls></video>
                                            <?php } ?>
                                        </div>
                                        <?php $i = 0; ?>
                                        <?php foreach($files as $file) : ?>
                                            <?php if($file["type"] == "image") { ?>
                                                <div role="tabpanel" class="tab-pane fade" id="items<?=$i++?>">
                                                    <img class="img-fluid" src="<?= base_url() .'/'. $file['url'] ?>">
                                                </div>
                                            <?php } else { ?>
                                                <div role="tabpanel" class="tab-pane fade" id="items<?=$i++?>">
                                                    <video id="display-video" src="<?= base_url() .'/'. $file['url'] ?>" controls></video>
                                                </div>
                                            <?php } ?>
                                        <?php endforeach ?>
                                    </div>
                                    <div class="tab-slide-content new-arrival-product mb-4 mb-xl-0">
                                        <ul class="nav slide-item-list mt-3" role="tablist">
                                            <?php $i = 0; ?>
                                            <?php foreach($files as $file) : ?>
                                                <?php if($file["type"] == "image") { ?>
                                                    <li role="presentation">
                                                        <a href="#items<?=$i++?>" role="tab" data-bs-toggle="tab">
                                                            <img class="img-fluid" src="<?= base_url() .'/'. $file['url'] ?>" width="50">
                                                        </a>
                                                    </li>
                                                <?php } else { ?>
                                                    <li role="presentation">
                                                        <a href="#items<?=$i++?>" role="tab" data-bs-toggle="tab">
                                                            <video src="<?= base_url() .'/'. $file['url'] ?>" width="100"></video>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            <?php endforeach ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-xl-9 col-lg-6  col-md-6 col-xxl-7 col-sm-12">
                                    <div class="product-detail-content">
                                        <div class="new-arrival-content pr">
                                            <h4><?= $title ?></h4>
                                            <p>Upload by:&nbsp;&nbsp;
                                                <span class="badge badge-success light"><?= $uploadedby ?></span>
                                            </p>
                                            <p class="text-content"><?= $description ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?= view('layouts/footer') ?>

    <script src="<?= base_url() ?>/public/assets/vendor/global/global.min.js"></script>
    <script src="<?= base_url() ?>/public/assets/js/custom.min.js"></script>

    <script>
        var mainListDiv = document.getElementById("mainListDiv")
        mediaButton = document.getElementById('mediaButton')

        mediaButton.onclick = function() {
            mainListDiv.classList.toggle('show_list')
            mediaButton.classList.toggle('active')
        }
    </script>

</body>
</html>
