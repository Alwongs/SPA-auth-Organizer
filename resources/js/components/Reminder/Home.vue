<template>
    <div class="container-fluid">
        <div class="alert alert-danger" role="alert" v-if="errored">
            Ошибка загрузки данных! <br>
            {{errors}}
        </div>
        
        <edit-event @updateEvent="updateEvent"></edit-event>

        <div class="row">

                <!-- Overdue -->
            <div class="col-sm-4">
                <one-of-events 
                    :events="overdue" 
                    :title="'Overdue'" 
                    @deleteEvent="deleteEvent($event)"
                    @postponeEvent="postponeEvent($event)"
                ></one-of-events>
            </div>

                <!-- Today -->
            <div class="col-sm-4">
                <one-of-events 
                    :events="today" 
                    :title="'Today'" 
                    @deleteEvent="deleteEvent($event)"
                    @postponeEvent="postponeEvent($event)"
                ></one-of-events>
            </div>

                <!-- Tomorrow -->
            <div class="col-sm-4">
                <one-of-events 
                    :events="tomorrow" 
                    :title="'Tomorrow'" 
                    @deleteEvent="deleteEvent($event)"
                    @postponeEvent="postponeEvent($event)"                    
                ></one-of-events>
                <one-of-events 
                    :events="in_week" 
                    :title="'In week'" 
                    @deleteEvent="deleteEvent($event)"
                    @postponeEvent="postponeEvent($event)"                    
                ></one-of-events>
            </div>
        </div>
    </div>
</template>

<script>
import OneOfEvents from "./OneOfEvents.vue"
import EditEvent from "./EditEvent.vue"
import { bus } from "../../app"

export default {
    name: "Home",
    components: { OneOfEvents, EditEvent },
    data(){
        return {
            overdue: [],
            today: [],
            tomorrow: [],
            in_week: [],

            errored: false,
            errors: [],
            loading: true
        }
    },
    methods: {
        updateEvent(current_event){
            axios.post('/api/events/' + current_event.id, {
                _method: 'PATCH',
                title: current_event.title,
                date: current_event.date,
                type: current_event.type
            })
            .then(response => {
                this.getAllEvents()
            })
            .catch(error => {
                console.log(error.response.data.errors.title[0])
                this.errors = error.response.data.errors.title[0]
                this.errored = true
            })
            .finally(() => {
                this.loading = false
            })
        },
        postponeEvent(event){
            axios.post('/api/events/postpone/' + event.id, {
                _method: 'PATCH',
                title: event.title,
                date: event.date,
                type: event.type
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
        deleteEvent(id){
            if(confirm('The unique event will be deleted!')) {
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
                this.overdue = response.data.overdue
                this.today = response.data.today
                this.tomorrow = response.data.tomorrow
                this.in_week = response.data.in_week
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
        this.getAllEvents()
    }
}
</script>

