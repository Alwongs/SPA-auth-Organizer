<template>
    <div class="container">
        <div class="button-block px-2 py-0 row justify-content-around">
            <button title="На главную" class="col-2" @click="$router.push('/logbook')"><i class="bi bi-house-door"></i></button>
            <button title="Создать настройку" @click="createSetting()" class="col-2"><i class="bi bi-plus-square-dotted"></i></button>
            <button title="504" class="col-2"><i class="bi bi-calendar3"></i></button>
            <button title="резерв" class="col-2"></button>
            <button title="Удалить всё" class="col-2"><i class="bi bi-gear-wide"></i></button>
        </div>
        <hr>
        <div class="header text-center">
            <h3>Настройки</h3>
        </div>
        <div v-if="!showCreate && showEdit" class="data-block p-3">
            <div v-if="settings">
                <div v-for="setting in settings" :key="setting.id">
                    <form @submit.prevent="updateSettings(setting)">
                        <div class="row">
                            <div class="col-8">Автомобиль по умолчанию:</div>
                            <select v-model="setting.default_car_id" class="form-control col-4" id="createTypeModal">
                                <option v-for="(car, index) in cars" :key="index" :value="car.id">{{ car.car_number }}{{ car.region }}</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-8">Сезон нормы:</div>
                            <select v-model="setting.season" class="form-control col-4" id="createTypeModal">
                                <option value="summer">лето</option>
                                <option value="winter">зима</option>
                            </select>
                        </div>
                        <div class="row mt-4">
                            <div class="col-8">Зимний процент прибавки:</div>
                            <div class="col-4 pl-0">
                                <input class="text-center" type="number" step="any" v-model="setting.percent">
                            </div>
                        </div>
                        <button title="Сохранить" type="submit">&#128190;</button>
                    </form>
                </div>
            </div>
            <h6 class="text-center" v-if="settings.length === 0">Данных нет...</h6>            
        </div>
        <div v-if="!showEdit && !showCreate" @click="showEdit = true" type="button" class="btn btn-outline-danger">Изменить</div>


        <div v-if="showCreate">
            <form @submit.prevent="addSetting">
                <div class="form-group">
                    <label for="createNorm">Автомобиль по умолчанию:</label>
                    <select v-model="new_default_car_id" class="form-control" id="createTypeModal" required>
                        <option v-for="car in cars" :key="car.id" :value="car.id">{{ car.car_number }}{{ car.region }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="createNorm">Сезон нормы:</label>
                    <select v-model="new_season" class="form-control" id="createTypeModal" required>
                        <option value="summer">лето</option>
                        <option value="winter">зима</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="createPercent">Зимний процент прибавки:</label>
                    <input v-model="new_percent" type="number" step="any" class="form-control" id="createPercent" required>
                </div>              
                <div class="button-block px-2 py-0 row justify-content-around">
                    <button title="Сохранить" class="col-2" type="submit">&#128190;</button>                
                    <button title="На главную" class="col-2" @click="$router.push('/logbook')"><i class="bi bi-house-door"></i></button>
                </div>
            </form>
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

            cars: [],
            new_season: '',
            new_percent: '',
            new_default_car_id: '',
            settings: [],
            showCreate: false,
            showEdit: false
        }
    },
    methods: {
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
        updateSettings(setting) {
            axios.post('/api/settings/' + setting.id, {
                _method: 'PATCH',
                season: setting.season,
                percent: setting.percent,
                default_car_id: setting.default_car_id,
                user_id: this.user.id,
            })
            .then(response => {
                this.getSettings();
                this.showEdit = false;
            })
            .catch(error => {
                this.errored = true
            })
            .finally(() => {
                this.loading = false
            })
        },
        createSetting() {
            if(this.settings.length > 0) {
                alert('Кол-во настроек ограничено!');
            } else {
                this.showCreate = !this.showCreate;
            }
        },
        addSetting() {
            axios.post('/api/settings', {
                season: this.new_season,
                percent: this.new_percent,
                default_car_id: this.new_default_car_id,
                user_id: this.user.id
            })
                .then(response => {
                    this.showEdit = !this.showEdit;
                    this.showCreate = !this.showCreate;
                    this.getSettings();
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
    mounted(){
        this.getCars();
        this.getSettings();
    }    
}
</script>


<style scoped>
    select {
        font-size: 120%;
        width: 90%;
    }
    input {
        font-size: 120%;
        width: 90%;
    }
    .car-plate {
        font-size: 80%;
        border: 1px solid black;
        border-radius: 5px;
        width: fit-content;
        margin: 0 auto;
    }
    .car-number {
        border-right: 1px solid black;
    }
    .container {
        background-color: rgb(221, 221, 221);
        height: 100vh;
    }
    button {
        font-size: 140%;
        width: 100%;
        margin-top: 10px;
        padding: 20px;
    }
    .data-block {
        border: rgb(189, 189, 189) solid 1px;
        border-radius: 5px; 
    }
    .tr {
        border: rgb(189, 189, 189) solid 1px;
       cursor: pointer;
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