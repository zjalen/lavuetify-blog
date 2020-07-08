<template>
  <h1>Home</h1>
</template>

<script>
import { getUserMessage } from '../api'

export default {
  name: 'Home',
  mounted () {
    getUserMessage().then(response => {
      console.log(response)
    })
  }
}
</script>

<style scoped>

</style>
