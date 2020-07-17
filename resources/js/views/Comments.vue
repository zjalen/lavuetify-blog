<template>
  <div>
    <v-row class="mx-0">
      <v-col cols="12">
        <common-table
          ref="custom_table"
          :headers="table_headers"
          :api="table_api"
          :filters="table_filters"
          :bool_columns="table_bool_columns"
          :avatar_columns="table_avatar_columns"
          :actions="table_actions"
          @headerAction="onHeaderAction"
          @action="onClickAction"
        ></common-table>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { getComments, deleteComment, updateComment } from '../api/comment'
import CommonTable from '../components/table/CommonTable'

export default {
  name: 'Comments',
  components: { CommonTable },
  data () {
    return {
      current_comment: null,
      table_headers: [
        { text: 'id', value: 'id', align: 'center', sortable: true },
        { text: '用户', value: 'user.nickname', align: 'left', sortable: false },
        { text: '头像', value: 'avatar', align: 'center', sortable: false },
        { text: '文章', value: 'article.title', align: 'left', sortable: false},
        { text: '归属评论', value: 'belong_comment.content', align: 'left', sortable: false },
        { text: '内容', value: 'content', align: 'left', sortable: true },
        { text: '父级评论', value: 'parent.content', align: 'left', sortable: false },
        { text: '评论时间', value: 'created_at', align: 'center', sortable: true },
        { text: '审核', value: 'is_checked', align: 'center', width: 80, sortable: true },
        { text: '操作', value: 'action', align: 'center', sortable: false },
      ],
      table_bool_columns: [
        'item.is_checked'
      ],
      table_avatar_columns: ['item.avatar'],
      table_api: getComments,
      table_filters: [
        {
          column: 'content',
          title: '内容',
          value: '',
          type: 'text',
          options: [],
          compare: 'like',
          sign: 'where',
        },
        {
          column: 'title',
          title: '文章标题',
          value: '',
          type: 'text',
          options: [],
          relation_name: 'article',
          compare: 'like',
          sign: 'relation',
        },
        {
          column: 'nickname',
          title: '用户名',
          value: '',
          type: 'text',
          options: [],
          compare: 'like',
          sign: 'relation',
          relation_name: 'user',
        },
      ],
      table_actions: [
        {
          sign: 'delete',
          text: '删除',
          tip: '删除评论',
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
    },
    onHeaderAction(params) {
      if (params.sign === 'create') {
        this.$router.push({
          name: 'comments-create',
        })
      }
    },
    onClickAction (params) {
      if (params.sign === 'edit') {
        this.$router.push({
          name: 'comments-edit',
          params: {
            id: params.item.id
          },
        })
      }else if (params.sign === 'delete') {
        this.current_comment = params.item
        this.dialog = {
          show: true,
          title: '删除评论',
          text: '确定删除删除评论吗？',
          sign: 'deleteComment'
        }
        this.$store.commit('setDialog', this.dialog)
      }else if (params.sign === 'is_checked') {
        let param = {}
        param[params.sign] = params.item[params.sign] ? 1 : 0
        updateComment(params.item.id, param).then(response => {
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
      if (sign === 'deleteComment') {
        deleteComment(this.current_comment.id).then(() => {
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
