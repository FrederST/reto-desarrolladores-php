<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Customers
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <table
                        class="
                            rounded-t-lg
                            mt-5
                            min-w-full
                            mx-auto
                            bg-gray-800
                            text-gray-100
                        "
                    >
                        <caption>
                            Customers In DB
                        </caption>
                        <thead>
                            <tr class="text-left border-b border-gray-300">
                                <th
                                    scope="col"
                                    class="
                                        px-6
                                        py-3
                                        text-left text-xs
                                        font-medium
                                        text-gray-100
                                        uppercase
                                        tracking-wider
                                    "
                                >
                                    Id
                                </th>
                                <th
                                    scope="col"
                                    class="
                                        px-6
                                        py-3
                                        text-left text-xs
                                        font-medium
                                        text-gray-100
                                        uppercase
                                        tracking-wider
                                    "
                                >
                                    Name
                                </th>
                                <th
                                    scope="col"
                                    class="
                                        px-6
                                        py-3
                                        text-center text-xs
                                        font-medium
                                        text-gray-100
                                        uppercase
                                        tracking-wider
                                    "
                                >
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="
                                bg-gray-700
                                border-b border-gray-600
                                text-gray-200
                            "
                        >
                            <tr
                                v-for="customer in customers"
                                :key="customer.id"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium">
                                                {{ customer.id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm">
                                        {{ customer.name }}
                                    </div>
                                </td>
                                <td class="flex m-2">
                                    <Link
                                        type="button"
                                        as="button"
                                        :href="
                                            route('customer.edit', customer.id)
                                        "
                                    >
                                        Edit
                                    </Link>
                                    <Link
                                        type="button"
                                        as="button"
                                        method="delete"
                                        :href="
                                            route(
                                                'customer.destroy',
                                                customer.id
                                            )
                                        "
                                    >
                                        Delete
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import { Link } from "@inertiajs/inertia-vue3";
export default {
    props: {
        customers: Array,
    },
    components: {
        AppLayout,
        Link,
    },
};
</script>
