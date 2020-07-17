<template>
  <v-form ref="form">
    <v-row>
      <v-col
        cols="12"
        md="6"
      >
        <v-text-field
          label="昵称"
          outlined
          :rules="nameRules"
          v-model="item.name"
        >
        </v-text-field>
        <v-file-input
          id="avatar"
          outlined
          :rules="fileRules"
          accept="image/png, image/jpeg, image/bmp, image/gif"
          dense
          prepend-icon=""
          append-icon="mdi-camera"
          label="头像"
          hint="点击上传图片"
          v-model="avatar"
          filled
          @change="onFileChange"
          @click:append="onAppendClick"
        ></v-file-input>
        <div v-if="image_url" class="d-flex justify-center align-center pb-3">
          <v-img :src="image_url" max-width="100px" aspect-ratio="1"></v-img>
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
import { getAdminUserInfo, updateAdminUserInfo } from '../api'

export default {
  name: 'AdminUserSetting',
  data:() => ({
    item: {},
    avatar: null,
    image_url: null,
    nameRules: [
      value => !!value || '名称不能为空',
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
          value => !!value || '头像不能为空',
          value => !value || value.size < 2000000 || '图片不能超过 2 MB!',
        ]
    },
  },
  mounted () {
    getAdminUserInfo(this.$route.params.id).then(response => {
      this.item = response.data
      this.image_url = this.item.avatar_full_path
    })
  },
  methods: {
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
      document.getElementById('avatar').click()
    },
    onCancel() {
      history.go(-1)
    },
    onSubmit() {
      if (!this.$refs.form.validate()) {
        return false
      }
      let params = new FormData
      params.append('name', this.item.name)
      if (this.avatar) params.append('avatar', this.avatar)
      if (this.item.id) {
        params.append('_method', 'PUT')
        updateAdminUserInfo(params).then(response => {
          this.$store.dispatch('actionSetUser', response.data)
          this.$store.commit('setSnackbar', {
            message: '操作成功',
            color: 'success',
            timeout: 1500,
            show: true
          })
          this.$nextTick(() => {
            this.onCancel()
          })
        })
      }
    }
  }
}
</script>

<style scoped>

</style>
