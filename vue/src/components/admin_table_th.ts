import Vue from 'vue'
import Component from 'vue-class-component'

// @Component 修饰符注明了此类为一个 Vue 组件
@Component({
  // 所有的组件选项都可以放在这里
  template : require("./admin_table_th.html" ),
  created  : function () {
  },

  data : function () {
    return {

    };
  },
  props : {

    need_power: {
      type: [Function, String ],
      require: false,
    },

    order_field_name: {
      type:  String ,
      require: false,
    },
    field_info: {
      type: Object ,
      require: false,
      "default" : function(){
        return {};
      }
    }
  },
  computed : {
    real_field_info:function(){
      var field_info = this.$props.field_info;
      if (this.$props.need_power) {
        field_info.need_power= this.$props.need_power;
      }

      if (this.$props.order_field_name ) {
        field_info.order_field_name= this.$props.order_field_name;
      }
      return field_info ;
    }
  },
  mounted : function(){
  },
})


export default class admin_table_th extends Vue {


  on_created (){

  }

  check_show( ) {
    return this.$parent["check_show"]( this["real_field_info"]);
  }
  get_sort_class( ){
    return this.$parent["get_sort_class"]( this["real_field_info"] );
  }

}
