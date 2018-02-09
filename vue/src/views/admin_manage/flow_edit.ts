import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/admin_manage-flow_edit"
// @Component 修饰符注明了此类为一个 Vue 组件.
@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./flow_edit.html" ),
})
export default class extends vtable {

  get_args() :self_Args  {return  this.get_args_base();}
  $flow : any;

  //设置扩展信息
  set_node_info(id, args ) {
    this.$flow.$nodeData[id] = $.extend({}, this.$flow.$nodeData[id],  args);
  }

  get_node_type( type) {
    return type.split(" ")[0];
  }
  onItemDbClick(id, type){
    //this.$flow.get
    if (type=="node") {
      return this.do_node_opt(id);
    } else if (type =="line") {
      return this.do_line_opt(id);
    }


  }
  public do_line_opt(id) {
    var info=this.$flow. getItemInfo( id, "line");
    console.log("line:", info );
  }
  public get_node_type_by_id(id){
    var info=this.get_node_info(id);
    var node_type= this.get_node_type( info["type"]) ;
    return node_type;
  }
  flow_set_name( id, name,type  ) {
    this.$flow.setName(id, name, type);
  }

  get_node_info(id) {
    return this.$flow. getItemInfo( id, "node");
  }

  do_node_opt( id   ) {
    var info= this.get_node_info(id);
    var node_type= this.get_node_type( info["type"]) ;
    if( node_type == "notify" ) {
      this.do_node_notify_opt(id,info);
    }else if (  node_type == "admin"  ) {
      this.do_node_admin_opt(id,info);
    }else if (  node_type == "uplevel_admin"  ) {
      this.do_node_uplevel_admin_opt(id,info);

    }else if (  node_type == "function"  ) {
      this.do_node_function_opt(id,info);
    }

    console.log( "click:", info,  node_type );
  }


  do_node_uplevel_admin_opt( id,info ) {
  }

  do_node_function_opt( id,info ) {
    var  flow_function= info.flow_function? info.flow_function: 0;
    /*
    if (function_id != 0) {
      alert("已经有指定了, 只能删除再增加");
      return;
    }
    */

    var me=this;

    var $flow_function=$("<select/>");
    Enum_map.append_option_list("flow_function", $flow_function, true );
    $flow_function.val( flow_function);

    var arr=[
      ["分支函数" , $flow_function ],
    ];

    $.show_key_value_table("设置", arr ,{
      label: '确认',
      cssClass: 'btn-warning',
      action: function(dialog) {
        var flow_function=$flow_function.val();

        var args={
          "flow_function" : flow_function,
        }
        me.set_node_info(id, args );
        me.flow_set_name( id, "分支:" +  Enum_map.get_desc("flow_function",flow_function) ,"node"  );
      }
    },function(){
    });


  }

  do_node_notify_opt( id,info ) {
    var me=this;

    var $admin=$("<input/>");
    var adminid= info.adminid? info.adminid: 0;
    $admin.val(adminid);

    var arr=[
      ["管理员" , $admin  ],
    ];

    $.show_key_value_table("抄送给", arr ,{
      label: '确认',
      cssClass: 'btn-warning',
      action: function(dialog) {
        var adminid=$admin.val();
        var args={
          "adminid" : adminid,
        }
        me.set_node_info(id, args );
        $.do_ajax_get_nick( "account", adminid, function(_id, nick){
          me. flow_set_name( id, "抄送:"+nick ,"node"  );
        });
      }
    },function(){
      $.admin_select_user(
        $admin,
        "admin",function(){} ,false
      );
    });

  }

  do_node_admin_opt( id ,info) {

    var me=this;

    var $admin=$("<input/>");
    var adminid= info.adminid? info.adminid: 0;
    $admin.val(adminid);

    var arr=[
      ["管理员" , $admin  ],
    ];

    $.show_key_value_table("抄送给", arr ,{
      label: '确认',
      cssClass: 'btn-warning',
      action: function(dialog) {
        var adminid=$admin.val();
        var args={
          "adminid" : adminid,
        }
        me.set_node_info(id, args );
        $.do_ajax_get_nick( "account", adminid, function(_id, nick){
          me. flow_set_name( id, "审批:"+nick ,"node"  );
        });
      }
    },function(){
      $.admin_select_user(
        $admin,
        "admin",function(){} ,false
      );
    });

  }



  onItemAdd(id , type,json) {
    if (type=="line") {
      //get_node_type_by_id
      var from_node_type=this.get_node_type_by_id( json["from"]);
      var to_node_type=this.get_node_type_by_id( json["to"]);
      if (from_node_type=="notify" ) {
        alert("抄送节点不能有下游处理");
        return false;
      }
      if ( to_node_type =="notify" ) {
        json["dash"] = true;
      }

      console.log(from_node_type, to_node_type );

    }else if ( type == "node" ) {
      this.do_add_node(id, json);
    }
    return true;
  }

