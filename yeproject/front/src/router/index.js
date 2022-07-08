import {
    createRouter,
    createWebHistory
} from 'vue-router'
import axios from 'axios'
import sessionStore from '../utils/sessionStore'
import store from '../store'
import topics from './topics'
const routerHistory = createWebHistory('') //这里

const routes = [{
        path: '/',
        redirect: "/dashboard"
    }, {
        path: "/dashboard",
        meta: {
            auth: false
        },
        component: () => import("../views/layout.vue"),
    }, {
        path: '/login',
        meta: {
            auth: false
        },
        component: () => import('../views/login/login.vue')
    },
    {
        path: '/register',
        meta: {
            auth: false
        },
        component: () => import('../views/register.vue')
    },
    ...topics,
    
]
const router = createRouter({
    history: routerHistory,
    routes
})

router.beforeEach(async (to, from, next) => {
    let token = sessionStore.get();
    if (token == null && to.path !== "/login") {
        if (to.meta.auth) {
            next("/login")
        } else {
            next()
        }
    } else {
        next()
    }
})



export default router