/// <reference path="../../d.ts.d/common.d.ts" />
import Vue from 'vue'
import Component from 'vue-class-component'

// @Component 修饰符注明了此类为一个 Vue 组件
@Component({
  // 所有的组件选项都可以放在这里
  created: function () {
    this["do_created"]();
  },
  mounted: function () {
    this["base_init"]();
  },

  data: function () {
    return $.extend({},
    {
      table_data: [],
      table_config:{},
      html_power_list:{},
    } ,this["data_ex"]());
  },
  watch: {
    // 如果路由有变化，会再次执行该方法
    '$route': 'load_data_for_route'
  },

})
export default class vtable extends Vue {

  loading_selector=".vue-table";
  data_ex(){
    return {};
  }

  do_created_ex( call_func ){


    call_func();
  }

  do_select_list( field_name ,call_func : ( select_list:Array<any> )=>void ) {

    var me=this;

    var admin_table :any = undefined;
    $.each(me.$children, function( i ,value ){
      if (value.$options["_componentTag"]=="admin-table")  {
        admin_table= value;
        admin_table.do_select_list( field_name, call_func  );
      }
    });
  }

  do_created( ) {
    var me=this;


    this.do_created_ex ( function(){
      me.load_data();
    } )
  };
  loadScript(url, callback){
    var script = document.createElement ("script")
    script.type = "text/javascript";
    if (script["readyState"]){ //IE
      script["onreadystatechange"]= function(){
        if (script["readyState"]== "loaded" || script["readyState"]== "complete"){
          script["onreadystatechange"]= null;
          callback();
        }
      };
    } else { //Others
      script.onload = function(){
        callback();
      };
    }
    script.src = url;
    document.getElementsByTagName("head")[0].appendChild(script);
  }

  load_admin_js_list( js_list, callback) {
    var me=this;
    var domain= window["admin_api"];
    var cur_index=0;
    var $header=$(".main-header");
    var do_funcion=function (  ){
      if (cur_index>=js_list.length ) {
        callback();
        return ;
      }
     var js_file= domain + js_list[cur_index];
      console.log("do load js:"+ js_file );

      me.loadScript( js_file , function(){
        cur_index++;
        do_funcion();
      } );
    }
    do_funcion();
  }

  last_page_url:any;
  $header_query_info:any;

  load_data_for_route () {
    this.load_data();
  };
  get_action_str() {
    var path = this["$route"].path;
    var arr=path.split("/");
    if (arr.length<3) {
      return "index";
    }else {
      return $.trim(arr[2]);
    }
  }
  get_query_header_init(){

    var $header_query_info= $("#id_header_query_info").admin_header_query ({
      "html_power_list": this.$data.html_power_list,
    });

    this.$header_query_info= $header_query_info ;
    return this.$header_query_info;

  }
  check_show(field_name) {
    return this.$data.html_power_list[field_name];
  }

  /*
    init call
   */
  base_init_ex () {}

  base_init () {
    var me = this;
    $(this.loading_selector).parent().addClass("box");
    window["vue_load_data"] = function () {
      me.load_data();
    };
    this.table_base_init();
    this.base_init_ex();
  };
  //
  query_init ( $header_query_info ) {

  };

