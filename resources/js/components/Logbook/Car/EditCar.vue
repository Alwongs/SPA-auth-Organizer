<template>
    <div class="container-fluid pt-2">
        <div v-if="errored" class="alert alert-danger" role="alert">
            Ошибка! {{ message }}
        </div>
        <div class="header text-center p-3">
            <h3>Изменить ам: {{ car.car_number }} {{ car.region }}</h3>
        </div>            

        <form @submit.prevent="updateCar(car)">
            <div class="form-row">
                <div class="form-group col">
                    <label for="createCarNumberModal">Гос.Номер</label>
                    <input v-model="car.car_number"  type="text" class="form-control" id="createCarNumberModal" required>
                </div>
                <div class="form-group col">
                    <label for="createRegionModal">Регион</label>
                    <input v-model="car.region"  type="text" maxlength="3" class="form-control region-input" id="createRegionModal" required>
                </div>
            </div>
            <div class="form-group">
                <label for="createModel">Модель автомобиля</label>
                <input v-model="car.model" type="text" class="form-control" id="createModel" required>
            </div>
            <div class="form-group">
                <label for="createTank">Объем бака</label>
                <input v-model="car.fuel_tank" type="number" class="form-control fuel-input" id="createTank" required>
            </div>
            <div class="form-group">
                <label for="createSummerNorm">Летняя норма</label>
                <input v-model="car.summer_norm" type="number" step="any" class="form-control fuel-input" id="createSummerNorm" required>
            </div>
            <div class="form-group">
                <label for="createWinterNorm">Зимняя норма</label>
                <input v-model="car.winter_norm" type="number" step="any" class="form-control fuel-input" id="createWinterNorm" required>
            </div>
            <hr class="mb-1">                      
            <div class="button-block px-2 py-0 row justify-content-around">
                <button title="Сохранить" class="col-2" type="submit">&#128190;</button>                
                <button title="На главную" class="col-2" @click="$router.push('/logbook')"><i class="bi bi-house-door"></i></button>
            </div>
        </form>
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
            message: '',

            id: localStorage.getItem('car_id'),
            car: {}
        }
    },
    methods: {
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
        updateCar(car) {
            axios.post('/api/cars/' + car.id, {
                _method: 'PATCH',
                car_number: car.car_number,
                region: car.region,
                model: car.model,
                fuel_tank: car.fuel_tank,
                summer_norm: car.summer_norm,
                winter_norm: car.winter_norm,
                user_id: this.user.id,
            })
            .then(response => {
                this.$router.push({name: 'car'});
            })
            .catch(error => {
                this.errored = true
            })
            .finally(() => {
                this.loading = false
            })
        },
        getSettings() {
            axios.get('/api/settings')
                .then(response => {
                    this.percent = response.data.data[0].percent;
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
        this.getSettings();
        this.getCar(this.id);
    }
}
</script>


<style scoped>
    input {
        font-size: 150%;        
    }
    .fuel-input {
        width: 30%;         
    }
    .region-input {
        width: 40%;        
    }
    .container-fluid {
        background-color: rgb(221, 221, 221);
        height: 100vh;
    }
    button {
        font-size: 140%;
        width: 100%;
        margin-top: 10px;
        padding: 20px;
    }
    .btn {
        border: 1px solid grey;
        max-width: fit-content;
        max-height: fit-content;
        margin: 0 10px 0 10px;
        padding-right: 20px;
        padding-left: 20px;
    }
    .form-group {
        margin-bottom: 10px;
    }
    .width-50 {
        max-width: 50%;        
    }
    .short {
        max-width: 20%;
    }
    label {
        margin-bottom: 0;
    }


</style>
