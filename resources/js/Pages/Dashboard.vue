<template>
    <app-layout title="Dashboard">
        <br />
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a-form>
                <a-row :gutter="24">
                    <a-col :span="8">
                        <a-form-item label="Name">
                            <a-input
                                v-model:value="searchValue"
                                placeholder="Name"
                            ></a-input>
                        </a-form-item>
                    </a-col>
                    <a-col :span="6">
                        <a-form-item label="Sale Price">
                            <a-input-number
                                style="width: 150px"
                                :formatter="formatNumber"
                                :parser="parseNumber"
                                v-model:value="sale_price"
                            >
                            </a-input-number>
                            {{
                                this.$page.props.default_currency
                                    .alphabetic_code
                            }}
                        </a-form-item>
                    </a-col>
                    <a-col :span="6">
                        <a-form-item label="Weight">
                            <a-select
                                placeholder="Weight Unit"
                                v-model:value="weight_unit_id"
                            >
                                <a-select-option class="m-left" value=""
                                    >All</a-select-option
                                >
                                <a-select-option
                                    class="m-left"
                                    v-for="weight_unit in weight_units"
                                    :key="weight_unit.id"
                                    :value="weight_unit.id"
                                    >{{
                                        weight_unit.weight_unit_name
                                    }}</a-select-option
                                >
                            </a-select>
                        </a-form-item>
                    </a-col>
                    <a-button @click="changePage(0)">Search</a-button>
                </a-row>
            </a-form>
            <a-pagination
                v-model:current="current"
                :total="products.total"
                :pageSize="products.per_page"
                show-less-items
                @change="changePage"
            />
        </div>

        <div
            v-if="products.data.length > 0"
            class="max-w-7xl mx-auto sm:px-6 lg:px-8"
        >
            <a-row :gutter="32">
                <a-col
                    v-for="product in products.data"
                    :key="product.id"
                    :span="8"
                >
                    <ProductCard :key="product.id" :product="product" />
                </a-col>
            </a-row>
        </div>

        <div v-else>
            <a-result status="403" title="No Products"></a-result>
        </div>
    </app-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import ProductCard from "@/Pages/Product/Partials/ProductCard.vue";
import { Inertia } from "@inertiajs/inertia";

export default defineComponent({
    props: {
        products: Object,
        weight_units: Object,
    },
    data() {
        return {
            searchValue: "",
            sale_price: 0,
            weight_unit_id: "",
        };
    },
    components: {
        AppLayout,
        ProductCard,
    },
    methods: {
        changePage(pag) {
            const filter = {
                name: this.searchValue,
                description: this.searchValue,
                sale_price: this.sale_price,
                weight_unit_id: this.weight_unit_id,
            };
            Inertia.get(
                route("dashboard"),
                {
                    page: pag,
                    filter,
                },
                {
                    preserveState: true,
                }
            );
        },
        formatNumber(value) {
            return `$ ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        parseNumber(value) {
            return value.replace(/\$\s?|(,*)/g, "");
        },
    },
    setup(props) {
        const current = ref(props.products.current_page);
        return {
            current,
        };
    },
});
</script>
