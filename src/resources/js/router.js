import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from './components/pages/Home'
import Login from './components/pages/Login'
import User from './components/pages/User'
import Product from './components/pages/Product'

Vue.use(VueRouter);

const routes = [
    {path: '/', component: Home},
    {path: '/login', component: Login},
    {path: '/user', component: User, meta: {requiresAuth: true}},
    {path: '/product', component: Product, meta: {requiresAuth: true}, name: 'product'}
];

const router = new VueRouter({
    mode: 'history',
    routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (state.isLogin === false) {
            next({
                path: '/login',
                query: {redirect: to.fullPath}
            })
        } else {
            next()
        }
    } else {
        next();
    }
});

export default router;
