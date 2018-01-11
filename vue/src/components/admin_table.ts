import Vue from 'vue'
import Component from 'vue-class-component'
import { timingSafeEqual } from 'crypto';

// @Component 修饰符注明了此类为一个 Vue 组件
@Component({
  // 所有的组件选项都可以放在这里
  template : require("./admin_table.html" ),
  created  : function () {
    this["on_created"](1);
  },

  data : function () {
    return {

    };
  },
  props : {
    table_data : {
      type     : Array,
      required : false,
      "default" :  function(){
        return [];
      },
    },

    auto_show: {//表配置
      type     : Boolean,
      required : true,
      "default" :  function(){
        return true ;
      } ,
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
    real_table_config:function() {
      return this.$props.auto_show? this.$props.table_config: {};
    }
  },
  mounted : function(){
  },
})



export default class admin_table extends Vue {

  table_field_show_config:Object;
  on_created (){
    var table_key = $.get_table_key("field_list");
    this.table_field_show_config={};
    if (!$.check_in_phone()) {
      var val = window.localStorage.getItem(table_key);
      var cur = (new Date()).getTime() / 1000;
      var config = {};
      try {
        if (val) {
          config = JSON.parse(val);
        }
      }
      catch ($e) {
      }

      var me=this;
      if (config && cur - config["log_time"] < 3600) {
        me.table_field_show_config =config;
      }
      else {
        $.do_ajax("/page_common/opt_table_field_list", {
          "opt_type": "get",
          "table_key": table_key
        }, function (resp) {
          me.table_field_show_config =config;
          resp.log_time = cur;
          window.localStorage.setItem(table_key, JSON.stringify(resp));
        });
      }
    }
  }
  show_as_list(e : MouseEvent ,item,index) {
      var th_row = $(e.currentTarget).closest("table").find("thead td");
      var data_row = $(e.currentTarget).closest("tr").find("td");
      var arr :Array<any> = [];
      th_row.each(function (index, element) {
        if (index != 0 && index != th_row.length - 1) {
          arr.push([$(element).text(), $(data_row[index]).text().replace(/,/g, ", ")]);
        }
        if (index == th_row.length - 1) {
          var a_list = $(data_row).find(">div >a");
          var opt_arr : Array<any> = [];
          var $show_inter_data = $("<a class=\"btn fa inter-data\" href=\"javascript:;\"  >inter data</a>");
          if (!$(e.currentTarget).hasClass("table-clean-flag")) {
            opt_arr.push(["操作", $show_inter_data]);
          }
          $show_inter_data.on("click", function () {
            var opt_data_arr  :Array<any>= [];
            $.each(item, function(k, v){
              opt_data_arr.push([k,v]);
            });
            $.show_key_value_table("内部数据", opt_data_arr);
          });
          $.each(a_list, function (a_i, a_item) {
            var new_item = $(a_item).clone();
            if (!new_item.hasClass("td-info")
                && new_item.css("display") != "none") {
              new_item.append(" " + new_item.attr("title"));
              new_item.on("click", function () {
                $(a_item)[0].click();
              });
              opt_arr.push(["操作",
                            new_item]);
            }
          });
          arr = opt_arr.concat(arr);
        }
      });
      $.show_key_value_table("详细信息", arr);
      return false;

  }

  field_render( item, field_info ) {
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
  get_sort_class(field_info){
    var order_by_str=this.$props.table_config.order_by_str;
    if (order_by_str){
      var tmp_arr = order_by_str.split(/ /);
      var order_field_name = tmp_arr[0];
      var order_flag = tmp_arr[1];
      console.log("ORDER:", field_info.order_field_name  );
      if (order_field_name==field_info.order_field_name ) {
        if (order_flag == "asc") {
          return "fa-sort-up";
        }
        else {
          return "fa-sort-down";
        }
      }else{
        return "fa-sort";
      }
    }else{
        return "fa-sort";
    }
  }
  //不输出
  check_power_show (field_info):boolean{
    var show_flag=true;
    if (field_info.need_power){
      show_flag= this.$props.table_config.html_power_list&& this.$props.table_config.html_power_list[field_info.need_power];
    }
    return show_flag;
  }
  check_show(field_info) {
    return this.check_power_show(field_info) && this.check_config_show(field_info);
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

  //隐藏
  check_config_show(field_info):boolean{
    //check 配置
    var field_list=this.table_field_show_config["field_list"];
    var config_value= field_list && field_list[field_info.title];
    if ( config_value === undefined || config_value===null ) {
      if (field_info.default_display===false) {
        return false;
      }else{
        return true;
      }
    }else{
      //console.log("POWER ", config_value );
      return config_value ;
    }
  }
  download( e :MouseEvent) {
    var csv=$(e.currentTarget).closest("table").table2CSV({
      delivery: 'value',
      headerSelector: 'thead td',
      columnSelector: 'td',
    });
     var href= 'data:text/csv;charset=UTF-8,'
      + encodeURIComponent(csv);

    var date_str=$.DateFormat((new Date).getTime()/1000,  "yyyy-MM-dd_hh-mm-ss" );
    var file_name=$.get_table_key("")+date_str+".csv" ;

    $(e.currentTarget) .attr({
                    'download': file_name,
                    'href': href ,
                    'target': '_blank'
                });
  }

  config_field_list(e :MouseEvent ){
    var arr :Array<any>= [];
    var me=this;
    $.each(this.$props.table_config.field_list, function (i, field_info) {
      if(me.check_power_show(field_info) ){
        var $input = $("<input type=\"checkbox\"/>");
        var title= field_info .title;
        if (me.check_config_show(field_info)){
          $input.attr("checked", "checked");
        }
        $input.data("index", title);
        arr.push([title, $input]);
      }

    });
    var table_key = $.get_table_key("field_list");
    $.show_key_value_table("列显示配置", arr, [{
      label: '默认',
      cssClass: 'btn-primary',
      action: function (dialog) {
        $.do_ajax("/page_common/opt_table_field_list", {
          "opt_type": "set",
          "table_key": table_key,
          "data": ""
        });
        //alert(" XXXXX set table_key clean 1 ");
        window.localStorage.setItem(table_key, "");
      }
    }, {
      label: '确认',
      cssClass: 'btn-warning',
      action: function (dialog) {
        var config_map = {};
        $.each(arr, function (i, item) {
          var $input = item[1];
          var index = $input.data("index");
          var value = $input.prop("checked");
          config_map[index] = value;
        });
        $.do_ajax("/page_common/opt_table_field_list", {
          "opt_type": "set",
          "table_key": table_key,
          "data": JSON.stringify(config_map)
        }, function () {
          $.do_ajax("/page_common/opt_table_field_list", {
            "opt_type": "get",
            "table_key": table_key
          }, function (resp) {
            var cur = (new Date()).getTime() / 1000;
            resp.log_time = cur;
            //alert("XXXX SET :"+ JSON.stringify(resp)  );
            window.localStorage.setItem(table_key, JSON.stringify(resp));
            window.location.reload();
          });
        });
      }
    }]);

  }
}
