import Vue from 'vue'
import App from './App.vue'
import router from './router'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'

Vue.use(Loading)
Vue.config.productionTip = false

// filter for date/time
Vue.filter('formatDate', function(value) {
  if ( !value ) {
      return ''
  } else {
    let d = new Date(value)
    let ampm = d.getHours() >= 12 ? 'PM' : 'AM'
    let hour = d.getHours() % 12 ? d.getHours() % 12 : 12
    return d.toDateString() + '; ' + hour.toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0') + ' ' + ampm
  }
})

new Vue({
  router,
  render: h => h(App),
}).$mount('#app')
