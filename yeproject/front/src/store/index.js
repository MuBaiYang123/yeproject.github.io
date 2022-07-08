import{
    createStore,
} from 'vuex';
import app from './modules/app';
import session from './modules/session'
import db from './modules/db'

export default createStore({
    modules:{
        session,
        app,
        db

    },
    state:{
        tagList:[],
        collapse:false
    },
    mutations:{ 
        
    }
});