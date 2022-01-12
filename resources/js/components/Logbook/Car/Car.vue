<template>
    <div class="container">
        <div class="button-block px-2 py-0 row justify-content-around">
            <button title="На главную" class="col-2" @click="$router.push('/logbook')"><i class="bi bi-house-door"></i></button>
            <button title="Посмотреть журнал" class="col-2"  @click="goToMonth()"><i class="bi bi-calendar3"></i></button>
            <button title="резерв" class="col-2" @click="goToEditCar()"><i class="bi bi-pen"></i></button>            
            <button title="Удалить" class="col-2" @click="deleteCar(car.id)"><i class="bi bi-trash"></i></button>
            <button title="Настройки" class="col-2" @click="goToSettings()"><i class="bi bi-gear-wide"></i></button>
        </div>
        <hr>
        <div v-if="loading" class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden"></span>
            </div>
        </div>
        <div v-if="!loading && car.id">
            <div class="header text-center p-3">
                <div class="car-plate bg-light row">
                    <div class="car-number bg-light px-1">&middot; {{ car.car_number }}</div>
                    <div class="car-region  bg-light px-1">{{ car.region }} &middot;</div>
                </div>
            </div>   
            <div class="data-block m-3 p-4">
                <p>модель: <span class="data-item">{{ car.model }}</span></p>
                <p>топливный бак: <span class="data-item">{{ car.fuel_tank }}<small> л</small></span></p>
                <p>летняя норма: <span class="data-item">{{ car.summer_norm }}<small> л</small></span></p>
                <p>зимняя норма: <span class="data-item">{{ car.winter_norm }}<small> л</small></span></p>
            </div> 
        </div>

        <h6 class="text-center px-2" v-if="!car.id">Данных нет...</h6> 
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

            car: {},
            id: localStorage.getItem('car_id'),
        }
    },
    methods: {
        goToEditCar() { 
            this.$router.push({name: 'edit-car'});
        },
        goToSettings() {
            if(confirm('Не изменяте настройки, если не уверены!')) {
                this.$router.push({name: 'settings'});
            }
        },
        goToMonth() {
            localStorage.setItem('month_type', 'current_month'); 
            localStorage.setItem('car_id', this.id); 
            this.$router.push({name: 'month'});
                
        },
        getCar(id) {
            axios.get('/api/cars/' + id)
                .then(response => {
                    this.car = response.data.data.car;
                })
                .catch(error => {
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        },
        deleteCar(id) {
            if(confirm(`Удалить автомобиль ${this.car.model} ${this.car.car_number}${this.car.region}?`)) {
                if(confirm(`Уверены? При удалении автомобиля, также будет удалён его журнал!`)) {
                    axios.post('/api/cars/' + id, {
                        _method: 'DELETE'
                    })
                        .then(response => {
                            this.$router.push({ name: 'logbook' })                        
                        })
                        .catch(error => {
                            console.log(error)
                            this.errored = true
                        })
                        .finally(() => {
                            this.loading = false
                        })
                }            
            }            
        }
    },
    mounted(){
        this.getCar(this.id)
    }
}
</script>

<style scoped>
    .data-item {
        font-size: 150%;
        color: rgb(0, 0, 146);
    }
    .data-block {
        font-style: italic;
        font-size: 110%;        
        border: 1px rgb(200, 200, 200) solid;
        border-radius: 5px;;
    }
    .car-plate {
        font-size: 170%;
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
