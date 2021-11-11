<template>
    <div class="container">

        <!-- Card -->                                              
        <div class="card mt-4 bg-secondary">
            <div class="card-body p-1">
                <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
                    <div class="container-fluid">
                        <div class="navbar-nav">
                            <h3 class="text-center text-light navbar-nav">Companies</h3>
                        </div>
                        <div class="navbar-nav">
                                <!-- Button trigger modal -->
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#createModal">
                                New company
                            </button>
                        </div>
                    </div>
                </nav>

                    <!-- Modal Create -->
                <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary">
                                <h5 class="modal-title text-light" id="createModalLabel">Add company</h5>
                                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form @submit.prevent="addCompany">
                                    <div class="form-group">
                                        <div class="alert alert-danger" role="alert" v-if="errored">
                                            Ошибка загрузки данных! <br>
                                            {{errors[0]}}
                                        </div>
                                        <label for="createTitleModal">title</label>
                                        <input v-model="new_title" type="text" class="form-control" id="createTitleModal" :class="{ 'is-invalid': $v.new_title.$error }">
                                        <!-- Mistakes -->
                                        <div class="invalid-feedback" v-if="!$v.new_title.required">
                                            Обязательное поле.
                                        </div>
                                        <div class="invalid-feedback" v-if="!$v.new_title.maxLength">
                                            Максимальное количество символов: {{$v.new_title.$params.maxLength.max}}
                                        </div>                                         
                                    </div>
                                    <div class="form-group">
                                        <label for="createDescriptionModal">Description</label>
                                        <textarea v-model="new_description" class="form-control" id="createDescriptionModal" rows="10"></textarea>
                                    </div>                                  
                                    <div class="modal-footer p-0">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table table-hover bg-light">
                    <thead class="text-secondary small">
                        <tr>
                            <th class="col-sm-1">id</th>
                            <th class="col-sm-8">title</th>
                            <th class="col-sm-2">description</th>
                            <th class="col-sm-1"></th>
                            <th class="col-sm-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="loading">
                            <td colspan="5" class="text-center">
                                <div class="spinner-grow text-center text-secondary m-2" role="status">
                                    <span class="sr-only"></span>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="companies.length == 0">
                            <td class="text-center text-secondary" colspan="6">You have no companies in the list yet. Add first one..</td>
                        </tr>
                        <tr v-for="company in companies" :key="company.id">
                            <td @click="$router.push({ name: 'company', params: {id: company.id}})" style="cursor:pointer;">{{company.id}}</td>
                            <td @click="$router.push({ name: 'company', params: {id: company.id}})" style="cursor:pointer;">{{company.title | capitalize }}</td>
                            <td @click="$router.push({ name: 'company', params: {id: company.id}})" style="cursor:pointer;">Подробнее</td>
                            <td>
                                <button @click.prevent="current_company = company" type="button" class="btn btn-primary m-0" data-toggle="modal" data-target="#editModal">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </button>
                            </td>
                            <td>
                                <button @click="deleteCompany(company.id)" class="btn btn-danger m-0">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-secondary">
                                            <h5 class="modal-title text-light" id="editModalLabel">Edit event</h5>
                                            <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form @submit.prevent="updateCompany(current_company)">
                                                <div class="form-group">
                                                    <label for="editTitleModal">title</label>
                                                    <input v-model="current_company.title" type="text" class="form-control" id="editTitleModal" required>
 
                                                </div>
                                                <div class="form-group">
                                                    <label for="editDescriptionModal">Description</label>
                                                    <textarea v-model="current_company.description" class="form-control" id="editDescriptionModal" rows="10"></textarea>
                                                </div>
                                                <div class="modal-footer p-0">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tr>
                    </tbody>
                </table>        
            </div>
        </div>
    </div>
</template>

<script>
import { required, maxLength } from 'vuelidate/lib/validators'

export default {
    data(){
        return {
            errored: false,
            errors: [],
            loading: true,
            companies: [],
            new_title: '',
            new_description: '',
            current_company: {},
            authenticated: auth.check(),
            user: auth.user
        }
    },
    filters: {
		// Регистрируем фильтр capitalize:
		capitalize: function(str) {
			return str[0].toUpperCase() + str.slice(1);
		}
	},
    methods: {
        addCompany(){
            this.$v.new_title.$touch()
            if(this.$v.new_title.$anyError) {
                return;
            }
            axios.post('/api/companies', {
                title: this.new_title,
                description: this.new_description,
                user_id: this.user.id
            })
            .then(response => {
                this.$v.$reset();
                this.new_title = '';
                this.new_description = '';
                $('#createModal').modal('hide');
                this.getAllCompanies();
                this.errors = [];
                this.errored = false;
            })
            .catch(error => {
                if(error.response.data.errors.title){
                    this.errors = []
                    this.errors.push(error.response.data.errors.title[0])
                }
                this.errored = true
            })
            .finally(() => {
                this.loading = false
            })
        },
        updateCompany(current_company){
            axios.post('/api/companies/' + current_company.id, {
                _method: 'PATCH',
                title: current_company.title,
                description: current_company.description
            })
            .then(response => {
                $('#editModal').modal('hide')
                this.getAllCompanies()
                
            })
            .catch(error => {
                console.log(error)
                this.errored = true
            })
            .finally(() => {
                this.loading = false
            })
        },
        deleteCompany(id){
            if(confirm('Are your shure you want to DELETE this?')){
                axios.post('/api/companies/' + id, {
                    _method: 'DELETE'
                })
                    .then(response => {
                        this.getAllCompanies()
                    })
                    .catch(error => {
                        console.log(error)
                        this.errored = true
                    })
                    .finally(() => {
                        this.loading = false
                    })
            }

        },
        getAllCompanies() {
            axios.get('/api/companies')
            .then(response => {
                console.log(response.data)
                this.companies = response.data.data
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
        this.getAllCompanies();
        Event.$on('userLoggedIn', () => {
            this.authenticated = true;
            this.user = auth.user;
        });
    },
    validations: {
        new_title: {
            required,
            maxLength: maxLength(50)
        },
        current_company: {
            title: {
                required,
                maxLength: maxLength(50)
            }
        }
    }
}
</script>
