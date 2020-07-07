const routes = [
    { path: '/login', component: () => import('@/layouts/LayoutWithoutBar'), children:
            [
                { name: 'login', path: '', component: () => import('@/views/Login') },
            ]
    },
    // { name: 'index', path: '/', component: () => import('../views/Articles') },
];

export default routes;
