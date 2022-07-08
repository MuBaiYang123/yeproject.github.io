import request from "../utils/request"

const topics = {
    topicsList(param) {
        return request({
            url:'/topics-list',
            method:'get',
            data:param
        })
    }

}
export default topics