import VueRouter from 'vue-router';
import Main from './components/Logbook/Main';
import Create from './components/Logbook/Day/Create';
import Update from './components/Logbook/Day/Update';
import Day from './components/Logbook/Day/Day';
import Report from './components/Logbook/Report';
import Settings from './components/Logbook/Settings';
import Month from './components/Logbook/Day/Month';
import Car from './components/Logbook/Car/Car';
import CreateCar from './components/Logbook/Car/CreateCar';
import EditCar from './components/Logbook/Car/EditCar';
import Print from './components/Logbook/Print';
import Home from './components/Reminder/Home';
import Events from './components/Reminder/Events';
import Event from './components/Reminder/Event';
import Users from './components/Users';
import Tasks from './components/To-do/Tasks';
import Companies from './components/Jobs/Companies';
import Company from './components/Jobs/Company';
import About from './components/About';
import Register from './components/Register';
import Login from './components/Login';

let routes = [
    {
        path: '/',
        component: Home,
        name: 'home',
        meta: { middlewareAuth: true }
    },
    {
        path: '/events',
        name: 'events',
        component: Events,
        meta: { middlewareAuth: true }
    },
    {
        path: '/events/:id',
        name: 'event',
        component: Event,
        meta: { middlewareAuth: true }
    },
    {
        path: '/tasks',
        component: Tasks,
        meta: { middlewareAuth: true }
    },
    {
        path: '/companies',
        name: 'companies',
        component: Companies,
        meta: { middlewareAuth: true }
    },
    {
        path: '/companies/:id',
        name: 'company',
        component: Company,
        meta: { middlewareAuth: true }
    },
    {
        path: '/about',
        component: About
    },
    {
        path: '/users',
        component: Users,
        meta: { middlewareAuth: true }
    },
    {
        path: '/logbook',
        component: Main,
        name: 'logbook',
        meta: { middlewareAuth: true }
    },
    {
        path: '/create',
        component: Create,
        name: 'create',
        meta: { middlewareAuth: true }
    },
    {
        path: '/update-day',
        component: Update,
        name: 'update-day',
        meta: { middlewareAuth: true }
    },
    {
        path: '/day',
        component: Day,
        name: 'day',
        meta: { middlewareAuth: true }
    },
    {
        path: '/report',
        component: Report,
        name: 'report',
        meta: { middlewareAuth: true }
    },
    {
        path: '/settings',
        component: Settings,
        name: 'settings',
        meta: { middlewareAuth: true }
    },
    {
        path: '/month',
        component: Month,
        name: 'month',
        meta: { middlewareAuth: true }
    },
    {
        path: '/car',
        component: Car,
        name: 'car',
        meta: { middlewareAuth: true }
    },
    {
        path: '/create-car',
        component: CreateCar,
        name: 'create-car',
        meta: { middlewareAuth: true }
    },
    {
        path: '/edit-car',
        component: EditCar,
        name: 'edit-car',
        meta: { middlewareAuth: true }
    },
    {
        path: '/print/:id',
        component: Print,
        name: 'print'
    },
    {
        path: '/register',
        component: Register
    },
    {
        path: '/login',
        component: Login
    },
];

const router = new VueRouter({
    routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.middlewareAuth)) {                
        if (!auth.check()) {
            next({
                path: '/login',
                query: { redirect: to.fullPath }
            });

            return;
        }
    }

    next();
})

export default router;