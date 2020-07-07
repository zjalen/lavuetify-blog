<template>
    <v-app id="inspire">
        <left-menu ></left-menu>
        <top-header></top-header>
        <v-main>
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </v-main>
        <v-footer
            color="indigo"
            app
        >
            <span class="white--text">&copy; 2020</span>
        </v-footer>
        <snack-bar-toast></snack-bar-toast>
        <my-dialog></my-dialog>
    </v-app>
</template>

<script>
  import LeftMenu from './LeftMenu';
  import TopHeader from './TopHeader'
  import SnackBarToast from '../components/SnackBarToast'
  import MyDialog from '../components/MyDialog'
  export default {
    name: 'Layout',
    components: { MyDialog, SnackBarToast, LeftMenu, TopHeader },
    data: () => ({

    }),
    methods: {
    }
  }
</script>

<style lang="scss" scoped>
    .theme--dark.v-application {
        background: #000 !important;
        color: #FFFFFF;
    }
</style>
