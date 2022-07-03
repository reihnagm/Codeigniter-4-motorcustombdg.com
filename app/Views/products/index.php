<section id="our-product"  
    x-data="productsInstance()" 
    x-init="getProducts()" class="latest top">
    <div class="scontainer">
      <div class="heading">
        <h1>OUR PRODUCTS</h1>
      </div>

      <template x-if="loading">
        <div class="center">
          <h3>Mohon Tunggu, sedang memuat data...</h3>
        </div>
      </template>
      <template x-if="!loading && products.length == 0">
        <div class="center">
          <h3>Produk belum ada</h3>
        </div>
      </template>

      <div class="content grid top">
        <template x-for="product in products">
          <div @click="productDetail(product.slug)" class="box">
            <div class="img">
              <template x-if="product.files[0].type == 'image'">
                <img :src="product.files[0].url" width="180"> 
              </template>
              <template x-if="product.files[0].type == 'video'">
                  <video :src="product.files[0].url" width="180" controls> </video>
              </template>
            </div>
            <div class="detalis">
              <h2 x-text="product.title.substring(0, 50) + '...'"></h2>
              <button>Upload by : <span x-text="product.username"></span> </button>
            </div>
          </div>
        </template>
        <template x-if="hasNext">
            <div class="d-flex justify-content-center"> 
              <button type="submit" @click="loadMoreProducts()" class="btn-load-more center"> Load More </button>
            </div>
        </template>
      </div>
    </div>
</section>