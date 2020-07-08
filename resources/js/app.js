// eslint-disable-next-line no-undef
require('./bootstrap')
import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import router from './router'
import store from './store'
import './utils/guard'

Vue.config.productionTip = false
Vue.prototype.$VM = new Vue()

export default new Vue({
  vuetify,
  router,
  store,
  render: h => h(App)
})
.$mount('#app')
