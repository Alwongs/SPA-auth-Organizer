<template>
    <div class="container">
        <!-- Card -->                                              
        <div class="card mt-4 bg-secondary">
            <div class="card-body p-1">
                <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
                    <div class="container-fluid">
                        <div class="navbar-nav">
                            <h3 class="text-center text-light navbar-nav">Company</h3>
                        </div>
                        <div class="navbar-nav">
                            <button @click.prevent="current_company = company" type="button" class="btn btn-primary m-1" data-toggle="modal" data-target="#editCompanyModal">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </button>
                            <button @click="deleteCompany(company.id)" class="btn btn-danger m-1">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </nav>

                <div class="content p-4 bg-light">
                    <div v-if="loading" class="text-center">
                        <div class="spinner-grow text-center text-secondary m-2" role="status">
                            <span class="sr-only"></span>
                        </div>
                    </div>

                    <h3>{{ company.title | capitalize }}</h3>
                    <b>Description:</b> 
                    <p>{{company.description | capitalize }}</p>

                     <b>Tests in the company:</b> 

                    <div class="table p-4">
                        <table class="table table-sm table-hover">
                            <thead class="text-secondary small">
                                <th class="col-sm-2">date</th>
                                <th class="col-sm-6">title</th>
                                <th class="col-sm-2">result</th>
                                <th class="col-sm-1"></th>
                                <th class="col-sm-1"></th>
                            </thead>
                            <tbody>
                                <tr v-if="loading">
                                    <td colspan="5" class="text-center">
                                        <div class="spinner-grow text-center text-secondary m-2" role="status">
                                            <span class="sr-only"></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="company.tests.length == 0">
                                    <td class="text-center text-secondary" colspan="6">You have no tests in the list yet. Add first one..</td>
                                </tr>
                                <tr v-for="test in company.tests" :key="test.id">
                                    <td @click.prevent="current_test = test" data-toggle="modal" data-target="#showModal">{{ test.date }}</td>
                                    <td @click.prevent="current_test = test" data-toggle="modal" data-target="#showModal">{{ test.title | capitalize }}</td>
                                    <td @click.prevent="current_test = test" data-toggle="modal" data-target="#showModal">{{ test.result }}</td>
                                    <td>
                                        <button @click.prevent="current_test = test" type="button" class="btn btn-primary m-0" data-toggle="modal" data-target="#editTestModal">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                    <td>


                                        <button @click="deleteTest(test.id)" class="btn btn-danger m-0">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-right">
                        <button type="submit" @click.prevent="$router.go(-1)" class="btn btn-primary">Back</button>
                            <!-- Button trigger modal -->
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#createModal">
                            New test
                        </button>
                    </div>
                </div>

                    <!-- Modal Show Test -->
                <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="showModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary">
                                <h4 class="modal-title text-light" id="showModalLabel">Test</h4>
                                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h3>{{ current_test.title }}</h3>
                                <p><b>Date:</b> {{ current_test.date }}</p>
                                <p><b>Description:</b> {{ current_test.description }}</p>
                                <p><b>Result:</b> {{ current_test.result }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                    <!-- Modal Create Test -->
                <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary">
                                <h5 class="modal-title text-light" id="createModalLabel">Add test</h5>
                                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form @submit.prevent="addTest">
                                    <div class="form-group">
                                        <label for="createDateModal">Date</label>
                                        <input v-model="new_date" type="text" class="form-control" id="createDateModal" :class="{ 'is-invalid': $v.new_date.$error }">
                                            <!-- Mistakes -->
                                        <div class="invalid-feedback" v-if="!$v.new_date.required">
                                            Обязательное поле.
                                        </div>                                         
                                    </div>
                                    <div class="form-group">
                                        <label for="createTitleModal">Title</label>
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
                                    <div class="form-group">
                                        <label for="createResultModal">Result</label>
                                        <input v-model="new_result" type="text" class="form-control" id="createResultModal" :class="{ 'is-invalid': $v.new_result.$error }">
                                            <!-- Mistakes -->
                                        <div class="invalid-feedback" v-if="!$v.new_result.required">
                                            Обязательное поле.
                                        </div>
                                        <div class="invalid-feedback" v-if="!$v.new_result.maxLength">
                                            Максимальное количество символов: {{$v.new_result.$params.maxLength.max}}
                                        </div>
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

                    <!-- Modal Edit Company -->
                <div class="modal fade" id="editCompanyModal" tabindex="-1" aria-labelledby="editCompanyModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary">
                                <h5 class="modal-title text-light" id="editCompanyModalLabel">Edit company</h5>
                                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form @submit.prevent="updateCompany(current_company)">

                                    <div class="form-group">
                                        <label for="editCompanyTitleModal">title</label>
                                        <input v-model="current_company.title" type="text" class="form-control" id="editCompanyTitleModal" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="editCompanyDescriptionModal">Description</label>
                                        <textarea v-model="current_company.description" class="form-control" id="editCompanyDescriptionModal" rows="10"></textarea>
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

                    <!-- Modal Edit Test -->
                <div class="modal fade" id="editTestModal" tabindex="-1" aria-labelledby="editTestModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary">
                                <h5 class="modal-title text-light" id="editTestModalLabel">Edit test</h5>
                                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form @submit.prevent="updateTest(current_test)">

                                    <div class="form-group">
                                        <label for="editTestDateModal">Date:</label>
                                        <input v-model="current_test.date" type="text" class="form-control" id="editTestDateModal" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="editTestTitleModal">Title:</label>
                                        <input v-model="current_test.title" type="text" class="form-control" id="editTestTitleModal" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="editTestDescriptionModal">Description</label>
                                        <textarea v-model="current_test.description" class="form-control" id="editTestDescriptionModal" rows="10"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="editTestResultModal">Result:</label>
                                        <input v-model="current_test.result" type="text" class="form-control" id="editTestResultModal" required>
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
            new_date: '',
            new_title: '',
            new_description: '',
            new_result: '',
            current_company: {},
            current_test: {},
            company: {},
            id: this.$route.params.id,
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
        addTest(){
            this.$v.new_date.$touch()
            this.$v.new_title.$touch()
            this.$v.new_result.$touch()
            if(this.$v.new_title.$anyError || this.$v.new_result.$anyError || this.$v.new_date.$anyError) {
                return;
            }
            axios.post('/api/tests', {
                date: this.new_date,
                title: this.new_title,
                description: this.new_description,
                result: this.new_result,
                company_id: this.company.id,
            })
            .then(response => {
                $('#createModal').modal('hide')
                this.$v.$reset()
                this.new_date = '',
                this.new_title = '',
                this.new_description = '',
                this.new_result = '',
                this.getCompany(this.id)
            })
            .catch(error => {
                console.log(error)
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
                description: current_company.description,
            })         
            .then(response => {
                $('#editCompanyModal').modal('hide')
                this.getCompany(this.id)
            })
            .catch(error => {
                console.log(error)
                this.errored = true
            })
            .finally(() => {
                this.loading = false
            })
        },
        updateTest(current_test){        
            axios.post('/api/tests/' + current_test.id, {
                _method: 'PATCH',                
                date: current_test.date,
                title: current_test.title,
                description: current_test.description,
                result: current_test.result,
            })         
            .then(response => {
                $('#editTestModal').modal('hide')
                this.getCompany(this.id)
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
            if(confirm('Are your shure you want to DELETE this?')) {
                axios.post('/api/companies/' + id, {
                    _method: 'DELETE'
                })
                    .then(response => {
                        this.getCompany(this.id)
                    })
                    .catch(error => {
                        console.log(error)
                        this.errored = true
                    })
                    .finally(() => {
                        this.loading = false
                    })
                this.$router.go(-1)
            }
        },
        deleteTest(id){
            if(confirm('Are your shure you want to DELETE this?')) {
                axios.post('/api/tests/' + id, {
                    _method: 'DELETE'
                })
                    .then(response => {
                        this.getCompany(this.id)
                    })
                    .catch(error => {
                        console.log(error)
                        this.errored = true
                    })
                    .finally(() => {
                        this.loading = false
                    })
                this.$router.go(-1)  
            }

        },
        getCompany(id) {
            axios.get('/api/companies/' + id)
            .then(response => {
                this.company = response.data.data
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
    mounted(){
        this.getCompany(this.id);
        Event.$on('userLoggedIn', () => {
            this.authenticated = true;
            this.user = auth.user;
        });
    },
    validations: {
        new_date: {
            required
        },
        new_title: {
            required,
            maxLength: maxLength(60)
        },
        new_result: {
            required,
            maxLength: maxLength(20)
        },
        current_company: {
            title: {
                required,
                maxLength: maxLength(60)
            }
        },
        current_test: {
            title: {
                required,
                maxLength: maxLength(60)
            },
            result: {
                required,
                maxLength: maxLength(20)
            }
        }
    }
}
</script>

<style scoped>
    td {
        cursor: pointer;
    }
</style>