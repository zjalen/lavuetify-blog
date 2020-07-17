<template>
  <v-form ref="form">
    <v-row>
      <v-col
        cols="12"
        md="6"
      >
        <v-text-field
          label="原密码"
          outlined
          :rules="nameRules"
          v-model="item.old_password"
          type="password"
        >
        </v-text-field>
        <v-text-field
          label="新密码"
          outlined
          :rules="nameRules"
          v-model="item.new_password"
          type="password"
        >
        </v-text-field>
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
import { resetPassword } from '../api'
import { logout } from '../api/login'

export default {
  name: 'AdminUserResetPassword',
  data:() => ({
    item: {
      old_password: null,
      new_password: null
    },
    avatar: null,
    image_url: null,
    nameRules: [
      value => !!value || '不能为空',
    ],
  }),
  mounted () {
  },
  methods: {
    onCancel() {
      history.go(-1)
    },
    onSubmit() {
      if (!this.$refs.form.validate()) {
        return false
      }
      resetPassword(this.item).then(() => {
        this.$store.commit('setSnackbar', {
          message: '操作成功',
          color: 'success',
          timeout: 1500,
          show: true
        })
        this.$store.dispatch('actionSetToken', null)
        this.$nextTick(() => {
          this.$router.push({name: 'login'})
        })
      })
    }
  }
}
</script>

<style scoped>

</style>
