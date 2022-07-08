<template>
    <div class="body-con">
        <div class="con">
            <div class="con-nav">
                <h2 style="padding-top:10px">用户注册</h2>
            </div>
            <div class="con-form">
                <el-form :model="parameters">
                    <el-form-item prop="login_id">
                        <div class="form-icon">
                            <el-icon>
                                <Message />
                            </el-icon>
                        </div>
                        <el-input v-model="parameters.login_id" placeholder="请输入邮箱……"></el-input>
                    </el-form-item>
                    <el-form-item prop="password">
                        <div class="form-icon">
                            <el-icon>
                                <Lock />
                            </el-icon>
                        </div>
                        <el-input type="password" v-model="parameters.password" placeholder="请输入密码……"></el-input>
                    </el-form-item>
                    <el-form-item prop="confirm_password">
                        <div class="form-icon">
                            <el-icon>
                                <Lock />
                            </el-icon>
                        </div>
                        <el-input type="password" v-model="parameters.confirm_password" placeholder="请确认密码……">
                        </el-input>
                    </el-form-item>
                    <el-form-item prop="user_name">
                        <div class="form-icon">
                            <el-icon>
                                <User />
                            </el-icon>
                        </div>
                        <el-input v-model="parameters.user_name" placeholder="请输入用户名……"></el-input>
                    </el-form-item>
                    <el-form-item prop="tel">
                        <div class="form-icon">
                            <el-icon>
                                <Phone />
                            </el-icon>
                        </div>
                        <el-input v-model="parameters.tel" placeholder="请输入电话……"></el-input>
                    </el-form-item>
                    <div class="form-button">
                        <el-button type="primary" @click="submitFrom()">
                            注册
                        </el-button>
                    </div>
                </el-form>
                <div class="footer-button">
                    <span>已有账号？去</span><a href="./login">登录</a>
                    <a href="./" style="margin-left:100px">回首页</a>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import { ref, reactive, toRefs } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import { ElMessage } from 'element-plus'
import api from '../api'
export default {
    setup() {
        const store = useStore()
        const router = useRouter()
        const paramData = reactive({
            parameters: {
                login_id: "",
                password: "",
                confirm_password: "",
                user_name: "",
                tel: ""
            }
        });
        const errors = reactive({
            info: {}
        })
        const submitFrom = () => {
            api.login.registers(paramData.parameters).then((res) => {
                if (res.access_status.code === store.state.app.failCode && res.result.errors) {
                    errors.info = res.result.errors
                } else if (res.access_status.code === store.state.app.successCode) {
                    router.push("/login");
                } else {
                    ElMessage.error(res.access_status.message)
                }
            })
        }

        return {
            ...toRefs(paramData),
            errors,
            submitFrom

        }
    }
}
</script>

<style scoped >
.body-con {
    width: 100%;
    height: 100%;
    display: flex;
    background: url("../assets/image/v2-2fa9649980c182f1275153d167c36435_1440w.jpg") center/cover fixed;
    justify-content: center;
    align-items: center;

}

.con {
    /* border: 1px solid red; */
    height: 500px;
    width: 550px;
    margin: 240px auto;
    background-color: rgb(236, 240, 242);
    border-radius: 5px;
    box-shadow: 0 2px 2px ghostwhite;
    opacity: 0.7;
}

.con-nav {
    /* border: 1px solid green; */
    border-bottom: 1px solid gainsboro;
    box-shadow: 0 1px 1px ghostwhite;
    height: 10%;
    text-align: center;
}

.con-form {
    /* border: 1px solid yellow; */
    height: 80%;
    margin-top: 40px;
}

.con-form .el-form {
    height: 100%;
    /* border: 1px solid red; */
}

.con-form .el-form .el-form-item {
    /* border: 1px solid green; */
    width: 80%;
    margin-left: 10%;
    margin-top: 30px;
}

.con-form .el-form .el-form-item .form-icon {
    /* border: 1px solid rebeccapurple; */
    background-color: ghostwhite;
    float: left;
    width: 8%;
    text-align: center;
    opacity: 0.7;
}

.con-form .el-form .el-form-item .el-input {
    /* border: 1px solid gold; */
    width: 90%;
}

.form-button {
    /* border: 1px solid cadetblue; */
}

.form-button .el-button {
    /* border: 1px solid cadetblue; */
    width: 80%;
    height: 40px;
    margin-left: 10%;
    margin-top: 15px;
    background-color: green;
    box-shadow: 0 1px 1px cadetblue;
}

.footer-button {
    /* border: 1px solid red; */
    margin-top: -35px;
    margin-left: 170px;
    font-size: 14px;
}

.footer-button a {
    text-decoration: none;
    color: black;

}

.footer-button a:hover {
    color: green;
    text-decoration: dotted;
}
</style>