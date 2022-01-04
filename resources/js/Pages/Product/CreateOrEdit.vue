<template>
    <a-form>
        <a-form-item label="Name">
            <a-input v-model:value="form.name" />
        </a-form-item>
        <a-form-item label="Quantity">
            <a-input v-model:value="form.quantity" type="number" />
        </a-form-item>
        <a-form-item :wrapper-col="{ span: 14, offset: 4 }" label="Weight">
            <a-input v-model:value="form.weight" type="number" />
            <a-select
                placeholder="Weight Unit"
                v-model:value="form.weight_unit_id"
            >
                <a-select-option
                    class="m-left"
                    v-for="weight_unit in weight_units"
                    :key="weight_unit.id"
                    :value="weight_unit.id"
                    >{{ weight_unit.weight_unit_name }}</a-select-option
                >
            </a-select>
        </a-form-item>
        <a-form-item label="Price">
            <a-input-number
                style="width: 200px"
                :formatter="formatNumber"
                :parser="parseNumber"
                v-model:value="form.price"
            >
            </a-input-number>
        </a-form-item>
        <a-form-item label="Sale Price">
            <a-input-number
                style="width: 200px"
                :formatter="formatNumber"
                :parser="parseNumber"
                v-model:value="form.sale_price"
            >
            </a-input-number>
            {{ this.$page.props.default_currency.alphabetic_code }}
        </a-form-item>
        <a-form-item label="Active">
            <a-switch v-model:checked="form.status" />
        </a-form-item>
        <a-form-item label="Description">
            <a-textarea v-model:value="form.description" type="textarea" />
        </a-form-item>
        <a-form-item :wrapper-col="{ span: 14, offset: 4 }">
            <a-button type="primary" @click="saveInfo">Save</a-button>
            <a-button class="m-left" @click="closeModal">Cancel</a-button>
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
        weight_units: Object,
        currencies: Object,
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
                weight_unit_id: this.product.weight_unit_id,
                price: this.product.price,
                sale_price: this.product.sale_price,
                currency_id: this.product.currency_id,
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
                onError: (errors) => console.log(errors),
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

        formatNumber(value) {
            return `$ ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },

        parseNumber(value) {
            return value.replace(/\$\s?|(,*)/g, "");
        },
    },
});
</script>

<style>
.m-left {
    margin-left: 10px;
}
</style>
