import Vue from 'vue'
import Component from 'vue-class-component'
import { timingSafeEqual } from 'crypto';
import { strictEqual } from 'assert';

// @Component 修饰符注明了此类为一个 Vue 组件
@Component({
  // 所有的组件选项都可以放在这里
  template : require("./admin_a.html" ),
  created  : function () {
  },

  data : function () {
    return {

    };
  },
  props : {
    opt_info:{
      type     : Object,
      required : true,
    },

    title: {
      type : String,
      required : false,
    },
    need_power: {
      required : false,
    },
    "class_list" : {
      type     : Array,
      required : false,
      "default" :function ()  {
        return [];
      }
    },
    "click"  : {
      type : Function,
      required :false,
      "default" : function() {
        return function() {
          BootstrapDialog.alert("sxx");
        };
      }

    },
    table_config : {//表配置
      type     : Object,
      required : false,
      "default" :  function(){
        return {} ;

      } ,
    },

  },


  computed : {
  },
  mounted : function(){
  },
})



export default class admin_a extends Vue {

  on_created (){
  }

  get_html_power_list() {
    if (this.$props.table_config.html_power_list) {
      return this.$props.table_config.html_power_list ;
    }else{
      return {} ;
    }
  }

  check_need_power(need_power){
    if (need_power ) {
      var html_power_list  = this.get_html_power_list();
      if ($.isFunction(need_power) ) {
        return need_power( html_power_list  );
      }else{
        return html_power_list[need_power ];
      }
    }else{
      return true;
    }
  }

  do_click( $event : MouseEvent ) {
    this.$props.click( $event, this.$parent.$props.row_data);
  }

}
