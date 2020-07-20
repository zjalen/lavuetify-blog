<template>
  <div />
</template>

<script>
export default {
  layout: 'empty',
  name: 'Login',
  mounted () {
    if (this.$route.query.access_token && this.$route.query.type) {
      this.$api.getUserInfoByAccessToken(this.$route.query.type, this.$route.query.access_token).then((response) => {
        this.$store.dispatch('actionSetUser', response.data.data)
        setTimeout(() => {
          this.$router.push({
            path: this.$route.query.path
          })
        }, 100)
      })
    }
  }
}
</script>

<style scoped>

</style>
