<template>
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-4">
                <overdue></overdue>
            </div>
            <div class="col-sm-4">
                <today></today>
            </div>
            <div class="col-sm-4">
                <tomorrow></tomorrow>
            </div>
        </div>
    </div>
</template>

<script>
import { required, maxLength } from 'vuelidate/lib/validators'

import Overdue from "./Reminder/Overdue.vue"
import Today from "./Reminder/Today.vue"
import Tomorrow from "./Reminder/Tomorrow.vue"
import Soon from "./Reminder/Soon.vue"

export default {
    name: "Home",
    components: {
        Overdue,
        Today,
        Tomorrow,
        Soon
    },
    data(){
        return {
            errored: false,
            errors: [],
            loading: true,
            events: [],
            new_title: '',
            new_date: '',
            new_type: ''
        }
    },
    methods: {
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
                type: this.new_type
            })
            .then(response => {
                this.$v.$reset()
                this.new_title = '',
                this.new_date = '',
                this.new_type = '',
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
                console.log(response.data)
                this.events = response.data.events
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

