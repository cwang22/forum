<template>
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination" v-if="shouldPaginate">
            <li class="page-item" v-show="prev">
                <a href="#" aria-label="Previous" class="page-link" @click.prevent="page--">
                    <span aria-hidden="true">&laquo; Previous</span>
                </a>
            </li>

            <li class="page-item" v-show="next">
                <a href="#" aria-label="Next" class="page-link" @click.prevent="page++">
                    <span aria-hidden="true">Next &raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</template>
<script>
    export default {
        props: ['dataSet'],
        data() {
            return {
                page: 1,
                prev: false,
                next: false
            }
        },
        computed: {
            shouldPaginate() {
                return this.prev || this.next
            }
        },
        watch: {
            dataSet() {
                this.page = this.dataSet.current_page
                this.prev = this.dataSet.prev_page_url
                this.next = this.dataSet.next_page_url
            },
            page() {
                this.broadcast()
            }
        },
        methods: {
            broadcast() {
                return this.$emit('changed', this.page).changeURL()
            },
            changeURL() {
                history.pushState(null, null, '?page=' + this.page)
            }
        }
    }
</script>