  do_add_node( id, info ) {
    var node_type = this.get_node_type(info["type"] );

    if ( info["name"].substr(0, 5) == "node_" ) {
      if (node_type =="function") {
        info["name"] ="条件分支";
      }else if  (node_type =="notify") {
        info["name"] ="抄送";
      }else if  (node_type =="admin") {
        info["name"] ="个人审批";
      }else if  (node_type =="uplevel_admin") {
        info["name"] ="上级审批";
      }else if  (node_type =="start") {
        info["name"] ="开始";
      }else if  (node_type =="end") {
        info["name"] ="结束";
      }
    }
  }


  flow_init(){
    var property={
      toolBtns:["start round mix","end round mix","admin","uplevel_admin","notify" ,"function mix"
                /*
                  "task","node mix adminid","chat","state","plug","join","fork","complex mix"
                */
               ],
      //haveHead:true,
      //headLabel:true,
      headBtns:[
        //"save","undo","redo",
        /*
          "new","open","save","undo","redo","reload","print"
        */
      ],//如果haveHead=true，则定义HEAD区的按钮
      haveTool:true,
      haveGroup:true,
      useOperStack:true
    };
    var GooFlow = window["GooFlow"];
    //取代setNodeRemarks方法，采用更灵活的注释配置
    GooFlow.prototype.remarks.toolBtns={
      cursor:"选择指针",
      direct:"结点连线",
      dashed:"关联虚线",
      start:"入口结点",
      end:"结束结点",
      task:"任务结点",
      node:"自动结点",

      uplevel_admin:"上级审批",
      admin:"指定人审批",
      "notify":"抄送",
      "function":"条件分支",

      chat:"决策结点",
      state:"状态结点",
      plug:"附加插件",
      fork:"分支结点",
      "join":"联合结点",
      "complex":"复合结点",
      group:"组织划分框编辑开关"
    };

    GooFlow.prototype.remarks.extendRight="工作区向右扩展";
    GooFlow.prototype.remarks.extendBottom="工作区向下扩展";
    var demo;
    demo=$.createGooFlow($("#demo"),property);

    //demo.onItemRightClick=this.onItemRightClick;
    demo.onItemDbClick=this.onItemDbClick;
    demo.onItemAdd=this.onItemAdd;
    this.$flow =demo;
  }
  do_created_ex(call_func) {

    //加载js
    var me = this;
    this.load_admin_js_list([
      "/Gooflow/codebase/GooFunc.js",
      "/Gooflow/codebase/GooFlow.js",
    ], function(){
      call_func();
      me.flow_init();
    } );

  }
  //
  data_ex() {
    //扩展的 data  数据
    var me=this;
    var field_list=[];
    var  row_opt_list =[];

    return {
      "table_config":  {
      }
    }
  }

  //请求完成
  do_load_data_end () {
    console.log("do_load_data_end ", this.$data.json_data );
    this.$flow.clearData();
    this.$flow.loadData( this.$data.json_data );
  }

  opt_edit( e:MouseEvent, opt_data: self_RowData ){
    alert(JSON.stringify( opt_data));
  }

  query_init( $header_query_info): void{
    console.log("init_query");
    var me =this;
    $.admin_enum_select({
      'join_header'  : $header_query_info,
      "enum_type"    : "flow_type",
      "field_name" : "flow_type",
      "title" : "flow_type",
      "length_css"      : "col-xs-12 col-md-4",
      "select_value" :  this.get_args().flow_type,
      "multi_select_flag"     : false ,
      "btn_id_config"     : {},
    });


    //JQuery 写法
    var jquery_body = $("<div> <button class=\"btn btn-primary do-save\">保存</button> ||||||  <a href=\"javascript:;\"class=\"btn btn-warning  do-reload\">重新加载</a> </div>");

    jquery_body.find(".do-save").on( "click" ,function(e) {
      var json_data= JSON.stringify(me.$flow.exportData());
      $.do_ajax("/admin_manage/flow_save",  {
        flow_type : me.get_args().flow_type,
        json_data:  json_data,
      });
    });

    jquery_body.find(".do-reload").on( "click" ,function(e) {
      BootstrapDialog.alert(" test 2");
    });


    $.admin_query_common({
      'join_header'  : $header_query_info,
      "jquery_body" :  jquery_body,
      "length_css"      : "col-xs-12 col-md-5",
    });



  }
}
