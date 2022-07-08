<template>

    <div class="con">

        <div id="formContainer" class="dwo">
            <div class="formLeft">
                <img src="../../assets/image/avatar.png">
            </div>
            <div class="formRight">
                <!-- Login form -->
                <form id="login">
                    <header>
                        <h1>欢迎回来</h1>
                        <p>请先登录</p>
                    </header>
                    <section>
                        <label>
                            <p>用户名</p>
                            <input type="text" placeholder=" " v-model="parameters.login_id" />
                            <div class="border"></div>
                        </label>
                        <label>
                            <p>密码</p>
                            <input type="password" placeholder=" " v-model="parameters.password" />
                            <div class="border"></div>
                        </label>
                    </section>
                </form>
            </div>
            <div class="button-label">
                <el-button type="primary" @click="submit()">登 录</el-button>
            </div>
            <div class="footer">
                <a href="">忘记密码？</a>
                <a href="./register" style="margin-left: -100px;">新用户？</a>
            </div>
        </div>
    </div>


</template>

<script>

import { useRouter } from "vue-router";
import { useStore } from "vuex";
import { reactive, ref, toRefs } from "vue";
import api from "../../api"
import sessionStore from "../../utils/sessionStore";
import { ElMessage } from "element-plus";
export default {
    setup() {
        const store = useStore();
        const router = useRouter()
        const formData = reactive({
            parameters: {
                login_id: "shh.ye+1@startiasoft.com",
                password: "123456huan"
            },
        })
        const errors = reactive({
            info: {}
        });
        const submit = () => {
            api.login.login(formData.parameters).then((res) => {
                if (res.access_status.code === store.state.app.failCode && res.result.errors) {
                    errors.info = res.result.errors
                } else if (res.access_status.code === store.state.app.successCode) {
                    sessionStore.set(res.result.token)
                    store.state.session.user_info = res.result.user_info
                    store.state.session.login_state = true
                    router.push("/");
                } else {
                    ElMessage.error(res.access_status.message)
                }
            })
        }

        return {
            ...toRefs(formData),
            submit,

        }
    }
}
</script>

<style scoped>
.con {
    /* border: 1px solid red; */
    width: 100vw;
    height: 100vh;
    display: flex;
    background: url("../../assets/image/v2-2fa9649980c182f1275153d167c36435_1440w.jpg") center/cover fixed;
    justify-content: center;
    align-items: center;
}

#formContainer {
    display: flex;
    transition: 0.2s ease;
    height: 342.5px;
    transition-delay: 0.3s;
}

#formContainer.toggle {
    height: 480px;
    transition-delay: 0s;
}

.button-label {
    position: relative;
    margin-top: 290px;
    margin-left: -325px;
}

.button-label .el-button {
    border: 1px solid #028e80;
    width: 300px;
    height: 40px;
    background-color: #028e80;
    font-size: 16px !important;
    color: white;
    font-weight: bold;
    opacity: 0.7;
}

.button-label .el-button:hover {
    opacity: 0.9;
}
.footer a{
    text-decoration: none;
    position: absolute;
    margin-top: 335px;
    margin-left: -270px;
    color: white;
    opacity: 0.6;
    font-size: 12px;
}
.footer a:hover{
    opacity: 0.8;
}

.formLeft {
    background: #fff;
    border-radius: 5px 0 0 5px;
    padding: 0 35px;
    box-sizing: border-box;
    display: flex;
    align-items: center;
    height: 360px;
}

.formLeft img {
    display: block;
    width: 72px;
    border-radius: 50%;
    box-shadow: 0 5px 5px rgba(0, 0, 0, 0.2);
}

.formRight {
    /* border: 1px solid red; */
    height: 360px;
    position: relative;
    overflow: hidden;
    border-radius: 0 5px 5px 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.formRight:before {
    content: "";
    position: absolute;
    top: -10px;
    left: -10px;
    width: calc(100% + 20px);
    height: calc(100% + 20px);
    opacity: 0.3;
    box-shadow: inset 0 0 0 1000px rgba(0, 0, 0, 0.5);
    filter: blur(5px);
}

.formRight form {
    position: relative;
    width: 350px;
    padding: 25px;
    box-sizing: border-box;
    white-space: nowrap;
    overflow: hidden;
}

.formRight form header {
    color: #fff;
    text-align: center;
    margin-bottom: 15px;
}

.formRight form header h1 {
    margin: 0;
    font-weight: 400;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.formRight form header p {
    margin: 5px 0 0;
    opacity: 0.5;
    font-size: 14px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.formRight form section label {
    display: block;
    margin-bottom: 15px;
    position: relative;
}

.formRight form section label p {
    color: #fff;
    margin: 0 0 10px 0;
    font-weight: 600;
    font-size: 12px;
    opacity: 0.5;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.formRight form section label input {
    width: 100%;
    display: block;
    border: none;
    background: transparent;
    color: #fff;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding: 0 0 10px;
    box-sizing: border-box;
    font-weight: 600;
}

.formRight form section label input:focus~.border {
    transform: scale(1, 1);
}

.formRight form section label input:not(:-moz-placeholder-shown)~.border {
    transform: scale(1, 1);
}

.formRight form section label input:not(:-ms-input-placeholder)~.border {
    transform: scale(1, 1);
}

.formRight form section label input:not(:placeholder-shown)~.border {
    transform: scale(1, 1);
}

.formRight form section label .border {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background: #fff;
    transform: scale(0, 1);
    transition: 0.2s ease;
}

.formRight form section label:last-child {
    margin-bottom: 0;
}

.formRight form section button {
    background: #00897B;
    border: none;
    width: 100%;
    padding: 10px 0;
    font-weight: 600;
    color: #fff;
    cursor: pointer;
}

.formRight form section button:hover {
    background: #007f72;
}


.formRight form.otherForm {
    top: 0;
    left: 0;
    position: absolute;
    background: #fff;
    height: 100%;
    z-index: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    width: 0;
    padding: 25px 0;
    transition: 0.2s ease;
    transition-delay: 0.2s;
    border-left: 1px solid rgba(0, 0, 0, 0.1);
}

.formRight form.otherForm header {
    color: #000;
    opacity: 0;
    transition: 0.2s ease;
    transition-delay: 0s;
}

.formRight form.otherForm p {
    color: #000;
}

.formRight form.otherForm section {
    opacity: 0;
    transition: 0.2s ease;
    transition-delay: 0s;
}

.formRight form.otherForm footer {
    border-top-color: rgba(0, 0, 0, 0.1);
    opacity: 0;
}

.formRight form.otherForm footer button {
    color: #000;
}

.formRight form.otherForm input {
    border-color: rgba(0, 0, 0, 0.1);
    color: #000;
}

.formRight form.otherForm .border {
    background: #000;
}

.formRight form.otherForm.toggle {
    width: 100%;
    padding: 25px;
    transition-delay: 0s;
}

.formRight form.otherForm.toggle header,
.formRight form.otherForm.toggle section,
.formRight form.otherForm.toggle footer {
    opacity: 1;
    transition-delay: 0.3s;
}
</style>