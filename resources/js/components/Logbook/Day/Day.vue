<template>
    <div class="container">
        <div class="button-block px-2 py-0 row justify-content-around">
            <button title="На главную" class="col-2" @click="$router.push('/logbook')"><i class="bi bi-house-door"></i></button>            
            <button title="Создать новый день" class="col-2" @click="$router.push('/create')"><i class="bi bi-plus-square-dotted"></i></button>
            <button title="Редактировать день" disabled class="col-2"><i class="bi bi-pen"></i></button>            
            <button title="Удалить" class="col-2" @click="deleteDay(day.id)"><i class="bi bi-trash"></i></button>
            <button title="Настройки" class="col-2" @click="goToSettings()"><i class="bi bi-gear-wide"></i></button>
        </div> 
        <hr>
        <div v-if="loading" class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden"></span>
            </div>
        </div>       
        <div v-if="day !== null && !loading">
            <div class="header text-center p-2 m-2" :class="{ 'text-danger': new Date(day.date).getDay() == 0 || new Date(day.date).getDay() == 6}">
                <h3>{{ formatDate(day.date) }}</h3>
            </div>
            <div class="data-block text-center">
                <span class="bg-warning">{{ Math.round(day.remains_pre) }}<small>л</small></span>
                .. 
                <span>{{ day.odo_pre }}<small>км</small></span>
                .. 
                <span class="text-danger">{{ Math.round(day.fuel) }}<small>л</small></span>
                ..
                <span>{{ day.odo_post }}<small>км</small></span>
                .. 
                <span class="bg-warning">{{ Math.round(day.remains_post) }}<small>л</small></span>
            </div>
            <div class="note text-center mb-4">

                <h5 class="mt-3" v-if="day.odo_pre === day.odo_post"> ...Без выезда</h5>
                <div v-else>
                    <h5 class="mt-3">Пробег: <span>{{ day.odo_post - day.odo_pre }}</span><small>км</small></h5>
                    <h5 class="mt-3">Расход: <span class="bg-warning">{{ Math.round(day.remains_pre + day.fuel - day.remains_post) }}</span><small>л</small></h5>
                </div>

            </div>
            <div v-if="day.comment" class="mt-4">
                <p>Комментарий:</p>
                <p class="p-2 bg-grey rounded"><i>{{ day.comment }}</i></p>
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
            day_id: localStorage.getItem('day_id'),
            day: {},
            month_type: this.$route.params.month_type,
            dayOfWeek: 'den nedeli'
        }
    },
    methods: {
        goToSettings() {
            if(confirm('Не изменяте настройки, если не уверены!')) {
                this.$router.push({name: 'settings'});
            }
        },
        notification(message) {
            alert(message);
        },
        modifMonth(monthName) {
            let modifName = "";

            switch (monthName) {
            case "январь":
                modifName = "января";
                break;
            case "февраль":
                modifName = "февраля";
                break;
            case "март":
                modifName = "марта";
                break;
            case "апрель":
                modifName = "апреля";
                break;
            case "май":
                modifName = "мая";
                break;
            case "июнь":
                modifName = "июня";
                break;
            case "июль":
                modifName = "июля";
                break;
            case "август":
                modifName = "августа";
                break;
            case "сентябрь":
                modifName = "сентября";
                break;
            case "октябрь":
                modifName = "октября";
                break;
            case "ноябрь":
                modifName = "ноября";
                break;
            case "декабрь":
                modifName = "декабря";
                break;
            default:
                modifName = "Месяц не найден";
            }
            return modifName;
        },
        formatDate(date) {

            let day = new Date(date). getDate();
            let month = new Date(date).toLocaleString('ru-Ru', { month: 'long' }).toLowerCase();

            let monthModif = this.modifMonth(month);


            let year = new Date(date). getFullYear();
            return day + ' ' + monthModif + ' ' + year;
        },
        deleteDay(id) {
            if(confirm(`Удалить ${this.formatDate(this.day.date)}?`)) {
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
        this.getDay(this.day_id);
    }
}
</script>

<style scoped>
    .container {
        background-color: rgb(221, 221, 221);
        height: 100vh;
    }
    .data-block {
        font-size: 130%;
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
