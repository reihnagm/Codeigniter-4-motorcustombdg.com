<?= view('layouts/headv2') ?>

<nav class="nav">
    <div class="container flex1">
      <div class="logo-nav">
        <img src="<?= base_url() ?>/public/assets/imagesv2/logo.png" width="80" alt="motorcustombdg logo">
      </div>
      <div class="menu">
        <div class="main_list" id="mainListDiv">
          <ul>
            <li> <a href="#">Home</a> </li>
            <li> <a href="#">Our Product</a> </li>
            <li> <a href="#">About Us</a> </li>
          </ul>
        </div>
      </div>
      <div class="media_button">
        <i class="fas fa-bars" id="mediaButton"></i>
      </div>
      <div class="account flex1">
        <p>Login / Register</p>
      </div>
    </div>
  </nav>
 
  <section class="home">
    <div class="content flex">
      <div class="left">
        <h2>BE DIFFERENT RIDE CUSTOM MOTORCYLE</h2>
        <p>we sell ready stock custom motorcycle for you</p>
      </div>

      <div class="right">
        <div class="ani_image">
          <img src="<?= base_url() ?>/public/assets/imagesv2/bike.png" alt="">
        </div>
      </div>
    </div>
  </section>


  <section class="latest top">
    <div class="scontainer">
      <div class="heading">
        <h1>OUR PRODUCTS</h1>
      </div>

      <div class="content grid top">
        <div class="box">
          <div class="img">
            <img src="<?= base_url() ?>/public/assets/imagesv2/l1.png" alt="">
          </div>

          <div class="detalis">
            <h3>Aerion Carrbo Helmet</h3>
            <h2>Rp. 18.000.000</h2>
            <button>Upload by : Admin</button>
          </div>
        </div>
        <div class="box">
          <div class="img">
            <img src="<?= base_url() ?>/public/assets/imagesv2/l2.png" alt="">
          </div>

          <div class="detalis">
            <h3>Aerion Carrbo Helmet</h3>
            <h2>Rp. 18.000.000</h2>
            <button>Upload by : Admin</button>
          </div>
        </div>
        <div class="box">
          <div class="img">
            <img src="<?= base_url() ?>/public/assets/imagesv2/l3.png" alt="">
          </div>

          <div class="detalis">
            <h3>Aerion Carrbo Helmet</h3>
            <h2>Rp. 18.000.000</h2>
            <button>Upload by : Admin</button>
          </div>
        </div>
        <div class="box">
          <div class="img">
            <img src="<?= base_url() ?>/public/assets/imagesv2/l4.png" alt="">
          </div>

          <div class="detalis">
            <h3>Aerion Carrbo Helmet</h3>
            <h2>Rp. 18.000.000</h2>
            <button>Upload by : Admin</button>
          </div>
        </div>
        <div class="box">
          <div class="img">
            <img src="<?= base_url() ?>/public/assets/imagesv2/l5.png" alt="">
          </div>

          <div class="detalis">
            <h3>Aerion Carrbo Helmet</h3>
            <h2>Rp. 18.000.000</h2>
            <button>Upload by : Admin</button>
          </div>
        </div>
        <div class="box">
          <div class="img">
            <img src="<?= base_url() ?>/public/assets/imagesv2/l6.png" alt="">
          </div>

          <div class="detalis">
            <h3>Aerion Carrbo Helmet</h3>
            <h2>Rp. 18.000.000</h2>
            <button>Upload by : Admin</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="wrapper top">
    <div class="scontainer">
      <div class="text">
        <h1>"Kami adalah solusi bagi kalian yang ingin tampil beda dijalan dengan mengendarai motor custom dengan aneka bentuk dan aliran modifikasi"</h1>
        <h2>Kami menjual motor custom ready stock yang siap pakai sehingga kalian tidak perlu menunggu lama untuk merakit dan memesan nya, karena kita disini menyediakan beraneka ragam motor custom ready stock yang pastinya bisa bikin kalian ganteng dijalan!</h2>
      </div>
    </div>
  </section>


<?= view('layouts/footerv2') ?>

<?= view('layouts/script') ?>