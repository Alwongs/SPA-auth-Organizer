<template>
    <div class="container-fluid">
        <div class="header text-center p-3">
            <h1>Главная</h1>
        </div>
        <div class="data-block border mt-2">
            <table class="table">
                <tr class="tr-small text-center">
                    <td colspan="2">Выезд: {{ formatDate(firstDate) }}</td>
                </tr>
                <tr>
                    <td>Остаток на начало месяца:</td>
                    <td class="bg-warning text-center">{{ Math.round(firstRemains) }}</td>
                </tr>
                <tr>
                    <td>Пробег на начало месяца:</td>
                    <td class="text-center">{{ firstOdo }}</td>
                </tr>
                <tr>
                    <td>Заправлено в этом месяце:</td>
                    <td class="text-danger text-center">{{ Math.round(fuel) }}</td>
                </tr>
                <tr class="tr-small text-center">
                    <td colspan="2">Заезд: {{ formatDate(lastDate) }}</td>
                </tr>
                <tr>
                    <td>Последний пробег:</td>
                    <td class="text-center">{{ lastOdo }}</td>
                </tr>
                <tr>
                    <td>Последний остаток:</td>
                    <td class="bg-warning text-center">{{ Math.round(lastRemains) }}</td>
                </tr>
            </table>
        </div>
        <div class="btn-block">
            <button @click="$router.push('/create')">Создать день</button>
            <button @click="$router.push({name: 'month', params: {month_type: 'pre_month'}})">Предыдущий месяц</button>
            <button @click="$router.push({name: 'month', params: {month_type: 'current_month'}})">Текущий месяц</button>
        </div>
        <div class="pt-4">
            <ul>
                <li>Добавить метку о выходных в таблице</li>
                <li>Уменьшить кнопки и добавить пиктограммы</li>
                <li><del>Дата начала и последняя дата на главной стр</del></li>
                <li>Реализовать перезагрузку страниц с сохранением данных</li>
                <li>Перенести заголовок на панель</li>
            </ul>
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
        formatDate(date) {
            let day = new Date(date). getDate();
            let month = new Date(date).toLocaleString('ru-Ru', { month: 'long' }).toLowerCase();
            let year = new Date(date). getFullYear();
            return day + ' ' + month + ' ' + year;
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
        padding: 0;
        padding-bottom: 8px;        
    }

    .tr-small td {
        padding: 0;
        font-size: 70%
    }

    li {
        font-style: italic;
        margin-bottom: 10px
    }
    .text-center {
        padding-top: 2px;
    }

</style>
