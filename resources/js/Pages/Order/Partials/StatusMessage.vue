<template>
    <a-alert :message="order.status" :type="getType()" show-icon />
</template>

<script>

export default {
    props: {
        order: Object,
    },
    methods: {
        getType(){
            const status = this.order.status;
            if (status == 'PENDING') {
                return 'info'
            } else if (status == 'APPROVED') {
                return 'success'
            } else if (status == 'REJECTED' || status == 'FAILED') {
                return 'error'
            }
            return 'warning'
        }
    }
};
</script>
