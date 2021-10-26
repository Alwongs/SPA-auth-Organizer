<template>
    <div class="container">
        <div class="row mb-3">
            <div class="col-sm-12">
                <h2 class="text-center">Register</h2>
            </div>
        </div>
        <div class="row">
            <div class="offset-sm-4 col-sm-4">
                <form class="border rounded-lg p-4  bg-white">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" v-model="name" class="form-control" id="name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" v-model="email" class="form-control" id="email" aria-describedby="emailHelp">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" v-model="password" class="form-control" id="password">
                    </div>
                    <div class="text-right">
                        <button type="submit" @click.prevent="register" class="btn btn-primary">Save</button>
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
            name: '',
            email: '',
            password: '',
        };
    },

    methods: {
        register() {
            let data = {
                name: this.name,
                email: this.email,
                password: this.password
            };
            axios.post('/api/register', data)
                .then(response => {
                    console.log(response);
                    this.$router.push('/login');
                })
                .catch(({response}) => {                    
                    alert(response.data.message);
                });
        },
    },

}
</script>