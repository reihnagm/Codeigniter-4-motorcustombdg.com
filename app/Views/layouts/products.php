<section class="small-container"
    x-data="productsInstance()" 
    x-init="getProducts()">
    <h2 class="title">Featured Products</h2>
    <div class="d-flex flex-row flex-wrap justify-content-between gap-8">
        <template x-for="product in products" :key="product.id">
            <div class="box-product">
                <a href="javascript:void(0)">
                    <img :src="product.img" width="180">
                </a>
                <h4 x-text="product.title"></h4>
                <p x-text="product.description"></p>
            </div>
        </template>
    </div>
    <button type="submit" @click="loadMoreProducts()" class="btn"> Load More </button>
</section>