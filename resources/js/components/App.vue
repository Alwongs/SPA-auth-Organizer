<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
            <div class="container">
                <div @click="clickHandler()" class="toggle text-light">{{ option }}</div>
                <div v-if="show == true" class="navbar-nav">
                    <template>
                    <router-link class="nav-item nav-link" to="/">Home</router-link>
                    <router-link class="nav-item nav-link" to="/events">Events</router-link>
                    <router-link class="nav-item nav-link" to="/tasks">Todo-list</router-link>
                    <router-link class="nav-item nav-link" to="/companies">Companies</router-link>
                    <router-link class="nav-item nav-link" to="/logbook">Logbook</router-link>
                    <router-link class="nav-item nav-link" to="/users">Users</router-link>
                    <router-link class="nav-item nav-link" to="/about">About</router-link>
                    </template>
                </div>

                <button v-if="this.$route.path == '/' || this.$route.path == '/events'" @click="openAddModal()" type="button" class="btn btn-info">
                    New event
                </button>
                <add-event></add-event>

                <div class="navbar-nav">
                    <div v-if="authenticated && user">
                        <span><a class="nav-item nav-link" href="#" @click.prevent="logout()"><span class="text-success"><b>{{ user.name }}</b></span>&emsp;Logout</a></span>                                 
                    </div>
                    <div class="navbar-nav" v-else>
                        <router-link v-if="this.$route.path == '/register'" class="nav-item nav-link text-danger" to="/login"><span class="bg-warning p-1 rounded">Login</span></router-link>
                        <router-link v-if="this.$route.path == '/login'" class="nav-item nav-link text-danger" to="/register"><span class="bg-warning p-1 rounded">Register</span></router-link>
                    </div>

                </div>
            </div>
        </nav>
        
        

        <div>
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import { bus } from "../app"
import AddEvent from "./Reminder/AddEvent.vue"



export default {
    components: { AddEvent },
    data() {
        return {
            authenticated: auth.check(),
            user: auth.user,
            show: false,
            isAddModalOpen: false,
            option: 'Aplications'
        };
    },
    methods: {
        clickHandler() {
            console.log('works')
            this.show = !this.show
            if(this.show) {
                this.option = 'Hide'
            } else {
                this.option = 'Aplications'
            }
        },
        logout() {
            if(confirm('Do you really want to logout?')) {
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
        openAddModal() {
            bus.$emit('openAddModal', {
                isAddModalOpen: true
            }) 
        },
    },
    mounted() {
        Event.$on('userLoggedIn', () => {
            this.authenticated = true;
            this.user = auth.user;
        });
        console.log(this.$route.path)
    },
}
</script>

<style>
.bg-grey {
    background-color: rgb(236, 241, 241);
}

.toggle {
    cursor: pointer;
}

</style>