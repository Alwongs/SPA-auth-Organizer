<template>
    <transition name="modal">
        <div v-if="isAddModalOpen" class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">

                    <div class="modal-header bg-secondary">
                        <slot name="header">
                            <h4 class="text-light">Add new event</h4>
                            <button @click="closeModal" type="button" class="close text-light" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </slot>
                    </div>

                    <div class="modal-body">
                        <slot name="body">
                                <form @submit.prevent="addEvent">
                                    <div class="form-group">
                                            <!-- request mistakes -->
                                        <div class="alert alert-danger" role="alert" v-if="errored">
                                            Ошибка загрузки данных! <br>
                                            {{errors[0]}}
                                        </div>
                                        <div class="alert alert-success" role="alert" v-if="success">
                                            The event has been added successfully!
                                        </div>

                                        <label for="createTitleModal">title</label>
                                        <input v-model="new_title" type="text" class="form-control" id="createTitleModal" :class="{ 'is-invalid': $v.new_title.$error }">
                                        <!-- Mistakes -->
                                        <div class="invalid-feedback" v-if="!$v.new_title.required">
                                            Обязательное поле.
                                        </div>
                                        <div class="invalid-feedback" v-if="!$v.new_title.maxLength">
                                            Максимальное количество символов: {{$v.new_title.$params.maxLength.max}}
                                        </div>                                         
                                    </div>
                                    <div class="form-group">
                                        <label for="createDateModal">Date</label>
                                        <input v-model="new_date" type="date" min="2021-01-01" max="2100-01-01" class="form-control" id="createDateModal" :class="{ 'is-invalid': $v.new_date.$error }">
                                        <!-- Mistakes -->
                                        <div class="invalid-feedback" v-if="!$v.new_date.required">
                                            Обязательное поле.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="createDescriptionModal">Description</label>
                                        <textarea v-model="new_description" class="form-control" id="createDescriptionModal" rows="10"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="createTypeModal">Type</label>
                                        <select v-model="new_type" class="form-control" id="createTypeModal" :class="{ 'is-invalid': $v.new_type.$error }">
                                            <option value="">Choose type for event</option>
                                            <option value="unique">unique</option>
                                            <option value="annual">annual</option>
                                            <option value="monthly">monthly</option>
                                        </select>
                                        <!-- Mistakes -->
                                        <div class="invalid-feedback" v-if="!$v.new_type.required">
                                            Обязательное поле.
                                        </div>
                                    </div>                                  
                                    <div class="modal-footer p-0">
                                        <button  @click="closeModal" type="button" class="btn btn-secondary">Close</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
import { bus } from "../../app"
import { required, maxLength } from 'vuelidate/lib/validators'

export default {
    data() {
        return {
            errored: false,
            errors: [],
            loading: true,
            new_title: '',
            new_date: '',
            new_description: '',
            new_type: '',
            isAddModalOpen: false,
            authenticated: auth.check(),
            user: auth.user,
            success: false
        }
    },
    name: 'AddEvent',
    methods: {
        closeModal() {
            this.isAddModalOpen = false
            this.success = false
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
                this.new_title = ''
                this.new_date = ''
                this.new_type = ''
                this.success = true
                bus.$emit('rerender')
                this.closeModal()
            })
            .catch(error => {
                this.errors = error.response.data.errors.title[0]
                console.log(this.errors)
                this.errored = true
            })
            .finally(() => {
                this.loading = false
            })
        },
    },
    created () {
        bus.$on('openAddModal', (data) => {
            console.log(data)
            this.isAddModalOpen = data.isAddModalOpen
        })
    },
    mounted(){
        //this.getAllEvents();
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

<style scoped>
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: table;
        transition: opacity 0.3s ease;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
    }

    .modal-container {
        max-width: 600px;
        margin: 0px auto;
        padding: 0;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
        transition: all 0.3s ease;
        font-family: Helvetica, Arial, sans-serif;
    }

    .modal-header h3 {
        margin-top: 0;
        color: #42b983;
    }

    .modal-body {
         margin: 10px 0;
    }

    .modal-default-button {
        float: right;
    }

    /*
    * The following styles are auto-applied to elements with
    * transition="modal" when their visibility is toggled
    * by Vue.js.
    *
    * You can easily play with the modal transition by editing
    * these styles.
    */

    .modal-enter {
        opacity: 0.5;
    }

    .modal-leave-active {
        opacity: 0;
    }

    .modal-enter .modal-container,
    .modal-leave-active .modal-container {
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }

</style>