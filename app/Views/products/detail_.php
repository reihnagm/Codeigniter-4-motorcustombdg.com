<?= view("layouts/head") ?>

<?= view("layouts/header") ?>

<div class="small-container single-product">
    <div class="row">
        <div class="col-2">
            <?php if($files[0]["type"] == "image") { ?>
                <img src="<?= base_url() .'/'. $files[0]["url"] ?>" width="100%" id="productImg">
            <?php } ?>
            <?php if($files[0]["type"] == "video") { ?>
                <video src="<?= base_url() .'/'. $files[0]["url"] ?>" width="100%" id="productVid" controls>  </video>
            <?php } ?>

            <div class="small-img-row">
                <?php foreach($files as $key => $val): ?>
                    <?php if($val["type"] == "image") { ?>
                        <div class="small-img-col">
                            <img src="<?= base_url() .'/'. $val["url"] ?>" width="100%" id="small-img-<?= $key ?>" data-type="<?= $val["type"] ?>">
                        </div>
                    <?php } ?>
                    <?php if($val["type"] == "video") { ?>
                        <div class="small-img-col">
                            <video src="<?= base_url() .'/'. $val["url"] ?>" width="230" id="small-img-<?= $key ?>" data-type="<?= $val["type"] ?>"> 
                        </video>
                        </div>
                    <?php } ?>
                <?php endforeach; ?>
            </div>

        </div>
        <div class="col-2">
            <h1><?= $title ?></h1>
            <h3>Product Details <i class="fa fa-indent"></i></h3>
            <br>
            <p><?= $description ?></p>
        </div>
    </div>
</div>

<?= view("layouts/footer") ?>

<?= view("layouts/script") ?>