  row_init  () {
  };
  page_info_init (page_info) {
    var me = this;
    var cur_page_num = page_info.page_num;
    var per_page_count = page_info.per_page_count;
    var total_num = page_info.total_num;
    var total_page_count = Math.ceil(total_num / per_page_count);
    var fix_count= 3;
    if ($.check_in_phone() ) {
      fix_count =0;
    }
    var show_start_index = cur_page_num - fix_count  ;
    var show_end_index = cur_page_num + fix_count  ;
    if (show_start_index < 1) {
      show_start_index = 1;
    }
    if (show_end_index > total_page_count) {
      show_end_index = total_page_count;
    }
    var $page_info = $('<div class="pages"> </div>');
    //   <a class="page_next "  >&gt;</a>
    $page_info.append("<span>总记录数:" + total_num + "</span>");
    $page_info.append('<select class="select_page_count" > <option value="10"> 每页10行 </option> <option value="50"> 每页50行 </option> <option value="100"> 每页100行 </option> <option value="500"> 每页500行 </option> <option value="1000"> 每页1000行 </option> <option value="5000"> 每页5000行 </option> </select> <input style="width:50px" placeholder="输入页数" class="input_page_num" >');
    $page_info.find(".select_page_count").val(page_info.per_page_count);
    var pre_page_num = cur_page_num - 1;
    if (pre_page_num < 1) {
      pre_page_num = 1;
    }
    var next_page_num = cur_page_num + 1;
    if (next_page_num > total_page_count) {
      next_page_num = total_page_count;
    }
    $page_info.append(' <a class="page_prev" data-page_num="' + pre_page_num + '"   >&lt;</a> ');
    $page_info.append(' <a class=" page_num" data-page_num="1" >1</a> ');
    var left_page_num = show_start_index;
    if (show_start_index > 2) {
      $page_info.append('<span >...</span>');
    }
    else {
      if (show_start_index < 2) {
        left_page_num = 2;
      }
    }
    for (let i = left_page_num; i < cur_page_num; i++) {
      $page_info.append(' <a class=" page_num" data-page_num="' + i + '" >' + i + ' </a> ');
    }
    // 当前页
    if (cur_page_num != 1 && cur_page_num != total_page_count) {
      $page_info.append(' <a class=" page_num" data-page_num="' + cur_page_num + '" >' + cur_page_num + ' </a> ');
    }
    var right_page_num = show_end_index;
    if (show_end_index == total_page_count) {
      right_page_num--;
    }
    for (let i = cur_page_num + 1; i <= right_page_num; i++) {
      $page_info.append(' <a class=" page_num" data-page_num="' + i + '" >' + i + '</a> ');
    }
    if (right_page_num < total_page_count - 1) {
      $page_info.append('<span >...</span>');
    }
    if (total_page_count > 1) {
      $page_info.append(' <a class=" page_num" data-page_num="' + total_page_count + '" >' + total_page_count + '</a> ');
    }
    $page_info.append(' <a class="page_next " data-page_num="' + next_page_num + '"     >&gt;</a> ');
    var $table_p = $(".common-table, .vue-table").parent();
    $table_p.find(".pages").remove();
    $table_p.append($page_info);
    $page_info.find("[data-page_num=" + cur_page_num + "]").addClass("page_cur");
    $page_info.find("a").attr("href", "javascript:;");
    $page_info.find("a").on("click", function (e) {
      var page_num = $(e.currentTarget).data("page_num");
      //alert(page_num);
      if (page_num != cur_page_num) {
        me.reload_page_by_page_info(page_num);
      }
      return false;
    });
    $page_info.find(".select_page_count").on("change", function (e) {
      var page_count = $(e.currentTarget).val();
      me.reload_page_by_page_info(1, page_count);
      return false;
    });
    $page_info.find(".input_page_num").on("keypress", function (e) {
      if (e.which == 13) {
        var page_num = $(e.currentTarget).val();
        me.reload_page_by_page_info(page_num);
      }
    });
  };
  reload_page_by_page_info  (page_num?, page_count?, order_by_str?) {
    var query_args = this["$route"].query;
    if (page_num) {
      query_args["page_num"] = page_num;
    }
    if (page_count) {
      query_args["page_count"] = page_count;
    }
    if (order_by_str) {
      query_args["order_by_str"] = order_by_str;
    }
    //TODO: 不知道为什么, 这样调用才行
    $.reload_self_page({});
    $.reload_self_page(query_args);
  };
  do_load_data_end () {
  }
  start_loading() {
  }
  load_data () {
    var query_args = this["$route"].query;
    var path = this["$route"].path;
    var me = this;
    var $table_p = $(".common-table, .vue-table").parent();
    if (me.last_page_url != path  ) { //
      me.$data.table_data=[];
      $table_p.find(".pages").remove();
    }
    me.last_page_url= path;
    console.log("do:ajax ", path );
    $(me.loading_selector).parent().append(' <div class="overlay"> <i class="fa fa-refresh fa-spin"></i> </div> <!-- end loading --> </div> ');
    $.do_ajax(path, query_args, function (resp) {
      if (resp.ret == 0) {
        console.log("ajax out",resp);
        me.$data.html_power_list =resp.html_power_list;
        me.$data.table_config.html_power_list =resp.html_power_list;
        me.$data.table_config.order_by_str = resp.g_args.order_by_str;
        me.$data.table_data = resp.list;

        //附加数据
        $.each(resp ,function(k,v){
          if ($.inArray(k, ["page_info", "ret","info","g_args","list","html_power_list"] ) === -1  ) {
            me.$data[k]= v;
          }
        });

        window["g_args"] = resp.g_args;
        console.log( "g_args", window["g_args"] );
        $(me.loading_selector).parent().find(".overlay").remove();

        me.$nextTick(function () {
          me.query_init(  me.get_query_header_init() );
          me.table_row_init();
          me.page_info_init(resp.page_info);
          me.do_load_data_end();
        });
      }
      else {
        if (resp.ret == 1005) {
          alert("未登录, 将跳转到登录页");
          $.wopen(window["admin_api"] + "?to_url=" + encodeURIComponent(window.location.href), true);
        }
      }
    }, true);
    this.reset_menu_select();
  };
  reset_menu_select () {
    var $menu = $("#__id_menu");
    var check_url = window.location.toString().split("?")[0];
    var obj = $menu.find("li>a[href=\"" + check_url + "\"]");
    var existed_flag = false;
    $.each(obj, function (i, item) {
      if ($(item).parent().hasClass("active")) {
        existed_flag = true;
      }
    });
    if (existed_flag) {
      return;
    }
    $.do_select_menu(obj);
  };


  get_args_base  () {
    return window["g_args"];
  };
  get_opt_data_base (obj) {
    var $obj = $(obj);
    return this.$data.table_data[$obj.parent().data("index")];
  };
  table_base_init () {

  };
  table_row_init () {
    var me =this;
    var admin_table :any = undefined;
    $.each(me.$children, function( i ,value ){
      if (value.$options["_componentTag"]=="admin-table")  {
        admin_table= value;
        admin_table.reset_row();
      }
    });

    me.row_init();
  }
}
