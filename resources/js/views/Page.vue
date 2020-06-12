<template>
  <v-row dense>
    <v-col cols="12">
      <v-skeleton-loader class="mx-auto" :loading="loading" type="article">
        <v-card elevation="0" :style="card_style" class="pa-4 pa-sm-11">
          <mavon-editor
            v-model="content_md"
            :ishljs="true"
            :codeStyle="codeStyle"
            :subfield="false"
            defaultOpen="preview"
            :toolbarsFlag="false"
            :boxShadow="false"
          ></mavon-editor>
        </v-card>
      </v-skeleton-loader>
    </v-col>
    <my-image-viewer :showImg="showImgView" @hideImg="hideImg" :imgSrc="imgSrc"></my-image-viewer>
  </v-row>
</template>

<script>
// 自定义样式，调整默认样式以适应 dark 模式
import "../scss/custom-markdown.scss";
import { mavonEditor } from "mavon-editor";
import { getArticleMD } from "../api";
// 预览大图组件
import MyImageViewer from "../components/MyImageViewer";

export default {
  components: {
    mavonEditor,
    MyImageViewer
  },
  data() {
    return {
      content_md: null,
      imgSrc: null,
      showImgView: false,
      loading: true,
      codeStyle: "github",
      card_style: "border: 1px solid #f2f6fc"
    };
  },
  mounted() {
    this.codeStyle = this.$store.getters.codeStyle;
    this.card_style = this.$vuetify.theme.isDark
      ? "border: 1px solid #6d6e6f"
      : "border: 1px solid #f2f6fc";
    let page = this.$route.params.page;
    this.init(page);
  },
  methods: {
    init(page) {
      getArticleMD(page).then(response => {
        setTimeout(() => {
          this.loading = false;
          this.content_md = response;
        }, 500);
      });
    },
    hideImg() {
      this.showImgView = false;
    },
    addImgClickEvent() {
      let objs = document.getElementsByTagName("img");
      objs.forEach(obj => {
        obj.onclick = () => {
          this.showImgView = true;
          this.imgSrc = obj.src;
        };
      });
    }
  }
};
</script>
<style lang=scss scoped>
.v-note-wrapper {
    z-index: 1 !important;
}
.notice {
    border-radius: 4px;
    border-left: 5px solid var(--v-secondary-darken2);
    font-weight: 300;
    margin: 12px 0;
    padding: 8px;
    color: #fff;
    background-color: var(--v-secondary-lighten1);
}
</style>