<script>
    import Highlight from '../components/Highlight.vue'
    import Replies from '../components/Replies.vue'
    import SubscribeButton from '../components/SubscribeButton.vue'


    export default {
        props: ['thread'],
        components: {Highlight, Replies, SubscribeButton},
        data() {
            return {
                title: this.thread.title,
                body: this.thread.body,
                repliesCount: this.thread.replies_count,
                editing: false,
                locked: this.thread.locked,
                form: {
                    title: this.thread.title,
                    body: this.thread.body
                }
            }
        },
        methods: {
            toggleLock() {
                const uri = `/locked-threads/${this.thread.slug}`
                axios[this.locked ? 'delete' : 'post'](uri)
                this.locked = !this.locked
            },

            update() {
                const uri = `/threads/${this.thread.channel.slug}/${this.thread.slug}`
                axios.patch(uri, this.form)
                    .then(() => {
                        this.editing = false
                        this.title = this.form.title
                        this.body = this.form.body
                        flash('You thread has been updated.')
                    })
            },

            cancel() {
                this.editing = false
                this.form.title = this.title
                this.form.body = this.body
            }

        }
    }
</script>