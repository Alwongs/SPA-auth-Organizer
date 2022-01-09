<template>
    <div class="container">
        <div class="button-block mb-1 px-2 py-0 row justify-content-around">
            <button title="На главную" class="col-2" @click="$router.push('/logbook')"><i class="bi bi-house-door"></i></button>
            <button title="Создать новый день" class="col-2" @click="goToCreate()"><i class="bi bi-plus-square-dotted"></i></button>
            <button title="Предыдущий месяц" class="col-2" @click="goToMonth('pre_month')" :class="{'is-pushed': month_type == 'pre_month' }"><i class="bi bi-calendar-minus"></i></button>
            <button title="Текущий месяц" class="col-2"  @click="goToMonth('current_month')" :class="{'is-pushed': month_type == 'current_month' }"><i class="bi bi-calendar-check"></i></button>
            <button title="Удалить всё" class="col-2" @click="goToSettings()"><i class="bi bi-gear-wide"></i></button>
        </div>
        <div v-if="loading" class="d-flex justify-content-center p-1">
            <div class="spinner-border" role="status">
                <span class="visually-hidden"></span>
            </div>
        </div>               
        <div v-else class="header text-center p-1">
            <h3>{{ month_name }}</h3>
        </div> 
                    <h6 class="mb-0 px-4"><i>Журнал автомобиля: {{ car.model }} {{ car.car_number }} {{ car.region }}</i></h6>               
        <div class="table-block t-wrapper">
            <table class="table table-sm table-hover text-light text-right">
                <thead class=" bg-secondary">
                    <th class="col-1">Дт</th>
                    <th class="col-1">Ост</th>
                    <th class="col-3 text-center">Км</th>
                    <th class="col-1 text-center">Зап</th>
                    <th class="col-3 text-center">Км</th>
                    <th class="col-1">Ост</th>
                    <th class="col-2"></th>
                </thead>
                <tbody class="text-dark bg-light">
                    <tr @click="goToDay(day.id)" v-for="day in month" :key="day.id"  :class="{'weekend': isWeekend(new Date(day.date).getDay()) || day.red_day}">
                        <th class="border-right-bold" scope="row">{{ new Date(day.date).getDate()  }}.</th>
                        <td class="border-right">{{ Math.round(day.remains_pre) }}</td>
                        <td class="border-right-bold pr-3">{{ day.odo_pre }}</td>
                        <td class="border-right-bold text-center">{{ Math.round(day.fuel) === 0 ? '-' : Math.round(day.fuel) }}</td>
                        <td v-if="day.odo_post == day.odo_pre" class="border-right text-left pl-3"><small class="pb-4">без выезда</small></td>
                        <td v-else class="border-right text-left pl-3">{{ day.odo_post }}</td>
                        <td v-if="day.odo_post == day.odo_pre" class="border-right-bold" :class="{ 'text-danger': day.remains_post > car.fuel_tank}">--</td>
                        <td v-else class="border-right-bold" :class="{ 'text-danger': day.remains_post > car.fuel_tank}">{{ Math.round(day.remains_post) }}</td>
                        <td class="text-right pr-2">
                            <i v-if="day.comment" class="bi bi-chat-dots-fill text-primary"></i>
                            <i v-if="day.is_trip" class="bi bi-briefcase-fill"></i>
                            <i v-if="day.work_in_red" class="bi bi-hammer"></i>
                            <i v-if="(new Date(day.date).getDay() == 0 || new Date(day.date).getDay() == 6 || day.red_day) && !day.work_in_red" class=" text-danger bi bi-brightness-high-fill"></i>
                            <i v-if="!(new Date(day.date).getDay() == 0 || new Date(day.date).getDay() == 6 || day.red_day) && !day.red_day" class="text-primary bi bi-truck"></i>
                        </td>                                        
                    </tr>
                </tbody>
            </table>
            <div v-if="month.length === 0" class="text-center">
                <h6 class="text-center">Данных нет...</h6>
            </div>
            <div v-else>
                <table class="table">
                    <thead>
                        <th class="col-6 p-0"></th>
                        <th class="col-6 p-0"></th>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="col-6 text-right">Заправлено: </th>
                            <td class="col-6 text-left"><span class="bg-warning p-1 text-dark"><b>{{ fuel }}</b> л</span></td>
                        </tr>
                        <tr>
                            <th class="col-6 text-right">Пройдено: </th>
                            <td class="col-6 text-left"><span class="bg-info p-1 text-dark"><b>{{ fullOdo }}</b> км</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
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

            car_id: localStorage.getItem('car_id'),
            month_type: localStorage.getItem('month_type'),

            car: {},
            month: [],
            pre_month: [],
            current_month: [],
            previous_month_name: '',
            current_month_name: '',
            month_name: '',

            fuel: 0,
            fullOdo: 0,

        }
    },
    methods: {
        getSummFuel(month) {
            let result = 0;
            month.map((day) => {
               result += day.fuel;
            }) 
            this.fuel = result;
        },
        getFullOdo(month) {
            if (month.length !== 0) {
                let firstDay = month[0];
                let lastDay = month[month.length - 1];
                this.fullOdo = lastDay.odo_post - firstDay.odo_pre;
            } else {
                this.fullOdo = 0;
            }
        },
        goToSettings() {
            if(confirm('Не изменяте настройки, если не уверены!')) {
                this.$router.push({name: 'settings'});
            }
        },
        goToCreate() {
           this.$router.push({name: 'create'});
        },
        copyLink() {
            this.$clipboard(`http://o937876j.beget.tech/#/print/${this.user.id}`);
            alert(`Ссылка: http://o937876j.beget.tech/#/print/${this.user.id} скопирована`)
        },        
        goToMonth(type) {
            localStorage.setItem('month_type', type);
            this.month_type = type;
            this.getCar();
        },        
        goToDay(id) {
            localStorage.setItem('day_id', id.toString())
            this.$router.push({ name: 'day'})
        },
        deleteAll() {
            if(confirm('Вы уверены, что хотите удалить все данные в таблице?!!!')) {
                if(confirm('Хорошо подумали?!!!')) {

                    this.month.map((day) => {
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

        getCar() {
            axios.get('/api/cars/' + this.car_id)
                .then(response => {
                    this.car = response.data.data.car;

                    if (this.month_type == 'pre_month') {
                        this.month = response.data.data.days.pre_month;
                        this.month_name = response.data.data.days.previous_month_name;
                    } else if (this.month_type == 'current_month') {
                        this.month = response.data.data.days.current_month;
                        this.month_name = response.data.data.days.current_month_name;
                    } else {
                        this.month = []
                    }
                    this.getSummFuel(this.month);
                    this.getFullOdo(this.month); 
                })
                .catch(error => {
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        },
/*
        getData() {
            if(this.params.car) {
                if (this.month_type == 'pre_month') {
                    this.month = this.params.days.pre_month;
                    this.month_name = this.params.days.previous_month_name;
                } else if (this.month_type == 'current_month') {
                    this.month = this.params.days.current_month;
                    this.month_name = this.params.days.current_month_name;
                } else {
                    this.month = []
                } 
                this.car = this.params.car;
            } else {
                axios.get('/api/cars/' + this.car_id)
                    .then(response => {
                        this.car = response.data.data.car;

                        if (this.month_type == 'pre_month') {
                            this.month = response.data.data.days.pre_month;
                            this.month_name = response.data.data.days.previous_month_name;
                        } else if (this.month_type == 'current_month') {
                            this.month = response.data.data.days.current_month;
                            this.month_name = response.data.data.days.current_month_name;
                        } else {
                            this.month = []
                        } 
                    })
                    .catch(error => {
                        this.errored = true
                    })
                    .finally(() => {
                        this.loading = false
                    })                
            }
        }

/*
        getData(params) {
            if (this.month_type == 'pre_month') {
                this.month = params.days.pre_month;
                this.month_name = params.days.previous_month_name;

            } else if (this.month_type == 'current_month') {
                this.month = params.days.current_month;
                this.month_name = params.days.current_month_name;

            } else {
                this.month = []
            } 
            this.car = params.car;
        }, 
        
        getCar(id) {            
            axios.get('/api/cars/' + id)
                .then(response => {
                    this.car = response.data.data.car;

                    if (this.month_type == 'pre_month') {
                        this.month = response.data.data.days.pre_month;
                        this.month_name = response.data.data.days.previous_month_name;
                    } else if (this.month_type == 'current_month') {
                        this.month = response.data.data.days.current_month;
                        this.month_name = response.data.data.days.current_month_name;
                    } else {
                        this.month = []
                    } 
                })
                .catch(error => {
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
                
        },
        /*      
        getMonth(days){
            if (this.month_type == 'pre_month') {
                this.month = days.pre_month;
                this.month_name = days.previous_month_name;

            } else if (this.month_type == 'current_month') {
                this.month = days.current_month;
                this.month_name = days.current_month_name;

            } else {
                this.month = []
            }            
        },
        */
    },
    mounted(){
        this.getCar();
    }
}
</script>

<style scoped>
    .is-pushed {
        background-color: lightgrey;
    }
    .container {
        padding: 0 5px 0 5px;
        background-color: rgb(221, 221, 221);
        min-height: 100vh;
    }
    .button-block {
        margin-bottom: 20px;
    }
    .weekend {
        background-color: rgba(218, 35, 96, 0.13);
    }
    .red-day {
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
