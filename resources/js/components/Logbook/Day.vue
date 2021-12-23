<template>
    <div class="container-fluid pt-3">

        <div v-if="data !== null">
            <div class="header text-center p-2">
                <h3>{{ formatDate(data.date) }}</h3>
            </div>
            <div class="data-block m-3">
                <p @click="notification('fuck!')">Остаток: <span class="bg-warning">{{ Math.round(data.remains_pre) }}</span></p>
                <p>Километраж: <span>{{ data.odo_pre }}</span></p>
                <p>Заправка: <span class="text-danger">{{ Math.round(data.fuel) }}</span></p>
                <p>Километраж: <span>{{ data.odo_post }}</span></p>
                <p>Остаток: <span class="bg-warning">{{ Math.round(data.remains_post) }}</span></p>
            </div>
        </div>
        <div class="button-block">
            <button @click="$router.push('/create')">Создать день</button>
            <button @click="deleteDay(data.id)">Удалить</button>
            <button @click="$router.push('/logbook')">Домой</button>
            <button @click.prevent="$router.push({ name: 'month', params: { month_type: month_type} })">Back</button>
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
            month_type: this.$route.params.month_type,
            dayOfWeek: 'den neleli'
        }
    },
    methods: {
        notification(message) {
            alert(message);
        },
        formatDate(date) {
            let day = new Date(date). getDate();
            let month = new Date(date).toLocaleString('ru-Ru', { month: 'long' }).toLowerCase();
            let year = new Date(date). getFullYear();
            return day + ' ' + month + ' ' + year;
        },
        deleteDay(id) {
            if(confirm('Are your shure you want to DELETE this?')) {
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
        }
    },
    mounted(){
        if(this.data == null) {
            alert('данные устарели!')
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
