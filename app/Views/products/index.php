<section id="our-product"  
    x-data="productsInstance()" 
    x-init="getProducts()" 
    class="latest top">
    <div class="scontainer">
      <div class="heading">
        <h1>OUR PRODUCTS</h1>
      </div>      

      <template x-if="!loading">
        <div class="row justify-content-end">
          <div class="col-md-6">
            <div class="form-group">
              <label for="search" class="sr-only">Search</label>
              <input @input.debounce.500ms="filteredProducts" type="search" class="form-control" id="search" placeholder="Search by title"
                x-ref="searchFieldProductTitle"
                x-model="querySearchTitle"
                x-on:keydown.window.prevent.slash="$refs.searchFieldProductTitle.focus()"  
              >
            </div>
          </div>
        </div>
      </template>

      <template x-if="!loading && products.length == 0">
        <div class="center">
          <h3 class="empty-data">Produk belum ada / tidak ditemukan</h3>
        </div>
      </template>

      <template x-if="loading">
        <div class="center">
          <h3 class="progress-load-data">Mohon Tunggu, sedang memuat data...</h3>
        </div>
      </template>

      <div class="grid">
        <template x-for="product in products">

          <div @click="productDetail(product.slug)" class="my-5 card-home-container">
            <div class="card-home"> 
              <div class="card-home-image">
                <template x-if="product.files[0].type == 'image'">
                  <img :src="product.files[0].url" width="180"> 
                </template>
                <template x-if="product.files[0].type == 'video'">
                    <video :src="product.files[0].url" width="180" controls> </video>
                </template>
              </div>
              <h3 x-text="product.title.substring(0, 15) + '...'"></h3>
              <div class="badge-custom">Upload by : <span x-text="product.username"></span> </div>
            </div>
          </div>
          <!-- <div @click="productDetail(product.slug)" class="box">
            <div class="img">
              <template x-if="product.files[0].type == 'image'">
                <img :src="product.files[0].url" width="180"> 
              </template>
              <template x-if="product.files[0].type == 'video'">
                  <video :src="product.files[0].url" width="180" controls> </video>
              </template>
            </div>
            <div class="detalis">
              <h2 x-text="product.title.substring(0, 15) + '...'"></h2>
              <button>Upload by : <span x-text="product.username"></span> </button>
            </div>
          </div> -->
        </template>
      </div>
      <template x-if="products.length != 0">
        <template x-if="hasNext">
            <div class="d-flex justify-content-center"> 
              <template x-if="loadingMore">
                <div class="center">
                  <h3 class="progress-load-data">Mohon Tunggu, sedang memuat data...</h3>
                </div>
              </template>
              <template x-if="!loadingMore">
                <button type="submit" @click="loadMoreProducts()" class="btn btn-dark btn-load-more"> Load More </button>
              </template>
            </div>
        </template>
      </template>
    </div>
</section>