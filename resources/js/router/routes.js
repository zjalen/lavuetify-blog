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
        { name: 'categories-create', path: '/categories/create', component: () => import('../views/CategoryCreateAndEdit') },
        { name: 'categories-edit', path: '/categories/edit/:id', component: () => import('../views/CategoryCreateAndEdit') },
        { name: 'tags', path: '/tags', component: () => import('../views/Tags') },
        { name: 'tags-create', path: '/tags/create', component: () => import('../views/TagCreateAndEdit') },
        { name: 'tags-edit', path: '/tags/edit/:id', component: () => import('../views/TagCreateAndEdit') },
      ],
  },
]

export default routes
