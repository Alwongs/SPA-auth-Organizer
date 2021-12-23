<template>
    <div class="container-fluid pt-2">
        <div v-if="data !== null" class="alert alert-success" role="alert">
            Дата <strong>{{ formatDate(data.date, 'DD.MM.YYYY') }}</strong> успешно сохранена!
        </div>
        <div v-if="data !== null" class="data-block">
            <p>Остаток: <span>{{ Math.round(data.remains_pre) }}</span></p>
            <p>Километраж: <span>{{ data.odo_pre }}</span></p>
            <p>Заправка: <span>{{ data.fuel }}</span></p>
            <p>Километраж: <span>{{ data.odo_post }}</span></p>
            <p>Остаток: <span>{{ Math.round(data.remains_post) }}</span></p>
        </div>
        <div class="button-block">
            <button @click="$router.push('/create')">Создать день</button>
            <button @click="$router.push('/logbook')">Домой</button>
            <button type="submit" @click.prevent="$router.push({ name: 'month', params: { data: data,  month_type: month_type} })">Back</button>
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
            data: this.$route.params.data ? this.$route.params.data : null,
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
        if(this.data == null) {
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
        font-size: 120%;
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
