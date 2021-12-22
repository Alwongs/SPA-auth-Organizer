<template>
    <div class="container-fluid">
        <div class="header text-center p-3">
            <h1>{{ month_name }}</h1>
        </div>
        <div class="table-block t-wrapper">
            <table class="table table-sm table-hover text-light bg-secondary">
                <thead>
                    <th>Дт</th>
                    <th>Ост</th>
                    <th>Км</th>
                    <th>Запр</th>
                    <th>Км</th>
                    <th>Ост</th>
                </thead>
                <tbody class="text-dark bg-light">
                    <tr @click="$router.push({ name: 'day', params: {data: day, month_type: month_type}})" v-for="day in days" :key="day.id"  :class="{ 'weekend': isWeekend(new Date(day.date).getDay()) }">
                        <th scope="row">{{ new Date(day.date).getDate()  }}</th>
                        <td>{{ Math.round(day.remains_pre) }}</td>
                        <td>{{ day.odo_pre }}</td>
                        <td>{{ Math.round(day.fuel) }}</td>
                        <td>{{ day.odo_post }}</td>
                        <td>{{ Math.round(day.remains_post) }}</td>
                    </tr>
                </tbody>
            </table>
            <h6 class="text-center" v-if="days.length === 0">Данных нет...</h6>
        </div>
        <div class="button-block">
            <button @click="$router.push('/create')">Создать день</button>
            <button @click="$router.push('/logbook')">Домой</button>
            <button @click="deleteAll()">Удалить всё</button>
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
            days: [],
            loading: true,
            data: this.$route.params.data,   
            month_name: '',
            month_type: this.$route.params.month_type,
            month_js: new Date() 
        }
    },
    methods: {
        deleteAll() {
            if(confirm('Are your shure you want to DELETE All notes?!!!')) {
                    console.log(this.days)
                this.days.map((day) => {
                    console.log(day.id)
                    this.deleteDay(day.id);
                });
                
                this.getMonth();
            }
        },
        deleteDay(id) {
            axios.post('/api/days/' + id, {
                _method: 'DELETE'
            })
                .then(response => {                       
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        },
        isWeekend(day) {
            if(day === 0 || day === 6) {
                return true;
            }
        },
        getMonth(){
            axios.get('/api/days')
                .then(response => {
                    if (this.month_type == 'pre_month') {
                        this.days = response.data.pre_month
                        this.month_name = response.data.previous_month_name

                    } else if (this.month_type == 'current_month') {
                        this.days = response.data.current_month
                        this.month_name = response.data.current_month_name

                    } else {
                        this.days = {}
                    }

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
        this.getMonth()
    }
}
</script>


<style scoped>

    .container-fluid {
        background-color: rgb(221, 221, 221);
        height: 100vh;
    }
    .button-block {
        margin-bottom: 20px;
    }
    .weekend {
        background-color: rgba(218, 35, 96, 0.13);
    }
    .t-wrapper {
        max-height: 75%;
        overflow-y: auto;
    }
    tr {
        cursor: pointer;
    }
    button {
        font-size: 140%;
        width: 100%;
        margin-top: 10px;
        padding: 20px;
    }
</style>
