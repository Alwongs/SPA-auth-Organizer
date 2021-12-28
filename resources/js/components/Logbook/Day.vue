<template>
    <div class="container-fluid pt-3">
        <div v-if="day !== null">
            <div class="header text-center p-2 mb-2" :class="{ 'text-danger': new Date(day.date).getDay() == 0 || new Date(day.date).getDay() == 6}">
                <h3>{{ formatDate(day.date) }}</h3>
            </div>
            <div class="data-block text-center">
                <span class="bg-warning">{{ Math.round(day.remains_pre) }}<small>л</small></span>
                .. 
                <span>{{ day.odo_pre }}<small>км</small></span>
                ... 
                <span class="text-danger">{{ Math.round(day.fuel) }}<small>л</small></span>
                ...
                <span>{{ day.odo_post }}<small>км</small></span>
                .. 
                <span class="bg-warning">{{ Math.round(day.remains_post) }}<small>л</small></span>
            </div>
            <div class="note text-center mb-4">
                <span class="text-info pl-5" v-if="day.odo_pre === day.odo_post"> ...Без выезда</span>
            </div>
            <div v-if="day.comment" class="mt-4">
                <p>Комментарий:</p>
                <p class="p-2 bg-grey rounded"><i>{{ day.comment }}</i></p>
            </div>
        </div>
        <div class="button-block">
            <button @click="$router.push('/create')">Создать день</button>
            <button @click="deleteDay(day.id)">Удалить</button>
            <button @click="$router.push('/logbook')">Домой</button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            authenticated: auth.check(),
            user: auth.user,
            errored: false,
            errors: [],
            loading: true,
            day_id: localStorage.getItem('day_id'),
            day: {},
            month_type: this.$route.params.month_type,
            dayOfWeek: 'den neleli'
        }
    },
    methods: {
        notification(message) {
            alert(message);
        },
        formatDate(date) {
            let day = new Date(date). getDate();
            let month = new Date(date).toLocaleString('ru-Ru', { month: 'long' }).toLowerCase();
            let year = new Date(date). getFullYear();
            return day + ' ' + month + ' ' + year;
        },
        deleteDay(id) {
            if(confirm('Are your shure you want to DELETE this?')) {
                axios.post('/api/days/' + id, {
                    _method: 'DELETE'
                })
                    .then(response => {
                        this.$router.push({ name: 'month', params: { month_type: this.month_type} })                        
                    })
                    .catch(error => {
                        console.log(error)
                        this.errored = true
                    })
                    .finally(() => {
                        this.loading = false
                    })
            }
        },
        getDay(id) {
            axios.get('/api/days/' + id)
                .then(response => {
                    this.day = response.data.data
                })
                .catch(error => {
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        }
    },
    mounted(){
        console.log(this.day_id);
        this.getDay(this.day_id);
    }
}
</script>

<style scoped>
    .container-fluid {
        background-color: rgb(221, 221, 221);
        height: 100vh;
    }
    .data-block {
        font-size: 140%;
    }
    button {
        font-size: 140%;
        width: 100%;
        margin-top: 10px;
        padding: 20px;
    }
    span {
        color: blue;
    }
    .form-group {
        margin-bottom: 30px;
    }
    .short {
        width: 25%;
    }
</style>
