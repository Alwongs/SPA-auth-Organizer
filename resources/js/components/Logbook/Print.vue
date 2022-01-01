<template>
    <div class="container-fluid">
        <div class="header row p-3">
            <a v-if="month_type !== 'pre_month'" @click="openMonth('pre_month')" class="btn col-4 btn-outline-secondary">{{ previous_month_name }}</a>
            <a v-else class="btn col-4 btn-outline-grey disabled"><span>Предыдущий месяц</span></a>
            <h2 class="col-4 text-center"> {{ month_name }}</h2>
            <a v-if="month_type !== 'current_month'" @click="openMonth('current_month')" class="btn col-4 btn-outline-secondary">{{ current_month_name }}</a>
            <a v-else class="btn col-4 btn-outline-grey disabled"><span>Текущий месяц</span></a>
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
            month_name: '',
            previous_month_name: '',
            current_month_name: '',
            month_type: localStorage.getItem('month_type') ? localStorage.getItem('month_type') : 'pre_month',
            id: this.$route.params.id,
        }
    },
    methods: {
        openMonth(msg) {
            if(msg === 'pre_month') {
                this.month_type = 'pre_month';
            } else if (msg === 'current_month') {
                this.month_type = 'current_month';
            }
            this.getMonth(this.id)            
        },
        getMonth(id){
            
            axios.get('/api/days/print/' + id)
                .then(response => {
                    this.previous_month_name = response.data.previous_month_name;
                    this.current_month_name = response.data.current_month_name;
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
        },
        isWeekend(day) {
            if(day === 0 || day === 6) {
                return true;
            }
        },
    },
    mounted(){
        this.getMonth(this.id)
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
    span {
        color: rgb(199, 199, 199);
    }
</style>
