import request from "../utils/request"

const login = {
    login(data) {
        return request({
            url: '/login',
            method: 'post',
            data
        })
    }
}
export default login