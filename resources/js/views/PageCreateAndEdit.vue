<template>
  <v-form>
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
          v-model="page.title"
        >
        </v-text-field>
        <v-text-field
          label="名称"
          outlined
          :rules="titleRules"
          v-model="page.name"
        >
        </v-text-field>
        <v-text-field
          label="链接"
          outlined
          :rules="titleRules"
          v-model="page.url"
        >
        </v-text-field>
        <div class="markdown-area">
          <mavon-editor ref="md" :codeStyle="$store.getters.light_code_style" @imgAdd="imgAdd" @imgDel="imgDel"
                        v-model="page.content_md" :subfield="true" :toolbarsFlag="true" :boxShadow="false"
                        style="min-height: 500px;border: 1px solid #ccc;"
                        @change="changeData">
          </mavon-editor>
        </div>
        <v-row class="pa-4">
          <v-spacer />
          <v-btn class="warning mx-3" @click="onCancel">取消</v-btn>
          <v-btn class="primary" @click="onSubmit">提交</v-btn>
        </v-row>
      </v-col>
    </v-row>
  </v-form>
</template>

<script>

import '../scss/custom-markdown.scss'
import { mavonEditor } from 'mavon-editor'
import 'mavon-editor/dist/css/index.css'
import { createPage, deletePageImage, getPage, updatePage, uploadPageImage } from '../api/page'

export default {
  components: {
    mavonEditor,
  },
  name: 'PageCreateAndEdit',
  data: () => ({
    item_id: null,
    form_data: {
      title: null,
      name: null,
      content_md: null,
      content_html: null,
      url: null
    },
    page: {},
    fileRules: [
      value => !!value || '封面不能为空',
      value => !value || value.size < 2000000 || '图片不能超过 2 MB!',
    ],
    titleRules: [
      value => !!value || '标题必填'
    ],
    categoryRules: [
      value => !!value || '分类必选'
    ]
  }),
  mounted () {
    this.item_id = this.$route.params.id
    if (this.item_id) {
      getPage(this.item_id).then(response => {
        this.page = response.data
      })
    }
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
    onCancel () {
      history.go(-1)
    },
    onSubmit () {
      this.params = new FormData()
      if (!this.page['content_html']) {
        return this.$store.commit('setSnackbar', {
          message: '正文不能为空',
          color: 'error',
          timeout: 1000,
          show: true,
        })
      }
      Object.keys(this.form_data).forEach(value => {
        let val = this.page[value] === true ? 1 : this.page[value]
        val = val === undefined ? null : this.page[value]
        this.params.append(value, val)
      })
      if (this.$route.name === 'pages-edit') {
        this.params.append('_method', 'PUT')
        updatePage(this.item_id, this.params).then(() => {
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
        createPage(this.params).then(() => {
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
      uploadPageImage(form_data).then(response => {
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
      deletePageImage({image_url: item[0]}).then(() => {
        this.$store.commit('setSnackbar', {
          message: '删除成功',
          color: 'success',
          timeout: 1000,
          show: true
        })
      })
    },
    changeData(value, render) {
      this.page.content_html = render
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
    }
  },
}
</script>

<style scoped>
  .markdown-body {
    z-index: 0;
  }
</style>
