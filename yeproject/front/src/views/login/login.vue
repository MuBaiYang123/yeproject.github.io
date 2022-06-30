<template>
    <el-form :model="formData" ref="vForm" :rules="rules" label-position="left" label-width="150px" size="default"
        @submit.prevent>
        <el-form-item label="用户名" prop="user_name" class="required label-right-align">
            <el-input v-model="formData.username" type="text" clearable></el-input>
        </el-form-item>
        <el-form-item label="密码" prop="password" class="required label-right-align">
            <el-input v-model="formData.password" type="text" clearable></el-input>
        </el-form-item>
        <div class="static-content-item">
            <el-button type="primary" @click="login()">button</el-button>
        </div>
    </el-form>

</template>

<script>
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import { reactive, ref } from "vue";
import api from "../../api"
export default {
    setup() {
        const formData = reactive({
            username: "",
            password: ""
        })
        const login =()=> {
            // error.info = {}
            const data = {
                user_name:'yezi',
                password:'yezi'
            }
            api.login.login(data).then((res)=>{
                console.log(res);
            })
            .catch(error=>{
                this.$message.error('获取失败！');
            })
            
        }
        return {
            formData,
            login
        }
    }
}

</script>
<style scoped>
.static-content-item {
    margin-left: 20%;
}
</style>