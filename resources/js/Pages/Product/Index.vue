<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Products
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-alert
                    v-if="$page.props.message"
                    :message="$page.props.message"
                    type="warning"
                    show-icon
                />

                <a-button type="primary" @click="createCustomer()">
                    New Product
                </a-button>
                <a-popover title="Import" trigger="focus">
                    <template #content>
                        <a-upload-dragger
                            name="products"
                            :multiple="false"
                            action="/products/import"
                            :headers="{
                                'X-CSRF-TOKEN':
                                    this.$page.props.auth.csrf_token,
                            }"
                            @change="handleChange"
                        >
                            <p class="ant-upload-drag-icon">
                                <inbox-outlined></inbox-outlined>
                            </p>
                            <p class="ant-upload-text">
                                Click or drag file to this area to upload
                            </p>
                        </a-upload-dragger>
                    </template>
                    <a-button type="primary">Import</a-button>
                </a-popover>
                <a-table
                    :columns="columns"
                    :data-source="products.data"
                    :pagination="pagination"
                    @change="handleTableChange"
                >
                    <template #emptyText>
                        <a-result status="403" title="No Products"> </a-result>
                    </template>
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex === 'price'">
                            {{ record.price.value }}
                        </template>
                        <template v-if="column.dataIndex === 'sale_price'">
                            {{ record.sale_price.value }}
                        </template>
                        <template v-if="column.dataIndex === 'active'">
                            <span>
                                <check-outlined v-if="!record.disabled_at" />
                                <close-outlined v-else />
                            </span>
                        </template>
                        <template v-if="column.dataIndex === 'actions'">
                            <span>
                                <a @click="uploadImages(record)">Images</a>
                                <a-divider type="vertical" />
                                <a @click="editProduct(record)">Edit</a>
                                <a-divider type="vertical" />
                                <a @click="deleteProduct(record)">Delete</a>
                                <a-divider type="vertical" />
                                <a @click="disableProduct(record)">Disable</a>
                            </span>
                        </template>
                    </template>
                </a-table>
            </div>
        </div>

        <a-modal
            :title="modalTitle"
            v-model:visible="visible"
            :destroyOnClose="true"
            :footer="null"
        >
            <CreateOrEditProductInformationForm
                @close="modalClose"
                :product="productForEdit"
                :weight_units="weight_units"
                :currencies="currencies"
                :edit="modalEdit"
            ></CreateOrEditProductInformationForm>
        </a-modal>
        <a-modal
            :title="modalTitle"
            v-model:visible="visibleUploadImages"
            :destroyOnClose="true"
            :footer="null"
        >
            <UploadImages
                @upload="reloadPage"
                :product="productForUploadImage"
            ></UploadImages>
        </a-modal>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import { Link } from "@inertiajs/inertia-vue3";
import CreateOrEditProductInformationForm from "@/Pages/Product/CreateOrEdit";
import UploadImages from "@/Pages/Product/Partials/UploadImages";

import { createVNode, computed } from "vue";
import { message, Modal } from "ant-design-vue";
import { Inertia } from "@inertiajs/inertia";
import Button from "@/Jetstream/Button.vue";

import {
    CheckOutlined,
    CloseOutlined,
    ExclamationCircleOutlined,
    InboxOutlined,
} from "@ant-design/icons-vue";

const columns = [
    {
        title: "Code",
        dataIndex: "code",
    },
    {
        title: "Name",
        dataIndex: "name",
    },
    {
        title: "Quantity",
        dataIndex: "quantity",
    },
    {
        title: "Price",
        dataIndex: "price",
    },
    {
        title: "Sale Price",
        dataIndex: "sale_price",
    },
    {
        title: "Active",
        dataIndex: "active",
    },
    {
        title: "Actions",
        dataIndex: "actions",
    },
];

export default {
    props: {
        products: Object,
        weight_units: Object,
        currencies: Object,
    },
    data() {
        return {
            productForEdit: Object,
            productForUploadImage: Object,
            visible: false,
            visibleUploadImages: false,
            modalEdit: true,
        };
    },
    components: {
        AppLayout,
        Link,
        Button,
        CreateOrEditProductInformationForm,
        UploadImages,
        CheckOutlined,
        CloseOutlined,
        InboxOutlined,
    },
    methods: {
        deleteProduct(product) {
            Modal.confirm({
                title: "Do you Want to Delete these Product?",
                icon: createVNode(ExclamationCircleOutlined),
                content: createVNode(
                    "div",
                    { style: "color:red;" },
                    "Some descriptions"
                ),
                onOk() {
                    Inertia.delete(route("products.destroy", product.id));
                },
            });
        },
        handleTableChange(pag) {
            Inertia.get(route("products.index", { page: pag.current }));
        },
        editProduct(customer) {
            this.productForEdit = customer;
            this.modalEdit = true;
            this.visible = true;
        },
        modalClose() {
            this.productForEdit = new Object();
            this.modalEdit = true;
            this.visible = false;
        },
        createCustomer() {
            this.productForEdit = new Object();
            this.modalEdit = false;
            this.visible = true;
        },
        modalTitle() {
            return this.modalEdit ? "Edit" : "Create";
        },
        uploadImages(product) {
            this.productForUploadImage = product;
            this.visibleUploadImages = true;
        },
        reloadPage() {
            Inertia.reload();
        },
        disableProduct(product) {
            Inertia.put(route("products.disable", product.id));
        },
        handleChange(info) {
            const status = info.file.status;

            if (status !== "uploading") {
                console.log(info.file, info.fileList);
            }

            if (status === "done") {
                message.success(
                    `${info.file.name} file uploaded successfully.`
                );
                location.reload();
            } else if (status === "error") {
                message.error(`${info.file.name} file upload failed.`);
            }
        },
    },
    setup(props) {
        const pagination = computed(() => ({
            total: props.products.total,
            current: props.products.current_page,
            pageSize: props.products.per_page,
        }));

        return {
            columns,
            pagination,
        };
    },
};
</script>
