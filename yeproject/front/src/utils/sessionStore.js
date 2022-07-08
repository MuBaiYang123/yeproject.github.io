//缓存登录认证
const toKenKey = 'token_yeproject';

//获取token
const getToken = ()=>{
    return window.sessionStorage.getItem(toKenKey);
}

//保存token
const setToken = token =>{
    return window.sessionStorage.setItem(toKenKey,token);
}

//移除token
const removeToken = ()=>{
    return window.sessionStorage.removeItem(toKenKey);
}

const sessionStore = {
    get:getToken,
    set:setToken,
    remove:removeToken
}
export default sessionStore
