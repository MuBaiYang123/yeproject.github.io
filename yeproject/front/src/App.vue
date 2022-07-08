<template>
<router-view></router-view>
</template>

<script>
import { provide,onMounted,onBeforeUnmount } from "vue";
import {useStore} from 'vuex'
export default{
    setup(){
        const store = useStore()
        onMounted(()=>{
            document.title = 'yeBBS'
        })
        const setMetaTitle =(title)=>{
            document.title = title
        }
        provide('setMetaTitle',setMetaTitle)

        //页面刷新，保持用户信息不变
        if(sessionStorage.getItem('temporary')){
            store.state.session = JSON.parse(sessionStorage.getItem('temporary'))
            //删除
            sessionStorage.removeItem('temporary')
        }
        window.addEventListener('beforeunload',()=>{
            sessionStorage.setItem('temporary',JSON.stringify(store.state.session))
        })
    }
}
</script>



<style>

</style>
