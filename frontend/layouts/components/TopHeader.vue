<template>
  <div>
    <v-navigation-drawer
      v-model="drawer"
      class="secondary darken-3"
      width="40%"
      absolute
      right
      temporary
    >
      <v-list dense>
        <v-list-item-group v-model="current_menu_index" dark color="primary">
          <template v-for="(menu, key) in cateArray">
            <v-list-item
              v-if="!menu.children || menu.children.length === 0"
              :key="key"
              link
              elevation="0"
              @click="onTabClick(key)"
            >
              <v-list-item-title>
                {{ menu.name }}
              </v-list-item-title>
            </v-list-item>
            <v-list-group
              v-else
              :key="key"
              dark
              value="true"
            >
              <template v-slot:activator>
                <v-list-item-content>
                  <v-list-item-title>{{ menu.name }}</v-list-item-title>
                </v-list-item-content>
              </template>
              <v-list-item
                v-for="(submenu, i) in menu.children"
                :key="i"
                link
                class="pl-10"
                @click="onSubMenuClick(submenu)"
              >
                <v-list-item-title v-text="submenu.name" />
              </v-list-item>
            </v-list-group>
          </template>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>
    <v-app-bar height="120px" elevation="0">
      <client-only>
        <vue-particles
          id="particles-js"
          color="#83d2f8"
          :particle-opacity="0.5"
          :particles-number="88"
          shape-type="circle"
          :particle-size="1"
          lines-color="#33b1f8"
          :lines-width="1"
          :line-linked="true"
          :line-opacity="0.3"
          :lines-distance="150"
          :move-speed="1"
          :hover-effect="true"
          hover-mode="null"
          :click-effect="false"
          click-mode="push"
        />
      </client-only>
      <v-container style="max-width: 1185px;">
        <v-layout justify-center align-center wrap>
          <v-flex column wrap>
            <div class="blog-title">
              {{ title }}
            </div>
            <div class="slogan hidden-sm-and-down">
              他沉默，随手写下一行 <code>Hello</code>, <code>World</code>!
            </div>
          </v-flex>
          <v-spacer />
          <oauth-login-card :user="user" @onOauthClick="onOauthClick" @onLogoutClick="onLogoutClick" />
          <v-btn title="切换暗色/亮色主题" icon @click.stop="switchTheme">
            <v-icon class="display-1">
              mdi-brightness-{{ icon_light }}
            </v-icon>
          </v-btn>
          <v-btn title="菜单" icon class="ml-2 d-block d-md-none" @click.stop="drawer = !drawer">
            <v-icon class="display-1">
              mdi-menu
            </v-icon>
          </v-btn>
        </v-layout>
      </v-container>

      <template v-slot:extension>
        <v-container style="max-width: 1185px;" class="px-3 py-0 my-0">
          <v-toolbar-items class="d-sm-block d-none secondary darken-3 menu-area">
            <template v-for="(menu, key) in cateArray">
              <v-btn
                v-if="!menu.children || menu.children.length === 0"
                :key="key"
                middle
                :text="key !== current_menu_index"
                :class="key !== current_menu_index ? `secondary darken-3` : `secondary darken-1`"
                elevation="0"
                @click="onTabClick(key)"
              >
                {{ menu.name }}
              </v-btn>
              <v-menu v-else :key="key" open-on-hover offset-y>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    middle
                    :text="key !== current_menu_index"
                    :class="key !== current_menu_index ? `secondary darken-3` : `secondary darken-1`"
                    elevation="0"
                    v-bind="attrs"
                    v-on="on"
                    @mouseenter="onSwitchSubAction"
                    @mouseleave="onSwitchSubAction"
                  >
                    {{ menu.name }}
                    <v-icon right>
                      mdi-{{ subIcon }}
                    </v-icon>
                  </v-btn>
                </template>
                <v-list dark color="secondary">
                  <v-list-item
                    v-for="(item, index) in menu.children"
                    :key="index"
                    @click="onSubMenuClick(item)"
                  >
                    <v-list-item-title>{{ item.name }}</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </template>
          </v-toolbar-items>
          <div class="slogan d-sm-none">
            他沉默，随手写下一行 <code>Hello</code>, <code>World</code>!
          </div>
        </v-container>
      </template>
    </v-app-bar>
  </div>
