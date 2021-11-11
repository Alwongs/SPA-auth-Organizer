<template>

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
            <tr v-if="loading">
                <td colspan="4" class="text-center text-secondary">
                    <div class="spinner-grow text-secondary m-2" role="status">
                        <span class="sr-only"></span>
                    </div>
                </td>                        
            </tr>
            <tr class="no-content" v-if="in_week.length == 0">
                <td colspan="5" class="text-center text-secondary">
                    <h5>nothing in week..</h5>
                </td>                        
            </tr>

            <tr class="content" v-for="(event, index) in in_week" :key="event.id">
                <th scope="row">{{index + 1}}.</th>
                <td @click="$router.push({ name: 'event', params: {id: event.id}})" style="cursor:pointer;" class="show-date">{{event.title | capitalize }}</td>
                <td>
                    <button v-if="event.type != 'unique'" @click="postpone(event)" type="button" class="btn btn-success m-0">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </button>
                    <button v-else @click="deleteEvent(event.id)" type="button" class="btn btn-success m-0">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </button>                             
                </td>
                <td>
                    <button @click.prevent="current_event = event" type="button" class="btn btn-primary" data-toggle="modal" data-target="#soonEditModal">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
        </tbody>

            <!-- Modal Edit -->
        <div class="modal fade" id="soonEditModal" tabindex="-1" aria-labelledby="soonEditModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-secondary">
                        <h5 class="modal-title text-light" id="soonEditModalLabel">Edit event</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="updateEvent(current_event)">
                            <div class="form-group">
                                <label for="soonEditTitleModal">title</label>
                                <input v-model="current_event.title" type="text" class="form-control" id="soonEditTitleModal">
                            </div>
                            <div class="form-group">
                                <label for="soonEditDateModal">date</label>
                                <input v-model="current_event.date"  type="date" min="2021-01-01" max="2100-01-01" class="form-control" id="soonEditDateModal" required>
                            </div>
                            <div class="form-group">
                                <label for="soonEditTypeModal">Type</label>
                                <select v-model="current_event.type" class="form-control" id="soonEditTypeModal">
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
    </table>          

</template>

<script>
export default {
    name: "Soon",
    data(){
        return {
            errored: false,
            errors: [],
            loading: true,
            in_week: [],
            current_event: {}
        }
    },
    filters: {
		// Регистрируем фильтр capitalize:
		capitalize: function(str) {
			return str[0].toUpperCase() + str.slice(1);
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
                $('#soonEditModal').modal('hide');
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
                this.in_week = response.data.in_week
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
        background-color: rgb(245, 255, 236);
    }
    h4 {
        color:brown;
    }
    .show-date {
        cursor: pointer;
    }
</style>