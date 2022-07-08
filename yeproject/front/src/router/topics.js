//话题路由
const topics = [
    {
        path:"/topics/create_topic",
        meta:{
            auth:true
        },
        component:()=>import("../views/topics/create_topic.vue")
    },

]

export default topics