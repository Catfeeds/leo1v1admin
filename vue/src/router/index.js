import Vue from 'vue'
import Router from 'vue-router'
const _import = require('./_import_' + process.env.NODE_ENV)
const _import_ts = require('./_import_' + process.env.NODE_ENV + "_ts")
// in development-env not use lazy-loading, because lazy-loading too many pages will cause webpack hot update too slow. so only in production use lazy-loading;
// detail: https://panjiachen.github.io/vue-element-admin-site/#/lazy-loading

Vue.use(Router)


// 不要手动修改
export const constantRouterMap = [
  { path: '/test/get_user_list1', component: _import_ts('test/get_user_list1')  },
  { path: '/test/get_user_list', component: _import_ts('test/get_user_list')  },

  { path: '*', component: _import('404')  }
]

export default new Router({
  // mode: 'history', //后端支持可开
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRouterMap
})
