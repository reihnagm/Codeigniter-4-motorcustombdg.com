<?php 
  use Config\Services;
  $session = Services::session();
?>

<?= view("admin/layouts/head") ?>

  <!-- ADD PRODUCT  -->

  <div class="modal fade bd-create-products-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Create a Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="title" class="col-form-label">Title:</label>
            <input type="text" class="form-control" id="title">
          </div>
          <div class="form-group">
            <label for="description" class="col-form-label">Description:</label>
            <textarea class="form-control" id="description"></textarea>
          </div>
          <div class="form-group">
            <label for="max-upload">Min upload 2 Images & Videos (10 MB) - (1080×1080 & 1920×1080) :</label>
            <div style="margin: 10px 0px; display: flex;">
              <?php for($i = 0; $i < 5; $i++): ?> 
                <form id="form-preview-files-<?= $i ?>" style="margin: 0px 4px 0px 4px;">
                  <label class="product-files-label" for="product-files-<?= $i ?>">
                    <div id="wrapper-product-files">
                      <img id="preview-image-<?= $i ?>" src="https://via.placeholder.com/1080x1080" width="140">
                      <div class="products-files-remove" id="product-files-remove-<?= $i ?>"></div>
                    </div>
                  </label>
                  <input type="file" accept="image/*,video/*" onchange="changeProductFile(this, <?= $i ?>)" name="file" id="product-files-<?=$i?>" style="display:none">     
                </form>
              <?php endfor; ?>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button id="btn-create-a-product" type="button" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- EDIT PRODUCT  -->

  <div class="modal t fade bd-edit-products-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" 
    aria-hidden="true" 
    x-data="productsInstance()">
    <input type="hidden" id="slug" />
    <input type="hidden" id="files-edit" />
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="title-edit" class="col-form-label">Title:</label>
              <input type="text" class="form-control" id="title-edit">
            </div>
            <div class="form-group">
              <label for="description-edit" class="col-form-label">Description:</label>
              <textarea class="form-control" id="description-edit"></textarea>
            </div>
            <div class="form-group">
              <label for="max-upload">Min upload 2 Images & Videos (10 MB) - (1080×1080 & 1920×1080) :</label>
              <div class="wrapper-preview-files-edited" style="margin: 10px 0px; display: flex;"></div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button @click="updateProduct($event)" type="button" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- SHOW FILE -->

  <div class="modal fade bd-show-file-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" 
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Show File</h5>
        </div>
        <div class="modal-body show-file">
          <div id="carouselIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators show-carousel-indicators-file-wrapper">

            </ol>
            <div class="carousel-inner show-carousel-inner-file-wrapper">
              
            </div>
            <a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="wrapper">

    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="<?= base_url() ?>/public/assets/admin/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
    </div>

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
              <i class="fas fa-expand-arrows-alt"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
              <i class="fas fa-th-large"></i>
            </a>
          </li>
      </ul>
    </nav>

    <?= view("admin/layouts/aside") ?>

    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>/admin">Dashboard</a></li>
                <li class="breadcrumb-item active">Products</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <section class="content" x-data="productsInstance()">
        <div class="container-fluid">          
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <div class="float-sm-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-create-products-modal-lg">Create a Product</button>
                  </div>
                </div>
                <div class="card-body">
                  <table id="datatables-products" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Files</th>
                        <th>Upload by</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section>
    </div>

    <footer class="main-footer">
      <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
      All rights reserved.
      <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.2.0
      </div>
    </footer>

  </div>

<?= view("admin/layouts/foot") ?>


