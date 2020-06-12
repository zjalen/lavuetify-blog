<template>
    <v-app style="overflow-x: hidden">
        <top-header class="header" :categories="prop_data.categories"></top-header>
<!--        <v-content class="content">-->
<!--            <v-container style="max-width: 1185px;">-->
<!--                &lt;!&ndash; Provides the application the proper gutter &ndash;&gt;-->
<!--&lt;!&ndash;                <router-view :key="$route.fullPath"></router-view>&ndash;&gt;-->
<!--                <component :is="component" :content_data="prop_data.content_data"></component>-->
<!--            </v-container>-->
<!--        </v-content>-->
<!--        <bottom-footer class="footer"></bottom-footer>-->
<!--&lt;!&ndash;        <go-to-top></go-to-top>&ndash;&gt;-->
<!--        <v-overlay :value="loading">-->
<!--            <v-progress-circular indeterminate size="64"></v-progress-circular>-->
<!--        </v-overlay>-->
    </v-app>
</template>

<script>
import "../../scss/custom.scss";
// import GoToTop from "./GoToTop";
import TopHeader from "./TopHeader";
// import BottomFooter from "./BottomFooter";

export default {
  name: "Layout",
  props: {
    component: {
      type: Object,
      default() {
        return null
      }
    },
    prop_data: {
      type: Object,
      default() {
        return {}
      }
    },
  },
  components: {
    TopHeader,
    // BottomFooter,
    // GoToTop
  },
  data: () => ({
    visible: false,
    title: "JALEN博客",
    slogan: "他沉默，什么都不说，随手写下一行 <code>Hello,World!</code>",
    userInfo: null,
    fixed: false,
    scrollTop: "",
    tab: null,
    loading: true,
    items: ["web", "shopping", "videos", "images", "news"],
    text:
      "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat."
  }),
  computed: {
    // loading() {
    //   return this.$store.getters.loading;
    // }
  },
  mounted() {
    this.loading = false
    // setTimeout(() => {
    //   this.$store.commit("setLoading", false);
    // }, 500)
  },
  methods: {}
};
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
}
</style>
