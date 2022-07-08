<template>
    <div class="common-layout">
        <el-container>
            <el-header>
                <v-header />
            </el-header>
            <el-container>
                <el-aside>
                    <div class="resources-recommend">
                        <div class="resources-nav">
                            <p>资源推荐</p>
                        </div>
                        <ul v-for="item in resourceList">
                            <li>
                                <span class="span-label">
                                    <el-icon>
                                        <Flag />
                                    </el-icon>
                                </span>
                                <el-link :underline="false" :href="item.resource_path" target="_blank">
                                    {{ item.resource_name }}</el-link>

                            </li>
                        </ul>
                        <div>

                        </div>
                    </div>
                </el-aside>
                <el-main>
                    <div class="topics-list">
                        <div class="list-nav">
                            <p>最新帖子</p>
                        </div>
                        <ul v-for="item in topicList">
                            <li>
                                <div class="list-links">
                                    <el-link :underline="false">{{ item.topic_title }}</el-link>
                                </div>
                                <div class="list-labels">
                                    <el-link :underline="false">
                                        <el-icon style="margin-right: 5px;">
                                            <FolderRemove />
                                        </el-icon><span>{{ item.category_name }}</span>
                                    </el-link>
                                    <el-link :underline="false">
                                        <el-icon style="margin-right: 5px;">
                                            <UserFilled />
                                        </el-icon><span>{{ item.user_name }}</span>
                                    </el-link>
                                    <el-link :underline="false" type="info">
                                        <el-icon style="margin-right: 5px;">
                                            <Clock />
                                        </el-icon><span>{{ item.create_date_diff }}</span>
                                    </el-link>
                                </div>
                            </li>
                        </ul>
                        <div class="page-nav">
                            <el-pagination background layout="prev, pager, next" :total="topicTotal" hide-on-single-page
                                :page-size="query.limit" small @current-change="handleCurrentChange" />
                        </div>
                    </div>
                    <div class="topic_button">
                        <el-button @click="createTopic()">
                            <el-icon>
                                <EditPen />
                            </el-icon><span style="margin-left: 5px;">新建帖子</span>
                        </el-button>
                    </div>
                    <div class="notics">
                        <div class="notics-nav">
                            <p>最新公告</p>
                        </div>
                        <div class="notics-content">

                        </div>
                        <!-- <ul>
                            <li></li>
                        </ul> -->
                    </div>
                </el-main>
            </el-container>

            <el-footer>
                <v-footer />
            </el-footer>
        </el-container>
    </div>
</template>

<script>
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { reactive, ref, toRefs } from "vue";
import vFooter from "../components/Footer.vue"
import vHeader from "../components/Header.vue"
import api from "../api"
import { ElMessage } from "element-plus";
export default {
    components: {
        vFooter,
        vHeader
    },
    setup() {
        const store = useStore();
        const router = useRouter()
        const params = reactive({
            topicList: [],
            topicTotal: 0,
            resourceList: []
        });
        const login_status = store.state.session.login_state;
        const query = reactive({
            page: store.state.db.page,
            limit: store.state.db.limit
        })
        const error = reactive({
            info: {}
        })
        //获取最新十条资源数据
        const getResourcesList = () => {
            api.resource.getResourcesList().then((res) => {
                if (res.access_status.code === store.state.app.failCode && res.result.errors) {
                    error.info = res.result.errors
                } else if (res.access_status.code === store.state.app.successCode) {
                    params.resourceList = res.result.resources_list
                } else {
                    ElMessage.error(res.access_status.message)
                }
            })
        }
        getResourcesList();
        //获取话题列表
        const getTopicsList = (val = 1) => {
            const data = {
                page: val,
                limit: query.limit
            };
            api.topic.topicsList(data).then((res) => {
                if (res.access_status.code === store.state.app.failCode && res.result.errors) {
                    error.info = res.result.errors
                } else if (res.access_status.code === store.state.app.successCode) {
                    params.topicList = res.result.topic_list
                    params.topicTotal = res.result.topic_total
                } else {
                    ElMessage.error(res.access_status.message)
                }
            })
        }
        getTopicsList();
        //分页，获取当前点击页码
        const handleCurrentChange = (val) => {
            getTopicsList(val);
        }

        //新建帖子
        const createTopic = ()=>{
            if(login_status){
                router.push('/topics/create_topic')
            }else{
                router.push('/login')
            }
        }

        return {
            ...toRefs(params),
            getTopicsList,
            query,
            handleCurrentChange,
            getResourcesList,
            createTopic
        }
    }


}
</script>

