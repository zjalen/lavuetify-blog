// 1. 引入 vue-router 组件
import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

// 2. 定义路由
// 每个路由应该映射一个组件。
import routes from './routes';

// 3. 创建 router 实例，然后传 `routes` 配置
export default new VueRouter({
    mode: 'history',
    routes: routes
});
