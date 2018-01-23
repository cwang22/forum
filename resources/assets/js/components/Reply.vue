<template>
    <div :id="'reply-'+id" class="panel" :class="isBest ? 'panel-success' : 'panel-default'">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/' + reply.owner.name">
                        {{ reply.owner.name }} ( {{ reply.owner.reputation}} XP )
                    </a>
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
                        <wysiwyg v-model="body"></wysiwyg>
                    </div>
                    <button class="btn btn-xs btn-primary">Update</button>
                    <button class="btn btn-xs btn-default" type="button" @click="cancel">Cancel</button>
                </form>
            </div>
            <highlight v-else :content="body"></highlight>
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
    import Highlight from './Highlight.vue'
    export default {
        props: ['reply'],
        components: {Favorite, Highlight},
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