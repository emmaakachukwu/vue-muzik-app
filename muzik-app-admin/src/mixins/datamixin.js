const axios = require('axios').default;

export const DataMixin = {

    methods: {
        postMethod(payload) {
            return axios.post('http://localhost/vue-muzik-app/api/vue-app-api.php', payload);
        }
    }

}