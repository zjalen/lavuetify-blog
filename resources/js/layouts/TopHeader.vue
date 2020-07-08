<template>
    <v-app-bar
        app
        dark
        color="primary"
        clipped-left
    >
        <v-app-bar-nav-icon v-if="show_menu" @click.stop="switchDrawer" />
        <v-toolbar-title>Jalen 的博客后台</v-toolbar-title>

        <v-spacer></v-spacer>
        <div class="pr-1">
            <v-btn title="切换暗色/亮色主题" icon @click.stop="switchTheme">
                <v-icon class="display-1">mdi-brightness-{{icon_light}}</v-icon>
            </v-btn>

            <v-btn title="退出登录" icon @click.stop="logout">
                <v-icon class="display-1">mdi-logout</v-icon>
            </v-btn>
        </div>
    </v-app-bar>
</template>

<script>
    import { logout } from '@/api/login'
  export default {
    name: 'TopHeader',
    props: {
      show_menu: {
        type: Boolean,
        default() {
          return true
        }
      },
    },
    data() {
      return {
        project: null
      }
    },
    mounted() {
      this.project = this.$store.getters.current_project
    },
    computed: {
      icon_light() {
        return this.$store.getters.dark ? 4 : 7
      }
    },
    methods: {
      switchDrawer() {
        let value = this.$store.getters.drawer
        this.$VM.$emit('switchDrawer', !value)
      },
      switchTheme() {
        let theme = this.$store.getters.dark
        this.$store.dispatch('actionSetDarkTheme', !theme)
        location.reload()
      },
      logout() {
        logout().then(() => {
          this.$store.dispatch('actionSetToken', null)
          this.$router.push({ name: 'login' })
        })
      }
    }
  }
</script>

<style scoped>

</style>
