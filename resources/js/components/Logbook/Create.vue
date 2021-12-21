<template>
    <div class="container-fluid pt-2">
        <div v-if="errored" class="alert alert-danger" role="alert">
            Ошибка! {{ message }}
        </div>
        <div class="header text-center p-3">
            <h1>Создать новый день</h1>
        </div>
        <form @submit.prevent="addDay">
            <div class="form-group">
                <label for="createDateModal">Date</label>
                <input v-model="new_date" :type="dataExist ? 'text' : 'date'" min="2021-01-01" max="2100-01-01" class="form-control" id="createDateModal" required>
            </div>
            <fieldset v-bind:disabled="dataExist">
                <div class="form-group">
                    <label for="createRemindsPre">Остаток при выезде</label>
                    <input v-model="new_remains_pre" type="text" class="form-control short" id="createRemindsPre">                                       
                </div>
                <div class="form-group">
                    <label for="createRemindsPre">Километраж при выезде</label>
                    <input v-model="new_odo_pre" type="number" class="form-control" id="createRemindsPre" required>                                         
                </div>
            </fieldset> 
            <div class="form-group">
                <label for="createRemindsPre">Заправка</label>
                <div class="form-row">
                    <input v-model="new_fuel" type="text" class="form-control short col" id="createRemindsPre">
                    <div class="btn col" @click="new_fuel = 0">0</div>  
                    <div class="btn col" @click="new_fuel = 25">25</div>  
                    <div class="btn col" @click="new_fuel = 40">40</div>   
                </div>                                      
            </div>
            <div class="form-group">
                <label for="createRemindsPre">Километраж при заезде</label>
                <input v-model="new_odo_post" :input="countOdoPost()" type="number" class="form-control" id="createRemindsPre" required>                                         
            </div>
            <div class="form-group">
                <label for="createRemindsPre">Остаток при заезде</label>
                <input v-model="new_remains_post" @focus="countOdoPost" type="text" class="form-control short" id="createRemindsPre" required>                                         
            </div>
                                 
            <div class="btn-block p-0">
                <button type="submit">Сохранить</button>                
                <button @click="$router.push('/logbook')">Домой</button>
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
            new_date: '',
            new_remains_pre: '',
            new_odo_pre: '',
            new_fuel: '',
            new_odo_post: '',
            new_remains_post: '',
            message: '',
            dataExist: false
        }
    },
    methods: {
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
        countOdoPost() {
            let norm = 15.4;
            let odo = parseInt(this.new_odo_post, 10) - parseInt(this.new_odo_pre, 10);
            let spent = odo/100*norm;
            let fuel = this.new_fuel ? this.new_fuel : 0;
            let result = parseInt(this.new_remains_pre, 10) + parseInt(fuel, 10) - spent;
            this.new_remains_post = result ? result : 0;
        },
        addDay(){
            if(this.new_odo_post >= this.new_odo_pre) {
                axios.post('/api/days', {
                    date: this.new_date,
                    remains_pre: this.new_remains_pre,
                    odo_pre: this.new_odo_pre,
                    fuel: this.new_fuel ? this.new_fuel : 0,
                    odo_post: this.new_odo_post,
                    remains_post: this.new_remains_post,
                    user_id: this.user.id
                })
                    .then(response => {
                        let data = response.data.data
                        this.$router.push({ name: 'report', params: {data: data}});
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
            let next_date = year + '-' + month + '-' + day;
            return next_date;
        },
        getMonth(){
            axios.get('/api/days')
                .then(response => {                    
                    let all_days = response.data.all_days;
                    if (all_days.length !== 0) {
                        this.dataExist = true
                        let last_data = this.getLastData(all_days);
                        this.new_date = this.getNextDate(last_data.date)

                        this.new_remains_pre = last_data.remains_post;
                        this.new_odo_pre = last_data.odo_post;
                    } else {
                        this.new_remains_pre = 0;
                        this.new_odo_pre = 0      ;                
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
    .short {
        max-width: 25%;
    }

</style>
