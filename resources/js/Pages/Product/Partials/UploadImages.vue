<template>
    <a-upload
        name="image"
        :action="`productImages/upload/${product.id}`"
        :withCredentials="true"
        v-model:file-list="fileList"
        :headers="{ 'X-CSRF-TOKEN': this.$page.props.auth.csrf_token }"
        list-type="picture-card"
        :remove="removeFile"
        @change="handleChange"
    >
        <div v-if="fileList.length < 8">
            <plus-outlined />
            <div class="ant-upload-text">Upload</div>
        </div>
    </a-upload>
</template>

<script>
import { defineComponent, ref } from "vue";
import { PlusOutlined } from "@ant-design/icons-vue";
import { Inertia } from "@inertiajs/inertia";
import { message } from "ant-design-vue";

export default defineComponent({
    props: {
        product: Object,
        edit: false,
    },
    components: {
        PlusOutlined,
    },
    methods: {
        uploadImage(upload = false) {
            this.$emit("upload", upload);
        },

        removeFile(file) {
            const index = this.fileList.indexOf(file);
            if (index > -1) {
                Inertia.delete(route("products.images.destroy", file.uid), {
                    headers: {
                        "X-CSRF-TOKEN": this.$page.props.auth.csrf_token,
                    },
                    preserveScroll: true,
                    onFinish: () => this.uploadImage(true),
                });
                this.fileList.splice(index, 1);
                return true;
            }
            return false;
        },

        loadImages() {
            this.product.images.forEach((image) => {
                this.fileList.push({
                    uid: image.id,
                    name: "image.png",
                    status: "done",
                    url: image.path,
                });
            });
        },

        handleChange(info) {
            const status = info.file.status;
            if (status !== "uploading") {
                console.log(info.file, info.fileList);
            }
            if (status === "done") {
                message.success(`${info.file.name} file uploaded successfully.`);
                this.uploadImage(true);
            } else if (status === "error") {
                message.error(`${info.file.name} file upload failed.`);
            }
        },
    },
    setup() {
        const fileList = ref([]);
        return {
            fileList,
        };
    },
    mounted() {
        this.loadImages();
    },
});
</script>
