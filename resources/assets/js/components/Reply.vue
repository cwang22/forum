<template>
    <div :id="'reply-'+id" class="card mt-4" :class="isBest ? 'border-info' : ''">
        <div class="card-header d-flex" :class="isBest ? 'text-info' : ''">
                <a :href="'/profiles/' + reply.owner.name">
                    {{ reply.owner.name }} ( {{ reply.owner.reputation}} XP )
                </a>
                said
            <favorite :reply="reply" v-if="signedIn"></favorite>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <wysiwyg v-model="body"></wysiwyg>
                    </div>
                    <button class="btn btn-sm btn-primary mr-2">Update</button>
                    <button class="btn btn-sm btn-default" type="button" @click="cancel">Cancel</button>
                </form>
            </div>
            <highlight v-else :content="body"></highlight>
        </div>
        <div class="card-footer d-flex" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
            <div v-if="authorize('owns', reply)">
                <button class="btn btn-sm mr-2" @click="editing = true">Edit</button>
                <button class="btn btn-sm btn-danger" @click="destroy">Delete</button>
            </div>

            <button class="btn btn-sm btn-default ml-auto" @click="markAsBest" v-if="authorize('owns', reply)">
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