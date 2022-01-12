<template>
    <div class="container">
        <div class="button-block px-2 py-0 row justify-content-around">
            <button title="На главную" disabled class="col-2"><i class="bi bi-house-door"></i></button>
            <button title="Создать новый автомобиль" class="col-2" @click="goToCreate()"><i class="bi bi-plus-square-dotted"></i></button>
            <button title="504" @click="goToMonth()" class="col-2"><i class="bi bi-calendar3"></i></button>
            <button title="Чат" disabled class="col-2"><i class="bi bi-chat-dots"></i></button>
            <button title="Настройки" class="col-2" @click="goToSettings()"><i class="bi bi-gear-wide"></i></button>
        </div>
        <hr>
        <div class="header text-center p-2">
            <h4>Выберите автомобиль..</h4>
        </div>
        <div class="data-block m-1 p-2">
            <div v-if="cars">
                <div class="tr my-1 py-3 row" @click="goToCar(car.id)" v-for="car in cars" :key="car.id">
                    <div class="col-6 text-white"><i>{{ car.model }}</i></div>
                    <div class="col-6">
                        <div class="car-plate bg-white row">
                            <div class="car-number bg-white px-1">&middot; {{ car.car_number }} </div>
                            <div class="car-region  bg-white px-1"> {{ car.region }} &middot;</div>
                        </div>
                    </div>                                         
                </div>
            </div>
            <div v-if="loading" class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden"></span>
                </div>
            </div>
            <h6 class="text-center" v-if="cars.length === 0 && !loading">Данных нет...</h6>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            errored: false,
            errors: [],
            loading: true,
            cars: [],
            settings: [],
            default_car_id: 0,
            /*
            firstDate: '',
            firstRemains: 0,
            firstOdo: 0,
            fuel: 0,
            lastDate: '',
            lastOdo: 0,
            lastRemains: 0
            */
        }
    },
    methods: {
        goToMonth() {
            if (this.default_car_id !== 0) {
                localStorage.setItem('month_type', 'current_month');
                localStorage.setItem('car_id', this.default_car_id); 
                this.$router.push({name: 'month'}); 
            } else {
                alert('"Автомобиль по умолчанию" не установлен. Проверьте настройки!');
            }               
        },
        goToSettings() {
            if(confirm('Не изменяте настройки, если не уверены!')) {
                this.$router.push({name: 'settings'});
            }
        },
        goToCreate() {
            if(this.settings.length !== 0) {
                this.$router.push({name: 'create-car'});               
            } else {
                alert('Необходимо создать настройки!')
                this.$router.push({name: 'settings'});
            }

        },
        goToCar(id) {
            localStorage.setItem('car_id', id.toString())
            this.$router.push({ name: 'car', params: {car_id: id}})
        },
        getCars(){
            axios.get('/api/cars')
                .then(response => {
                    this.cars = response.data;
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        },
        getSettings() {
            axios.get('/api/settings')
                .then(response => {
                    this.settings = response.data.data;
                    if (response.data.data[0]) {
                        this.default_car_id = response.data.data[0].default_car_id;
                    }                     
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })            
        } 
                /*
        goToMonth(type) {
            localStorage.setItem('month_type', type);
            this.$router.push({name: 'month'});
        },
        formatDate(date) {
            if (date) {
                let day = new Date(date). getDate();
                let month = new Date(date).toLocaleString('ru-Ru', { month: 'long' }).toLowerCase();
                let year = new Date(date). getFullYear();
                return day + ' ' + month + ' ' + year;
            } else {
                return 'нет данных';
            }

        },
        getCurrentMonth() {
            axios.get('/api/days')
                .then(response => {
                    let current_month = response.data.current_month;
                    if(current_month.length !== 0) {
                        this.getFirstData(current_month);
                        this.getLastData(current_month);
                        this.getSummFuel(current_month)
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
        getFirstData(current_month) {
            let firstDay = current_month[0];
            this.firstDate = firstDay.date;
            this.firstRemains = firstDay.remains_pre;
            this.firstOdo = firstDay.odo_pre;

        },
        getLastData(current_month) {
            let lastDay = current_month[current_month.length - 1];
            this.lastDate = lastDay.date;
            this.lastOdo = lastDay.odo_post;          
            this.lastRemains = lastDay.remains_post;          
        },
        getSummFuel(current_month) {
            let result = 0
           current_month.map((day) => {
               result += day.fuel;
           }) 
           this.fuel = result;
        }
        */
    },    
    mounted(){
        this.getCars();
        this.getSettings();
    }    
}
</script>


<style scoped>
    .car-plate {
        font-size: 100%;
        border: 1px solid black;
        border-radius: 3px;
        width: fit-content;
        margin: 0 auto;
    }
    .car-number {
        border-right: 1px solid black;
        border-radius: 3px 0 0 3px;
    }
    .car-region {
        border-radius: 0 3px 3px 0;
    }
    .container {
        padding: 0 5px 0 5px;
        background-color: rgb(221, 221, 221);
        min-height: 100vh;
    }
    button {
        font-size: 140%;
        width: 100%;
        margin-top: 10px;
        padding: 20px;
    }
    .data-block {
        font-size: 150%;
        border-radius: 5px; 
    }
    .tr {
        border: rgb(189, 189, 189) solid 2px;
        cursor: pointer;
        background-color: rgb(78, 131, 131);
    }

    tr td {
        cursor: pointer;
        font-size: 120%;
        padding: 0;
        padding-bottom: 8px;        
    }

    .tr-small td {
        padding: 0;
        font-size: 70%
    }
    span {
        color: blue;
    }

    li {
        font-style: italic;
        margin-bottom: 10px
    }
    .text-center {
        padding-top: 2px;
    }

</style>