</template>
<script>
import OauthLoginCard from '../../components/OauthLoginCard'
export default {
  components: { OauthLoginCard },
  props: {
    categories: {
      type: Array,
      default () {
        return []
      }
    }
  },
  data () {
    return {
      title: 'JALEN博客',
      slogan: '他沉默，随手写下一行 <code>Hello</code>, <code>World</code>!',
      drawer: false,
      subIcon: 'chevron-down',
      user: null,
      cateArray: [],
      current_menu_index: null,
      icon_light: null
    }
  },
  computed: {
    // current_menu_index: {
    //   get () {
    //     return this.$store.state.current_menu_index
    //   },
    //   set () {
    //     return false
    //   }
    // },
    // icon_light () {
    //   return this.$store.state.dark ? 4 : 7
    // }
  },
  mounted () {
    this.switchMenu()
    this.user = this.$store.state.user
    this.cateArray = this.$store.state.menus
    this.current_menu_index = this.$store.state.current_menu_index
    this.icon_light = this.$store.state.dark ? 4 : 7
    // sessionStorage.setItem('userInfo', null)
    // if (sessionStorage.getItem('userInfo')) {
    //   this.user = JSON.parse(sessionStorage.getItem('userInfo'))
    //   this.$store.commit('setUser', this.user)
    // }
  },
  methods: {
    switchTheme () {
      const theme = !this.$store.state.dark
      this.$vuetify.theme.dark = theme
      this.$store.dispatch('actionSetDarkTheme', theme)
    },
    switchMenu () {
      return true
    },
    onTabClick (key) {
      this.onMenuClick(key)
    },
    onTabChange (index) {
      this.onMenuClick(index)
    },
    onSubMenuClick (menu) {
      const rt = {}
      rt.path = '/'
      rt.query = {}
      rt.query.category = menu.name
      this.$router.push(rt)
    },
    onSwitchSubAction () {
      this.subIcon = this.subIcon === 'chevron-down' ? 'chevron-up' : 'chevron-down'
    },
    onMenuClick (key) {
      if (key === this.current_menu_index) {
        return
      }
      const menus = this.$store.state.menus
      let menu = null
      menus.filter((item, index) => {
        if (index === key) {
          menu = item
        }
      })
      const rt = {}
      if (menu.type === 'page') {
        rt.path = menu.path
        rt.query = {}
      } else {
        rt.path = '/'
        rt.query = {}
        rt.query.category = menu.name
      }
      this.$router.push(rt)
    },
    onOauthClick (type) {
      let page = window.location.pathname
      page = encodeURIComponent(page)
      // page = page.substring(1);
      const url = process.env.API_HOST + '/oauth/login/' + type + '?frontend_url=' + page
      window.location.href = url
    },
    onLogoutClick () {
      const type = this.$store.state.user.type
      const accessToken = this.$store.state.user.access_token
      this.$api.logout(type, accessToken).then(() => {
        this.$store.dispatch('actionSetUser', null)
        location.reload()
      }).catch(() => {
        this.$store.dispatch('actionSetUser', null)
        location.reload()
      })
    }
  }
}
</script>
<style scoped lang=scss>
  .blog-title {
    font-size: 30px;
    color: var(--v-primary-base);
  }

  .slogan {
    color: var(--v-primary-lighten3);
  }

  a {
    text-decoration: unset;
  }

  #particles-js {
    position: absolute;
    background-color: var(--v-secondary-base);
    left: 0;
    right: 0;
    top: -80px;
    bottom: 0;
    z-index: -1;
    height: 248px;
  }
  .menu-area {
    height: 48px;
    overflow-y: scroll;
    white-space: nowrap;
    &::-webkit-scrollbar {
      display: none;
    }
  }
</style>
