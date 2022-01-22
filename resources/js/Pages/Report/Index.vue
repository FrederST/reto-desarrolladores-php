<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Report
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

                <a-button type="primary" @click="createReport()">
                    New Report
                </a-button>
                <a-table
                    :columns="columns"
                    :data-source="reports.data"
                    :pagination="pagination"
                    @change="handleTableChange"
                >
                    <template #emptyText>
                        <a-result status="403" title="No Products"> </a-result>
                    </template>
                    <template #bodyCell="{ column, record }">
                        <!-- <template v-if="column.dataIndex === 'active'">
                            <span>
                                <check-outlined v-if="!record.disabled_at" />
                                <close-outlined v-else />
                            </span>
                        </template> -->
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
            title="Create Report"
            v-model:visible="visible"
            :destroyOnClose="true"
            :footer="null"
        >
            <CreateOrEditReportForm @close="modalClose" :reportTypes="reportTypes" />
        </a-modal>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import { Link } from "@inertiajs/inertia-vue3";
import CreateOrEditReportForm from "@/Pages/Report/CreateOrEdit";

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
        title: "Type",
        dataIndex: "type",
    },
    {
        title: "Status",
        dataIndex: "status",
    },
    {
        title: "Actions",
        dataIndex: "actions",
    },
];

export default {
    props: {
        reports: Object,
        reportTypes: Object
    },
    data() {
        return {
            visible: false,
        };
    },
    components: {
        AppLayout,
        Link,
        Button,
        CreateOrEditReportForm,
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
        modalClose() {
            this.visible = false;
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
        createReport() {
            this.visible = true;
        },
    },
    setup(props) {
        const pagination = computed(() => ({
            total: props.reports.total,
            current: props.reports.current_page,
            pageSize: props.reports.per_page,
        }));

        return {
            columns,
            pagination,
        };
    },
};
</script>
