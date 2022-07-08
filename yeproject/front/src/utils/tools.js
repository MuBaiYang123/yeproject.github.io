import store from '../store/index'
import sessionStore from './sessionStore'
// import {ELMessage} from 'element-plus'

//退出登录
const logout = ()=>{
    sessionStore.remove()
    store.state.session.user_info = {}
    store.state.session.login_state=false
}
const tools = {
    logout
}

export default tools