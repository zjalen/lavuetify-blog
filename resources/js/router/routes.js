const routes = [
    { path: '/login', component: () => import('@/layouts/LayoutWithoutBar'), children:
            [
                { name: 'login', path: '', component: () => import('@/views/Login') },
            ]
    },
  { path: '/', component: () => import('@/layouts/Layout'), children:
      [
        { name: 'home', path: '/', component: () => import('../views/Home') },
      ]
  },
]

export default routes
