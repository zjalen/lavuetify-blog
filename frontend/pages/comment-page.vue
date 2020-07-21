<template>
  <v-row dense>
    <v-col cols="12">
      <v-breadcrumbs :items="breadcrumbs" class="pa-0" />
    </v-col>
    <v-col cols="12">
      <v-skeleton-loader class="mx-auto" :loading="loading" type="article">
        <v-card elevation="1" :style="card_style" class="pa-4 pa-sm-11">
          <v-card-title class="pl-0 headline">
            {{ article.title }}
          </v-card-title>

          <!-- eslint-disable-next-line -->
          <div id="article-content" class="md markdown-body" v-html="article.content_html" />
        </v-card>
      </v-skeleton-loader>
      <v-card elevation="1" class="mt-4 pa-4" :style="card_style">
        <v-card-subtitle>评论区</v-card-subtitle>
        <v-card-text class="pb-0">
          <oauth-login-card :user="user" @onOauthClick="onOauthClick" @onLogoutClick="onLogoutClick" />
          <v-textarea
            v-model="submitParams.content"
            :placeholder="user ? user.nickname + ':' : '请点击上方头像登录后发表评论'"
            :hint="user ? '欢迎评论交流' : '请先登录再评论'"
            rows="2"
            class="mt-3"
            outlined
          />
        </v-card-text>
        <v-card-actions class="pr-4">
          <v-spacer />
          <v-btn class="secondary" @click="onSubmitComment">
            提交
          </v-btn>
        </v-card-actions>
        <div v-for="(comment, key) in comments" :key="key" class="px-4">
          <comment-tree-item :comment="comment" @onReplyClick="onReply" />
        </div>
      </v-card>
    </v-col>
    <my-image-viewer :show-img="showImgView" :img-src="imgSrc" @hideImg="hideImg" />
    <v-snackbar
      v-model="snackbar.show"
      top
      :timeout="snackbar.timeout ? snackbar.timeout : 5000"
      :color="snackbar.color"
    >
      {{ snackbar.message }}
      <v-btn
        text
        @click="snackbar.show=false"
      >
        关闭
      </v-btn>
    </v-snackbar>
    <v-dialog
      v-model="dialog.show"
      width="500"
    >
      <v-card>
        <v-card-title
          class="title secondary white--text"
          primary-title
        >
          {{ dialog.title }}
        </v-card-title>

        <v-textarea v-model="dialog.content" rows="2" hint="请登录后发表评论" class="body-1 ma-4" outlined />

        <v-divider />

        <v-card-actions>
          <v-spacer />
          <v-btn
            color="warning"
            @click="onDialogClose"
          >
            取消
          </v-btn>
          <v-btn
            color="primary"
            @click="onDialogConfirm"
          >
            确定
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
// 自定义样式，调整默认样式以适应 dark 模式
import hljs from 'highlight.js'
import MyImageViewer from '../components/MyImageViewer'
import CommentTreeItem from '../components/CommentTreeItem'
import OauthLoginCard from '../components/OauthLoginCard'

export default {
  components: {
    OauthLoginCard,
    CommentTreeItem,
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
      const res = await app.$api.getArticle(1)
      article = res.data.data
    }
    const breadcrumbs = [
      {
        disabled: false,
        to: '/',
        text: '首页',
        nuxt: true
      },
      {
        disabled: true,
        to: '/',
        text: '留言区',
        nuxt: true
      }
    ]
    store.dispatch('actionSetCurrentMenu', '留言区')
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
      snackbar: {
        show: false,
        timeout: 2000,
        color: 'primary',
        message: ''
      },
      dialog: {
        show: false,
        title: '发表评论',
        content: ''
      },
      comments: [],
      article: {},
      imgSrc: null,
      showImgView: false,
      loading: true,
      user: null,
      currentComment: {
        content: ''
      },
      submitParams: {
        belong: null,
        parent_id: null,
        article_id: null,
        content: ''
      }
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
    },
    card_style () {
      return this.$vuetify.theme.isDark
        ? 'border: 1px solid #666666'
        : 'border: 1px solid #f2f6fc'
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
    this.user = this.$store.state.user
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
        this.loadComments()
      })
    }, 120)
  },
  methods: {
    loadComments () {
      this.$api.getComments(this.article.id).then((response) => {
        this.comments = response.data.data
      })
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
    },
    onOauthClick (type) {
      let page = window.location.pathname
      page = encodeURIComponent(page)
      // page = page.substring(1);
      const url = process.env.API_HOST + '/oauth/login/' + type + '?frontend_url=' + page
      console.log(url)
      window.location.href = url
    },
    onLogoutClick () {
      const type = this.$store.state.user.type
      const accessToken = this.$store.state.user.access_token
      this.$api.logout(type, accessToken).then(() => {
        this.$store.dispatch('actionSetUser', null)
        location.reload()
      }).catch(() => {
        this.$store.dispatch('actionSetUser', null)
        location.reload()
      })
    },
    onSubmitComment (isTop = true) {
      if (!this.user) {
        const snackbar = this.snackbar
        snackbar.message = '请先点击头像登录再评论'
        snackbar.show = true
        this.snackbar = snackbar
        return
      }
      this.submitParams.article_id = this.article.id
      if (isTop) {
        this.submitParams.parent_id = null
        this.submitParams.belong = null
      } else {
        this.submitParams.parent_id = this.currentComment.id
        this.submitParams.belong = this.currentComment.belong
        this.submitParams.content = this.dialog.content
      }
      const type = this.$store.state.user.type
      const accessToken = this.$store.state.user.access_token
      this.$api.commentSubmit(type, accessToken, this.submitParams).then(() => {
        this.loadComments()
        this.onDialogClose()
      })
    },
    onDialogClose () {
      this.currentComment.content = ''
      this.dialog.content = ''
      this.dialog.show = false
    },
    onDialogConfirm () {
      this.submitParams.content = this.dialog.content
      this.onSubmitComment(false)
    },
    onReply (item) {
      this.currentComment.id = item.parent_id
      this.currentComment.belong = item.belong
      this.dialog.show = true
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
  .reply {
    font-size: 15px;
    font-weight: 300;
    padding: 8px 16px;
    background-color: #ecf8ff;
    border-radius: 4px;
    border-left: 5px solid #50bfff;
    margin: 20px 0;
  }

  .button-area {
    margin-top: 20px;
    text-align: right;
  }

</style>
