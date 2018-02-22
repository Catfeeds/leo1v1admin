import Vue from 'vue'
import Component from 'vue-class-component'
import { timingSafeEqual } from 'crypto';
import { Stream } from 'stream';
import { isFunction, isBoolean } from 'util';

// @Component 修饰符注明了此类为一个 Vue 组件
@Component({
  // 所有的组件选项都可以放在这里
  template : require("./admin_table.html" ),
  created  : function () {
    this["on_created"]();
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
    multi_select:  {
      type     :  [Boolean ],
      required : false,
      "default" :  function(){
        return false;
      },
    },

    table_config : {//表配置
      type     : Object,
      required : false,
      "default" :  function(){
        return {} ;

      },
    },

  },
  computed : {
    real_table_config:function() {
      return this.$props.table_config;
      //return this.$props.table_data.length>0 ? this.$props.table_config: {};
    }
  },
  mounted : function(){
    this["on_mounted"]();
  },
})


export default class admin_table extends Vue {

  table_field_show_config:Object;
  auto_gen_field_list:Array<any>;
  reset_auto_gen_field_list() {
    var me=this;
    me.auto_gen_field_list=[];
    if (! this.$props.table_config.field_list ) {
      $.each( me.$children,function(i, child_item){
        var field_info={};
        if (child_item.$options["_componentTag"]=="admin-table-th"){
          var title=$.trim(child_item.$slots["default"]["0"]["text"]);
          field_info["title"]=title;
          console.log( child_item["real_field_info"] );
          if (me.check_power_show( child_item["real_field_info"])) {
            field_info["power_show_flag"]=true;
            if (child_item.$el.tagName=="TD") {
              field_info["display_flag"]=true;
            }else{
              field_info["display_flag"]=false;
            }
          }else{
            field_info["power_show_flag"]=false;
          }
          me.auto_gen_field_list.push( field_info );
        }
      });
      console.log(me.auto_gen_field_list);
    }
  }
  on_mounted() {
    this.reset_auto_gen_field_list();
  }

  on_created (){

    var me=this;



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
      if ($.isFunction ( field_info.need_power) ) {
        show_flag=field_info.need_power(field_info ) ;
      }else{
        console.log("html_power_list:"+ field_info.need_power, this.$props.table_config.html_power_list );
        show_flag= this.$props.table_config.html_power_list&& this.$props.table_config.html_power_list[field_info.need_power];
      }
    }
    return show_flag;
  }
  check_show(field_info,title="") {
    return this.check_power_show(field_info) && this.check_config_show(field_info,title);
  }
  get_html_power_list() {
    if (this.$props.table_config.html_power_list) {
      return this.$props.table_config.html_power_list ;
    }else{
      return {} ;
    }
  }
  check_show_row_opt( opt_info,  row_data ) {
    var display=true;
    if (isFunction(opt_info.display) ) {
      display= opt_info.display( row_data );
    }else if ( isBoolean(opt_info.display) ) {
      display= opt_info.display;
    }
    if (display) {
      return this.check_need_power(opt_info.need_power);
    }else{
      return false;
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
  check_config_show(field_info,title=""):boolean{
    //check 配置
    if (!title) {
      title=field_info.title;
    }
    var field_list=this.table_field_show_config["field_list"];
    var config_value= field_list && field_list[title];
    if ( config_value === undefined || config_value===null ) {
      if (field_info.default_display===false    ) {
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
    console.log(e );
    var arr :Array<any>= [];
    var me=this;
    if (this.$props.table_config.field_list ) {
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
    }else {
      $.each( me.auto_gen_field_list, function(i ,field_info ){
        if (field_info["power_show_flag"]) {
          var $input = $("<input type=\"checkbox\"/>");
          var title= field_info .title;
          if ( field_info["display_flag"]){
            $input.attr("checked", "checked");
          }
          $input.data("index", title);
          arr.push([title, $input]);
        }
      });
    }

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
        window.location.reload();
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
  reset_row() {
    var me=this;
    me.reset_auto_gen_field_list();
    $.each( me.$children, function(i, child_item){
      if (child_item.$options["_componentTag"]=="admin-table-row")  {
        var td_list=child_item.$children.filter(function(value){
          return value.$options["_componentTag"]=="admin-table-td";
        });
        $.each( td_list , function(td_index, child_td){
          child_td.$data.display_flag=me.auto_gen_field_list[td_index].display_flag;
        });
      }
    });
  }
  do_sort(e : MouseEvent ){

    var order_by_str = "";
    var $this = $(e.currentTarget );
    var field_name =   $this.parent().data("field_name");

    if ($this.hasClass( "fa-sort-down")) {
      order_by_str = field_name + " " + "asc";
    } else {
      order_by_str = field_name + " " + "desc";
    }

    this.$parent["reload_page_by_page_info"](null, null, order_by_str );
  }

  opt_multi_select_all( e : MouseEvent  ) {
    $(e.currentTarget ).closest("table").find (".multi-select-item").iCheck("check");
  }
  opt_multi_select_other(e:MouseEvent ) {
    var opt_list=$(e.currentTarget ).closest("table").find (".multi-select-item");
    $.each( opt_list ,function (i, item){
      var $item= $(item);
      if ($item.iCheckValue()) {
        $item.iCheck("uncheck");
      } else {
        $item.iCheck("check");
      }
    });
  }
  do_select_list( field_name ,call_func ) {

    var select_list:Array<any>=[];
    var opt_list= $(this.$el).find (".multi-select-item");
    var me =this;
    $.each( opt_list ,function (i, item){
      var $item= $(item);
      if ( $item.iCheckValue()) {
        var index= $item.data('index');
        if ( $.isArray( field_name ) ){
          var data_item:any={};
          $.each(field_name, function (i,name){
            data_item[name]= me.$props.table_data[index][name];
          });
          select_list.push(data_item);
        }else{
          select_list.push(me.$props.table_data[index][field_name]  );
        }

      }
    });
    if (select_list.length ) {
      call_func(select_list);
    }else{
      BootstrapDialog.alert("请选择要操作的数据");
    }

  }

}
