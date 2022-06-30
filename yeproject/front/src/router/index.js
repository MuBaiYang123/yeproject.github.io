import{
    createRouter,
    createWebHistory
} from 'vue-router'
import axios from 'axios'
const routerHistory = createWebHistory('') //这里

const routes = [
    {
        path:'/',
    },{
        path:'/foo',
        component:()=>import('../views/foo.vue')

    },{
        path:'/bar',
        component:()=>import('../views/bar.vue')
    },{

        path:'/login',
        component:()=>import('../views/login/login.vue')
    }
]
const router = createRouter({
    history:routerHistory,
    routes
})

export default router