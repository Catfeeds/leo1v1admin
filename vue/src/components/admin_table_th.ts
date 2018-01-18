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
    default_display:{
      type: [Boolean, String ],
      require: false,
      "default" : function(){
        return undefined;
      }
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

      if (this.$props.default_display !== undefined ) {
        if ( !this.$props.default_display || this.$props.default_display ==="false") {
          field_info.default_display= false;
        }else{
          field_info.default_display= true;
        }
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

  do_sort() {
    var order_by_str = "";
    var $this = $(this);
    var field_info =   this["real_field_info"];
    var field_name =  field_info .order_field_name ;
    if (this.get_sort_class()== "fa-sort-down") {
      order_by_str = field_name + " " + "asc";
    } else {
      order_by_str = field_name + " " + "desc";
    }
    this.$parent.$parent["reload_page_by_page_info"](null, null, order_by_str );
  }

  check_show( ) {
    var field_info =   this["real_field_info"];
    var title=$.trim( this.$slots["default"]["0"]["text"]);
    return this.$parent["check_show"](field_info,title);
  }
  get_sort_class( ){
    return this.$parent["get_sort_class"]( this["real_field_info"] );
  }
  get_title() {

  }

}
