<template>
    <jet-validation-errors class="mb-4" />
    <a-form>
        <a-form-item label="Type">
            <a-select placeholder="Type" v-model:value="form.type">
                <a-select-option
                    class="m-left"
                    v-for="type in reportTypes"
                    :key="type"
                    :value="type"
                    >{{ type }}</a-select-option
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

export default defineComponent({
    props: {
        reportTypes: Object,
    },
    components: {
        PlusOutlined,
        JetValidationErrors,
    },
    data() {
        return {
            form: useForm({
                type: "",
            }),
        };
    },
    methods: {
        saveInfo() {
            this.form.post(route("reports.store"), {
                preserveScroll: true,
                onError: (errors) => console.log(errors),
                onSuccess: (data) => {
                    console.log(data);
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
