const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin')
module.exports = {
  "transpileDependencies": [
    "vuetify"
  ],
  plugins: [
      new VuetifyLoaderPlugin()
    ],
  devServer: {
    // proxy: {
    //     '/api': {    // search为转发路径
    //         target: 'http://jalen-blog.test',  // 目标地址
    //         ws: true, // 是否代理websockets
    //         changeOrigin: true,   // 设置同源  默认false，是否需要改变原始主机头为目标URL,
    //         pathRewrite: { '^/api': ''}
    //     }
    // }
    },
}
