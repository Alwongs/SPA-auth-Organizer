<template>
    <div class="container">

        <!-- Tasks -->     
        <div class="card p-4 mx-auto" style="max-width: 800px;">
            <div class="row">
                <div class="offset-sm-2 mb-2 col-sm-8 text-center text-dark">
                    <h3>Todo-list</h3>
                </div>
            </div>
            <div class="alert alert-danger" role="alert" v-if="errored">
                Ошибка загрузки данных! <br>
                {{errors[0]}}
            </div>
            <div class="form-group form-check p-0">
                <form @submit.prevent="addTask">
                    <input @blure="addTask" type="text" v-model="new_title" class="form-control" placeholder="Enter your task here"  :class="{ 'is-invalid': $v.new_title.$error }">

                    <!-- Mistakes -->
                    <div class="invalid-feedback" v-if="!$v.new_title.required">
                        Обязательное поле.
                    </div>
                    <div class="invalid-feedback" v-if="!$v.new_title.maxLength">
                        Максимальное количество символов: {{$v.new_title.$params.maxLength.max}}
                    </div>                
                </form>                
            </div>            
            <div v-if="loading" class="spinner-border text-secondary m-2" role="status">
                <span class="visually-hidden"></span>
            </div>
            <table class="table table-hover table-sm">
                <thead class="">
                    <tr>
                        <th class="col-sm-1"></th>
                        <th class="col-sm-8"></th>
                        <th class="col-sm-1"></th>
                        <th class="col-sm-1"></th>
                        <th class="col-sm-1"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(task, index) in tasks" :key="task.id">
                        <th :class="[{'task-is-done': task.is_done},{'text-danger': task.importance == 5}]" scope="row">{{index+1}}.</th>
                        <td>
                            <form @submit.prevent="updateTask(task)" v-if="task_input_name_id == task.id" class="d-flex">
                                <input v-on:keyup.esc="task_input_name_id = null" type="text" v-model="task.title" class="form-control" placeholder="enter your task here" required>                               
                            </form>
                            <h4 
                                :class="[
                                    {'task-is-done': task.is_done},
                                    {'text-brown bg-warning': task.importance == 5},
                                    {'text-brown': task.importance == 4},
                                    {'text-muted': task.importance == 0},
                                    {'text-dark': task.importance == 1}
                                ]" 
                                v-if="task_input_name_id != task.id" 
                                @click="task_input_name_id = task.id"
                            >
                                {{ task.title | capitalize }}
                            </h4>
                        </td>                     
                        <td>
                            <form>
                                <input @change="updateTask(task)" type="checkbox" v-model="task.is_done" class="custom-control-input" :id="'checkbox-3' + index">
                                <label class="custom-control-label" :for="'checkbox-3' + index"></label>
                            </form>
                        </td>
                        <td>
                            <select @change="updateTask(task)" v-model="task.importance" class="custom-select-sm">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </td>
                        <td>
                            <button @click="deleteTask(task.id)" type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { required, maxLength } from 'vuelidate/lib/validators'

export default {
    data() {
        return {
            tasks: [],
            errored: false,
            errors: [],
            loading: true,
            new_title: '',
            task_input_name_id: null,
            updated_title: '',
            select_is_open: false,
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
        updateTask(task){
            axios.post('/api/tasks/' + task.id, {
                _method: 'PATCH',
                title: task.title,
                is_done: task.is_done,
                importance: Number.parseInt(task.importance)
            })
                .then(response => {
                    this.task_input_name_id = null
                    this.updated_title = ''
                    this.getTasks()
                    this.errors = []
                    this.errored = false
                })
                .catch(error => {
                    console.log(error.response)
                    if(error.response.data.errors.title){
                        this.errors.push(error.response.data.errors.title[0])
                    }
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        },
        deleteTask(id){
            axios.post('/api/tasks/' + id, {
                _method: 'DELETE'
            })
                .then(response => {
                    this.getTasks()
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
                
        },
        addTask(){
            this.$v.new_title.$touch()
            if(this.$v.new_title.$anyError) {
                return;
            }
            axios.post('/api/tasks', {
                title: this.new_title,
                user_id: this.user.id
            })
                .then(response => {
                    this.$v.$reset()
                    this.new_title = ''
                    this.getTasks()
                    this.errors = []
                    this.errored = false
                })
                .catch(error => {
                    console.log(error.response.data.errors.title)
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
        getTasks(){
            axios.get('/api/tasks')
                .then(response => {
                    this.tasks = response.data.data
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
        this.getTasks()
        Event.$on('userLoggedIn', () => {
            this.authenticated = true;
            this.user = auth.user;
        });
    },
    validations: {
        new_title: {
            required,
            maxLength: maxLength(65)
        }
    }
}
</script>



<style scoped>
    .card {
        background-color: rgb(223, 223, 223);
    }

    .task-is-done {
        text-decoration: line-through;
        text-decoration-color: grey;
        color: rgb(168, 168, 168);
    }
    .text-brown {
        color:brown;
    }

</style>
