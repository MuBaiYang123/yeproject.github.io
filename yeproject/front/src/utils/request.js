import axios from 'axios'
import sessionStore from './sessionStore'
import qs from 'qs'
import tools from './tools'

//利用自定义配置新建一个axios实例
const service = axios.create({
    baseURL: import.meta.env.VITE_BASE_URL,
    timeout: 60000
});

//请求拦截器
service.interceptors.request.use(config => {
        let data = {};
        let paramData = config.data || {};
        data.parameters = JSON.stringify(paramData)
        const token = sessionStore.get()
        data.access_system =
            import.meta.env.VITE_ACCESS_SYSTEM
        if (token) {
            //让每个请求携带token,
            data.access_token = token
        }
        if ('post' === config.method || 'put' === config.method) {
            config.data = data;
        }
        if ('get' === config.method || 'delete' === config.method) {
            config.params = config.params || data
            config.paramsSerializer = (params) => {
                return qs.stringify(params, {
                    arrayFormat: 'repeat'
                })
            }
            //删除data中的数据
            config.data = ''

        }
        return config
    },
    error => {
        return Promise.reject()
    }
)

//响应拦截器
service.interceptors.response.use(response => {
        if (response.status === 200) {
            if (response.data.access_status.code === 40000) {
                //删除
                tools.logout()
                setTimeout(() => {
                    window.location.href = '/login'
                })
            } else {
                return Promise.resolve(response.data)
            }
        } else {

        }
    },
    error => {
        const {
            response
        } = error
        if (response) {
            return Promise.reject(response.data)
        } else {
            //取消请求
        }

    })

export default service