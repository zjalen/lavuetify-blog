<template>
    <v-app id="inspire">
        <v-main>
            <v-container fluid class="fill-height">
                <router-view></router-view>
            </v-container>
        </v-main>
        <snack-bar-toast></snack-bar-toast>
    </v-app>
</template>

<script>
  import SnackBarToast from '../components/SnackBarToast'
  export default {
    name: 'LayoutWithoutBar',
    components: { SnackBarToast },
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
