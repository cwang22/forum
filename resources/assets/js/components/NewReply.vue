<template>
    <div v-if="signedIn" class="mt-4">
        <h4>New Reply</h4>
        <div class="form-group">
            <wysiwyg name="body" v-model="body" placeholder="Have something to say?"></wysiwyg>
        </div>
        <div class="form-group d-flex">
            <input type="submit" value="post" class="btn ml-auto" @click="addReply">
        </div>
    </div>
</template>
<script>
    import 'jquery.caret'
    import 'at.js'

    export default {
        data() {
            return {
                body: ''
            }
        },
        mounted() {
            $('#body').atwho({
                at: "@",
                delay: 750,
                callbacks: {
                    remoteFilter: function (query, callback) {
                        $.getJSON("/api/users", {name: query}, function (usernames) {
                            callback(usernames)
                        })
                    }
                }
            })
        },
        methods: {
            addReply() {
                axios.post(location.pathname + '/replies', {body: this.body})
                    .then(({data}) => {
                        this.body = ''
                        flash('Your reply has been posted.')
                        this.$emit('created', data)
                    }).catch(error => {
                    flash(error.response.data, 'danger')
                })
            }
        }
    }
</script>