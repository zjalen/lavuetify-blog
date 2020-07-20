export const state = () => ({
  // 这里放全局参数
  mounting: false,
  loading: true,
  menus: [],
  dark: false,
  current_menu_index: 0,
  dark_code_style: 'monokai-sublime',
  light_code_style: 'androidstudio',
  api_host: 'http://lavuetify-blog.test',
  // mock 数据
  categories: null,
  pages: null,
  topics: null,
  articles: null,
  user: null
})

export const mutations = {
  // 这里是同步 set 方法，通过 commit 方式触发 this.$store.commit('setToken', token)
  setMounting (state, status) {
    state.mounting = status
  },
  setUser (state, data) {
    state.user = data
  },
  setLoading (state, status) {
    state.loading = status
  },
  setDarkTheme (state, data) {
    state.dark = data
  },
  setMenus (state, menus) {
    state.menus = menus
  },
  setCurrentMenuIndex (state, index) {
    state.current_menu_index = index
  },
  setPages (state, pages) {
    state.pages = pages
  },
  setTopics (state, topics) {
    state.topics = topics
  },
  setArticles (state, articles) {
    state.articles = articles
  }
}

export const actions = {
  // 这里是异步方法，可以在组件中使用 this.$store.dispatch('actionSetUserInfo', user_info) 分发调用
  // mutation 方法进行 set
  actionSetMounting ({ commit }, status) {
    commit('setMounting', status)
  },
  actionSetLoading ({ commit }, status) {
    commit('setLoading', status)
  },
  actionSetUser ({ commit }, data) {
    // if (data) { sessionStorage.setItem('userInfo', JSON.stringify(data)) } else { sessionStorage.removeItem('userInfo') }
    commit('setUser', data)
  },
  actionSetMenus ({ commit }, categories) {
    categories.unshift({
      name: '首页',
      type: 'page',
      path: '/'
    })
    categories.push({
      type: 'page',
      name: '关于',
      path: '/about'
    })
    categories.push({
      name: '友链',
      type: 'page',
      path: '/links'
    })
    commit('setMenus', categories)
  },
  actionSetCurrentMenu ({ commit }, name) {
    let cateId = 0
    this.state.menus.some((menu, index) => {
      if (menu.name === name) {
        cateId = index
        return true
      } else if (menu.children) {
        const res = menu.children.some((sub) => {
          if (sub.name === name) {
            cateId = index
            return true
          }
        })
        if (res) {
          return res
        }
      }
    })
    commit('setCurrentMenuIndex', cateId)
  },
  actionSetPages ({ commit }, data) {
    commit('setPages', data)
  },
  actionSetTopics ({ commit }, data) {
    commit('setTopics', data)
  },
  actionSetArticles ({ commit }, data) {
    commit('setArticles', data)
  },
  actionSetDarkTheme ({ commit }, data) {
    commit('setDarkTheme', data)
    // const theme = data ? 'dark' : 'light'
    // sessionStorage.setItem('current_theme', theme)
  }
}
