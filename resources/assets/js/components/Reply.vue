<template>
    <div :id="'reply-'+id" class="panel" :class="isBest ? 'panel-success' : 'panel-default'">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/' + reply.owner.name" v-text="reply.owner.name"></a>
                    said
                </h5>
                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>
                    <button class="btn btn-xs btn-primary">Update</button>
                    <button class="btn btn-xs btn-default" type="button" @click="cancel">Cancel</button>
                </form>
            </div>
            <div v-else="" v-html="body"></div>
        </div>
        <div class="panel-footer level" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
            <div v-if="authorize('owns', reply)">
                <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
            </div>

            <button class="btn btn-xs btn-default ml-a" @click="markAsBest" v-if="authorize('owns', reply)">
                Best Reply?
            </button>
        </div>
    </div>
</template>
<script>
    import Favorite from './Favorite.vue'

    export default {
        props: ['reply'],
        components: {Favorite},
        data() {
            return {
                editing: false,
                id: this.reply.id,
                body: this.reply.body,
                previousBody: this.reply.body,
                isBest: this.reply.isBest
            }
        },
        created() {
            window.events.$on('best-replies-selected', id => {
                this.isBest = (id === this.id)
            })
        },
        methods: {
            update() {
                axios.patch('/replies/' + this.reply.id, {
                    body: this.body
                })

                this.editing = false
                this.previousBody = this.body

                flash('Updated!')
            },

            destroy() {
                axios.delete('/replies/' + this.reply.id)

                this.$emit('deleted', this.reply.id)

            },

            cancel() {
                this.body = this.previousBody
                this.editing = false
            },

            markAsBest() {
                axios.post('/replies/' + this.reply.id + '/best')
                window.events.$emit('best-replies-selected', this.reply.id)
            }
        }
    }
</script>