<style>
/* .common-layout{
    height: 100%;
    width: 100vw;
    display: flex;
    background: url("../assets/image/v2-2fa9649980c182f1275153d167c36435_1440w.jpg") center/cover fixed;
} */
.el-aside {
    /* border: 1px solid red; */
    height: 1000px;
}

.topic_button {
    border: 1px solid red;
    border: 1px solid gainsboro;
    height: 70px;
    width: 20%;
    margin-top: -62%;
    margin-left: 74%;
}

.topic_button .el-button {
    /* border: 1px solid yellow; */
    width: 80%;
    height: 50%;
    margin: 6% 11%;
    background-color: rgb(21, 115, 71);
    color: white;
    font-weight: bold;
    font-size: 14px;
}
.notics{
    border: 1px solid gainsboro;
    width: 20%;
    height: 400px;
    margin-left: 74%;
    margin-top: 5%;
}
.notics-nav{
    /* border: 1px solid yellow; */
      width: 99%;
      margin: 0 auto;
      border-bottom: 1px solid gainsboro;
      box-shadow: 0 2px 2px ghostwhite
}
.notics-nav p{
    text-align: center;
  
}
.resources-recommend {
    border: 1px solid gainsboro;
    height: 60%;
    width: 80%;
    margin-left: 10%;
    margin-top: 8%;
    opacity: 0.8;
}

.resources-nav {
    /* border: 1px solid red; */
    text-align: center;
    height: 45px;
    border-bottom: 1px solid gainsboro;
    box-shadow: 0 2px 2px royalblue;
}

.resources-recommend ul li {
    /* border: 1px solid green; */
    list-style: none;
    margin-top: 20px;
    margin-left: -30px;
    padding-bottom: 10px;
    border-bottom: 1px solid gainsboro;
    color: black;
}

.span-label {
    /* border: 1px solid red; */
    display: block;
    float: left;
    margin-right: 10px;
    height: 20px;
    width: 20px;
    text-align: center;
    background-color: black;
    border-radius: 10px;
    color: red;
    margin-top: 3px;

}

.span-label .el-icon {
    margin-left: 3px;
    margin-top: 2px;
}

.el-main {
    /* border: 1px solid green; */
    margin-left: 7%;
}

.topics-list {
    border: 1px solid gainsboro;
    height: 99%;
    width: 65%;
}

.list-nav {
    /* border: 1px solid rebeccapurple; */
    margin-left: 20px;
    border-bottom: 1px solid gainsboro;
}

.list-nav p {
    display: block;
    /* border: 1px solid red; */
    width: 80px;
    height: 40px;
    text-align: center;
    line-height: 40px;
    background-color: #409EFF;
    border-radius: 5px;
    color: white;
}

.topics-list ul {
    /* border: 1px solid red; */

}


.topics-list ul li {
    list-style: none;
    /* border: 1px solid blue; */
    height: 60px;
    margin-left: -20px;
    border-bottom: 1px solid gainsboro;

}

.list-links {
    /* border: 1px solid green; */
    margin-bottom: 6px;
}

.el-link {
    font-size: 16px;
}

.list-labels {
    /* border: 1px solid gold; */
    font-size: 13px;
}

.list-labels .el-link {
    font-size: 13px;

}

.el-icon {
    margin-left: 10px;
}

.page-nav {
    /* border: 1px solid red; */
    position: absolute;
    margin-left: 70px;
}
</style>