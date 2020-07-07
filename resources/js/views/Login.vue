<template>
    <v-row
        align="center"
        justify="center"
        class="mx-0 fill-height"
    >
        <v-col
            cols="12"
            sm="8"
            md="4"
        >
            <v-card class="elevation-12">
                <v-card-title class="primary white--text">
                    请登录使用
                </v-card-title>
                <v-form
                    ref="form"
                >
                    <v-row class="mx-0">
                        <v-col cols="12">
                            <v-text-field
                                v-model="form.username"
                                prepend-icon="mdi-account-outline"
                                :rules="rules"
                                label="用户名"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                v-model="form.password"
                                prepend-icon="mdi-lock"
                                type="password"
                                :rules="rules"
                                label="密码"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" class="pa-0">
                            <v-row class="ma-0">
                                <v-col cols="6" class="d-flex justify-center align-center">
                                    <v-img max-height="45" class="mb-3" contain alt="点击刷新" :src="captcha_src"
                                           v-if="show_captcha" @click="refreshCaptcha()"></v-img>
                                </v-col>
                                <v-col cols="6">
                                    <v-text-field
                                        v-model="form.captcha"
                                        label="验证码"
                                        :rules="rules"
                                        placeholder="输入计算结果"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-col>
                        <v-col cols="12">
                            <div class="d-flex justify-end">
                                <v-btn class="primary" @click="onSubmit">登录</v-btn>
                            </div>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
  import { login } from '@/api/login'

  export default {
    name: 'Login',
    data () {
      return {
        form: {
          username: '',
          password: '',
          captcha: '',
        },
        captcha_src: '',
        show_captcha: true,
        rules: [
          v => !!v || '该项必填',
        ],
      }
    },
    mounted () {
      this.refreshCaptcha()
    },
    methods: {
      onSubmit () {
        if (!this.$refs.form.validate()) {
          return
        }
        login(this.form).then(() => {
          location.href = location.origin + '/index'
        }).catch(() => {
          this.refreshCaptcha()
        })
      },
      refreshCaptcha () {
        this.captcha_src = location.origin + '/captcha?' + Math.random()
      },
    },
  }
</script>

<style scoped>

</style>
