<template>
    <div class="container-fluid">
        <div class="header text-center pt-2 pb-1">
            <h3>{{ month_name }}</h3>
        </div>
        <div class="table-block t-wrapper">
            <table class="table table-sm table-hover text-light text-right">
                <thead class=" bg-secondary">
                    <th class="col-1">Дт</th>
                    <th class="col-1.5">Ост</th>
                    <th class="col-3 text-center">Км</th>
                    <th class="col-1 text-center">Зап</th>
                    <th class="col-3 text-center">Км</th>
                    <th class="col-1.5">Ост</th>
                    <th class="col-1"></th>
                </thead>
                <tbody class="text-dark bg-light">
                    <tr @click="goToDay(day.id)" v-for="day in days" :key="day.id"  :class="{ 'weekend': isWeekend(new Date(day.date).getDay()) }">
                        <th class="border-right-bold" scope="row">{{ new Date(day.date).getDate()  }}.</th>
                        <td class="border-right">{{ Math.round(day.remains_pre) }}</td>
                        <td class="border-right-bold pr-3">{{ day.odo_pre }}</td>
                        <td class="border-right-bold text-center">{{ Math.round(day.fuel) === 0 ? '-' : Math.round(day.fuel) }}</td>
                        <td class="border-right pr-3">{{ day.odo_post }}</td>
                        <td class="border-right">{{ Math.round(day.remains_post) }}</td>
                        <td v-if="day.is_trip" class="text-center">
                            <i class="bi bi-briefcase-fill"></i>
                        </td>
                        <td v-else-if="(new Date(day.date).getDay() == 0 || new Date(day.date).getDay() == 6) && !day.is_day_off" class="text-center">
                            <i class="bi bi-hammer"></i>
                        </td>
                        <td v-else-if="day.is_day_off" class="text-center">
                            <i class=" text-danger bi bi-brightness-high-fill"></i>
                        </td>
                        <td v-else class="text-center">
                            <i class="text-primary bi bi-truck"></i>
                        </td>                                            
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
            month_type: localStorage.getItem('month_type'),
            month_js: new Date() 
        }
    },
    methods: {
        goToDay(id) {
            localStorage.setItem('day_id', id.toString())
            this.$router.push({ name: 'day'})
        },
        deleteAll() {
            if(confirm('Вы уверены, что хотите удалить все данные в таблице?!!!')) {
                if(confirm('Хорошо подумали?!!!')) {

                    this.days.map((day) => {
                        console.log(day.id)
                        this.deleteDay(day.id);
                    });

                    setTimeout(() => { this.getMonth(); }, 5000);                   
                    
                }
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
                padding: 5px;
        background-color: rgb(221, 221, 221);
        min-height: 100vh;
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
    table {
        border: 1px solid rgba(167, 167, 167, 0.904);
    }
    tr {
        cursor: pointer;
    }
    .bg-data {
        background-color: rgba(231, 232, 235, 0.493);
    }
    small {
        font-size: 70%;
    }
    .border-right-bold {
        border-right: 2px solid rgba(204, 204, 204, 0.904);
    }
    button {
        font-size: 140%;
        width: 100%;
        margin-top: 10px;
        padding: 20px;
    }
</style>
