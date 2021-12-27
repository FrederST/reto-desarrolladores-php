<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Welcome
            </h2>
        </template>

        <div class="py-12">
            <input
                class="w-full rounded-md text-lg p-4 border-2 border-gray-200"
                v-model="searchTerm"
                placeholder="Search Product"
            />
            <ul v-if="filteredProducts.length > 0">
                <a-list size="small" bordered :data-source="filteredProducts">
                    <template #renderItem="{ item }">
                        <a-list-item>{{ item.name }} - {{ item.sale_price }}</a-list-item>
                    </template>
                </a-list>
            </ul>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="py-4 grid gap-4 md:grid-cols-4 grid-cols-2">
                    <ProductCard
                        v-for="product in products.data"
                        :key="product.id"
                        :product="product"
                    />
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import ProductCard from "@/Pages/Product/Partials/ProductCard.vue";

export default defineComponent({
    props: {
        products: Object,
    },
    data() {
        return {
            searchTerm: "",
            filteredProducts: [],
        };
    },
    components: {
        AppLayout,
        ProductCard,
    },
    watch: {
        searchTerm() {
            this.searchProducts();
        },
    },
    methods: {
        searchProducts() {
            axios
                .get("/api/products/search", {
                    params: { searchTerm: this.searchTerm },
                })
                .then((res) => (this.filteredProducts = res.data))
                .catch((error) => {});
        },
    },
});
</script>
