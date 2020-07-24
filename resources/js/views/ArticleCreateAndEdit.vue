<template>
  <v-form ref="form">
    <v-row>
      <v-col
        cols="12"
        md="8"
        lg="9"
      >
        <v-text-field
          label="标题"
          outlined
          :rules="titleRules"
          v-model="article.title"
        >
        </v-text-field>
        <v-textarea
          label="摘要，留空默认选取正文前 60 字"
          outlined
          rows="2"
          v-model="article.description"
        >
        </v-textarea>
        <div class="markdown-area">
          <mavon-editor ref="md" :codeStyle="$store.getters.light_code_style" @imgAdd="imgAdd" @imgDel="imgDel"
                        v-model="article.content_md" :subfield="true" :toolbarsFlag="true" :boxShadow="false"
                        style="min-height: 500px;border: 1px solid #ccc;"
                        @change="changeData">
          </mavon-editor>
        </div>
      </v-col>
      <v-col
        cols="12"
        md="4"
        lg="3"
      >
        <v-file-input
          id="cover_input"
          outlined
          :rules="fileRules"
          accept="image/png, image/jpeg, image/bmp, image/gif"
          dense
          prepend-icon=""
          append-icon="mdi-camera"
          label="封面"
          hint="点击上传图片"
          v-model="cover_file"
          filled
          @change="onFileChange"
          @click:append="onAppendClick"
        ></v-file-input>
        <div v-if="image_url" class="d-flex justify-center align-center pb-3">
          <v-img :src="image_url" max-width="200px" aspect-ratio="1"></v-img>
        </div>
        <v-card class="mt-4">
          <v-card-title>文章分类</v-card-title>
          <v-card-text>
            <v-select
              v-model="article.category_id"
              :items="categories"
              item-text="name"
              item-value="id"
              :rules="categoryRules"
            >
            </v-select>
          </v-card-text>
        </v-card>
        <v-card class="mt-4">
          <v-card-title>文章主题</v-card-title>
          <v-card-text>
            <v-select
              v-model="article.topic_id"
              :items="topics"
              item-text="name"
              item-value="id"
              clearable
            >
            </v-select>
          </v-card-text>
        </v-card>
        <v-card class="mt-4">
          <v-card-title>文章标签</v-card-title>
          <v-card-subtitle>可输入新标签并回车添加</v-card-subtitle>
          <v-card-text>
            <v-combobox
              v-model="article.tag_names"
              :items="tags"
              chips
              clearable
              label="文章标签"
              multiple
              solo
            >
              <template v-slot:selection="{ attrs, item, select, selected }">
                <v-chip
                  v-bind="attrs"
                  class="primary lighten-2"
                  :input-value="selected"
                  close
                  @click="select"
                  @click:close="remove(item)"
                >
                  <span>{{ item }}</span>
                </v-chip>
              </template>
            </v-combobox>
          </v-card-text>
        </v-card>
        <v-row class="mx-2">
          <v-switch v-model="article.is_top" label="置顶" inset></v-switch>
          <v-spacer/>
          <v-switch v-model="article.is_draft" label="草稿" inset></v-switch>
        </v-row>
        <v-divider/>
        <v-row class="mx-0 mt-4">
          <v-btn class="warning" @click="onCancel">
            返回
            <v-icon right color="white">mdi-backup-restore</v-icon>
          </v-btn>
          <v-spacer/>
          <v-btn class="primary" @click="onSubmit">
            发布
            <v-icon right color="white">mdi-send</v-icon>
          </v-btn>
        </v-row>
      </v-col>
    </v-row>
  </v-form>
</template>

<script>
import {
  getArticle,
  updateArticle,
  createArticle,
  uploadArticleImage,
  deleteArticleImage,
  getCategories,
  getTopics,
  getArticlesTagList,
} from '../api/article'

import '../scss/custom-markdown.scss'
import { mavonEditor } from 'mavon-editor'
import 'mavon-editor/dist/css/index.css'

