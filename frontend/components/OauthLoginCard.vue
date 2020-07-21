<template>
  <div>
    <v-menu v-if="user" offset-y>
      <template v-slot:activator="{ on, attrs }">
        <v-avatar
          v-bind="attrs"
          v-on="on"
        >
          <img
            class="pa-1"
            :src="user.avatar"
            :alt="user.nickname"
          >
        </v-avatar>
      </template>
      <v-list>
        <v-list-item
          @click.stop="logout"
        >
          <v-list-item-title class="d-flex justify-center align-center">
            <v-icon class="mr-2">
              mdi-logout
            </v-icon>
            退出登录
          </v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-menu v-else offset-y>
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          title="使用第三方登录"
          icon
          v-bind="attrs"
          v-on="on"
        >
          <v-icon class="display-1">
            mdi-account
          </v-icon>
        </v-btn>
      </template>
      <v-card class="social-flex">
        <a href="javascript:" @click="login('github')">
          <div class="social-logo">
            <div class="social-div">
              <img style="width: 60px;" src="/images/github.png">
              <img style="width: 60px;" src="/images/github_hover.png">
            </div>
            <div>Github</div>
          </div>
        </a>
        <a href="javascript:" @click="login('qq')">
          <div class="social-logo">
            <div class="social-div">
              <img style="width: 60px;" src="/images/qq.png">
              <img style="width: 60px;" src="/images/qq_hover.png">
            </div>
            <div>QQ</div>
          </div>
        </a>
        <a href="javascript:" @click="login('weibo')">
          <div class="social-logo">
            <div class="social-div">
              <img style="width: 60px;" src="/images/weibo.png">
              <img style="width: 60px;" src="/images/weibo_hover.png">
            </div>
            <div>微博</div>
          </div>
        </a>
      </v-card>
    </v-menu>
  </div>
</template>

<script>
export default {
  name: 'OauthLoginCard',
  props: {
    user: {
      type: Object,
      default: null
    }
  },
  methods: {
    login (type) {
      this.$emit('onOauthClick', type)
    },
    logout () {
      this.$emit('onLogoutClick')
    }
  }
}
</script>

<style lang="scss" scoped>
  .social-flex {
    display: flex;
    justify-content: space-between;

    .social-logo {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      width: 80px;
      height: 80px;
      border-radius: 5px;
      color: #9a9a9a;

      &:hover {
        background-color: var(--v-primary-base);
        color: #fff;

        .social-div {

          img:last-child {
            opacity: 1;
          }

          img:first-child {
            opacity: 0;
          }
        }
      }

      .social-div {
        position: relative;
        width: 60px;
        height: 60px;

        img:last-child {
          position: absolute;
          top: 0;
          left: 0;
          opacity: 0;
          z-index: 1;
          transition: all 0.3s ease-in;

          &:hover {
            opacity: 1;
          }
        }

        img:first-child {
          position: absolute;
          top: 0;
          left: 0;
          opacity: 1;
          z-index: 1;
          transition: all 0.3s ease-in;

          &:hover {
            opacity: 0;
          }
        }
      }
    }
  }
</style>
