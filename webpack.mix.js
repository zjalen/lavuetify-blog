const mix = require('laravel-mix');

if (mix.inProduction()) {
    mix.version()
}

const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin')

mix.webpackConfig({
    output: {
        publicPath: '/assets/', // 设置默认打包目录
        chunkFilename: `js/[name].${mix.inProduction() ? '[chunkhash].' : ''}js` // 路由懒加载的时候打包出来的js文件
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                loader:'babel-loader',
                exclude: /(node_modules)/,
                options:{
                    plugins:['@babel/syntax-dynamic-import']
                },
            },
        ],
    },
    plugins: [
         new VuetifyLoaderPlugin()
    ],
}).js('resources/js/app.js', 'public/assets/js') // 打包后台js
   .js('resources/js/app-client.js', 'public/assets/js') // ssr
   .js('resources/js/app-server.js', 'public/assets/js') // ssr
    .sass('resources/sass/app.scss', 'public/assets/css') // 打包后台css
    // .extract(['vue', 'element', 'axios']) // 提取依赖库
    .setResourceRoot('/assets/') // 设置资源目录
    .setPublicPath('public/assets'); // 设置 mix-manifest.json 目录
