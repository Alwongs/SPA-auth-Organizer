<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
            <div class="container">
                <div class="navbar-nav">
                    <router-link class="nav-item nav-link" to="/">Home</router-link>
                    <router-link class="nav-item nav-link" to="/events">Events</router-link>
                    <router-link class="nav-item nav-link" to="/tasks">Todo-list</router-link>
                    <router-link class="nav-item nav-link" to="/users">Users</router-link>
                    <router-link class="nav-item nav-link" to="/about">About</router-link>
                </div>
                <div class="navbar-nav">
                    <div v-if="authenticated && user">
                        <span><a class="nav-item nav-link" href="#" @click.prevent="logout()"><span class="text-success"><b>{{ user.name }}</b></span>&emsp;Logout</a></span>                                 
                    </div>
                    <div class="navbar-nav" v-else>
                        <router-link class="nav-item nav-link" to="/login">Login</router-link>
                        <router-link class="nav-item nav-link" to="/register">Register</router-link>
                    </div>

                </div>
            </div>
        </nav>


        <div style="margin-top: 2rem">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            authenticated: auth.check(),
            user: auth.user
        };
    },
    methods: {
        logout() {
            auth.logout();
            axios.post('/api/logout')
                .then(response => {
                    this.user = null;
                    this.$router.push('/about');
                })
                .catch(error => {
                     console.log(error);
                });
        }
    },
    mounted() {
        Event.$on('userLoggedIn', () => {
            this.authenticated = true;
            this.user = auth.user;
        });
    },
}
</script>

<style>
.bg-grey {
    background-color: rgb(236, 241, 241);
}

</style>