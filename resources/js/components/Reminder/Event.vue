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
                            <button @click.prevent="current_event = event" type="button" class="btn btn-primary m-1" data-toggle="modal" data-target="#editCompanyModal">
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


                    <!-- Modal Edit Event -->
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
                                <form @submit.prevent="updateCompany()">

                                    <div class="form-group">
                                        <label for="editCompanyTitleModal">title</label>
                                        <input v-model="current_company.title" type="text" class="form-control" id="editCompanyTitleModal">
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
            current_company: {},
            event: {},
            id: this.$route.params.id,
            authenticated: auth.check(),
            user: auth.user
        }
    },
    methods: {
        updateEvent(){       
            axios.post('/api/companies/' + current_company.id, {
                _method: 'PATCH',                
                title: current_company.title,
                description: current_company.description,
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
        },
        deleteEvent(id){
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
        },
        getEvent(id) {
            axios.get('/api/events/' + id)
            .then(response => {
                this.event = response.data.data
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