<template>
    <app-layout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <br />
                <a-row :gutter="20">
                    <a-col :span="16">
                        <a-typography>
                            <a-typography-title :level="2">
                                {{ report.type }}
                            </a-typography-title>
                            <a-typography-title :level="3">
                                Info
                            </a-typography-title>
                            <a-typography-paragraph>
                                {{ report.info }}
                            </a-typography-paragraph>
                        </a-typography>
                    </a-col>
                    <a-col :span="8">
                        <a-typography-title :level="2"
                            >Status: {{ report.status }}
                        </a-typography-title>
                        <a-typography-paragraph>
                            <a-button
                                v-if="
                                    report.status == 'FINISHED' && report.path
                                "
                                block
                                type="primary"
                                ><a
                                    :href="route('reports.download', report.id)"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    >Download</a
                                ></a-button
                            >
                        </a-typography-paragraph>
                        <a-typography-title :level="2"
                            >Filters
                        </a-typography-title>
                        <a-typography-paragraph>
                            {{ report.filters }}
                        </a-typography-paragraph>
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
        report: Object,
    },
    components: {
        AppLayout,
        Link,
        StatusMessage,
    },
    methods: {},
};
</script>
