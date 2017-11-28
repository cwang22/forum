<template>
    <div>
        <div v-if="signedIn">
            <h4>New Reply</h4>
            <div class="form-group">
                <textarea id="body" name="body" class="form-control" cols="30" rows="10"
                          placeholder="do you have something to say?" v-model="body" required></textarea>
            </div>
            <div class="form-group">
                <input type="submit" value="post" class="btn pull-right" @click="addReply">
            </div>
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