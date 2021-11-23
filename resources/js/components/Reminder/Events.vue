<template>
    <div class="container">
            
        <div class="alert alert-danger" role="alert" v-if="errored">
            Ошибка загрузки данных! <br>
            {{errors}}
        </div>


        <!-- Card -->                                              
        <div class="card mt-4 bg-secondary">
            <div class="card-body p-1">
                <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
                    <div class="container-fluid">
                        <div class="navbar-nav">
                            <h3 class="text-center text-light navbar-nav">All events</h3>
                        </div>
                    </div>
                </nav>

                <table class="table table-hover bg-light">
                    <thead class="text-secondary small">
                        <tr>
                        <th class="col-sm-8">title</th>
                        <th class="col-sm-2">date</th>
                        <th class="col-sm-2">type</th>
                        <th class="col-sm-1"></th>
                        <th class="col-sm-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="event in events" :key="event.id">
                            <td>{{event.title}}</td>
                            <td class="px-0">{{event.date}}</td>
                            <td class="pl-0">{{event.type}}</td>
                            <td class="pl-0">
                                <button @click.prevent="editEvent(event)" type="button" class="btn btn-primary">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </button>
                            </td>
                            <td class="pl-0">
                                <button @click="deleteEvent(event.id)" class="btn btn-danger m-0">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>       

                <edit-event @updateEvent="updateEvent"></edit-event> 
            </div>
        </div>
    </div>
</template>

<script>
import EditEvent from "./EditEvent.vue"
import { bus } from "../../app"
import { required, maxLength } from 'vuelidate/lib/validators'

export default {
    components: { EditEvent },
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
        editEvent(event) {
            bus.$emit('editEvent', {
                isModalOpen: true,
                event: event,
                title: 'event'
            }) 
        },
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
                description: this.new_description,
                type: this.new_type,
                user_id: this.user.id
            })
            .then(response => {
                this.$v.$reset()
                this.new_title = '',
                this.new_date = '',
                this.new_type = '',
                this.getAllEvents()
            })
            .catch(error => {
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
                description: current_event.description,
                type: current_event.type
            })
            .then(response => {
                this.getAllEvents()
            })
            .catch(error => {
                this.errors = error.response.data.errors.title[0]
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
                        this.getAllEvents()
                    })
                    .catch(error => {
                        this.errored = true
                    })
                    .finally(() => {
                        this.loading = false
                    })
                }

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
        bus.$on('rerender', () => {
            this.getAllEvents()
        });
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
