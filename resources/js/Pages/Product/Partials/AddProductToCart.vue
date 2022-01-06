<template>
    <a-button @click="showProductInfo()" type="primary">
        <shopping-cart-outlined key="shopping" />
    </a-button>
    <a-modal
        :title="product.name"
        v-model:visible="visible"
        :confirm-loading="confirmLoading"
        :destroyOnClose="true"
        @ok="addToCart"
    >
        Cantidad:  <a-input-number
            style="width: 350px"
            :min="1"
            v-model:value="addCartForm.quantity"
        />
    </a-modal>
</template>

<script>
import { defineComponent } from "vue";
import { ShoppingCartOutlined } from "@ant-design/icons-vue";
import { useForm } from "@inertiajs/inertia-vue3";

export default defineComponent({
    props: {
        product: Object,
    },
    data() {
        return {
            visible: false,
            confirmLoading: false,
            addCartForm: useForm({
                quantity: 1,
                product_id: this.product.id,
            }),
        };
    },
    components: {
        ShoppingCartOutlined,
    },
    methods: {
        showProductInfo() {
            this.visible = true;
        },
        addToCart() {
            this.confirmLoading = true;
            this.addCartForm.post(route('shoppingCart.store'));
        },
    },
});
</script>

<style>
.center {
    text-align: center;
}
</style>
