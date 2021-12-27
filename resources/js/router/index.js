import Vue from 'vue'
import VueRouter from 'vue-router'

import routes from './routes'

Vue.use(VueRouter)

export default function () {
  const Router = new VueRouter({
    scrollBehavior: () => ({ x: 0, y: 0 }),
    routes,
    mode: 'history'
  })

  // TODO: Verify if user is correctly login
  Router.beforeEach((to, from, next) => {
      if(!localStorage.getItem('user') && to.name != 'login'){
          next('/login')
      }
    next()
  })

  return Router
}
