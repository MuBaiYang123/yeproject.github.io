<template>
    <div class="body-con">
        <div class="header">
            <v-header />
        </div>
        <div class="con">
            <div class="con-nav">
                <h4>新建帖子</h4>
            </div>

        </div>
    </div>
</template>

<script>
import vHeader from '../../components/Header.vue'
import { reactive,ref,toRefs } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import api from '../../api'
export default {
    components: {
        vHeader
    },
    setup(){
        const store = useStore()
        const router = useRouter()
        const paramData = reactive({
            parameters:{

            }
        })

        return {
            ...toRefs(paramData)
        }
    }
}
</script>

<style scoped>
</style>