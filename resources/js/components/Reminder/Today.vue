<template>

    <!-- Card -->                                              
    <div class="card mt-4 bg-secondary">
        <div class="card-body p-1">
            <div class="row">
                <div class="offset-sm-2 col-sm-8 text-center text-light">
                    <h3>Today</h3>
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
                    <tr class="no-content" v-if="today.length == 0">
                        <td colspan="5" class="text-center text-secondary">
                            <h5>no events today..</h5>
                        </td>                        
                    </tr>

                    <tr class="content" v-for="(event, index) in today" :key="event.id">
                        <th scope="row">{{index + 1}}.</th>
                        <td @click="$router.push({ name: 'event', params: {id: event.id}})" style="cursor:pointer;"><h4>{{event.title}}!</h4></td>
                        <td>
                            <button v-if="event.type != 'unique'" @click="postpone(event)" type="button" class="btn btn-success m-0">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </button>
                            <button v-else @click="deleteEvent(event.id)" type="button" class="btn btn-success m-0">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </button>                            
                        </td>
                        <td>
                            <button @click.prevent="current_event = event" type="button" class="btn btn-primary" data-toggle="modal" data-target="#todayEditModal">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </button>
                        </td>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="todayEditModal" tabindex="-1" aria-labelledby="todayEditModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-secondary">
                                        <h5 class="modal-title text-light" id="todayEditModalLabel">Edit event</h5>
                                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form @submit.prevent="updateEvent(current_event)">
                                            <div class="form-group">
                                                <label for="todayEditTitleModal">title</label>
                                                <input v-model="current_event.title" type="text" class="form-control" id="todayEditTitleModal">
                                            </div>
                                            <div class="form-group">
                                                <label for="todayEditDateModal">date</label>
                                                <input v-model="current_event.date" type="date" min="2021-01-01" max="2100-01-01" class="form-control" id="todayEditDateModal" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="todayEditTypeModal">Type</label>
                                                <select v-model="current_event.type" class="form-control" id="todayEditTypeModal">
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

</template>

<script>
export default {
    name: "Today",
    data(){
        return {
            today: [],
            current_event: {},
            changed_date: false,
        }
    },
    methods: {
        showDate(message) {
            alert('Deadline is: ' + message);
        },
        postpone(event){
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
                console.log(error)
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
                $('#todayEditModal').modal('hide');
                this.getAllEvents()
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
            axios.post('/api/events/' + id, {
                _method: 'DELETE'
            })
                .then(response => {
                    this.getAllEvents()
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        },
        getAllEvents() {
            axios.get('/api/events')
            .then(response => {
                this.today = response.data.today
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
        this.getAllEvents()
    }
}
</script>

<style scoped>
    .content {
        background-color: rgb(253, 237, 163);
        font-size: 120%;
    }
    h4 {
        color:brown;
    }
    .show-date {
        cursor: pointer;
    }
</style>