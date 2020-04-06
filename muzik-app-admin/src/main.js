import Vue from 'vue'
import App from './App.vue'
import router from './router';
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import CKEditor from 'ckeditor4-vue'

// import CKEditor from '@ckeditor/ckeditor5-vue';


Vue.use(Loading)
Vue.use(CKEditor)
Vue.config.productionTip = false;

new Vue({
router,
  render: h => h(App),
}).$mount('#app')
