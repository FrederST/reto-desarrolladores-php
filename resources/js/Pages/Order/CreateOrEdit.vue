<template>
    <app-layout>
        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-row :gutter="20">
                    <a-col :span="16">
                        <jet-validation-errors class="mb-4" />
                        <a-form>
                            <a-form-item label="Fist Name">
                                <a-input v-model:value="form.first_name" />
                            </a-form-item>
                            <a-form-item label="Last Name">
                                <a-input v-model:value="form.last_name" />
                            </a-form-item>
                            <a-form-item label="Address">
                                <a-input v-model:value="form.address" />
                            </a-form-item>
                            <a-form-item label="Country">
                                <a-select
                                    placeholder="Country"
                                    v-model:value="form.country_id"
                                    @change="searchCities"
                                >
                                    <a-select-option
                                        v-for="country in countries"
                                        :key="country.id"
                                        :value="country.id"
                                        >{{ country.name }}</a-select-option
                                    >
                                </a-select>
                            </a-form-item>
                            <a-form-item label="City">
                                <a-select
                                    placeholder="City"
                                    v-model:value="form.city_id"
                                    :loading="loadingCities"
                                >
                                    <a-select-option
                                        v-for="city in cities"
                                        :key="city.id"
                                        :value="city.id"
                                        >{{ city.name }}</a-select-option
                                    >
                                </a-select>
                            </a-form-item>
                            <a-form-item label="Post Code">
                                <a-input-number
                                    style="width: 100%"
                                    v-model:value="form.post_code"
                                />
                            </a-form-item>
                            <a-form-item label="Phone Number">
                                <a-input-number
                                    style="width: 100%"
                                    v-model:value="form.phone_number"
                                />
                            </a-form-item>
                            <a-form-item label="Notes">
                                <a-textarea
                                    v-model:value="form.notes"
                                    type="textarea"
                                />
                            </a-form-item>
                        </a-form>
                    </a-col>
                    <a-col :span="8">
                        <a-typography>
                            <a-typography-title :level="2"
                                >Total Cost
                                <a-statistic
                                    prefix="$"
                                    :value="calculateCost()"
                                />
                            </a-typography-title>
                            <a-typography-paragraph>
                                <a-button
                                    block
                                    type="primary"
                                    @click="createOrder()"
                                    >Pay</a-button
                                >
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
import { Link, useForm } from "@inertiajs/inertia-vue3";
import axios from "axios";
import { message } from "ant-design-vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

export default {
    props: {
        order: Object,
        countries: Object,
        shoppingCart: Object,
    },
    components: {
        AppLayout,
        Link,
        JetValidationErrors
    },
    data() {
        return {
            cities: [],
            loadingCities: false,
            form: useForm({
                first_name: this.order
                    ? this.order.first_name
                    : this.$page.props.user.name,
                last_name: this.order?.last_name,
                address: this.order?.address,
                country_id: this.order
                    ? this.order.country
                    : this.setDefaultCountry(),
                city_id: this.order?.city,
                post_code: this.order?.post_code,
                phone_number: this.order?.phone_number,
                notes: this.order?.notes,
            }),
        };
    },
    methods: {
        searchCities(value) {
            this.loadingCities = true;
            axios
                .get(route("api.cities"), { params: { country_id: value } })
                .then((res) => (this.cities = res.data))
                .catch((error) => message.error(error))
                .finally(() => (this.loadingCities = false));
        },
        setDefaultCountry() {
            this.searchCities();
            return 187;
        },
        createOrder() {
            this.form.post(route("orders.store"), {
                preserveScroll: true,
                onError: (errors) => message.error(errors),
                onSuccess: () => message.success("Order Created"),
            });
        },
        calculateCost() {
            let cost = 0;
            this.shoppingCart?.data.forEach((item) => {
                cost += item.total;
            });
            return cost;
        },
        getCurrentUserName() {
            return this.$page.props.user.name;
        },
    },
};
</script>
