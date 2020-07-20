<template>
  <v-row dense>
    <v-col cols="12">
      <v-breadcrumbs :items="breadcrumbs" class="pa-0" />
    </v-col>
    <v-col cols="12">
      <v-skeleton-loader class="mx-auto" :loading="loading" type="article">
        <v-card elevation="0" :style="card_style" class="pa-4 pa-sm-11">
          <div class="text-center font-regular headline">
            {{ article.title }}
          </div>
          <div class="text-right font-weight-light body-1 pt-3">
            <v-icon left>
              mdi-clock-outline
            </v-icon>
            {{ article.created_at }}
          </div>
          <div
            v-if="article.topic || article.category"
            cols="12"
            class="d-flex flex-sm-row flex-column-reverse py-3"
          >
            <v-btn
              v-if="article.topic"
              small
              class="mb-2"
              outlined
              color="tertiary"
              @click="onTopicClick(article.topic.name)"
            >
              <v-icon left>
                mdi-file-document
              </v-icon>
              主题：{{ article.topic.name }}
            </v-btn>
            <v-spacer />
            <v-btn
              v-if="article.category"
              small
              class="mb-2"
              outlined
              color="secondary"
              @click="$router.push({name: 'cate', params: {id: article.category_id}})"
            >
              <v-icon left>
                mdi-menu
              </v-icon>
              分类：{{ article.category.name }}
            </v-btn>
          </div>
          <v-divider class="my-3" />

          <!-- eslint-disable-next-line -->
          <div id="article-content" class="md markdown-body" v-html="article.content_html" />

          <v-divider class="my-3" />
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
              <v-icon left>
                mdi-tag-outline
              </v-icon>
              {{ tag.name }}
            </v-btn>
          </div>
          <v-divider />

          <div cols="12" class="d-flex flex-sm-row flex-column-reverse py-3">
            <v-btn
              v-if="article.prev"
              style="overflow: hidden"
              small
              color="secondary"
              rounded
              class="mb-2"
              outlined
              @click="toArticle(article.prev)"
            >
              <v-icon left>
                mdi-chevron-left
              </v-icon>
              上一篇：{{ prev_title }}
            </v-btn>
            <v-spacer />
            <v-btn
              v-if="article.next"
              style="overflow: hidden"
              color="secondary"
              small
              rounded
              class="mb-2"
              outlined
              @click="toArticle(article.next)"
            >
              下一篇：{{ next_title }}
              <v-icon right>
                mdi-chevron-right
              </v-icon>
            </v-btn>
          </div>
        </v-card>
      </v-skeleton-loader>
    </v-col>
    <my-image-viewer :show-img="showImgView" :img-src="imgSrc" @hideImg="hideImg" />
  </v-row>
</template>

<script>
// 自定义样式，调整默认样式以适应 dark 模式
import hljs from 'highlight.js'
// import 'highlight.js/styles/github.css'
// import { mavonEditor } from 'mavon-editor'
// 预览大图组件
import MyImageViewer from '../../components/MyImageViewer'

