import router from '../router'
import store from '../store'

router.beforeEach((to, from, next) => {
  if (to.name !== 'login') {
    if (store.getters.token) {
      next()
    }else {
      next({ name: 'login' })
    }
  }else {
    if (store.getters.token) {
      next({name: 'home'})
    }else {
      next()
    }
  }
})
