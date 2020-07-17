const routes = [
  {
    path: '/login',
    component: () => import('@/layouts/LayoutWithoutBar'),
    children:
      [
        { name: 'login', path: '', component: () => import('@/views/Login') },
      ],
  },
  {
    path: '/', component: () => import('@/layouts/Layout'), children:
      [
        { name: 'home', path: '/', component: () => import('../views/Home') },
        { name: 'articles', path: '/articles', component: () => import('../views/Articles') },
        { name: 'articles-create', path: '/articles/create', component: () => import('../views/ArticleCreateAndEdit') },
        { name: 'articles-edit', path: '/articles/edit/:id', component: () => import('../views/ArticleCreateAndEdit') },
        { name: 'comments', path: '/comments', component: () => import('../views/Comments') },
        { name: 'topics', path: '/topics', component: () => import('../views/Topics') },
        { name: 'topics-create', path: '/topics/create', component: () => import('../views/TopicCreateAndEdit') },
        { name: 'topics-edit', path: '/topics/edit/:id', component: () => import('../views/TopicCreateAndEdit') },
        { name: 'categories', path: '/categories', component: () => import('../views/Categories') },
        { name: 'tags', path: '/tags', component: () => import('../views/Tags') },
        { name: 'pages', path: '/pages', component: () => import('../views/Pages') },
        { name: 'pages-create', path: '/pages/create', component: () => import('../views/PageCreateAndEdit') },
        { name: 'pages-edit', path: '/pages/edit/:id', component: () => import('../views/PageCreateAndEdit') },
        { name: 'links', path: '/links', component: () => import('../views/Links') },
        { name: 'links-create', path: '/links/create', component: () => import('../views/LinkCreateAndEdit') },
        { name: 'links-edit', path: '/links/edit/:id', component: () => import('../views/LinkCreateAndEdit') },
        { name: 'setting', path: '/setting', component: () => import('../views/AdminUserSetting') },
        { name: 'reset-password', path: '/reset-password', component: () => import('../views/AdminUserResetPassword') },
      ],
  },
]

export default routes
