<template>
    <div class="nav">
        <div class="nav-logo">
            <a href="">YeBBS</a>
        </div>
        <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" :ellipsis="false"
            @select="handleSelect">
            <div class="flex-grow" />
            <el-menu-item index="1">话题</el-menu-item>
            <el-menu-item index="2">分享</el-menu-item>
            <el-menu-item index="3">教程</el-menu-item>
            <el-menu-item index="4">问答</el-menu-item>
            <el-menu-item index="5">公告</el-menu-item>
        </el-menu>
        <div class="nav-item">
            <div v-if="!showname">
                <a href="/login">登录</a>
                <a href="./register" style="margin-left: 20%; margin-top: -19px;">注册</a>
            </div>
            <div v-if="showname" style="margin-top: -4px;">
                <el-dropdown trigger="click" @command="handleCommand">
                    <span>
                        <el-icon style="padding-right: 5px;">
                            <UserFilled />
                        </el-icon>{{ username }}
                    </span>
                    <template #dropdown>
                        <el-dropdown-menu>
                            <el-dropdown-item command="profile">个人中心</el-dropdown-item>
                            <el-dropdown-item command="logout">退出登录</el-dropdown-item>
                        </el-dropdown-menu>
                    </template>
                </el-dropdown>
            </div>

        </div>

    </div>
</template>

<script>
import { reactive, ref,computed } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import api from '../api'
import tools from "../utils/tools";
export default {
    setup() {
        const store = useStore()
        const router = useRouter()
        let username = computed(() => store.state.session.user_info.user_name)
        let showname = computed(() =>store.state.session.login_state)
        const handleCommand = (command) => {
            if (command == 'logout') {
                api.login.logout().then((res) => {
                    if (res.access_status.code == store.state.app.successCode) {
                        router.push('/')
                        tools.logout()
                    }
                })
            } else if (command == 'profile') {

            }
        }
        return {
            username,
            showname,
            handleCommand
        }
    }
}
</script>

<style>
.nav {
    /* border: 1px solid red; */
    width: 102.7% !important;
    background-color: white;
    margin-top: -8px;
    height: 40px;
    margin-left: -27px;
    position: relative;
    -webkit-box-shadow: 0px 2px 2px rgb(239, 250, 233);
    -moz-box-shadow: 0px 2px 2px rgb(239, 250, 233);
    box-shadow: 0px 1px 5px rgb(172, 167, 167);
}

.nav-logo {
    position: absolute;
    margin-top: 7px;
    margin-left: 220px;
    font-size: 18px;
}

.nav-logo a {
    text-decoration: none;
    color: black;
}

.el-menu {
    /* border: 10px solid greenyellow; */
    background-color: white;
    height: 40px;
    margin-left: 300px;
    border-bottom: 0px;
}

.el-menu-item {
    font-size: 14px;
}

.nav-item {
    /* border: 1px solid red; */
    height: 30px;
    width: 15%;
    position: absolute;
    margin-left: 74%;
    margin-top: -25px;
}

.nav-item a {
    /* border: 1px solid yellow; */
    display: block;
    width: 20%;
    text-decoration: none;
    color: black;
    font-size: 14px;
    margin-top: -4px;
    /* position: absolute;
    margin-top: -30px;
    margin-left: 80%; */
}

.nav-item a:hover {
    text-decoration: solid;
    color: blue;
}
</style>