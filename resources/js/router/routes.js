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
      ],
  },
]

export default routes