export default {
  components: {
    // mavonEditor,
    MyImageViewer
  },
  // fetch 与 asyncData 仅在 pages 下有效， component 中无效
  async fetch ({ store, app }) {
    return await app.$api.getCategories()
      .then((res) => {
        const categories = res.data.data.items
        store.dispatch('actionSetMenus', categories)
      })
  },
  async asyncData ({ app, params, store, payload }) {
    let article = null
    if (payload) {
      article = payload
    } else {
      const res = await app.$api.getArticle(params.id)
      article = res.data.data
    }
    const breadcrumbs = [
      {
        disabled: false,
        to: '/',
        text: '首页',
        nuxt: true
      }
    ]
    breadcrumbs.push({
      disabled: false,
      to: '/?category=' + article.category.name,
      text: article.category.name,
      nuxt: true
    })
    breadcrumbs.push({
      disabled: true,
      to: null,
      text: article.title,
      nuxt: true
    })
    store.dispatch('actionSetCurrentMenu', article.category.name)
    return { article, breadcrumbs }
  },
  data () {
    return {
      breadcrumbs: [
        {
          disabled: false,
          href: '/#/',
          link: true,
          text: '首页'
        }
      ],
      article: {},
      imgSrc: null,
      showImgView: false,
      loading: true,
      card_style: 'border: 1px solid #f2f6fc'
    }
  },
  computed: {
    prev_title () {
      const title = this.article.prev ? this.article.prev.title : null
      return title.length > 16 ? title.substr(0, 16) + '...' : title
    },
    next_title () {
      const title = this.article.next ? this.article.next.title : null
      return title.length > 16 ? title.substr(0, 16) + '...' : title
    },
    title () {
      return this.article.title
    },
    description () {
      return this.article.description
    },
    tags () {
      let tags = ''
      this.article.tags.forEach((value) => {
        tags += value.name + ','
      })
      return tags
    }
  },
  watch: {
    '$store.state.dark': {
      handler (newValue, oldValue) {
        const style = newValue === true ? this.$store.state.dark_code_style : this.$store.state.light_code_style
        const oldStyle = newValue === true ? this.$store.state.light_code_style : this.$store.state.dark_code_style
        this.importHighlightStyle(style, oldStyle)
      }
    }
  },
  mounted () {
    this.loading = false
    this.card_style = this.$vuetify.theme.isDark
      ? 'border: 1px solid #6d6e6f'
      : 'border: 1px solid #f2f6fc'
    document.querySelectorAll('pre code').forEach((block) => {
      hljs.highlightBlock(block)
    })
    setTimeout(() => {
      this.$nextTick(() => {
        const val = this.$store.state.dark
        const style = val === true ? this.$store.state.dark_code_style : this.$store.state.light_code_style
        const oldStyle = val === true ? this.$store.state.light_code_style : this.$store.state.dark_code_style
        this.importHighlightStyle(style, 'github')
        this.importHighlightStyle(style, oldStyle)
        this.addImgClickEvent()
      })
    }, 120)
  },
  methods: {
    onTagClick (tagName) {
      this.$router.push({
        path: '/',
        query: {
          page: 1,
          tag: tagName
        }
      })
    },
    onTopicClick (topicName) {
      const rt = {
        path: '/',
        query: {
          page: 1,
          topic: topicName
        }
      }
      this.$router.push(rt)
    },
    toArticle (article) {
      const rt = {
        path: '/articles/' + article.id
      }
      this.$router.push(rt)
    },
    hideImg () {
      this.showImgView = false
    },
    addImgClickEvent () {
      const article = document.getElementById('article-content')
      const objs = article.getElementsByTagName('img')
      Array.from(objs).forEach((obj) => {
        obj.onclick = () => {
          this.showImgView = true
          this.imgSrc = obj.src
        }
      })
    },
    importHighlightStyle (style, oldStyle) {
      this.removeStyle(oldStyle)
      const css = document.createElement('link')
      css.href = '/hljs-styles/' + style + '.css'
      css.rel = 'stylesheet'
      css.type = 'text/css'
      document.head.appendChild(css)
    },
    /**
     * 删除 link 文件
     * @param style
     */
    removeStyle (style) {
      const links = document.head.getElementsByTagName('link')
      Array.from(links).forEach((link) => {
        if (link.href.includes(style)) {
          document.head.removeChild(link)
        }
      })
      // for (let i = 0; i < links.length; i++) {
      //   if (links[i] && links[i].href && links[i].href.includes(style)) {
      //     console.log(links[i])
      //     // document.head.removeChild(links[i])
      //   }
      // }
    }
  },
  head () {
    return {
      title: this.title,
      meta: [
        { hid: 'description', name: 'description', content: this.description },
        { hid: 'author', name: 'author', content: 'Jalen 张佳林' },
        { hid: 'keywords', name: 'keywords', content: this.tags }
      ]
    }
  }
}
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
