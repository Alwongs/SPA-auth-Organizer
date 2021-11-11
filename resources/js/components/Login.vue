<template>
    <div class="container">
        <div class="row mb-3">
            <div class="col-sm-12">
                <h2 class="text-center">Login</h2>
            </div>
        </div>
        <div class="row">
            <div class="offset-sm-4 col-sm-4">
                <form class="border rounded-lg p-4 bg-white">
                    <div class="form-group">
                        <label for="username">Email:</label>
                        <input type="email" name="username" v-model="username" class="form-control" id="username" aria-describedby="emailHelp">
                    </div>
                        <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" v-model="password" class="form-control" id="password">
                    </div>
                    <div @click="$router.push('/register')"><a href="#">Register</a></div>
                    <div class="text-right">
                        <button type="submit" @click.prevent="login" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            username: '',
            password: '',
        };
    },

    methods: {
        login() {
            let data = {
                username: this.username,
                password: this.password
            };
            axios.post('/api/login', data)
                .then(({data}) => {
                    auth.login(data.token, data.user);
                    this.$router.push('/');
                })
                .catch(({response}) => {                    
                    alert(response.data.message);
                });
        },
    },

}
</script>