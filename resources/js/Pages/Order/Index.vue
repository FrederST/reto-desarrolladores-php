<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Order
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <a-alert v-if="$page.props.message" :message="$page.props.message" type="warning" show-icon />
                <a-table
                    :columns="columns"
                    :data-source="orders.data"
                    @change="handleTableChange"
                >
                    <template #emptyText>
                        <a-result status="403" title="No Orders"> </a-result>
                    </template>
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex === 'actions'">
                            <span v-if="record.status != 'APPROVED'">
                                <a v-if="record.user_id == $page.props.user.id" :href="route('orders.retry', record.id)">Retry</a>
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
import { computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Button from "@/Jetstream/Button.vue";

import {
    CheckOutlined,
    CloseOutlined,
} from "@ant-design/icons-vue";

const columns = [
    {
        title: "Order Number",
        dataIndex: "order_number",
    },
    {
        title: "Status",
        dataIndex: "status",
    },
    {
        title: "Items",
        dataIndex: "item_count",
    },
    {
        title: "Total",
        dataIndex: "grand_total",
    },
    {
        title: "Actions",
        dataIndex: "actions",
    },
];

export default {
    props: {
        orders: Object,
    },
    components: {
        AppLayout,
        Link,
        Button,
        CheckOutlined,
        CloseOutlined,
    },
    methods: {
        handleTableChange(pag) {
            Inertia.get(route("orders.index", { page: pag.current }));
        },
    },
    setup(props) {
        const pagination = computed(() => ({
            total: props.orders.total,
            current: props.orders.current_page,
            pageSize: props.orders.per_page,
        }));

        return {
            columns,
            pagination,
        };
    },
};
</script>
