<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Order Info
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <StatusMessage :order="order" />
                <br />
                <a-row :gutter="20">
                    <a-col :span="16">
                        <a-typography-title :level="2">
                            Products
                        </a-typography-title>
                        <a-collapse>
                            <a-collapse-panel
                                v-for="orderItem in order.order_items"
                                :key="orderItem.id"
                                :header="`${orderItem.product.name} - ${orderItem.quantity} - ${orderItem.price}`"
                            >
                                <p>{{ orderItem.product.description }}</p>
                            </a-collapse-panel>
                        </a-collapse>
                    </a-col>
                    <a-col :span="8">
                        <a-typography>
                            <a-typography-title :level="2"
                                >Total Cost
                                <a-statistic
                                    prefix="$"
                                    :value="order.grand_total"
                                />
                            </a-typography-title>
                            <a-typography-paragraph>
                                <a-button
                                    v-if="order.status != 'APPROVED'"
                                    block
                                    type="primary"
                                    ><a
                                        :href="getRetry()"
                                        rel="noopener noreferrer"
                                    >Pay</a
                                ></a-button>
                            </a-typography-paragraph>
                        </a-typography>
                    </a-col>
                </a-row>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import { Link } from "@inertiajs/inertia-vue3";
import StatusMessage from "@/Pages/Order/Partials/StatusMessage.vue";

export default {
    props: {
        order: Object,
    },
    components: {
        AppLayout,
        Link,
        StatusMessage,
    },
    methods: {
        getRetry() {
            return route("orders.retry", this.order.id);
        },
    },
};
</script>
