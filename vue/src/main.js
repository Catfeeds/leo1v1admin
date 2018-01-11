import Vue from 'vue'
//import ElementUI from 'element-ui'
//import 'element-ui/lib/theme-chalk/index.css'
//import locale from 'element-ui/lib/locale/lang/en'
import App from './App'
import router from './router'
import store from './store'
//import '@/icons' // icon
import '@/permission' // 权限
import admin_table from "./components/admin_table"
import admin_table_row from "./components/admin_table_row"
import admin_a from "./components/admin_a"
import admin_table_th from "./components/admin_table_th"
import admin_table_td from "./components/admin_table_td"
Vue.component('admin-table' ,admin_table );
Vue.component('admin-table-row' ,admin_table_row );
Vue.component('admin-table-th' ,admin_table_th );
Vue.component('admin-table-td' ,admin_table_td );
Vue.component('admin-a' ,admin_a );

Vue.component('admin-remote-script', {

  render: function (createElement) {
    var self = this;
    return createElement('script', {
      attrs: {
        type: 'text/javascript',
        src: window["admin_api"]+ this.src
      },
      on: {
        load: function (event) {
         self.$emit('load', event);
        },
        error: function (event) {
          self.$emit('error', event);
        },
        readystatechange: function (event) {
          if (this.readyState == 'complete') {
            self.$emit('load', event);
          }
        }
      }
    });
  },

  props: {
    src: {
      type: String,
      required: true
    }
  }
});

Vue.component('admin-remote-css', {

  render: function (createElement) {
      //<link rel="stylesheet" href="http://self.admin.leo1v1.com/AdminLTE-2.4.0-rc/bower_components/bootstrap/dist/css/bootstrap.min.css">
    var self = this;
    return createElement('link', {
      attrs: {
        rel: 'stylesheet',
        href: window["admin_api"]+ this.href
      },
    });
  },

  props: {
    href: {
      type: String,
      required: true
    }
  }
});

Vue.config.productionTip = false

new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: { App }
})
