// 1. 引入 vue-router 组件
import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

// 2. 定义路由
// 每个路由应该映射一个组件。
import routes from './routes'

// 拦截同一路由参数不同的跳转错误信息
const originalPush = VueRouter.prototype.push
VueRouter.prototype.push = function push(location) {
  return originalPush.call(this, location).catch(err => err)
}

// 3. 创建 router 实例，然后传 `routes` 配置
export default new VueRouter({
    // mode: 'history',
    routes: routes
})
