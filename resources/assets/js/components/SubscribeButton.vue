<template>
    <button :class="classes" @click="toggle">Subscribe</button>
</template>

<script>
    export default {
        props: ['subscribed'],
        data() {
            return {
                active: false
            }
        },
        created() {
            this.active = this.subscribed
        },
        computed: {
            classes() {
                return ['btn', this.active ? 'btn-primary' : 'btn-default']
            }
        },
        methods: {
            toggle() {
                axios[(this.active ? 'delete' : 'post')](location.pathname + '/subscriptions')
                this.active =  !this.active
            }
        }
    }
</script>