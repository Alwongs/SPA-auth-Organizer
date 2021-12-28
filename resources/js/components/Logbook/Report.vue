<template>
    <div class="container-fluid p-3">
        <div v-if="day !== null" class="alert alert-success" role="alert">
            Дата <strong>{{ formatDate(day.date, 'DD.MM.YYYY') }}</strong> успешно сохранена!
        </div>
            <div v-if="day !== null" class="data-block text-center mt-4">
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
                <h5 class="mt-3" v-else>Пробег: <span>{{ day.odo_post - day.odo_pre }}</span><small>км</small></h5>
            </div>
            <div v-if="day.comment" class="mt-4">
                <p>Комментарий:</p>
                <p class="p-2 bg-grey rounded"><i>{{ day.comment }}</i></p>
            </div>
        <div class="button-block">
            <button @click="$router.push('/create')">Создать день</button>
            <button @click="$router.push('/logbook')">Домой</button>
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
            day: this.$route.params.data ? this.$route.params.data : null,
            month_type: 'current_month'
        }

    },
    methods: {
        formatDate(date) {
            let day = new Date(date). getDate();
            let month = new Date(date).toLocaleString('ru-Ru', { month: 'long' }).toLowerCase();
            let year = new Date(date). getFullYear();
            return day + ' ' + month + ' ' + year;
        },
    },
    mounted(){
        if(this.day == null) {
            this.$router.push({ name: 'logbook' });
        }
    }
}
</script>


<style scoped>
    .container-fluid {
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
