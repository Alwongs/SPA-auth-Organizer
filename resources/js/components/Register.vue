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
                        <input type="text" name="name" v-model="name" class="form-control" id="name" :class="{ 'is-invalid': $v.name.$error }">
                            <!-- Mistakes -->
                        <div class="invalid-feedback" v-if="!$v.name.required">
                            Обязательное поле.
                        </div>
                        <div class="invalid-feedback" v-if="!$v.name.minLength">
                            Минимальное количество символов: {{$v.name.$params.minLength.min}}
                        </div> 
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" v-model="email" class="form-control" id="email" aria-describedby="emailHelp" :class="{ 'is-invalid': $v.email.$error }">
                            <!-- Mistakes -->
                        <div class="invalid-feedback" v-if="!$v.email.required">
                            Обязательное поле.
                        </div>
                        <div class="invalid-feedback" v-if="!$v.email.email">
                            Некорректный email
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" v-model="password" class="form-control" id="password" :class="{ 'is-invalid': $v.password.$error }">
                            <!-- Mistakes -->
                        <div class="invalid-feedback" v-if="!$v.password.required">
                            Обязательное поле.
                        </div>
                        <div class="invalid-feedback" v-if="!$v.password.minLength">
                            Миниимальное количество символов: {{$v.password.$params.minLength.min}}
                        </div>
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
import { required, email, minLength, maxLength } from 'vuelidate/lib/validators'

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
            this.$v.name.$touch()
            this.$v.email.$touch()
            this.$v.password.$touch()
            if(this.$v.name.$anyError || this.$v.email.$anyError || this.$v.password.$anyError) {
                return;
            }
            let data = {
                name: this.name,
                email: this.email,
                password: this.password
            };
            axios.post('/api/register', data)
                .then(response => {
                    this.$v.$reset();
                    console.log(response);
                    this.$router.push('/login');
                })
                .catch(({response}) => {                    
                    alert(response.data.message);
                });
        },
    },
    validations: {
        name: {
            required,
            maxLength: maxLength(50),
            minLength: minLength(2),
        },
        email: {
            required,
            email
        },
        password: {
            required,
            minLength: minLength(8),
        }
    }

}
</script>