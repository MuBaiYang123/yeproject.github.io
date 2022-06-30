import axios from 'axios'
// import {Message} from 'element-ui'
// import { config } from 'process'

const token = '你自己的token'

//利用自定义配置新建一个axios实例
const service = axios.create({
    baseURL:'http://yeproject.com/api/v1/',
    timeout:80000
})

//请求拦截器
// service.interceptors.request.use(
//     config=>{
//         if(token){
//           //让每个请求携带token,
//           config.headers.Authorization = token  
//         }
//         return config
//     },
//     error=>{
//         Promise.reject(error)
//     }
// )

// //响应拦截器
// service.interceptors.response.use(
//     response=>response,
//     error=>{
//         console.log('err'+error)
//         return Promise.reject(error)
//     }
// )

export default service
