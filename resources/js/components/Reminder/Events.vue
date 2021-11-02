<template>
    <div class="container">
        <div class="alert alert-danger" role="alert" v-if="errored">
            Ошибка загрузки данных! <br>
            {{errors[0]}}
        </div>
        <!-- Card -->                                              
        <div class="card mt-4 bg-secondary">
            <div class="card-body p-1">
                <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
                    <div class="container-fluid">
                        <div class="navbar-nav">
                            <h3 class="text-center text-light navbar-nav">All events</h3>
                        </div>
                        <div class="navbar-nav">
                                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#createModal">
                                Add new event
                            </button>
                        </div>
                    </div>
                </nav>

                <!-- Modal Create -->
                <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary">
                                <h5 class="modal-title text-light" id="createModalLabel">Add event</h5>
                                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form @submit.prevent="addEvent">
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
                                        <label for="createDateModal">Date</label>
                                        <input v-model="new_date" type="date" min="2021-01-01" max="2100-01-01" class="form-control" id="createDateModal" :class="{ 'is-invalid': $v.new_date.$error }">
                                        <!-- Mistakes -->
                                        <div class="invalid-feedback" v-if="!$v.new_date.required">
                                            Обязательное поле.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="createDescriptionModal">Description</label>
                                        <textarea v-model="new_description" class="form-control" id="createDescriptionModal" rows="10"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="createTypeModal">Type</label>
                                        <select v-model="new_type" class="form-control" id="createTypeModal" :class="{ 'is-invalid': $v.new_type.$error }">
                                            <option value="">Choose type for event</option>
                                            <option value="unique">unique</option>
                                            <option value="annual">annual</option>
                                            <option value="monthly">monthly</option>
                                        </select>
                                        <!-- Mistakes -->
                                        <div class="invalid-feedback" v-if="!$v.new_type.required">
                                            Обязательное поле.
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

                <table class="table table-hover bg-light">
                    <thead class="text-secondary small">
                        <tr>
                        <th class="col-sm-1">id</th>
                        <th class="col-sm-8">title</th>
                        <th class="col-sm-2">date</th>
                        <th class="col-sm-2">type</th>
                        <th class="col-sm-1"></th>
                        <th class="col-sm-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <div v-if="loading" class="spinner-border text-secondary m-2" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                        <tr v-for="event in events" :key="event.id">

                            <td @click="$router.push({ name: 'event', params: {id: event.id}})" style="cursor:pointer;">{{event.id}}</td>
                            <td @click="$router.push({ name: 'event', params: {id: event.id}})" style="cursor:pointer;">{{event.title}}</td>
                            <td @click="$router.push({ name: 'event', params: {id: event.id}})" style="cursor:pointer;">{{event.date}}</td>
                            <td @click="$router.push({ name: 'event', params: {id: event.id}})" style="cursor:pointer;">{{event.type}}</td>
                            <td>
                                <button @click.prevent="current_event = event" type="button" class="btn btn-primary m-0" data-toggle="modal" data-target="#editModal">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </button>
                            </td>
                            <td>
                                <button @click="deleteEvent(event.id)" class="btn btn-danger m-0">
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
                                            <form @submit.prevent="updateEvent(current_event)">
                                                <div class="alert alert-danger" role="alert" v-if="errored">
                                                    Ошибка загрузки данных! <br>
                                                    {{errors[0]}}
                                                </div>
                                                <div class="form-group">
                                                    <label for="editTitleModal">title</label>
                                                    <input v-model="current_event.title" type="text" class="form-control" id="editTitleModal">
                                                </div>
                                                <div class="form-group">
                                                    <label for="editDateModal">Date</label>
                                                    <input v-model="current_event.date" type="date"  min="2021-01-01" max="2100-01-01" class="form-control" id="editDateModal" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="editDescriptionModal">Description</label>
                                                    <textarea v-model="current_event.description" class="form-control" id="editDescriptionModal" rows="10"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="editTypeModal">Type</label>
                                                    <select v-model="current_event.type" class="form-control" id="editTypeModal">
                                                        <option value="unique">unique</option>
                                                        <option value="annual">annual</option>
                                                        <option value="monthly">monthly</option>
                                                    </select>
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
            events: [],
            new_title: '',
            new_date: '',
            new_description: '',
            new_type: '',
            current_event: {},
            authenticated: auth.check(),
            user: auth.user
        }
    },
    methods: {
        addEvent(){
            this.$v.new_title.$touch()
            this.$v.new_date.$touch()
            this.$v.new_type.$touch()
            if(this.$v.new_title.$anyError || this.$v.new_date.$anyError || this.$v.new_type.$anyError) {
                return;
            }
            axios.post('/api/events', {
                title: this.new_title,
                date: this.new_date,
                type: this.new_type,
                user_id: this.user.id
            })
            .then(response => {
                this.$v.$reset();
                this.new_title = '';
                this.new_date = '';
                this.new_type = '';
                $('#createModal').modal('hide');
                this.getAllEvents();
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
        updateEvent(current_event){
            axios.post('/api/events/' + current_event.id, {
                _method: 'PATCH',
                title: current_event.title,
                date: current_event.date,
                type: current_event.type
            })
            .then(response => {
                $('#editModal').modal('hide');
                this.getAllEvents()
                this.errors = []
                this.errored = false
            })
            .catch(error => {
                if(error.response.data.errors.title){
                    this.errors.push(error.response.data.errors.title[0])
                }
                this.errored = true
            })
            .finally(() => {
                this.loading = false
            })
        },
        deleteEvent(id){
            axios.post('/api/events/' + id, {
                _method: 'DELETE'
            })
                .then(response => {
                    this.getAllEvents()
                })
                .catch(error => {
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        },
        getAllEvents() {
            axios.get('/api/events')
            .then(response => {
                this.events = response.data.events
            })
            .catch(error => {
                this.errored = true
            })
            .finally(() => {
                this.loading = false
            })
        },
    },
    mounted(){
        this.getAllEvents();
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
        new_date: {
            required
        },
        new_type: {
            required
        }
    }
}
</script>
