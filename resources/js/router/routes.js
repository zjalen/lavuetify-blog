const routes = [
    { name: '/', path: '/', component: () => import('../views/Articles') },
    { name: 'index', path: '/', component: () => import('../views/Articles') },
    { name: 'cate', path: '/cates/:cate', component: () => import('../views/Articles') },
    { name: 'topic', path: '/topics/:topic', component: () => import('../views/Articles') },
    { name: 'tag', path: '/tags/:tag', component: () => import('../views/Articles') },
    { name: 'article', path: '/cates/:cate/articles/:id', component: () => import('../views/Article') },
    { name: 'page', path: '/pages/:page', component: () => import('../views/Page') },
];

export default routes;