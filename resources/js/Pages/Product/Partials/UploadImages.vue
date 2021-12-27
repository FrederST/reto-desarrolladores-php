<template>
    <a-upload
        name="image"
        :action="`productImages/upload/${product.id}`"
        :withCredentials="true"
        v-model:file-list="fileList"
        :headers="{ 'X-CSRF-TOKEN': this.$page.props.auth.csrf_token }"
        list-type="picture-card"
        :remove="removeFile"
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

export default defineComponent({
    props: {
        product: Object,
        edit: false,
    },
    components: {
        PlusOutlined,
    },
    methods: {
        closeModal() {
            this.$emit("close", true);
        },

        removeFile(file) {
            const index = this.fileList.indexOf(file);
            if (index > -1) {
                Inertia.delete(route("products.images.destroy", file.uid), {
                    "X-CSRF-TOKEN": this.$page.props.auth.csrf_token,
                });
                this.fileList.splice(index, 1);
                return true;
            }
            return false;
        },

        print() {
            console.log(this.fileList);
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
