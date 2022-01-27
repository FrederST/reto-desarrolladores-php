<template>
    <jet-validation-errors class="mb-4" />
    <a-form>
        <a-form-item label="Status">
            <a-select placeholder="Status" v-model:value="form.filter.status">
                <a-select-option
                    class="m-left"
                    v-for="status in orderStatuses"
                    :key="status"
                    :value="status"
                    >{{ status }}</a-select-option
                >
            </a-select>
        </a-form-item>
        <a-form-item label="Payment Method">
            <a-select
                placeholder="Payment Method"
                v-model:value="form.filter.payment_method"
            >
                <a-select-option
                    class="m-left"
                    v-for="method in paymentMethods"
                    :key="method"
                    :value="method"
                    >{{ method }}</a-select-option
                >
            </a-select>
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
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import { message } from "ant-design-vue";

export default defineComponent({
    props: {
        orderStatuses: Object,
        paymentMethods: Object,
    },
    components: {
        PlusOutlined,
        JetValidationErrors,
    },
    data() {
        return {
            form: useForm({
                type: "ORDERS",
                filter: {
                    status: "",
                    payment_method: "",
                },
            }),
        };
    },
    methods: {
        saveInfo() {
            this.form.post(route("reports.store"), {
                preserveScroll: true,
                onError: (errors) => console.log(errors),
                onSuccess: (data) => {
                    message.success(
                        "Report created we notify when is complete"
                    );
                    this.closeModal();
                },
            });
        },

        closeModal() {
            this.$emit("close", true);
        },
    },
});
</script>

<style>
.m-left {
    margin-left: 10px;
}
</style>
