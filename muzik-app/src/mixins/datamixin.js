import router from './../router';
const axios = require('axios').default;

export const DataMixin = {
    methods: {
        postMethod(payload) {
            return axios.post('http://localhost/vue-muzik-app/api/vue-app-api.php', payload);
        },

        loggedIn(){
            return !localStorage.getItem('user') ? true : false
        },

        logout(){
            localStorage.clear()
            router.push({name: 'home'})
        }
    }

}