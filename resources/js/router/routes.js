
const routes = [
  {
    path: '/',
    component: () => import('../layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('../pages/ApplicationLog.vue'), name: 'log' },
      { path: 'create', component: () => import('../pages/CreateApplication.vue'), name: 'create' },
      { path: 'report', component: () => import('../pages/Report.vue'), name: 'report' },
    ]
  }
]

routes.push({
  path: '*',
  component: () => import('../pages/Error404.vue')
})

export default routes
