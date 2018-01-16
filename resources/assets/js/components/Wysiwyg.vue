<template>
    <div>
        <input type="hidden" id="trix" :name="name" :value="value">
        <trix-editor input="trix" ref="trix" @trix-change="change" :placehoder="placeholder"></trix-editor>
    </div>
</template>

<style lang="scss">
    @import '~trix/dist/trix.css';
</style>

<script>
    import Trix from 'trix'

    export default {
        props: ['name', 'value', 'placeholder'],
        methods: {
            change({target}) {
                this.$emit('input', target.value)
            }
        },
        watch: {
            value(val) {
                if (val === '') {
                    this.$refs.trix.value = ''
                }
            }
        }
    }
</script>