<template>
    <router-view />
</template>

<script>
import Layout from "./layouts/Layout";

export default {
  name: "App",
  components: { Layout },
  data() {
    return {
      home: null
    };
  },
  computed: {
    // loading() {
    //   return this.$store.getters.loading;
    // }
  },
  mounted() {
    // if (process.env.VUE_APP_MOCK === "1") {
    //   this.$store.dispatch("actionGetJsonData", "articles").then(() => {
    //     this.$store.dispatch("actionGetJsonData", "categories").then(() => {
    //       this.$store.dispatch("actionGetJsonData", "pages").then(() => {
    //         this.$store.dispatch("actionGetJsonData", "topics").then(() => {
    //           this.$store.commit("setLoading", false);
    //           this.home = Layout;
    //         });
    //       });
    //     });
    //   });
    // } else {
    //   setTimeout(() => {
    //     this.$store.commit("setLoading", false);
    //     this.home = Layout;
    //   }, 500)
    // }
  }
}
</script>
<style>
</style>
