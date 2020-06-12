<template>
  <layout :component="content" :prop_data="prop_data"></layout>
</template>

<script>
  import Layout from '../../components/layouts/Layout'
  import ArticlesContent from './ArticlesContent'
  export default {
    name: 'index',
    components: { Layout, ArticlesContent },
    props: {
      categories: {
        type: Array,
        default() {
          return []
        }
      },
      prop_data: {
        type: Object,
        default() {
          return {}
        }
      },
    },
    data: () => ({
      content: ArticlesContent
    })
  }
</script>

<style scoped>

</style>
