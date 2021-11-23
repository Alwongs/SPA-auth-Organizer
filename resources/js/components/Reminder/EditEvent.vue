<template>
    <transition name="modal">
        <div v-if="isModalOpen" class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">

                    <div class="modal-header bg-secondary">
                        <slot name="header">
                            <h4 class="text-light">Edit {{ title }}</h4>
                            <button @click="closeModal" type="button" class="close text-light" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </slot>
                    </div>

                    <div class="modal-body">
                        <slot name="body">
                            <form @submit.prevent="updateEvent(event)">
                                <div class="form-group">
                                    <label for="editTitleModal">title</label>
                                    <input v-model="event.title" type="text" class="form-control" id="editTitleModal">
                                </div>
                                <div class="form-group">
                                    <label for="editDateModal">Date</label>
                                    <input v-model="event.date" type="date"  min="2021-01-01" max="2100-01-01" class="form-control" id="editDateModal" required>
                                </div>
                                <div class="form-group">
                                    <label for="editDescriptionModal">Description</label>
                                    <textarea v-model="event.description" class="form-control" id="editDescriptionModal" rows="10"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="editTypeModal">Type</label>
                                    <select v-model="event.type" class="form-control" id="editTypeModal">
                                        <option value="unique">unique</option>
                                        <option value="annual">annual</option>
                                        <option value="monthly">monthly</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <slot name="footer">
                                        <button  @click="closeModal" type="button" class="btn btn-secondary">Close</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </slot>
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

export default {
    data() {
        return {
            isModalOpen: false,
            event: {},
            title: ''
        }
    },
    name: 'EditEvent',
    methods: {
        closeModal() {
            this.isModalOpen = false
        },
        updateEvent(event) {
            this.isModalOpen = false
            this.$emit('updateEvent', event)
        },
    },
    created () {
        bus.$on('editEvent', (data) => {
            this.isModalOpen = data.isModalOpen,
            this.event = data.event,
            this.title = data.title
        })
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
        width: 600px;
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