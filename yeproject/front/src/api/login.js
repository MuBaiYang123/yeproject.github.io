import request from "../utils/request"

const login = {
    
    //登录
    login(data) {
        return request({
            url: '/login',
            method: 'post',
            data
        })
    },
    //注册
    registers(data){
        return request({
            url:'/user',
            method:'post',
            data
        })
    },
    //退出登录
    logout(){
        return request({
            url:'/logout',
            method:'post',
        })
    }
}
export default login