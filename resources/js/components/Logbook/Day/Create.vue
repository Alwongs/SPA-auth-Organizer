<template>
    <div class="container-fluid pt-2">
        <div v-if="errored" class="alert alert-danger" role="alert">
            Ошибка! {{ message }}
        </div>
        <div class="header text-center p-3">
            <h2>Создать новый день</h2>
        </div>            

        <form @submit.prevent="addDay">
            <hr class="mb-1">
            <fieldset v-bind:disabled="dataExist">
                <div class="form-group">
                    <label for="createDateModal">Date</label>
                    <div class="width-30 form-row" :class="{ 'text-danger': new Date(this.new_date).getDay() == 0 || new Date(this.new_date).getDay() == 6}">
                        <input v-model="new_date" :type="dataExist ? 'text' : 'date'" min="2021-01-01" max="2100-01-01" class="ml-1 form-control col" id="createDateModal" required>
                        <h5 class="text-center col pt-1" :class="{'text-danger': redDay}">{{ dayOfWeek[new Date(this.new_date).getDay()] }}</h5>
                        <div @click="redDay = !redDay" class="btn col" :class="{ 'btn-danger': redDay}">Красный день</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="createRemindsPre">Остаток при выезде</label>
                     <div class="form-row justify-content-between">
                        <input v-model="new_remains_pre" type="text" class="form-control short col" id="createRemindsPre">
                        <div @click="workInRed = !workInRed" class="btn col" :class="{ 'btn-success': workInRed}">Работа в выходной</div>                                       
                    </div>
                </div>
                <div class="form-group">
                    <label for="createRemindsPre">Километраж при выезде</label>
                     <div class="form-row justify-content-between">
                        <input v-model="new_odo_pre" type="number" class="form-control width-50" id="createRemindsPre" required>
                        <div @click="isTrip = !isTrip" class="btn col" :class="{ 'btn-warning': isTrip}">Командировка</div>                                                                 
                    </div>
                </div>
            </fieldset> 
            <hr class="mb-0">
            <div class="form-group">
                <label for="createRemindsPre">Заправка</label>
                <div class="form-row">
                    <input v-model="new_fuel" type="number" class="ml-1 form-control short col" id="createRemindsPre">
                    <div class="btn col" @click="new_fuel = 0">0</div>  
                    <div class="btn col" @click="new_fuel = 25">25</div>  
                    <div class="btn col" @click="new_fuel = 30">30</div>  
                    <div class="btn col" @click="new_fuel = 40">40</div>   
                </div>                                      
            </div>
            <hr>
            <div class="form-group">
                <label for="createRemindsPre">Километраж при заезде</label>
                <div class="form-row justify-content-between">                
                    <input v-model="new_odo_post" @change="flash()" type="number" class="form-control width-50" id="createRemindsPre" required>                                         
                    <div type="button" class="btn col" @click="fillSame()" :class="{ 'btn-light': notMoved}">Без выезда</div>
                </div>
            </div>
            <div class="form-group">
                <label for="createRemainsPost">Остаток при заезде</label>
                <div class="form-row justify-content-between">
                    <input v-model="new_remains_post" :class="{'fuel-over': new_remains_post > car.fuel_tank}" type="number" step="any" class="ml-1 form-control short" id="createRemainsPost" required>                                         
                    <div :class="{'bg-secondary': isLight}" type="button" class="btn p-2 m-0 col" @click="countRemainsPost()"><i class="bi bi-box-arrow-in-left"></i> остаток</div>
                    <div type="button" class="btn col" @click="addComment()" :class="{ 'btn-info': commentExist}">Комментарий</div>
                </div>
            </div>
            <div v-if="commentExist" class="form-group">
                <label for="createComment">Комментарий</label>
                <textarea v-model="new_comment" type="text" class="form-control" id="createComment"></textarea>                                         
            </div>
            <hr class="mb-1">                                     
            <div class="button-block px-2 py-0 row justify-content-around">
                <button title="Сохранить" class="col-2 save" type="submit">&#128190;</button>                
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

            norm: '',
            percent: null,
            car: {},
            all_days: [],
            new_date: '',
            new_remains_pre: '',
            new_odo_pre: '',
            new_fuel: '',
            new_odo_post: '',
            new_remains_post: '',
            message: '',
            dataExist: false,
            dayOfWeek: ['Вc', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
            workInRed: false,
            isTrip: false,
            redDay: false,
            notMoved: false,
            commentExist: false,
            new_comment: '',
            isLight: false,
            stopFlash: false,
          
            car_id:  localStorage.getItem('car_id'),
        }
    },
    methods: {
        flash() {
            if(!this.stopFlash) {
                this.isLight = !this.isLight;
                setTimeout(() => this.flash(), 500)
            } else {
                this.isLight = false;
                return
            }
        },
        addComment() {
            this.commentExist = !this.commentExist;
        },
        fillSame() {
            if (this.notMoved === false) {
                this.new_odo_post = this.new_odo_pre;
                let remains_post = this.new_remains_pre;
                this.new_remains_post = remains_post;
                this.notMoved = !this.notMoved;
            } else {
                this.new_odo_post = ''; 
                this.new_remains_post = '';
                this.notMoved = !this.notMoved;                          
            }
        },
        getLastData(array) {
            let lastData = array[array.length - 1];
            return lastData
        },
        formatDate(date) {
            let day = new Date(date). getDate();
            let month = new Date(date).toLocaleString('ru-Ru', { month: 'long' }).toLowerCase();
            let year = new Date(date). getFullYear();
            return day + ' ' + month + ' ' + year;
        },
        getNorm() {
            let norm = 0;
            if(this.norm == 'summer') {
                norm = this.car.summer_norm;
            } else if (this.norm == 'winter') {
                norm = this.car.winter_norm;
            } else {
                alert('ошибка в методе getNorm!');
            }
            return norm;
        },
        countRemainsPost() {
            let norm = this.getNorm();
            let odo = parseInt(this.new_odo_post) - parseInt(this.new_odo_pre);
            let spent = odo/100*norm;
            let fuel = this.new_fuel ? this.new_fuel : 0;
            let result = parseFloat(this.new_remains_pre) + parseFloat(fuel) - spent;
            this.new_remains_post = result ? result : 0;
            this.stopFlash = true;
        },
        addDay(){
            if(this.new_remains_post > this.car.fuel_tank) {
                alert('Ваш остаток больше чем ёмкость бака!');
            }
            if(this.new_odo_post >= this.new_odo_pre) {
                axios.post('/api/days', {
                    date: this.new_date,
                    remains_pre: this.new_remains_pre,
                    odo_pre: this.new_odo_pre,
                    fuel: this.new_fuel ? this.new_fuel : 0,
                    odo_post: this.new_odo_post,
                    remains_post: this.new_remains_post,
                    work_in_red: this.workInRed,
                    is_trip: this.isTrip,
                    red_day: this.redDay,
                    comment: this.new_comment,
                    car_id: this.car_id
                })
                    .then(response => {
                        this.$router.push({ name: 'month'});
                    })
                    .catch(error => {
                        console.log(error)
                        this.errored = true
                    })
                    .finally(() => {
                        this.loading = false
                    })
            } else {
                this.message = 'Километраж не может уменьшиться!';
                this.errored = true;
            }

        },
        getNextDate(date) {
            let previous_date = new Date(date);
            let next_date_unix = previous_date.setDate(previous_date.getDate() + 1); // unix
            let year = new Date(next_date_unix).getFullYear();
            let month = new Date(next_date_unix).getMonth()+1;
            let day = new Date(next_date_unix).getDate();
            if(day < 10) {
                day = '0' + day;
            }
            if(month < 10) {
                month = '0' + month;
            }            
            let next_date = year + '-' + month + '-' + day;
            return next_date;
        },               
        getCar(id){
            axios.get('/api/cars/' + id)
                .then(response => {
                    this.all_days = response.data.data.days.all_days;
                    this.car = response.data.data.car;
                    this.initialData();
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })               
        },        
        initialData() {
            if (this.all_days.length !== 0) {
                this.dataExist = true

                let last_data = this.getLastData(this.all_days);
                this.new_date = this.getNextDate(last_data.date)
                this.new_remains_pre = last_data.remains_post;
                this.new_odo_pre = last_data.odo_post;
            } else {
                this.new_remains_pre = 0;
                this.new_odo_pre = 0      ;                
            }             
        },
        getSettings() {
            axios.get('/api/settings')
                .then(response => {
                    this.norm = response.data.data[0].norm;
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
        this.getCar(this.car_id);
    }
}
</script>


<style scoped>
    .border {
        border: black solid 1px;
    }
    .fuel-over {
        border: red solid 2px;
        color: red;
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
        max-width: 40%;        
    }
    .short {
        max-width: 20%;
    }
    label {
        margin-bottom: 0;
    }


</style>
