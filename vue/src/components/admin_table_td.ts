import Vue from 'vue'
import Component from 'vue-class-component'

// @Component 修饰符注明了此类为一个 Vue 组件
@Component({
  // 所有的组件选项都可以放在这里
  template : require("./admin_table_td.html" ),
  created  : function () {
  },

  data : function () {
    return {

    };
  },
  props : {

    default_display:{
      type: Boolean,
      require: false,
      "default" : function(){
        return undefined;
      }
    },
    need_power: {
      type: [Function, String ],
      require: false,
    },
    field_info: {
      type: Object ,
      require: false,
      "default":function() {return  {};}
    },
    use_slot :{
      type:  Boolean,
      require: false,
      "default":true,
    }
  },
  computed : {

    real_field_info:function(){
      var field_info = this.$props.field_info;
      if (this.$props.need_power) {
        field_info.need_power= this.$props.need_power;
      }

      if (this.$props.default_display !== undefined ) {
        field_info.default_display= this.$props.default_display;
      }
      return field_info ;
    }

  },
  mounted : function(){
  },
})

export default class admin_table_td extends Vue {


  on_created (){

  }

  check_show(  ) {
    var field_info = this["real_field_info"];
    return this.$parent.$parent["check_show"]( field_info);
  }


  field_render(  ) {
    var item= this.$parent.$props.row_data;
    var field_info = this["real_field_info"];
    var field_name= field_info["field_name"];

    var field_value="";
    if (field_name){
      field_value=item[field_name];
    }
    if (field_info["render"]) {
      return field_info["render"]( field_value ,item  );
    }else{
      return field_value;
    }
  }

}
