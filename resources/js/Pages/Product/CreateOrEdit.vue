<template>
    <a-row :gutter="16">
        <a-col :span="12">
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
                    <a-textarea
                        v-model:value="form.description"
                        type="textarea"
                    />
                </a-form-item>
                <a-form-item :wrapper-col="{ span: 14, offset: 4 }">
                    <a-button type="primary" @click="saveInfo">Save</a-button>
                    <a-button style="margin-left: 10px" @click="closeModal"
                        >Cancel</a-button
                    >
                </a-form-item>
                <input
                    type="file"
                    accept="image"
                    ref="images"
                    @change="handleFiles"
                    hidden
                />
            </a-form>
        </a-col>
        <a-col :span="12">
            <a-button @click="$refs.images.click()"> Add Image </a-button>
            <a-button @click="print"> P </a-button>
            <a-upload
                v-model:file-list="previewFileList"
                list-type="picture-card"
                :remove="removeFile"
            >
            </a-upload>
        </a-col>
    </a-row>
</template>

<script>
import { defineComponent, ref } from "vue";
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
                images: null,
            }),
        };
    },
    methods: {
        saveInfo() {
            this.form.images = this.fileList;
            if (this.edit) {
                this.updateProductInformation();
            } else {
                this.createProductInformation();
            }
        },

        createProductInformation() {
            this.form.post(route("products.store"), {
                preserveScroll: true,
                onSuccess: () => console.log("Good"),
            });
        },

        updateProductInformation() {
            this.form.put(route("products.update", this.product.id), {
                preserveScroll: true,
                onSuccess: () => console.log("Good"),
            });
        },

        closeModal() {
            this.$emit("close", true);
        },

        handleFiles(e) {
            const file = e.target.files[0];
            const reader = new FileReader();

            reader.readAsDataURL(file);

            reader.onload = () => {
                this.previewFileList.push({
                    uid: Math.random(),
                    name: "image.png",
                    status: "done",
                    url: reader.result,
                });
                this.fileList.push(file);
            };
        },

        removeFile(file) {
            const index = this.previewFileList.indexOf(file);
            if (index > -1) {
                this.fileList.splice(index, 1);
                return true;
            }
            return false;
        },

        print() {
            console.log(this.fileList);
        },
    },
    setup() {
        const fileList = ref([]);
        const previewFileList = ref([]);
        return {
            fileList,
            previewFileList,
        };
    },
});
</script>
