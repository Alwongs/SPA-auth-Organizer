<template>
    <div class="container">

        <!-- Tasks -->     
        <div class="card p-3 mt-5 mx-auto" style="max-width: 800px;">
            <div class="form-group form-check">
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
                                <input v-on:keyup.esc="task_input_name_id = null" type="text" v-model="task.title" class="form-control" placeholder="enter your task here">                               
                            </form>
                            <h4 :class="[{'task-is-done': task.is_done},{'text-brown bg-warning': task.importance == 5},{'text-brown': task.importance == 4}]" v-if="task_input_name_id != task.id" @click="task_input_name_id = task.id">{{ task.title }}</h4>
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
                            <button @click="deleteTask(task.id)" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="spinner-border" style="width: 4rem; height: 4rem;" role="status" v-if="loading">
            <span class="sr-only">Loading...</span>
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
            select_is_open: false
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
                })
                .catch(error => {
                    console.log(error)
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
                title: this.new_title
            })
                .then(response => {
                    this.$v.$reset()
                    this.new_title = ''
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
