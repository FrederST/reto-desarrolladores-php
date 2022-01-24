<template>
    <app-layout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-alert
                    v-if="$page.props.message"
                    :message="$page.props.message"
                    type="warning"
                    show-icon
                />

                <a-dropdown>
                    <template #overlay>
                        <a-menu @click="handleMenuClick">
                            <a-menu-item key="1" @click="createProductsReport()"
                                >Products</a-menu-item
                            >
                            <a-menu-item key="2" @click="createOrdersReport()"
                                >Orders</a-menu-item
                            >
                        </a-menu>
                    </template>
                    <a-button>
                        New Report
                        <DownOutlined />
                    </a-button>
                </a-dropdown>
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
                        <template v-if="column.dataIndex === 'actions'">
                            <span>
                                <a @click="details(record)">Details</a>
                            </span>
                            <a-divider type="vertical" />
                            <span>
                                <a
                                    v-if="
                                        record.status == 'FINISHED' &&
                                        record.path
                                    "
                                    :href="route('reports.download', record.id)"
                                    target="_blank"
                                    >Download</a
                                >
                            </span>
                        </template>
                    </template>
                </a-table>
            </div>
        </div>

        <a-modal
            title="Create Products Report"
            v-model:visible="visibleProductsForm"
            :destroyOnClose="true"
            :footer="null"
        >
            <CreateForProductsForm @close="modalClose" />
        </a-modal>

        <a-modal
            title="Create Orders Report"
            v-model:visible="visibleOrdersForm"
            :destroyOnClose="true"
            :footer="null"
        >
            <CreateForOrdersForm
                @close="modalClose"
                :orderStatuses="orderStatuses"
                :paymentMethods="paymentMethods"
            />
        </a-modal>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import { Link } from "@inertiajs/inertia-vue3";
import CreateForProductsForm from "@/Pages/Report/CreateForProducts";
import CreateForOrdersForm from "@/Pages/Report/CreateForOrders";

import { createVNode, computed } from "vue";
import { Modal } from "ant-design-vue";
import { Inertia } from "@inertiajs/inertia";
import Button from "@/Jetstream/Button.vue";

import {
    CheckOutlined,
    CloseOutlined,
    ExclamationCircleOutlined,
    InboxOutlined,
    DownOutlined,
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
        orderStatuses: Object,
        paymentMethods: Object,
    },
    data() {
        return {
            visibleProductsForm: false,
            visibleOrdersForm: false,
        };
    },
    components: {
        AppLayout,
        Link,
        Button,
        CreateForProductsForm,
        CreateForOrdersForm,
        CheckOutlined,
        CloseOutlined,
        InboxOutlined,
        DownOutlined,
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
            this.visibleProductsForm = false;
            this.visibleOrdersForm = false;
        },
        details(report) {
            Inertia.get(route("reports.show", report.id));
        },
        reloadPage() {
            Inertia.reload();
        },
        createProductsReport() {
            this.visibleProductsForm = true;
        },
        createOrdersReport() {
            this.visibleOrdersForm = true;
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
