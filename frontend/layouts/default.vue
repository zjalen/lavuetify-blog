<template>
  <v-app style="overflow-x: hidden">
    <top-header class="header" :categories="propData.categories" />
    <v-main class="content">
      <v-container style="max-width: 1185px;">
        <nuxt />
      </v-container>
    </v-main>
    <bottom-footer class="footer" />
    <go-to-top />
    <v-overlay :value="loading">
      <v-progress-circular indeterminate size="64" />
    </v-overlay>
  </v-app>
</template>

<script>
import GoToTop from './components/GoToTop'
import TopHeader from './components/TopHeader'
import BottomFooter from './components/BottomFooter'

export default {
  name: 'Layout',
  components: {
    TopHeader,
    BottomFooter,
    GoToTop
  },
  props: {
    component: {
      type: Object,
      default () {
        return null
      }
    },
    propData: {
      type: Object,
      default () {
        return {}
      }
    }
  },
  data: () => ({
    visible: false,
    title: 'JALEN博客',
    slogan: '他沉默，什么都不说，随手写下一行 <code>Hello,World!</code>',
    userInfo: null,
    fixed: false,
    loading: true,
    scrollTop: '',
    tab: null,
    items: ['web', 'shopping', 'videos', 'images', 'news'],
    text:
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
  }),
  computed: {
    // loading () {
    //   return this.$store.state.loading
    // }
  },
  mounted () {
    this.loading = this.$store.state.loading
    setTimeout(() => {
      // this.$nextTick(() => {
      //   // const theme = sessionStorage.getItem('current_theme')
      //   // this.$store.commit('setDarkTheme', theme === 'dark')
      //   this.$vuetify.theme.dark = this.$store.state.dark
      // })
      this.$vuetify.theme.dark = this.$store.state.dark
      this.$store.commit('setLoading', false)
    }, 100)
  },
  methods: {}
}
</script>
<style>
  .header {
    height: 168px;
    background-color: rgba(0, 0, 0, 0) !important;
  }
  .footer {
    height: 123px;
  }
  .content {
    min-height: calc(100vh - 168px - 123px);
    background-color: var(--v-background-base);
  }
</style>
