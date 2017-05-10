<script>
    import Favorite from './Favorite.vue';
    export default {
        props: ['attributes'],
        components: { Favorite },
        data() {
            return {
                editing: false,
                body: this.attributes.body,
                previousBody: this.attributes.body
            }
        },
        methods: {
            update() {
                axios.patch('/replies/' + this.attributes.id, {
                    body: this.body
                });

                this.editing = false;
                this.previousBody = this.body;

                flash('Updated!');
            },

            destroy() {
                axios.delete('/replies/' + this.attributes.id);
                $(this.$el).fadeOut(300, () => flash('Reply deleted.'));
            },

            cancel() {
                this.body = this.previousBody;
                this.editing = false;
            }
        }
    }
</script>