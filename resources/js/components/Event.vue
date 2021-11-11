<template>
    <div class="container">
        <!-- Card -->                                              
        <div class="card mt-4 bg-secondary">
            <div class="card-body p-1">
                <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
                    <div class="container-fluid">
                        <div class="navbar-nav">
                            <h3 class="text-center text-light navbar-nav">Event</h3>
                        </div>
                        <div class="navbar-nav">
                            <button @click.prevent="current_event = event" type="button" class="btn btn-primary m-1" data-toggle="modal" data-target="#editModal">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </button>
                            <button @click="deleteEvent(event.id)" class="btn btn-danger m-1">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </nav>

                <div class="content p-4 bg-light">
                    <h3>{{ event.title }}</h3>
                    <p><b>Date: </b>{{event.date}}</p>
                    
                    <b>Description:</b> 
                    <p>{{event.description}}</p>
                        <hr>
                    <div class="text-right">
                        <button type="submit" @click.prevent="$router.go(-1)" class="btn btn-primary">Back</button>
                    </div>
                </div>


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
            current_event: {},
            event: {},
            id: this.$route.params.id,
            authenticated: auth.check(),
            user: auth.user,
            str: ''
        }
    },
    methods: {
        updateEvent(current_event){       
            axios.post('/api/events/' + current_event.id, {
                _method: 'PATCH',                
                date: current_event.date,
                title: current_event.title,
                description: current_event.description,
                type: current_event.type,
            })         
            .then(response => {                
                $('#editModal').modal('hide');
                this.getEvent(this.id)
            })
            .catch(error => {
                this.errored = true
            })
            .finally(() => {
                this.loading = false
            })
        },
        deleteEvent(id){
            if(confirm('Are your shure you want to DELETE this?')) {
                axios.post('/api/events/' + id, {
                    _method: 'DELETE'
                })
                    .then(response => {
                        this.getEvent(this.id)
                    })
                    .catch(error => {
                        this.errored = true
                    })
                    .finally(() => {
                        this.loading = false
                    })
                this.$router.go(-1)
            }
        },
        getEvent(id) {
            axios.get('/api/events/' + id)
                .then(response => {
                    this.event = response.data.data
                })
                .catch(error => {
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        }
    },
    mounted(){
        this.getEvent(this.id);
        Event.$on('userLoggedIn', () => {
            this.authenticated = true;
            this.user = auth.user;
        });
    }
}
</script>

<style scoped>
    td {
        cursor: pointer;
    }
</style>