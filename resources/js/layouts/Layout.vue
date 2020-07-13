<template>
  <v-app id="inspire">
    <left-menu></left-menu>
    <top-header></top-header>
    <v-main>
      <v-container fluid>
        <router-view></router-view>
      </v-container>
    </v-main>
    <go-to-top></go-to-top>
    <bottom-footer></bottom-footer>
    <snack-bar-toast></snack-bar-toast>
    <my-dialog></my-dialog>
  </v-app>
</template>

<script>
import LeftMenu from './LeftMenu'
import TopHeader from './TopHeader'
import SnackBarToast from '../components/SnackBarToast'
import MyDialog from '../components/MyDialog'
import BottomFooter from './BottomFooter'
import GoToTop from './GoToTop'

export default {
  name: 'Layout',
  components: { GoToTop, BottomFooter, MyDialog, SnackBarToast, LeftMenu, TopHeader },
  data: () => ({}),
  methods: {},
}
</script>

<style lang="scss" scoped>
  .theme--dark.v-application {
    background: #000 !important;
    color: #FFFFFF;
  }
</style>
