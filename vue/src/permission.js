import router from './router'
import store from './store'
import { getToken } from '@/utils/auth' // 验权

router.beforeEach((to, from, next) => {
  next()
  /*
  if (getToken()) {
    if (to.path === '/login') {
      next({ path: '/' })
    } else {
      if (store.getters.roles.length === 0) {
        store.dispatch('GetInfo').then(res => { // 拉取用户信息
          next()
        }).catch(() => {
          store.dispatch('FedLogOut').then(() => {
            next({ path: '/login' })
          })
        })
      } else {
        next()
      }
    }
  } else {
      next()
  }
  */
})

router.afterEach(() => {
})
