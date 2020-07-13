import Vue from 'vue'
import Vuex from 'vuex'
// import vuetify from '../plugins/vuetify'
// import { getJsonData } from '../api'
import Cookies from 'js-cookie'

Vue.use(Vuex)

let cookie_token = Cookies.get('jalen_token')

export default new Vuex.Store({
  state: {
    user: null,
    token: cookie_token ? cookie_token : null,
    drawer: sessionStorage.getItem('drawer') === '1',
    dark: sessionStorage.getItem('current_theme') === 'dark',
    dark_code_style: 'monokai-sublime',
    light_code_style: 'androidstudio',
    current_community: sessionStorage.getItem('current_community') ? JSON.parse(
      sessionStorage.getItem('current_community')) : null,
    snackbar: {
      show: false,
      message: '',
      color: 'primary',
      timeout: 5000,
    },
    dialog: {
      show: false,
      title: '',
      text: '',
      sign: '',
    },
  },

  getters: {
    // 这里是get方法
    dark_code_style: state => state.dark_code_style,
    light_code_style: state => state.light_code_style,
    user: state => state.user,
    drawer: state => state.drawer,
    dark: state => state.dark,
    current_community: state => state.current_community,
    snackbar: state => state.snackbar,
    dialog: state => state.dialog,
    token: state => state.token,
    current_project: state => state.current_project,
  },

  mutations: {
    // 这里是同步 set 方法，通过 commit 方式触发 this.$store.commit('setToken', token)
    setUser (state, data) {
      state.user = data
    },
    setDrawer (state, data) {
      state.drawer = data
    },
    setDarkTheme (state, data) {
      state.dark = data
    },
    setSnackbar (state, data) {
      state.snackbar = data
    },
    switchSnackbar (state, bool) {
      state.snackbar.show = bool
    },
    setDialog (state, data) {
      state.dialog = data
    },
    setToken (state, token) {
      if (!token) {
        Cookies.remove('jalen_token')
      }else {
        Cookies.set('jalen_token', token)
      }
      state.token = token
    }
  },

  actions: {
    // 这里是异步方法，可以在组件中使用 this.$store.dispatch('actionSetUserInfo', user_info)
    // 分发调用 mutation 方法进行 set
    actionSetUser ({ commit }, data) {
      commit('setUser', data)
    },
    actionSetDrawer ({ commit }, data) {
      commit('setDrawer', data)
      let sign = '1'
      if (!data) {
        sign = '0'
      }
      sessionStorage.setItem('drawer', sign)
    },
    actionSetDarkTheme ({ commit }, data) {
      commit('setDarkTheme', data)
      let theme = data ? 'dark' : 'light'
      sessionStorage.setItem('current_theme', theme)
    },
    actionSetToken ({ commit }, data) {
      commit('setToken', data)
    }
  },

  modules: {
    // 分组模块，当 state 参数过多可以单独分组建立文件，从这里引入
  },
})
