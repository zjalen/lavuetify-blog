<template>
  <v-form ref="form">
    <v-row>
      <v-col
        cols="12"
        md="6"
        style="overflow-y: scroll"
      >
        <v-text-field
          label="名称"
          outlined
          :rules="nameRules"
          v-model="item.name"
        >
        </v-text-field>
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
          <v-img :src="image_url" max-width="200px" aspect-ratio="1.7"></v-img>
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
import { createTopic, getTopic, updateTopic } from '../api'

export default {
  name: 'TopicCreateAndEdit',
  data:() => ({
    item: {},
    cover_file: null,
    image_url: null,
    nameRules: [
      value => !!value || '名称不能为空',
    ],
    fileRules: [
      value => !!value || '封面不能为空',
      value => !value || value.size < 2000000 || '图片不能超过 2 MB!',
    ],
  }),
  mounted () {
    if (this.$route.params.id) {
      getTopic(this.$route.params.id).then(response => {
        this.item = response.data
        this.image_url = this.item.cover_full_path
      })
    }
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
      document.getElementById('cover_input').click()
    },
    onCancel() {
      history.go(-1)
    },
    onSubmit() {
      if (!this.item.name || (!this.cover_file && !this.image_url)) {
        this.$refs.form.validate()
        return
      }
      let params = new FormData
      params.append('name', this.item.name)
      params.append('cover', this.cover_file)
      if (this.item.id) {
        params.append('_method', 'PUT')
        updateTopic(this.item.id, params).then(() => {
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
      }else {
        createTopic(params).then(() => {
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
