<template>

    <!-- Card -->                                              
    <div class="card mt-4 bg-secondary">
        <div class="card-body p-1">
            <div class="row">
                <div class="offset-sm-2 col-sm-8 text-center text-light">
                    <h3>Overdue</h3>
                </div>
            </div>

            <table class="table table-hover bg-light">
                <thead class="text-secondary small">
                    <tr>
                        <th class="col-sm-1">№</th>
                        <th class="col-sm-9">title</th>
                        <th class="col-sm-1"></th>
                        <th class="col-sm-1"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="no-content" v-if="overdue.length == 0">
                        <td colspan="5" class="text-center text-secondary">
                            <h5>no overdue..</h5>
                        </td>                        
                    </tr>

                    <tr v-for="(event, index) in overdue" :key="event.id">
                        <th scope="row">{{index + 1}}.</th>
                        <td @click="$router.push({ name: 'event', params: {id: event.id}})" style="cursor:pointer;" class="text-danger show-date"><b>{{event.title}}</b></td>
                        <td>
                            <button v-if="event.type != 'unique'" @click="postpone(event)" type="button" class="btn btn-success m-0">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </button>
                            <button v-else @click="deleteEvent(event.id)" type="button" class="btn btn-success m-0">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </button>                                                         
                        </td>
                        <td>
                            <button @click.prevent="current_event = event" type="button" class="btn btn-primary m-0" data-toggle="modal" data-target="#overdueEditModal">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </button>
                        </td>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="overdueEditModal" tabindex="-1" aria-labelledby="overdueEditModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-secondary">
                                        <h5 class="modal-title text-light" id="overdueEditModalLabel">Edit event</h5>
                                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form @submit.prevent="updateEvent(current_event)">
                                            <div class="form-group">
                                                <label for="overdueEditTitleModal">title</label>
                                                <input v-model="current_event.title" type="text" class="form-control" id="overdueEditTitleModal">
                                            </div>
                                            <div class="form-group">
                                                <label for="overdueEditDateModal">Date</label>
                                                <input v-model="current_event.date" type="date" min="2021-01-01" max="2100-01-01" class="form-control" id="overdueEditDateModal" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="overdueEditTypeModal">Type</label>
                                                <select v-model="current_event.type" class="form-control" id="overdueEditTypeModal">
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
    name: "Overdue",
    data(){
        return {
            overdue: [],
            current_event: {}
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
                $('#overdueEditModal').modal('hide');
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
                this.overdue = response.data.overdue
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
    .show-date {
        cursor: pointer;
    }
</style>