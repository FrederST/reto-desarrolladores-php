<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Customers
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-button @click="createCustomer()" type="primary" > New Customer </a-button>
                <a-table
                    :columns="columns"
                    :data-source="customers.data"
                    :pagination="pagination"
                    @change="handleTableChange"
                >
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex === 'active'">
                            <span>
                                <check-outlined v-if="!record.banned_at" />
                                <close-outlined v-else />
                            </span>
                        </template>
                        <template v-if="column.dataIndex === 'actions'">
                            <span>
                                <a @click="editCustomer(record)">Edit</a>
                                <a-divider type="vertical" />
                                <a @click="deleteCustomer(record)">Delete</a>
                                <a-divider type="vertical" />
                                <a @click="disableCustomer(record)">Disable</a>
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
            <CreateOrEditCustomerInformationForm
                @close="modalClose"
                :user="userForEdit"
                :edit="modalEdit"
            ></CreateOrEditCustomerInformationForm>
        </a-modal>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import { Link } from "@inertiajs/inertia-vue3";
import CreateOrEditCustomerInformationForm from "@/Pages/Customer/CreateOrEdit";

import { createVNode, computed } from "vue";
import { Modal } from "ant-design-vue";
import { Inertia } from "@inertiajs/inertia";
import Button from "@/Jetstream/Button.vue";

import {
    CheckOutlined,
    CloseOutlined,
    ExclamationCircleOutlined,
} from "@ant-design/icons-vue";

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
        customers: Object,
    },
    data() {
        return {
            userForEdit: Object,
            visible: false,
            modalEdit: true,
        };
    },
    components: {
        AppLayout,
        Link,
        Button,
        CreateOrEditCustomerInformationForm,
        CheckOutlined,
        CloseOutlined,
    },
    methods: {
        deleteCustomer(customer) {
            Modal.confirm({
                title: "Do you Want to deactivate these Customer?",
                icon: createVNode(ExclamationCircleOutlined),
                content: createVNode(
                    "div",
                    { style: "color:red;" },
                    "Some descriptions"
                ),
                onOk() {
                    Inertia.delete(route("customers.destroy", customer.id));
                },
            });
        },
        handleTableChange(pag) {
            Inertia.get(route("customers.index", { page: pag.current }));
        },
        editCustomer(customer) {
            this.userForEdit = customer;
            this.modalEdit = true;
            this.visible = true;
        },
        modalClose() {
            this.userForEdit = new Object();
            this.modalEdit = true;
            this.visible = false;
        },
        createCustomer() {
            this.userForEdit = new Object();
            this.modalEdit = false;
            this.visible = true;
        },
        modalTitle() {
            return this.modalEdit ? "Edit" : "Create";
        },
        disableCustomer() {
            Inertia.put(route("customers.disable", customer.id));
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
