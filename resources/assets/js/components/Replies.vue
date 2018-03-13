<template>
    <div>
        <div v-for="(reply, index) in items">
            <reply :reply="reply" @deleted="remove(index)" :key="reply.id"/>
        </div>
        <paginator :dataSet="dataSet" @changed="fetch"/>

        <p v-if="$parent.locked">
            This thread has been locked. No more replies are allowed.
        </p>

        <new-reply @created="add" v-else/>
    </div>
</template>
<script>
    import axios from 'axios'
    import Reply from './Reply'
    import Paginator from './Paginator'
    import NewReply from './NewReply'
    import collection from '../mixins/collection'

    export default {
        props: ['data'],
        components: {Reply, Paginator, NewReply},
        mixins: [collection],
        data() {
            return {
                dataSet: false,
                endpoint: location.pathname + '/replies'
            }
        },
        created() {
            this.fetch()
        },
        methods: {
            fetch(page) {
                axios.get(this.url(page)).then(this.refresh)
            },
            url(page) {
                if (!page) {
                    let query = location.search.match(/page=(\d+)/)
                    page = query ? query[1] : 1
                }

                return `${location.pathname}/replies?page=${page}`
            },
            refresh({data}) {
                this.dataSet = data
                this.items = data.data
                window.scrollTo(0, 0)
            }
        }
    }
</script>