<template>
    <div>
        <div v-if="signedIn">
            <h4>New Reply</h4>
            <div class="form-group">
                <textarea name="body" class="form-control" cols="30" rows="10"
                          placeholder="do you have something to say?" v-model="body" required></textarea>
            </div>
            <div class="form-group">
                <input type="submit" value="post" class="btn pull-right" @click="addReply">
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['endpoint'],
        data() {
            return {
                body: ''
            }
        },
        computed: {
            signedIn() {
                return window.App.signedIn;
            }
        },
        methods: {
            addReply() {
                axios.post(this.endpoint, { body: this.body})
                    .then(({data}) => {
                        this.body = '';
                        flash('Your reply has been posted.');
                        this.$emit('created', data);
                    });
            }
        }
    }
</script>