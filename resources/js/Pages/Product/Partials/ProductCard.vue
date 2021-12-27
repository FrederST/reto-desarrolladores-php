<template>
    <a-card hoverable class="list-none p-6 bg-gray-100 text-gray-800 text-center rounded-md shadow-sm hover:shadow-md flex flex-col items-center" style="width: 300px">
        <template #cover>
            <a-carousel >
                <div v-for="image in product.images" :key="image.id">
                    <img
                        alt="example"
                        :src="image.path"
                    />
                </div>
            </a-carousel>
        </template>
        <template #actions>
            <setting-outlined key="setting" />
            <edit-outlined key="edit" />
            <ellipsis-outlined key="ellipsis" />
        </template>
        <a-card-meta :title="product.name">
            <template #description>
                <strong>Price: </strong> {{product.sale_price}}
                <a-divider/>
                {{product.description}}
            </template>
        </a-card-meta>
    </a-card>
</template>

<script>
import { defineComponent } from "vue";
import {
    SettingOutlined,
    EditOutlined,
    EllipsisOutlined,
} from "@ant-design/icons-vue";
import { Inertia } from "@inertiajs/inertia";

export default defineComponent({
    props: {
        product: Object,
    },
    components: {
        SettingOutlined,
        EditOutlined,
        EllipsisOutlined,
    },
});
</script>
