import Vue from "vue";
import VueRouter from "vue-router";
import store from './store'

Vue.use(VueRouter);

import Auth from "./components/Auth.vue";
import Welcome from "./components/Welcome.vue";
import Logout from "./components/LogoutComponent.vue";
import NotFound from "./components/404.vue";
import Home from "./components/Home.vue";
import Register from "./components/Register.vue";

const router = new VueRouter({
    routes: [
        {
            path: "/",
            component: Welcome,
            name: "Welcome",
        },
        {
            path: "/login",
            component: Auth,
            name: "login",
        },
        {
            path: "/home",
            component: Home,
            name: "home",
            meta: { requiresAuth: true },
            children: [
                {
                  path: 'settings',
                },
              ],
        },
        {
            path: "/logout",
            component: Logout,
            name: "logout",
        },
        {
            path: "/register",
            component: Register,
            name: "register",
        },
        {
            path: "/404",
            component: NotFound,
            name: "404",
        },
        {
            path: "*",
            redirect: '/404',
        }
    ],
    mode: "history"
});

router.beforeEach((to, from, next) => {

    if (to.matched.some(route => route.meta.requiresAuth) && !store.state.isLoggedIn) {
        next({ name: 'login' })
        return
    }

    if(to.path === '/login' && store.state.isLoggedIn) {
        next({ name: 'home' })
        return
    }

    next()
});

export default router;