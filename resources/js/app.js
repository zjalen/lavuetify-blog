// require('./bootstrap');
import Vue from 'vue'
import App from './App.vue'
// import vuetify from './plugins/vuetify'
// import router from './router'
// import store from './store'

// import { mockXHR } from './mock'
// // 通过环境变量来判断是否需要加载启用 mockjs
// if (process.env.VUE_APP_MOCK === "1") {
//   mockXHR()
// }
//
// Vue.config.productionTip = false


// Vue.component('top-header', require('./components/layouts/TopHeader').default)
// Vue.component('bottom-footer', require('./components/layouts/BottomFooter').default)
// Vue.component('go-to-top', require('./components/layouts/GoToTop').default)
// Vue.component('layout', require('./components/layouts/Layout').default)
//
// Vue.component('articles', require('./views/Articles').default)

export default new Vue({
  // vuetify,
  // router,
  // store,
  render: h => h(App)
})
// // .$mount('#app')

// // app.js
// import Vue from "vue";
// import App from './App.vue'
//
// export default new Vue({
//     render: h => h(App)
//   });
