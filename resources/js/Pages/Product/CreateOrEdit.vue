<template>
    <a-form>
        <a-form-item label="Name">
            <a-input v-model:value="form.name" />
        </a-form-item>
        <a-form-item label="Quantity">
            <a-input v-model:value="form.quantity" type="number" />
        </a-form-item>
        <a-form-item label="Weight">
            <a-input v-model:value="form.weight" type="number" />
        </a-form-item>
        <a-form-item label="Price">
            <a-input v-model:value="form.price" type="number" />
        </a-form-item>
        <a-form-item label="Sale Price">
            <a-input v-model:value="form.sale_price" type="number" />
        </a-form-item>
        <a-form-item label="Active">
            <a-switch v-model:checked="form.status" />
        </a-form-item>
        <a-form-item label="Description">
            <a-textarea v-model:value="form.description" type="textarea" />
        </a-form-item>
        <a-form-item :wrapper-col="{ span: 14, offset: 4 }">
            <a-button type="primary" @click="saveInfo">Save</a-button>
            <a-button style="margin-left: 10px" @click="closeModal"
                >Cancel</a-button
            >
        </a-form-item>
    </a-form>
</template>

<script>
import { defineComponent } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { PlusOutlined } from "@ant-design/icons-vue";

export default defineComponent({
    props: {
        product: Object,
        edit: false,
    },
    components: {
        PlusOutlined,
    },
    data() {
        return {
            form: useForm({
                name: this.product.name,
                description: this.product.description,
                quantity: this.product.quantity,
                weight: this.product.weight,
                price: this.product.price,
                sale_price: this.product.sale_price,
                status: this.product.status,
            }),
        };
    },
    methods: {
        saveInfo() {
            if (this.edit) {
                this.updateProductInformation();
            } else {
                this.createProductInformation();
            }
        },

        createProductInformation() {
            this.form.post(route("products.store"), {
                preserveScroll: true,
                onError: errors => console.log(errors),
                onSuccess: () => this.closeModal(),
            });
        },

        updateProductInformation() {
            this.form.put(route("products.update", this.product.id), {
                preserveScroll: true,
                onSuccess: () => this.closeModal(),
            });
        },

        closeModal() {
            this.$emit("close", true);
        },
    },
});
</script>
