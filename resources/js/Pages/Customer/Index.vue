<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Customers
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-table
                    :columns="columns"
                    :data-source="customers.data"
                    :pagination="pagination"
                    @change="handleTableChange"
                >
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex === 'actions'">
                            <span>
                                <a @click="editCustomer(record)">Edit</a>
                                <a-divider type="vertical" />
                                <a @click="deleteCustomer(record)">Delete</a>
                            </span>
                        </template>
                    </template>
                </a-table>
            </div>
        </div>

        <a-modal
            title="Edit"
            v-model:visible="visible"
            :destroyOnClose="true"
            :footer="null"
        >
            <CreateOrEditCustomerInformationForm
                @close="modalClose"
                :user="userForEdit"
            ></CreateOrEditCustomerInformationForm>
        </a-modal>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import { Link } from "@inertiajs/inertia-vue3";
import CreateOrEditCustomerInformationForm from "@/Pages/Customer/CreateOrEdit";

import { ExclamationCircleOutlined } from "@ant-design/icons-vue";
import { createVNode, computed, ref } from "vue";
import { Modal } from "ant-design-vue";
import { Inertia } from "@inertiajs/inertia";
import Button from "@/Jetstream/Button.vue";

const columns = [
    {
        title: "Name",
        dataIndex: "name",
    },
    {
        title: "Email",
        dataIndex: "email",
    },
    {
        title: "Actions",
        dataIndex: "actions",
    },
];

export default {
    props: {
        customers: Object,
    },
    data() {
        return {
            userForEdit: Object,
            visible: false,
        };
    },
    components: {
        AppLayout,
        Link,
        Button,
        CreateOrEditCustomerInformationForm,
    },
    methods: {
        deleteCustomer(customer) {
            Modal.confirm({
                title: "Do you Want to delete these Customer?",
                icon: createVNode(ExclamationCircleOutlined),
                content: createVNode(
                    "div",
                    { style: "color:red;" },
                    "Some descriptions"
                ),
                onOk() {
                    Inertia.delete(route("customer.destroy", customer.id));
                },
            });
        },
        handleTableChange(pag) {
            Inertia.get(route("customer.index", { page: pag.current }));
        },
        editCustomer(customer) {
            this.userForEdit = customer;
            this.visible = true;
        },
        modalClose() {
            this.userForEdit = new Object();
            this.visible = false;
        },
    },
    setup(props) {
        const pagination = computed(() => ({
            total: props.customers.total,
            current: props.customers.current_page,
            pageSize: props.customers.per_page,
        }));

        return {
            columns,
            pagination,
        };
    },
};
</script>
