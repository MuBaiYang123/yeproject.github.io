import request from '../utils/request'

const resource = {
    getResourcesList(){
        return request({
            url:'/resources-list',
            method:'get'
        })
    }
};
export default resource;