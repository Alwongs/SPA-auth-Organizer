<template>
    <div class="container-fluid">
        <div class="header text-center p-2">
            <h1>Главная</h1>
        </div>
        <div class="data-block border">
            <table class="table">
                <tr class="tr-small text-center">
                    <td colspan="2">Выезд: {{ formatDate(firstDate) }}</td>
                </tr>
                <tr>
                    <td>Остаток на начало месяца:</td>
                    <td class="bg-warning text-danger text-center">{{ Math.round(firstRemains) }}<small>л</small></td>
                </tr>
                <tr>
                    <td>Пробег на начало месяца:</td>
                    <td class="text-center"><span>{{ firstOdo }}</span><small>км</small></td>
                </tr>
                <hr>
                <tr class="tr-small text-center">
                    <td colspan="2">Заезд: {{ formatDate(lastDate) }}</td>
                </tr>
                <tr>
                    <td>Последний пробег:</td>
                    <td class="text-center"><span>{{ lastOdo }}</span><small>км</small></td>
                </tr>
                <tr>
                    <td>Последний остаток:</td>
                    <td class="bg-warning text-danger text-center">{{ Math.round(lastRemains) }}<small>л</small></td>
                </tr>
                <hr>
                <tr class="tr-small text-center">
                    <td colspan="2"><b>Итог за месяц:</b></td>
                </tr>
                <tr class="">
                    <td><b>Заправлено:</b></td>
                    <td class="text-danger text-center">{{ Math.round(fuel) }}<small v-if="fuel != 0">л</small></td>
                </tr>
                <tr>
                    <td><b>Пройдено:</b></td>
                    <td class="text-center"><span>{{ lastOdo - firstOdo }}</span><small>км</small></td>
                </tr>
            </table>
        </div>
        <div class="btn-block">
            <button @click="$router.push('/create')">Создать день</button>
            <button @click="goToMonth('pre_month')">Предыдущий месяц</button>
            <button @click="goToMonth('current_month')">Текущий месяц</button>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            errored: false,
            errors: [],
            loading: true,
            days: [],
            firstDate: '',
            firstRemains: 0,
            firstOdo: 0,
            fuel: 0,
            lastDate: '',
            lastOdo: 0,
            lastRemains: 0
        }
    },
    methods: {
        goToMonth(type) {
            localStorage.setItem('month_type', type);
            this.$router.push({name: 'month'});
        },
        formatDate(date) {
            if (date) {
                let day = new Date(date). getDate();
                let month = new Date(date).toLocaleString('ru-Ru', { month: 'long' }).toLowerCase();
                let year = new Date(date). getFullYear();
                return day + ' ' + month + ' ' + year;
            } else {
                return 'нет данных';
            }

        },
        getCurrentMonth() {
            axios.get('/api/days')
                .then(response => {
                    let current_month = response.data.current_month;
                    if(current_month.length !== 0) {
                        this.getFirstData(current_month);
                        this.getLastData(current_month);
                        this.getSummFuel(current_month)
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
        getFirstData(current_month) {
            let firstDay = current_month[0];
            this.firstDate = firstDay.date;
            this.firstRemains = firstDay.remains_pre;
            this.firstOdo = firstDay.odo_pre;

        },
        getLastData(current_month) {
            let lastDay = current_month[current_month.length - 1];
            this.lastDate = lastDay.date;
            this.lastOdo = lastDay.odo_post;          
            this.lastRemains = lastDay.remains_post;          
        },
        getSummFuel(current_month) {
            let result = 0
           current_month.map((day) => {
               result += day.fuel;
           }) 
           this.fuel = result;
        }
    },
    mounted(){
        this.getCurrentMonth()
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
    .data-block {
        font-size: 120%;
    }

    tr td {
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
