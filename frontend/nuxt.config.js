import colors from 'vuetify/es5/util/colors'
require('dotenv').config()

export default {
  mode: 'universal',
  server: {
    port: process.env.SERVER_PORT, // default: 3000
    host: process.env.SERVER_HOST // default: localhost
  },
  /*
  ** Headers of the page
  */
  head: {
    titleTemplate: '%s - ' + 'Jalen 的博客',
    title: process.env.npm_package_name || '',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: process.env.npm_package_description || '' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ]
  },
  /*
  ** Customize the progress-bar color
  */
  loading: { color: colors.orange },
  /*
  ** Global CSS
  */
  css: [
    // '~/assets/custom-markdown.scss'
  ],
  /*
  ** Plugins to load before mounting the App
  */
  plugins: [
    // { src: '~/plugins/vuetify', mode: 'server' },
    { src: '~/plugins/vue-particles', mode: 'client' },
    { src: '~/plugins/axios' }
  ],
  /*
  ** Nuxt.js dev-modules
  */
  buildModules: [
    // Doc: https://github.com/nuxt-community/eslint-module
    '@nuxtjs/eslint-module',
    '@nuxtjs/vuetify'
  ],
  /*
  ** Nuxt.js modules
  */
  modules: [
    // Doc: https://axios.nuxtjs.org/usage
    '@nuxtjs/axios',
    '@nuxtjs/dotenv'
  ],
  /*
  ** Axios module configuration
  ** See https://axios.nuxtjs.org/options
  */
  axios: {
    proxy: true
  },
  proxy: {
    '/api/': { target: process.env.API_HOST, pathRewrite: { '^/api/': '/api/' } },
    '/hljs-styles/': { target: process.env.API_HOST, pathRewrite: { '^/hljs-styles/': '/hljs-styles/' } }
  },
  /*
  ** vuetify module configuration
  ** https://github.com/nuxt-community/vuetify-module
  */
  vuetify: {
    customVariables: ['~/assets/variables.scss'],
    theme: {
      dark: false,
      themes: {
        light: {
          primary: colors.lightGreen,
          secondary: colors.teal,
          tertiary: colors.orange,
          accent: colors.shades,
          error: colors.red,
          grey: colors.grey
        },
        dark: {
          primary: colors.shades,
          secondary: colors.blueGrey,
          tertiary: colors.orange,
          accent: colors.shades,
          error: colors.red,
          grey: colors.grey
        }
      },
      options: {
        customProperties: true
      }
      // themes: {
      //   dark: {
      //     primary: colors.blue.darken2,
      //     accent: colors.grey.darken3,
      //     secondary: colors.amber.darken3,
      //     info: colors.teal.lighten1,
      //     warning: colors.amber.base,
      //     error: colors.deepOrange.accent4,
      //     success: colors.green.accent3
      //   }
      // }
    }
  },
  /*
  ** Build configuration
  */
  build: {
    /*
    ** You can extend webpack config here
    */
    extend (config, ctx) {
    }
  },
  generate: {
    // routes () {
    //   const axios = require('axios')
    //   return axios.get(process.env.API_HOST + '/api/article-ids')
    //     .then((res) => {
    //       return res.data.map((article) => {
    //         return {
    //           route: '/articles/' + article.id
    //           // payload: article
    //         }
    //       })
    //     })
    // }
  }
}
