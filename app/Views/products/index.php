<?php 
    $baseUrl = base_url();
?>


<section id="products" class="small-container"
    x-data="productsInstance()" 
    x-init="getProducts()">
    <h2 class="title">Featured Products</h2>
    <div class="d-flex flex-row flex-wrap justify-content-between gap-8">
        <template x-if="products.length == 0">
            <div class="py-20 center">
                <p>Produk belum ada</p>
            </div>
        </template>
        <template x-for="product in products">
            <div class="box-product">
                <a @click="productDetail(product.slug)" href="javascript:void(0)">
                    <template x-if="product.files[0].type == 'image'">
                        <img :src="product.files[0].url" width="180"> 
                    </template>
                    <template x-if="product.files[0].type == 'video'">
                        <video :src="product.files[0].url" width="180" controls> </video>
                    </template>
                    <h3 class="py-5" x-text="product.title.substring(0, 50) + '...'"></h3>
                    <p class="f-24 py-5" x-text="product.description.substring(0, 30) + '...'"></p>
                    <small class="my-20">Uploaded by : <span class="badge badge-secondary" x-text="product.username"></span></small>
                </a>
            </div>
        </template>
    </div>
    <template x-if="hasNext">
        <div class="d-flex justify-content-center"> 
            <button type="submit" @click="loadMoreProducts()" class="btn-load-more center"> Load More </button>
        </div>
    </template>
</section>