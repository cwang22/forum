<script>
    export default {
        props: ['attributes'],
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

            cancel() {
                this.body = this.previousBody;
                this.editing = false;
            }
        }
    }
</script>