import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        session: localStorage.getItem('user')
    },

    mutations: {
        checkSessionStatus: state => state.session = localStorage.getItem('user')
    }
});