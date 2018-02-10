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

  //设置扩展信息
  set_line_info(id, args ) {
    this.$flow.$lineData[id] = $.extend({}, this.$flow.$lineData[id],  args);
  }
  //@desn:获取结点类型
  get_node_type( type) {
    return type.split(" ")[0];
  }
  //@desn:双击事件
  onItemDbClick(id, type){
    //this.$flow.get
    if (type=="node") {
      return this.do_node_opt(id);
    } else if (type =="line") {
      return this.do_line_opt(id);
    }


  }
  //@desn:获取线信息
  public do_line_opt(id) {
    var info=this.$flow. getItemInfo( id, "line");
    this.do_branch_line_function_opt(id,info);
    console.log("line:", info );
  }

  //@desn:添加分支后面线操作
  do_branch_line_function_opt( id,info ) {
    var from_id = info.from;
    var from_type = this.get_node_type_by_id(from_id);
    var from_info = this.get_node_info(from_id) ;//获取上个条件类型
    var flow_function = from_info.flow_function;
    console.log('from_type:',from_type);
    if(from_type == 'function' && flow_function){

      var  switch_value= info.switch_value? info.switch_value: 0;
      var me = this;
      //获取该分支类型的选项类型
      $.do_ajax("/admin_manage/get_flow_branch_switch_value",  {
        'flow_function' : flow_function,
      },function(data){
        //组合select  --begin--
        var select = "<select>";
        var switch_value_ar = JSON.parse(data.switch_value);
        $.each(switch_value_ar, function(i, switch_value ){
          select += "<option value='"+i+"'>"+switch_value+"</option>";
        });
        select += "</select>";
        var $switch_value=$(select);
        //组合select  --end--

        var arr=[
          ["分支条件" , $switch_value ],
        ];
        $.show_key_value_table("设置", arr ,{
          label: '确认',
          cssClass: 'btn-warning',
          action: function(dialog) {
            var switch_value=$switch_value.val();
            var switch_desc = $switch_value.find("option:selected").text();

            var args={
              "switch_value" : switch_value,
            }
            me.set_line_info(id, args );
            me.flow_set_name( id, switch_desc ,"line"  );
          }
        },function(){
        });

      });

    }else if(from_type == 'function' && !flow_function){
      alert('请先选择分支类型!');
    }


  }


  //@desn:通过id获取结点类型
  public get_node_type_by_id(id){
    var info=this.get_node_info(id);
    var node_type= this.get_node_type( info["type"]) ;
    return node_type;
  }
  //@desn:设置流程结点名称
  flow_set_name( id, name,type  ) {
    this.$flow.setName(id, name, type);
  }
  //@desn:根据结点id获取结点信息
  get_node_info(id) {
    return this.$flow. getItemInfo( id, "node");
  }
  //@desn:双击操作分发器
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
    console.log('id:'+id);
    console.log( "click:", info,  node_type );
  }

  //@desn:uplevel_admin 上级审批操作
  do_node_uplevel_admin_opt( id,info ) {

  }
  //@desn:分支操作
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
    Enum_map.append_option_list("flow_function", $flow_function, true, me.$data.flow_function_list );

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
  //@desn:抄送操作
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
  //@desn:指定人审批
  do_node_admin_opt( id ,info) {

    var me=this;

    var $admin=$("<input/>");
    var flow_title=$("<input/>");
    var adminid= info.adminid? info.adminid: 0;
    $admin.val(adminid);

    var arr=[
      ["管理员" , $admin  ],
      ["审批类型" , flow_title  ],
    ];

    $.show_key_value_table("指定人审批", arr ,{
      label: '确认',
      cssClass: 'btn-warning',
      action: function(dialog) {
        var adminid=$admin.val();
        var flow_title_val = flow_title.val();
        var args={
          "adminid" : adminid,
        }
        me.set_node_info(id, args );
        $.do_ajax_get_nick( "account", adminid, function(_id, nick){
          me. flow_set_name( id, flow_title_val+":"+nick ,"node"  );
        });
      }
    },function(){
      $.admin_select_user(
        $admin,
        "admin",function(){} ,false
      );
    });

  }



  //@desn:限制抄送不能有下游
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

  //@desn：添加结点操作
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

  //@desn:审批初始化
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
  //@desn:修改操作
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

    //@desn:保存审批信息
    jquery_body.find(".do-save").on( "click" ,function(e) {
      var data=me.$flow.exportData();
      var json_data= JSON.stringify(data);
      console.log ( data);

      $.do_ajax("/admin_manage/flow_save",  {
        flow_type : me.get_args().flow_type,
        json_data:  json_data,
      });
    });
    //@desn:重新加载
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
