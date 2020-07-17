<template>
  <v-app-bar
    app
    dark
    color="primary"
    clipped-left
  >
    <v-app-bar-nav-icon v-if="show_menu" @click.stop="switchDrawer"/>
    <v-toolbar-title>Jalen 的博客后台</v-toolbar-title>

    <v-spacer></v-spacer>
    <div class="pr-1">
      <v-btn title="切换暗色/亮色主题" icon @click.stop="switchTheme">
        <v-icon class="display-1">mdi-brightness-{{icon_light}}</v-icon>
      </v-btn>

      <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-avatar 
            v-bind="attrs"
            v-on="on"
          >
            <img
              class="pa-1"
              :src="$store.getters.user.avatar_full_path"
              :alt="$store.getters.user.name"
            >
          </v-avatar>
        </template>
        <v-list>
          <v-list-item
            @click.stop="setting"
          >
            <v-list-item-title class="d-flex justify-center align-center"><v-icon class="mr-2">mdi-cog</v-icon>账号设置</v-list-item-title>
          </v-list-item>
          <v-list-item
            @click.stop="resetPassword"
          >
            <v-list-item-title class="d-flex justify-center align-center"><v-icon class="mr-2">mdi-lock-reset</v-icon>密码修改</v-list-item-title>
          </v-list-item>
          <v-list-item
            @click.stop="logout"
          >
            <v-list-item-title class="d-flex justify-center align-center"><v-icon class="mr-2">mdi-logout</v-icon>退出登录</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
      <!--            <v-btn title="退出登录" icon @click.stop="logout">-->
      <!--                <v-icon class="display-1">mdi-logout</v-icon>-->
      <!--            </v-btn>-->
    </div>
  </v-app-bar>
</template>

<script>
import { logout } from '@/api/login'
import { getAdminUserInfo } from '../api'

export default {
  name: 'TopHeader',
  props: {
    show_menu: {
      type: Boolean,
      default () {
        return true
      },
    },
  },
  data () {
    return {
      user_info: {},
    }
  },
  mounted () {
    getAdminUserInfo().then(response => {
      this.$store.dispatch('actionSetUser', response.data)
    })
  },
  computed: {
    icon_light () {
      return this.$store.getters.dark ? 4 : 7
    },
  },
  methods: {
    switchDrawer () {
      let value = this.$store.getters.drawer
      this.$VM.$emit('switchDrawer', !value)
    },
    switchTheme () {
      let theme = this.$store.getters.dark
      this.$store.dispatch('actionSetDarkTheme', !theme)
      location.reload()
    },
    logout () {
      logout().then(() => {
        this.$store.dispatch('actionSetToken', null)
        this.$router.push({ name: 'login' })
      })
    },
    setting() {
      this.$router.push({name: 'setting'})
    },
    resetPassword() {
      this.$router.push({name: 'reset-password'})
    }
  },
}
</script>

<style scoped>

</style>
