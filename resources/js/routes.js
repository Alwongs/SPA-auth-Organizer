import VueRouter from 'vue-router';
import Home from './components/Home';
import Events from './components/Events';
import Event from './components/Event';
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