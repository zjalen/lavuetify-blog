<template>
  <v-row dense>
    <v-col cols="12">
      <v-breadcrumbs :items="breadcrumbs" class="pa-0"></v-breadcrumbs>
    </v-col>
    <v-col cols="12">
      <v-skeleton-loader class="mx-auto" :loading="loading" type="article">
        <v-card elevation="0" :style="card_style" class="pa-4 pa-sm-11">
          <div class="text-center font-regular headline">{{article.title}}</div>
          <div class="text-right font-weight-light body-1 pt-3">
            <v-icon left>mdi-clock-outline</v-icon>
            {{article.created_at}}
          </div>
          <div
            v-if="article.topic || article.category"
            cols="12"
            class="d-flex flex-sm-row flex-column-reverse py-3"
          >
            <v-btn
              small
              v-if="article.topic"
              class="mb-2"
              outlined
              color="tertiary"
              @click="onTopicClick(article.topic.name)"
            >
              <v-icon left>mdi-file-document</v-icon>
              主题：{{article.topic.name}}
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn
              small
              v-if="article.category"
              class="mb-2"
              outlined
              color="secondary"
              @click="$router.push({name: 'cate', params: {id: article.category_id}})"
            >
              <v-icon left>mdi-menu</v-icon>
              分类：{{article.category.name}}
            </v-btn>
          </div>
          <v-divider class="my-3"></v-divider>

          <!-- <div class="md markdown-body" v-html="article">
          </div>-->

          <mavon-editor
            v-model="article.content_md"
            :ishljs="true"
            :codeStyle="codeStyle"
            :subfield="false"
            defaultOpen="preview"
            :toolbarsFlag="false"
            :boxShadow="false"
          ></mavon-editor>

          <v-divider class="my-3"></v-divider>
          <div class="notice">
            原创文章，可以转载，但请注明出处，谢谢合作。Jalen的博客 (
            <a href="/">https://www.jalen.top</a>)
          </div>
          <div>
            标签：
            <v-btn
              v-for="(tag, index) in article.tags"
              :key="index"
              class="mr-1 my-3"
              small
              color="info"
              outlined
              @click="onTagClick(tag.name)"
            >
              <v-icon left>mdi-tag-outline</v-icon>
              {{tag.name}}
            </v-btn>
          </div>
          <v-divider></v-divider>

          <div cols="12" class="d-flex flex-sm-row flex-column-reverse py-3">
            <v-btn
              style="overflow: hidden"
              small
              color="secondary"
              rounded
              v-if="article.prev"
              class="mb-2"
              outlined
              @click="toArticle(article.prev)"
            >
              <v-icon left>mdi-chevron-left</v-icon>
              上一篇：{{prev_title}}
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn
              style="overflow: hidden"
              color="secondary"
              small
              rounded
              v-if="article.next"
              class="mb-2"
              outlined
              @click="toArticle(article.next)"
            >
              下一篇：{{next_title}}
              <v-icon right>mdi-chevron-right</v-icon>
            </v-btn>
          </div>
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
import { getArticle, getArticleMD } from "../api";
// 预览大图组件
import MyImageViewer from "../components/MyImageViewer";

export default {
  components: {
    mavonEditor,
    MyImageViewer
  },
  data() {
    return {
      breadcrumbs: [
        {
          disabled: false,
          href: "/#/",
          link: true,
          text: "首页"
        }
      ],
      article: {},
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
    let id = this.$route.params.id;
    this.init(id);
  },
  computed: {
    prev_title() {
      let title = this.article.prev ? this.article.prev.title : null;
      return title.length > 16 ? title.substr(0, 16) + "..." : title;
    },
    next_title() {
      let title = this.article.next ? this.article.next.title : null;
      return title.length > 16 ? title.substr(0, 16) + "..." : title;
    }
  },
  methods: {
    init(id) {
      getArticle(id).then(response => {
        this.loading = false;
        this.article = response.data;
        // getArticleMD(this.article.id).then(response => {
        //   this.article.content_md = response;
        // });
        let breadcrumb_name = null;
        this.$store.getters.menus.filter(menu => {
          if (menu.id === Number(this.$route.params.cate)) {
            breadcrumb_name = menu.name;
          }
        });
        this.breadcrumbs.push({
          disabled: false,
          href: "/#/cates/" + this.$route.params.cate,
          link: true,
          text: breadcrumb_name
        });
        this.breadcrumbs.push({
          disabled: true,
          href: null,
          link: false,
          text: this.article.title
        });
      });
    },
    onTagClick(tag_name) {
      this.$router.push({
        name: "index",
        query: {
          page: 1,
          tag: tag_name
        }
      });
    },
    onTopicClick(topic_name) {
      let rt = {
        name: "index",
        query: {
          page: 1,
          topic: topic_name
        }
      };
      this.$router.push(rt);
    },
    toArticle(article) {
      let rt = {
        name: "article",
        params: {
          cate: article.category.id,
          id: article.id
        }
      };
      this.$router.push(rt);
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
