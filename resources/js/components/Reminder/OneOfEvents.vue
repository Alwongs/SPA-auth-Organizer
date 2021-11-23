<template>
    <!-- Card -->                                              
    <div class="card mt-4 bg-secondary">
        <div class="card-body p-1">
            <div class="row">
                <div class="offset-sm-2 col-sm-8 text-center text-light">
                    <h3>{{ title }}</h3>
                </div>
            </div>

            <table class="table table-hover bg-light">
                <thead class="text-secondary small">
                    <tr class="m-0 p-0">
                        <th class="col-sm-1">№</th>
                        <th class="col-sm-9">title</th>
                        <th class="col-sm-1"></th>
                        <th class="col-sm-1"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="no-content" v-if="events.length == 0">
                        <td colspan="5" class="text-center text-secondary">
                            <h5>no events today..</h5>
                        </td>                        
                    </tr>
                    <tr  :class="{ 'today': isToday(), 'tomorrow': isTomorrow(), 'in-week': isInWeek() }" v-for="(event, index) in events" :key="event.id">
                        <th scope="row">{{index + 1}}.</th>
                        <td @click="$router.push({ name: 'event', params: {id: event.id}})" class="show-date"><h4>{{ event.title }}!</h4></td>
                        <td>
                            <button v-if="event.type != 'unique'" @click="postponeEvent(event)" type="button" class="btn btn-success m-0">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </button>
                            <button v-else @click="deleteEvent(event.id)" type="button" class="btn btn-success m-0">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </button>                            
                        </td>
                        <td>
                            <button @click.prevent="editEvent(event)" type="button" class="btn btn-primary" data-toggle="modal" data-target="#EditModal">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>      

        </div>
    </div>
</template>

<script>
import { bus } from "../app"

export default {
    name: "OneOfEvents",
    props: {
        events: Array,
        title: String
    },
    data(){
        return {
            current_event: {},
            isModalOpen: false
        }
    },
    methods: {
        isToday() {
            return this.title =='Today'
        },
        isTomorrow() {
            return this.title =='Tomorrow'
        },
        isInWeek() {
            return this.title =='In week'
        },
        editEvent(event) {
            bus.$emit('editEvent', {
                isModalOpen: true,
                event: event,
                title: this.title
            }) 
        },
        deleteEvent(eventId) {
            this.$emit('deleteEvent', eventId)
        },
        postponeEvent(event) {
            this.$emit('postponeEvent', event)
        }
    }
}
</script>

<style scoped>
    .today {
        background-color: rgb(253, 237, 163);
        font-size: 120%;
    }
    .tomorrow {
        background-color: rgb(255, 249, 220);
    }
    .in-week {
        background-color: rgb(245, 255, 236);
    }
    h4 {
        color:brown;
    }
    .show-date {
        cursor: pointer;
    }
</style>