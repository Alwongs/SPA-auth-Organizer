<template>
    <div class="container-fluid pt-2">
        <div v-if="errored" class="alert alert-danger" role="alert">
            Ошибка! {{ message }}
        </div>
        <div class="header text-center p-3">
            <h2>Редактировать {{ day.date }}</h2>
        </div>            

        <form @submit.prevent="updateDay">
            <hr class="mb-1">
            <div class="form-group">
                <label for="createDateModal">Date</label>
                <div class="width-30 form-row" :class="{ 'text-danger': new Date(this.day.date).getDay() == 0 || new Date(this.day.date).getDay() == 6}">
                    <input v-model="day.date" type="text" class="ml-1 form-control col" id="createDateModal" disabled>
                    <h5 class="text-center col pt-1" :class="{'text-danger': redDay}">{{ dayOfWeek[new Date(this.day.date).getDay()] }}</h5>
                    <div @click="redDay = !redDay" class="btn col" :class="{ 'btn-danger': redDay}">Красный день</div>
                </div>
            </div>
            <div class="form-group">
                <label for="createRemindsPre">Остаток при выезде</label>
                    <div class="form-row justify-content-between">
                    <input v-model="day.remains_pre" type="text" class="form-control short col" id="createRemindsPre" disabled>
                    <div @click="workInRed = !workInRed" class="btn col" :class="{ 'btn-success': workInRed}">Работа в выходной</div>                                       
                </div>
            </div>
            <div class="form-group">
                <label for="createRemindsPre">Километраж при выезде</label>
                    <div class="form-row justify-content-between">
                    <input v-model="day.odo_pre" type="number" class="form-control width-50" id="createRemindsPre" disabled>
                    <div @click="isTrip = !isTrip" class="btn col" :class="{ 'btn-warning': isTrip}">Командировка</div>                                                                 
                </div>
            </div>
            <hr class="mb-0">
            <div class="form-group">
                <label for="createRemindsPre">Заправка</label>
                <div class="form-row">
                    <input v-model="day.fuel" type="number" class="ml-1 form-control short col" id="createRemindsPre" disabled>
                    <div class="btn col" disabled>0</div>  
                    <div class="btn col" disabled>25</div>  
                    <div class="btn col" disabled>30</div>  
                    <div class="btn col" disabled>40</div>   
                </div>                                      
            </div>
            <hr>
            <div class="form-group">
                <label for="createRemindsPre">Километраж при заезде</label>
                <div class="form-row justify-content-between">                
                    <input v-model="day.odo_post" type="number" class="form-control width-50" id="createRemindsPre" disabled>                                         
                    <div type="button" class="btn col" :class="{ 'btn-light': notMoved}" disabled>Без выезда</div>
                </div>
            </div>
            <div class="form-group">
                <label for="createRemainsPost">Остаток при заезде</label>
                <div class="form-row justify-content-between">
                    <input v-model="day.remains_post" type="number" step="any" class="ml-1 form-control short" id="createRemainsPost" disabled>                                         
                    <div type="button" class="btn col" @click="addComment()" :class="{ 'btn-info': commentExist}">Комментарий</div>
                </div>
            </div>
            <div v-if="commentExist" class="form-group">
                <label for="createComment">Комментарий</label>
                <textarea v-model="day.comment" type="text" class="form-control" id="createComment"></textarea>                                         
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

            day: this.$route.params.day,
            car_id: this.$route.params.car_id,
            message: '',
            dayOfWeek: ['Вc', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
            workInRed: false,
            isTrip: false,
            redDay: false,
            notMoved: false,
            commentExist: false,
            new_comment: '',
            isLight: false,
        }
    },
    methods: {
        addComment() {
            this.commentExist = !this.commentExist;
        },
        formatDate(date) {
            let day = new Date(date). getDate();
            let month = new Date(date).toLocaleString('ru-Ru', { month: 'long' }).toLowerCase();
            let year = new Date(date). getFullYear();
            return day + ' ' + month + ' ' + year;
        },
        updateDay(){
            axios.post('/api/days/' + this.day.id, {
                _method: 'PATCH',
                date: this.day.date,
                remains_pre: this.day.remains_pre,
                odo_pre: this.day.odo_pre,
                fuel: this.day.fuel,
                odo_post: this.day.odo_post,
                remains_post: this.day.remains_post,
                work_in_red: this.workInRed,
                is_trip: this.isTrip,
                red_day: this.redDay,
                comment: this.day.comment,
                car_id: this.day.car_id
            })
                .then(response => {
                    //this.$router.push({ name: 'month'});
                                        this.$router.go(-1)
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false;

                })
        },              

    },
    mounted(){
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
