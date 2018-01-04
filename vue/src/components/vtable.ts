/// <reference path="../../d.ts.d/common.d.ts" />
import Vue from 'vue'
import Component from 'vue-class-component'

// @Component 修饰符注明了此类为一个 Vue 组件
@Component({
  // 所有的组件选项都可以放在这里
  created: function () {
    this["load_data"]();
  },
  mounted: function () {
    this["base_init"]();
  },

  data: function () {
    return $.extend({}, this["data_ex"](),
    {
      table_data: [],
      html_hide_list:{},
    });
  },
  watch: {
    // 如果路由有变化，会再次执行该方法
    '$route': 'load_data_for_route'
  },

})
export default class vtable extends Vue {

  data_ex(){
    return {};
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
      "html_hide_list": this.$data.html_hide_list,
    });

    this.$header_query_info= $header_query_info ;
    return this.$header_query_info;

  }
  check_show(field_name) {
    return !this.$data.html_hide_list[field_name];
  }

  base_init_ex () {}
  base_init () {
    var me = this;
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
    var $table_p = $(".common-table").parent();
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
  reload_page_by_page_info  (page_num?, page_count?) {
    var query_args = this["$route"].query;
    if (page_num) {
      query_args["page_num"] = page_num;
    }
    if (page_count) {
      query_args["page_count"] = page_count;
    }
    //TODO: 不知道为什么, 这样调用才行
    $.reload_self_page({});
    $.reload_self_page(query_args);
  };
  reset_sort_info = function (order_by_str) {
    var tmp_arr = order_by_str.split(/ /);
    var order_field_name = tmp_arr[0];
    var order_flag = tmp_arr[1];
    var $th_list = $(".common-table").find("thead >tr>td");
    $th_list.find(".td-sort-item").remove();
    $.each($th_list, function (i, item) {
      console.log( item);
      var field_name = $(item).data("field_name");
      if (field_name) {
        var $sort_item = $('<a href="javascript:;" class=" fa  td-sort-item " ></a>');
        if (field_name == order_field_name) {
          if (order_flag == "asc") {
            $sort_item.addClass("fa-sort-up");
          }
          else {
            $sort_item.addClass("fa-sort-down");
          }
        }
        else {
          $sort_item.addClass("fa-sort");
        }
        $(item).append($sort_item);
      }
    });
  };
  load_data () {
    var query_args = this["$route"].query;
    var path = this["$route"].path;
    var me = this;
    var $table_p = $(".common-table").parent();
    if (me.last_page_url != path  ) { //
      me.$data.table_data=[];
      $table_p.find(".pages").remove();
    }
    me.last_page_url= path;
    $table_p.append(' <div class="overlay"> <i class="fa fa-refresh fa-spin"></i> </div> <!-- end loading --> </div> ');
    $.do_ajax(path, query_args, function (resp) {
      if (resp.ret == 0) {
        console.log("ajax out",resp);
        me.$data.table_data = resp.list;
        me.$data.html_hide_list =resp.html_hide_list;

        //附加数据
        $.each(resp ,function(k,v){
          if ($.inArray(k, ["page_info", "ret","info","g_args","list","html_hide_list"] ) === -1  ) {
            me.$data[k]= v;
          }
        });

        window["g_args"] = resp.g_args;
        $table_p.find(".overlay").remove();

        me.$nextTick(function () {
          me.query_init(  me.get_query_header_init() );
          me.table_row_init();
          me.page_info_init(resp.page_info);
          if (resp.g_args.order_by_str) {
            me.reset_sort_info(resp.g_args.order_by_str);
          }
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

  get_table_key (fix) {
    var path_list = window.location.hash.split("/");
    return "" + fix + "-" + path_list[1] + "-" + path_list[2].split("?")[0];
  };

  get_args_base  () {
    return window["g_args"];
  };
  get_opt_data_base (obj) {
    var $obj = $(obj);
    return this.$data.table_data[$obj.parent().data("index")];
  };
  table_base_init () {
    var me = this;
    var $table_list = $(".common-table");
    $table_list.addClass("table");
    $table_list.addClass("table-bordered");
    $table_list.addClass("table-striped");
    var $div = $("<div class=\"table-responsive box \"/>");
    $table_list.before($div);
    $div.append($table_list);


    $table_list.on("click", ".remove-for-not-xs .start-opt-mobile", function (e) {
      $(e.currentTarget).closest("tr").find("td > div .td-info ").click();
    });
    var thead_tr = $table_list.find("thead >tr");
    thead_tr.prepend('<td class="remove-for-not-xs" > </td>');
    $.each(thead_tr, function (table_i, th_item) {
      if ($(th_item).parent().hasClass("table-clean-flag")) {
        return;
      }
      var path_list = window.location.pathname.split("/");
      var table_key = me.get_table_key("field_list");
      var opt_td = $(th_item).find("td:last");
      if (!opt_td.css("min-width")) {
        opt_td.css("min-width", "80px");
      }
      opt_td.addClass("remove-for-xs");
      var config_item = $(" <a href=\"javascript:; \" style=\"color:red;\" title=\" 列显示配置\"   >列</a>");
      config_item.on("click", function (e) {
        var $table = $(e.currentTarget).closest("table");
        var $th = $table.find("thead >tr");
        var $th_td_list = $th.find(">td");
        var arr :Array<any>= [];
        $.each($th_td_list, function (i, item) {
          if (!(i == 0 || i == $th_td_list.length - 1)) {
            var $item = $(item);
            var title = $item.data("title");
            if (!title) {
              title = $.trim($item.text());
            }
            var display = $item.css("display");
            var $input = $("<input type=\"checkbox\"/>");
            if (display == "none") {
              //$input.attr("checked",0) ;
            }
            else {
              $input.attr("checked", "checked");
            }
            $input.data("index", title);
            arr.push([title, $input]);
          }
        });
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
      });
      opt_td.append(config_item);
    });
    thead_tr = $table_list.find("thead >tr");
    //处理是否显示
    var reset_table_th_show = function (config) {
      if (config && config.field_list) {
        thead_tr.find("td").each(function (i, item) {
          var $th = $(item);
          var title = $th.data("title");
          if (!title) {
            title = $.trim($th.text());
          }
          if (config.field_list[title] === false) {
            $th.hide();
          }
        });
      }
    };
    var table_key = me.get_table_key("field_list");
    if (!$.check_in_phone()) {
      var val = window.localStorage.getItem(table_key);
      var cur = (new Date()).getTime() / 1000;
      var config = null;
      try {
        if (val) {
          config = JSON.parse(val);
        }
      }
      catch ($e) {
      }
      if (config && cur - config["log_time"] < 3600) {
        reset_table_th_show(config);
      }
      else {
        $.do_ajax("/page_common/opt_table_field_list", {
          "opt_type": "get",
          "table_key": table_key
        }, function (resp) {
          reset_table_th_show(config);
          resp.log_time = cur;
          window.localStorage.setItem(table_key, JSON.stringify(resp));
        });
      }
    }
    thead_tr.on("click", ".td-sort-item", function (e) {
      var order_by_str = "";
      var $this = $(e.currentTarget);
      var field_name = $.trim($this.parent().data("field_name"));
      if ($this.hasClass("fa-sort-down")) {
        order_by_str = field_name + " " + "asc";
      }
      else {
        order_by_str = field_name + " " + "desc";
      }
      window["g_args"].order_by_str = order_by_str;
      me.$header_query_info.query();
      return false;
    });
    $table_list.on("click", ".td-info", function (e) {
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
            var opt_div = $(data_row[data_row.length - 1]).find(">div");
            var opt_data = opt_div.data();
            var opt_data_arr  :Array<any>= [];
            $.each(opt_data, function (i, item) {
              opt_data_arr.push([i, item]);
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
    });
  };
  table_row_init () {
    var $table_list = $(".common-table");
    var me = this;
    $.each($table_list, function (table_i, item) {
      var path_list = window.location.pathname.split("/");
      var table_key = path_list[1] + "-" + path_list[2] + "-" + table_i;
      var $table = $(item);
      var reset_table = function () {
        var $row_list = $table.find("tbody >tr  ");
        var $th = $table.find("thead >tr");
        //处理disable
        var display_none_list : Array<any> = [];
        var not_for_xs_list : Array<any>  = [];
        var $th_td_list = $th.find("td");
        var set_reset_filed_flag = false;
        $.each($th_td_list, function (i, item) {
          var $item = $(item);
          if ($item.css("display") == "none") {
            display_none_list.push(i);
          }
          if ($item.hasClass("remove-for-xs")) {
            not_for_xs_list.push(i);
          }
        });
        //for
        $.each($row_list, function (i, item) {
          var $item = $(item);
          var td_list = $item.find("td");
          if ($item.find(".start-opt-mobile").length > 0) {
            return;
          }
          $item.prepend($('<td class="remove-for-not-xs"  > <a class="  fa  fa-cog  start-opt-mobile " style="font-size:25px"  href="javascript:;"  > </a> </td>'));
          $.each(display_none_list, function (i, display_none_id) {
            $(td_list[display_none_id - 1]).css("display", "none");
          });
          $.each(not_for_xs_list, function (i, id) {
            $(td_list[id]).addClass("remove-for-xs");
          });
          var $td_last = $(td_list[td_list.length - 1]);
          $td_last.addClass("remove-for-xs");
          $td_last.find("a").addClass("btn fa");
          $td_last.find(">div").prepend('<a href="javascript:;" class="btn  fa fa-cog td-info" title="竖向显示" ></a>');
        });
        if (!$.check_in_phone()) {
          $table.find(".remove-for-not-xs").hide();
          //TODO
        }
        else {
          $table.find(".remove-for-xs").hide();
        }
      };
      reset_table();
      me.row_init();
    });
  }
}
