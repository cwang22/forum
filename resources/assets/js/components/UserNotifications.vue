<template>
    <li class="nav-item dropdown" v-if="notifications.length">
        <a href="#" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true"
           aria-expanded="false"><i class="far fa-bell"></i></a>
        <div class="dropdown-menu py-0">
            <a v-for="notification in notifications" :href="notification.data.link" class="dropdown-item"
               v-text="notification.data.message" @click="markAsRead(notification)"></a>
        </div>
    </li>
</template>
<script>
    export default {
        data() {
            return {
                notifications: []
            }
        },
        created() {
            window.Echo.private(`App.User.${window.App.user.id}`)
                .notification(notification => {
                    flash(notification.data.message)
                    this.notifications.push(notification)
                })
            this.get()
        },
        methods: {
            get() {
                axios.get(`/profiles/${window.App.user.name}/notifications`)
                    .then(response => this.notifications = response.data)
            },
            markAsRead(notification) {
                axios.delete(`/profiles/${window.App.user.name}/notifications/${notification.id}`)
            }
        }
    }
</script>