<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Cart
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-table
                    :columns="columns"
                    :data-source="shoppingCarts.data"
                    :pagination="pagination"
                    @change="handleTableChange"
                >
                    <template #emptyText>
                        <a-result status="403" title="No Products"> </a-result>
                    </template>
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex === 'product'">
                            <span>
                                {{record.product.name}}
                            </span>
                        </template>
                        <template v-if="column.dataIndex === 'actions'">
                            <span>
                                <a @click="uploadImages(record)">Add</a>
                                <a-divider type="vertical" />
                                <a @click="editProduct(record)">Remove</a>
                            </span>
                        </template>
                    </template>
                </a-table>
            </div>
        </div>

    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import { Link } from "@inertiajs/inertia-vue3";
import { createVNode, computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Button from "@/Jetstream/Button.vue";

import {
    CheckOutlined,
    CloseOutlined,
    ExclamationCircleOutlined,
} from "@ant-design/icons-vue";
import { Modal } from 'ant-design-vue';

const columns = [
    {
        title: "Name",
        dataIndex: "product",
    },
    {
        title: "Quantity",
        dataIndex: "quantity",
    },
    {
        title: "Total",
        dataIndex: "total",
    },
    {
        title: "Actions",
        dataIndex: "actions",
    },
];

export default {
    props: {
        shoppingCarts: Object,
    },
    components: {
        AppLayout,
        Link,
        Button,
        CheckOutlined,
        CloseOutlined,
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
    },
    setup(props) {
        const pagination = computed(() => ({
            total: props.shoppingCarts.total,
            current: props.shoppingCarts.current_page,
            pageSize: props.shoppingCarts.per_page,
        }));

        return {
            columns,
            pagination,
        };
    },
};
</script>
