<template>
    <jet-validation-errors class="mb-4" />
    <a-form>
        <a-form-item label="Date">
            <a-date-picker
                style="width: 100%"
                :disabled-date="disabledDate"
                v-model:value="form.filter.created_at"
            />
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
    components: {
        PlusOutlined,
        JetValidationErrors,
    },
    data() {
        return {
            form: useForm({
                type: "PRODUCTS",
                filter: {
                    created_at: "",
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
                    message.success('Report created we notify when is complete');
                    this.closeModal();
                },
            });
        },
        disabledDate(current) {
            if (current > new Date()) {
            return true;
        }
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
