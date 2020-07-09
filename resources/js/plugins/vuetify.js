// src/plugins/vuetify.js

import Vue from 'vue'
import Vuetify from 'vuetify/lib'

import colors from 'vuetify/lib/util/colors'
import store from '../store'

Vue.use(Vuetify)

// Translation provided by Vuetify (javascript)
import zhHans from 'vuetify/es5/locale/zh-Hans'

export default new Vuetify({
  lang: {
    locales: { zhHans },
    current: 'zhHans',
  },
  theme: {
      themes: {
          light: {
              primary: colors.teal,
              secondary: colors.lightGreen,
              tertiary: colors.orange,
              accent: colors.shades,
              error: colors.red,
              grey: colors.grey
          },
          dark: {
              primary: colors.blueGrey,
              secondary: colors.shades,
              tertiary: colors.orange,
              accent: colors.shades,
              error: colors.red,
              grey: colors.grey
          }
      },
    dark: store.getters.dark,
    options: {
      customProperties: true,
    },
  },
})
