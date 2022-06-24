<?= view("layouts/head") ?>

<?= view("layouts/header") ?>

<div class="small-container single-product">
    <div class="row">
        <div class="col-2">
            <img src="<?= base_url() .'/'. $images[0]?>" width="100%" id="ProductImg">

            <div class="small-img-row">
                <?php foreach($images as $key => $value): ?>
                    <div class="small-img-col">
                        <img src="<?= base_url() .'/'. $value ?>" width="100%" class="small-img">
                    </div>
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
