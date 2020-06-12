import Vue from 'vue'
import Vuex from 'vuex'
import vuetify from '../plugins/vuetify'
import { getJsonData } from '../api'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        // 这里放全局参数
        mounting: false,
        loading: true,
        menus: [],
        codeStyle: 'github',
        dark: false,

        // mock 数据
        categories: null,
        pages: null,
        topics: null,
        articles: null,
    },

    mutations: {
        // 这里是同步 set 方法，通过 commit 方式触发 this.$store.commit('setToken', token)
        setMounting(state, status) {
            state.mounting = status
        },
        setLoading(state, status) {
            state.loading = status
        },
        setDarkTheme(state, data) {
            state.dark = data
        },
        setMenus(state, menus) {
            state.menus = menus
        },
        setCategories(state, categories) {
            state.categories = categories
        },
        setPages(state, pages) {
            state.pages = pages
        },
        setTopics(state, topics) {
            state.topics = topics
        },
        setArticles(state, articles) {
            state.articles = articles
        },
        setJsonData(state, params) {
            state[params.name] = params.value
        }
    },

    getters: {
        // 这里是get方法
        mounting: state => state.mounting,
        loading: state => state.loading,
        dark: state => sessionStorage.getItem('current_theme') === 'dark' ? true : state.dark,
        menus: state => state.menus,
        categories: state => state.categories,
        topics: state => state.topics,
        pages: state => state.pages,
        articles: state => state.articles,
        codeStyle: () => vuetify.preset.theme.dark ? 'androidstudio' : 'github',
    },

    actions: {
        // 这里是异步方法，可以在组件中使用 this.$store.dispatch('actionSetUserInfo', user_info) 分发调用 mutation 方法进行 set
        actionSetMounting({ commit }, status) {
            commit('setMounting', status)
        },
        actionSetLoading({ commit }, status) {
            commit('setLoading', status)
        },
        actionSetMenus({ commit }, menus) {
            commit('setMenus', menus)
        },
        actionSetCategories({ commit }, data) {
            commit('setMenus', data)
        },
        actionSetPages({ commit }, data) {
            commit('setPages', data)
        },
        actionSetTopics({ commit }, data) {
            commit('setTopics', data)
        },
        actionSetArticles({ commit }, data) {
            commit('setArticles', data)
        },
        actionSetDarkTheme({ commit }, data) {
            commit('setDarkTheme', data)
            let theme = data ? 'dark' : 'light';
            sessionStorage.setItem('current_theme', theme)
        },
        actionGetJsonData({ commit }, name) {
            return new Promise((resolve, reject) => {
                if (!this.state[name]) {
                    getJsonData('/json/' + name + '.json').then(response => {
                        let data = response;
                        commit('setJsonData', { name: name, value: data });
                        resolve(data)
                    }).catch(error => {
                        reject(error)
                    })
                } else {
                    resolve(this.state[name])
                }
            });
            //    commit('getJsonData', name);
        },
    },

    modules: {
        // 分组模块，当 state 参数过多可以单独分组建立文件，从这里引入
    }
})