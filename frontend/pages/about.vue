<template>
  <v-row dense>
    <v-col cols="12">
      <v-skeleton-loader class="mx-auto" :loading="loading" type="article">
        <v-card elevation="0" :style="card_style" class="pa-4 pa-sm-11 page">
          <!-- eslint-disable-next-line -->
          <div class="page-view" v-html="article.content_html" />
        </v-card>
      </v-skeleton-loader>
    </v-col>
    <my-image-viewer :show-img="showImgView" :img-src="imgSrc" @hideImg="hideImg" />
  </v-row>
</template>

<script>
// 预览大图组件
import MyImageViewer from '../components/MyImageViewer'

export default {
  components: {
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
  async asyncData ({ app, store, payload }) {
    let article = null
    if (payload) {
      article = payload
    } else {
      const res = await app.$api.getPage('about')
      article = res.data.data
    }
    store.dispatch('actionSetCurrentMenu', '关于')
    return {
      article
    }
  },
  data () {
    return {
      article: {},
      title: '关于我',
      description: '只是个开发。从后台开发到前端开发，从IOS到Android，从公众号到小程序，从物联网平台到桌面上位机。',
      imgSrc: null,
      showImgView: false,
      loading: true,
      card_style: 'border: 1px solid #f2f6fc'
    }
  },
  mounted () {
    this.loading = false
    this.addImgClickEvent()
  },
  methods: {
    addImgClickEvent () {
      const objs = document.getElementsByTagName('img')
      Array.from(objs).forEach((obj) => {
        obj.onclick = () => {
          this.showImgView = true
          this.imgSrc = obj.src
        }
      })
    },
    hideImg () {
      this.showImgView = false
    }
  },
  head () {
    return {
      title: this.title,
      meta: [
        { hid: 'description', name: 'description', content: this.description },
        { hid: 'author', name: 'author', content: 'Jalen 张佳林' },
        { hid: 'keywords', name: 'keywords', content: 'developer,ios,java,android,html,vue' }
      ]
    }
  }
}

</script>
<style lang="scss">
  .page-view {
    .title {
      line-height: 1.2;
      font-weight: normal;
      border-left: 6px solid var(--v-secondary-base) !important;
      padding-left: 10px;
      color: var(--v-secondary-base);
      margin-bottom: 20px;
      -webkit-box-reflect: below 1px -webkit-gradient(linear, 0 0, 0 100%, from(transparent), color-stop(.5, transparent), to(rgba(3, 3, 3, .2)));
    }
  }
</style>
