<template>
  <div>
    <v-row class="mx-0">
      <v-col :sm="6" :md="3" xs="12">
        <card-with-icon :item="info.total"></card-with-icon>
      </v-col>
      <v-col :sm="6" :md="3" xs="12">
        <card-with-icon :item="info.publish"></card-with-icon>
      </v-col>
      <v-col :sm="6" :md="3" xs="12">
        <card-with-icon :item="info.top"></card-with-icon>
      </v-col>
      <v-col :sm="6" :md="3" xs="12">
        <card-with-icon :item="info.draft"></card-with-icon>
      </v-col>
    </v-row>
    <v-row class="mx-0">
      <v-col cols="12">
        <common-table
          ref="custom_table"
          :headers="table_headers"
          :header_actions="table_head_actions"
          :api="table_api"
          :filters="table_filters"
          :image_columns="table_image_columns"
          :bool_columns="table_bool_columns"
          :actions="table_actions"
          @headerAction="onHeaderAction"
          @action="onClickAction"
        ></common-table>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import CardWithIcon from '../components/card/CardWithIcon'

import { getArticles, deleteArticle, getArticlesCount, updateArticle } from '../api/article'
import CommonTable from '../components/table/CommonTable'

export default {
  name: 'Articles',
  components: { CommonTable, CardWithIcon },
  data () {
    return {
      current_article: null,
      info: {
        total: {
          color: 'primary',
          subtitle: '文章总数',
          title: '0',
          icon: 'newspaper-variant',
        },
        draft: {
          color: 'secondary',
          subtitle: '草稿箱',
          title: '0',
          icon: 'newspaper-variant',
        },
        top: {
          color: 'error',
          subtitle: '置顶',
          title: '0',
          icon: 'newspaper-variant',
        },
        publish: {
          color: 'deep-orange',
          subtitle: '发布数',
          title: '0',
          icon: 'newspaper-variant',
        },
      },
      table_headers: [
        { text: 'id', value: 'id', align: 'center', sortable: true },
        { text: '标题', value: 'title', align: 'left', sortable: true, width: 500 },
        { text: '封面', value: 'cover_full_path', align: 'center', width: 150, sortable: false},
        { text: '置顶', value: 'is_top', align: 'center', width: 50, sortable: true },
        { text: '分类', value: 'category.name', align: 'left', sortable: false },
        { text: '主题', value: 'topic.name', align: 'left', sortable: false },
        { text: '创建日期', value: 'created_at', align: 'left', sortable: true },
        { text: '点击量', value: 'pageviews', align: 'left', sortable: true },
        { text: '草稿', value: 'is_draft', align: 'center', sortable: true },
        { text: '操作', value: 'action', align: 'center', sortable: false },
      ],
      table_image_columns: ['item.cover_full_path'],
      table_bool_columns: [
        'item.is_top',
        'item.is_draft',
      ],
      table_api: getArticles,
      table_filters: [
        {
          column: 'title',
          title: '标题',
          value: '',
          type: 'text',
          options: [],
          compare: 'like',
          sign: 'where',
        },
        {
          column: 'name',
          title: '分类',
          value: '',
          type: 'text',
          options: [],
          relation_name: 'category',
          compare: 'like',
          sign: 'relation',
        },
        {
          column: 'name',
          title: '主题',
          value: '',
          type: 'text',
          options: [],
          compare: 'like',
          sign: 'relation',
          relation_name: 'topic',
        },
      ],
      table_head_actions: [
        {
          sign: 'create',
          text: '写文章',
          tip: '写一篇新文章',
          icon: 'plus',
        },
      ],
      table_actions: [
        {
          sign: 'edit',
          text: '编辑',
          tip: '编辑文章',
          icon: 'pencil',
        },
        {
          sign: 'delete',
          text: '删除',
          tip: '删除文章',
          icon: 'delete',
        },
      ],
    }
  },
  mounted () {
    this.initData()
    this.$VM.$on('onDialogConfirm', this.onDialogConfirm)
  },
  beforeDestroy() {
    this.$VM.$off('onDialogConfirm', this.onDialogConfirm)
  },
  methods: {
    initData () {
      getArticlesCount().then(response => {
        this.info.total.title = response.data.total
        this.info.draft.title = response.data.draft
        this.info.top.title = response.data.top
        this.info.publish.title = response.data.publish
      })
    },
    onHeaderAction(params) {
      if (params.sign === 'create') {
        this.$router.push({
          name: 'articles-create',
        })
      }
    },
    onClickAction (params) {
      if (params.sign === 'edit') {
        this.$router.push({
          name: 'articles-edit',
          params: {
            id: params.item.id
          },
        })
      }else if (params.sign === 'delete') {
        this.current_article = params.item
        this.dialog = {
          show: true,
          title: '删除文章',
          text: '文章删除后将不可恢复，确定吗？',
          sign: 'deleteArticle'
        }
        this.$store.commit('setDialog', this.dialog)
      }else if (params.sign === 'is_top' || params.sign === 'is_draft') {
        let param = {}
        param[params.sign] = params.item[params.sign] ? 1 : 0
        param['_method'] = 'PUT'
        updateArticle(params.item.id, param).then(response => {
          this.$store.commit('setSnackbar', {
            message: response.data,
            color: 'success',
            timeout: 1500,
            show: true
          })
        })
      }
    },
    onDialogConfirm(sign) {
      this.dialog.show = false
      this.$store.commit('setDialog', this.dialog)
      if (sign === 'deleteArticle') {
        deleteArticle(this.current_article.id).then(() => {
          this.$store.commit('setSnackbar', {
            message: '删除成功',
            color: 'success',
            timeout: 1500,
            show: true
          })
          this.$nextTick(() => {
            this.$refs.custom_table.init()
          })
        })
      }
    },
  },
}
</script>

<style scoped>

</style>
