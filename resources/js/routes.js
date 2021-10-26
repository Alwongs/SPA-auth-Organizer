import VueRouter from 'vue-router';
import Home from './components/Home';
import Events from './components/Events';
import Users from './components/Users';
import Tasks from './components/Tasks';
import About from './components/About';
import Register from './components/Register';
import Login from './components/Login';

let routes = [
    {
        path: '/events',
        component: Events,
        meta: { middlewareAuth: true }
    },
    {
        path: '/tasks',
        component: Tasks,
        meta: { middlewareAuth: true }
    },
    {
        path: '/',
        component: Home,
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