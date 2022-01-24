<template>
    <app-layout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a-page-header title="Cart" sub-title="Ready for buy ?">
                <a-row justify="center" align="middle">
                    <a-col>
                        <a-statistic title="Items" :value="calculateItems()" />
                    </a-col>
                    <a-col>
                        <a-statistic
                            title="Price"
                            prefix="$"
                            :value="calculateCost()"
                            :style="{
                                margin: '0 32px',
                            }"
                        />
                    </a-col>
                    <a-col v-if="shoppingCart.length > 0" :span="12">
                        <a-button
                            block
                            type="primary"
                            @click="goToCreateOrder()"
                            >Buy</a-button
                        >
                    </a-col>
                </a-row>
            </a-page-header>

            <a-table
                :columns="columns"
                :data-source="shoppingCart"
                @change="handleTableChange"
            >
                <template #emptyText>
                    <a-result status="403" title="No Products"> </a-result>
                </template>
                <template #bodyCell="{ column, text, record }">
                    <template v-if="column.dataIndex === 'quantity'">
                        <div class="editable-cell">
                            <div
                                v-if="editableData[record.id]"
                                class="editable-cell-input-wrapper"
                            >
                                <a-input-number
                                    v-model:value="
                                        editableData[record.id].quantity
                                    "
                                    :max="record.product.quantity"
                                    :min="1"
                                    @pressEnter="editQuantity(record.id)"
                                />
                                <check-outlined
                                    class="editable-cell-icon-check"
                                    @click="editQuantity(record.id)"
                                />
                            </div>
                            <div v-else class="editable-cell-text-wrapper">
                                {{ text || " " }}
                                <edit-outlined
                                    class="editable-cell-icon"
                                    @click="edit(record.id)"
                                />
                            </div>
                        </div>
                    </template>
                    <template v-if="column.dataIndex === 'product'">
                        <span>
                            {{ record.product.name }}
                        </span>
                    </template>
                    <template v-if="column.dataIndex === 'actions'">
                        <span>
                            <a @click="removeProduct(record)">Remove</a>
                        </span>
                    </template>
                </template>
            </a-table>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import { Link } from "@inertiajs/inertia-vue3";
import { createVNode, computed, reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Button from "@/Jetstream/Button.vue";

import {
    CheckOutlined,
    CloseOutlined,
    EditOutlined,
    ExclamationCircleOutlined,
} from "@ant-design/icons-vue";
import { Modal } from "ant-design-vue";
import { cloneDeep } from "lodash";

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
        shoppingCart: Object,
    },
    components: {
        AppLayout,
        Link,
        Button,
        CheckOutlined,
        CloseOutlined,
        EditOutlined,
    },
    methods: {
        removeProduct(cartItem) {
            Modal.confirm({
                title: "Do you Want to Remote this these Item?",
                icon: createVNode(ExclamationCircleOutlined),
                onOk() {
                    Inertia.delete(
                        route("shoppingCartItems.destroy", cartItem.id)
                    );
                },
            });
        },
        handleTableChange(pag) {
            Inertia.get(route("products.index", { page: pag.current }));
        },
        calculateItems() {
            let items = 0;
            this.shoppingCart.forEach((item) => {
                items += item.quantity;
            });
            return items;
        },
        calculateCost() {
            let cost = 0;
            this.shoppingCart.forEach((item) => {
                cost += item.total;
            });
            return cost;
        },
        goToCreateOrder() {
            Inertia.get(route("orders.create"));
        },
        edit(id) {
            this.editableData[id] = cloneDeep(
                this.shoppingCart.filter((item) => id === item.id)[0]
            );
        },
        editQuantity(id) {
            Inertia.put(
                route("shoppingCartItems.update", id),
                this.editableData[id]
            );
            delete this.editableData[id];
        },
    },
    setup() {
        const editableData = reactive({});
        return {
            columns,
            editableData,
        };
    },
};
</script>

<style>
.editable-cell {
    position: relative;
}
.editable-cell:hover .editable-cell-icon {
    display: inline-block;
}
</style>
