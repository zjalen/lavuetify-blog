<template>
    <v-snackbar
        v-model="$store.getters.snackbar.show"
        top
        :timeout="$store.getters.snackbar.timeout ? $store.getters.snackbar.timeout : 5000"
        :color="$store.getters.snackbar.color"
    >
        {{ $store.getters.snackbar.message }}
        <v-btn
            text
            @click="$store.commit('switchSnackbar', false)"
        >
            关闭
        </v-btn>
    </v-snackbar>
</template>

<script>
  export default {
    name: 'SnackBarToast',
  }
</script>

<style scoped>

</style>
