<template>
    <div class="container">

        <!-- Card -->                                              
        <div class="card mt-4 bg-secondary">
            <div class="card-body p-1">
                <h3 class="text-center text-light">Users</h3>

                <table class="table table-hover bg-light">
                    <thead class="text-secondary small">
                        <tr>
                            <th class="col-sm-4">name</th>
                            <th class="col-sm-6">email</th>
                            <th class="col-sm-2">created_at</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id">
                            <td>{{user.name}}</td>
                            <td v-if="user.email == 'Alwong@ya.ru'">just e-mail</td>
                            <td v-else>{{user.email}}</td>
                            <td>{{user.created_at}}</td>
                        </tr>
                    </tbody>
                </table>        
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data(){
        return {
            errored: false,
            errors: [],
            loading: true,
            users: []
        }
    },
    methods: {
        getAllUsers() {
            axios.get('/api/users')
            .then(response => {
                console.log(response)
                this.users = response.data.data
            })
            .catch(error => {
                console.log(error)
                this.errored = true
            })
            .finally(() => {
                this.loading = false
            })
        },
    },
    mounted(){
        this.getAllUsers()
    }
}
</script>
