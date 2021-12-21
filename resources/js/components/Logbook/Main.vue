<template>
    <div class="container-fluid">
        <div class="header text-center p-3">
            <h1>Главная</h1>
        </div>
        <div class="data-block border mt-2">
            <table class="table">
                <tr>
                    <td>Остаток на начало месяца:</td>
                    <td class="bg-warning">{{ Math.round(firstRemains) }}</td>
                </tr>
                <tr>
                    <td>Пробег на начало месяца:</td>
                    <td>{{ firstOdo }}</td>
                </tr>
                <tr>
                    <td>Заправлено в этом месяце:</td>
                    <td class="text-danger">{{ Math.round(fuel) }}</td>
                </tr>
                <tr>
                    <td>Последний пробег:</td>
                    <td>{{ lastOdo }}</td>
                </tr>
                <tr>
                    <td>Последний остаток:</td>
                    <td class="bg-warning">{{ Math.round(lastRemains) }}</td>
                </tr>
            </table>
        </div>
        <div class="btn-block">
            <button @click="$router.push('/create')">Создать день</button>
            <button @click="$router.push({name: 'month', params: {month_type: 'pre_month'}})">Предыдущий месяц</button>
            <button @click="$router.push({name: 'month', params: {month_type: 'current_month'}})">Текущий месяц</button>
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
            firstRemains: 0,
            firstOdo: 0,
            fuel: 0,
            lastOdo: 0,
            lastRemains: 0
        }
    },
    methods: {
        getCurrentMonth() {
            axios.get('/api/days')
                .then(response => {
                    let current_month = response.data.current_month;
                    if(current_month.length !== 0) {
                        this.getFirstRemains(current_month);
                        this.getLastRemains(current_month);
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
        getFirstRemains(current_month) {
            let firstDay = current_month[0];
            this.firstRemains = firstDay.remains_pre;
            this.firstOdo = firstDay.odo_pre;

        },
        getLastRemains(current_month) {
            let lastDay = current_month[current_month.length - 1];
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

</style>
