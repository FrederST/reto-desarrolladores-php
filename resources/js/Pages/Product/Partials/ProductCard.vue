<template>
    <a-card
        hoverable
        class="
            list-none
            p-6
            bg-gray-100
            text-gray-800 text-center
            rounded-md
            shadow-sm
            hover:shadow-md
            flex flex-col
            items-center
        "
        style="width: 300px"
    >
        <template #cover>
            <div v-if="product.images.length > 0">
                <img alt="example" :src="product.images[0].path" />
            </div>
            <div v-else>
                <img
                    alt="no image"
                    src="https://thumbs.dreamstime.com/b/no-image-available-icon-flat-vector-no-image-available-icon-flat-vector-illustration-132482953.jpg"
                />
            </div>
        </template>
        <a-card-meta :title="product.name">
            <template #description>
                <strong>Price: </strong> {{ product.sale_price }}
                <a-divider />
                {{ product.description }}
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