export default {
  components: {
    mavonEditor,
  },
  name: 'ArticleCreateAndEdit',
  data: () => ({
    article_id: null,
    form_data: {
      title: null,
      description: null,
      category_id: null,
      topic_id: null,
      tag_names: [],
      cover: null,
      is_top: false,
      is_draft: false,
      content_md: null,
      content_html: null,
    },
    article: {},
    categories: [],
    topics: [],
    tags: [],
    cover_file: null,
    image_url: null,
    titleRules: [
      value => !!value || '标题必填',
    ],
    categoryRules: [
      value => !!value || '分类必选',
    ],
  }),
  computed: {
    fileRules () {
      return this.image_url ?
        [
          value => !value || value.size < 2000000 || '图片不能超过 2 MB!',
        ]
        :
        [
          value => !!value || '封面不能为空',
          value => !value || value.size < 2000000 || '图片不能超过 2 MB!',
        ]
    },
  },
  mounted () {
    this.article_id = this.$route.params.id
    if (this.article_id) {
      getArticle(this.$route.params.id).then(response => {
        this.article = response.data
        this.image_url = this.article.cover_full_path
      })
    }
    getCategories().then(response => {
      this.categories = response.data.items
    })
    getTopics().then(response => {
      this.topics = response.data.items
    })
    getArticlesTagList().then(response => {
      this.tags = response.data.items
    })
    setTimeout(() => {
      this.$nextTick(() => {
        const style = this.$store.state.light_code_style
        const oldStyle = 'github'
        this.importHighlightStyle(style, oldStyle)
        // document.getElementsByClassName('v-show-content')[0].style.backgroundColor='rgba(0,0,0,0)'
      })
    }, 120)
  },
  methods: {
    remove (item) {
      this.tags.splice(this.tags.indexOf(item), 1)
      this.tags = [...this.tags]
    },
    onFileChange (file) {
      if (!file) {
        return this.image_url = ''
      }
      let reader = new FileReader()
      reader.readAsDataURL(file)
      reader.onload = () => {
        this.image_url = reader.result
      }
    },
    onAppendClick () {
      document.getElementById('cover_input').click()
    },
    onCancel () {
      history.go(-1)
    },
    onSubmit () {
      if (!this.$refs.form.validate()) {
        return false
      }
      this.params = new FormData()
      if (!this.article['content_html']) {
        return this.$store.commit('setSnackbar', {
          message: '正文不能为空',
          color: 'error',
          timeout: 1000,
          show: true,
        })
      }
      Object.keys(this.form_data).forEach(value => {
        let val = this.article[value] === true ? 1 : this.article[value]
        val = val === false ? 0 : val
        val = (val === undefined || val === '') ? null : val
        this.params.append(value, val)
      })
      this.params.append('cover', this.cover_file)
      if (this.$route.name === 'articles-edit') {
        this.params.append('_method', 'PUT')
        updateArticle(this.article_id, this.params).then(() => {
          this.$store.commit('setSnackbar', {
            message: '修改成功',
            color: 'success',
            timeout: 1000,
            show: true,
          })
          setTimeout(() => {
            this.onCancel()
          }, 1000)
        })
      }
      else {
        createArticle(this.params).then(() => {
          this.$store.commit('setSnackbar', {
            message: '创建成功',
            color: 'success',
            timeout: 1000,
            show: true,
          })
          setTimeout(() => {
            this.onCancel()
          }, 1000)
        })
      }
    },
    // 绑定@imgAdd event
    imgAdd (pos, file) {
      // 第一步.将图片上传到服务器.
      let form_data = new FormData()
      form_data.append('image', file)
      uploadArticleImage(form_data).then(response => {
        // 第二步.将返回的url替换到文本原位置![...](0) -> ![...](url)
        /**
         * $vm 指为mavonEditor实例，可以通过如下两种方式获取
         * 1. 通过引入对象获取: `import {mavonEditor} from ...` 等方式引入后，`$vm`为`mavonEditor`
         * 2. 通过$refs获取: html声明ref : `<mavon-editor ref=md ></mavon-editor>，`$vm`为 `this.$refs.md`
         */
        this.$refs.md.$img2Url(pos, response.data)
      })
    },
    imgDel (item) {
      deleteArticleImage({ image_url: item[0] }).then(() => {
        this.$store.commit('setSnackbar', {
          message: '删除成功',
          color: 'success',
          timeout: 1000,
          show: true,
        })
      })
    },
    changeData (value, render) {
      this.article.content_html = render
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
    },
  },
}
</script>

<style scoped>
  .markdown-body {
    z-index: 0;
  }
</style>
