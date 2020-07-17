const mix = require('laravel-mix')

if (mix.inProduction()) {
  mix.version()
}
else {
  mix.sourceMaps()
}

const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin')
// const VueLoaderPlugin = require('vue-loader/lib/plugin')

mix.webpackConfig({
  output: {
    publicPath: '/backend/', // 设置默认打包目录
    chunkFilename: `js/[name].${mix.inProduction() ? '[chunkhash].' : ''}js`, // 路由懒加载的时候打包出来的js文件
  },
  module: {
    rules: [
      // { test: /\.vue$/, loader: 'vuetify-loader' },
      {
        test: /\.js$/,
        loader: 'babel-loader',
        exclude: /(node_modules)/,
        options: {
          plugins: ['@babel/syntax-dynamic-import'],
        },
      },
    ],
  },
  resolve: {
    alias: {
      '@': path.join(__dirname, 'resources/js'),
    },
  },
  plugins: [
    // new VueLoaderPlugin(),
    new VuetifyLoaderPlugin(),
  ],
}).js('resources/js/app.js', 'public/backend/js') // 打包后台js
  .sass('resources/sass/app.scss', 'public/backend/css') // 打包后台css
  // .extract(['vue', 'element', 'axios']) // 提取依赖库
  .setResourceRoot('/backend/') // 设置资源目录
  .setPublicPath('public/backend') // 设置 mix-manifest.json 目录
