import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/pic_manage-pic_info"
// @Component 修饰符注明了此类为一个 Vue 组件.
@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./pic_info.html"),
})
export default class extends vtable {

  get_args() :self_Args  {return  this.get_args_base();}
  data_ex() {
    //扩展的 data  数据
    var me=this;
    var field_list=[{
      field_name: "id",
      "title": 'id',
    },{
      field_name: "type_str",
      "title": "类型",
      "default_display": 1,
    },{
      field_name: "img_url",
      "title": "图片预览",
      render:function(value, item:self_RowData ,index){
        return "<img src=" + value + " height=\"100\">" ;
      }
    },{
      field_name: "name",
      "title": "图片名称",
    },{
      field_name: "usage_type_str",
      "title": "用途"
    },{
      field_name: "active_status",
      "title": "活动状态"
    }];
    var  row_opt_list =[{
      face_icon: "fa-edit",
      on_click: me.opt_edit ,
      "title": "编辑",
    },{
      face_icon: "fa-times",
      "title": "删除",
      on_click: me.opt_del
    }];

    return {
      "table_config":  {
        "field_list": field_list,
        "row_opt_list": row_opt_list,
      }
    }
  }
  opt_edit( e:MouseEvent, opt_data: self_RowData ){
    alert(JSON.stringify( opt_data));
  }

  query_init( $header_query_info): void{
    console.log("init_query");
    var me =this;
	$.admin_enum_select({
		'join_header'  : $header_query_info,
		"enum_type"    : "pic_type" ,
    "field_name"   : "type",
		"length_css" : "col-xs-6 col-md-2",
		"show_title_flag":true,
		"title"        :  "图片类型",
		"select_value" : this.get_args().type,
	});
	$.admin_enum_select({
		'join_header'  : $header_query_info,
		"enum_type"    : "pic_usage_type" ,
    "field_name"   : "usage_type",
		"length_css" : "col-xs-6 col-md-2",
		"show_title_flag":true,
		"title"        :  "使用类型",
		"select_value" : this.get_args().usage_type,
	});

	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "active_status" ,
		"length_css" : "col-xs-6 col-md-2",
		"show_title_flag":true,
		"title"        :  "活动状态",
		"select_value" : this.get_args().active_status,
	});

  }

  opt_del(e:MouseEvent,opt_data: self_RowData){
    var id=opt_data.id;
        BootstrapDialog.show({
            title: '删除信息',
            message : "确定删除?",
            closable: true,
            buttons: [{
                label: '确认',
                cssClass: 'btn-primary',
                action: function(dialog){
                    $.do_ajax('/pic_manage/del_pic_info', {'id':id});
                    dialog.close();
                }
            }, {
                label: '取消',
                cssClass: 'btn',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    }

}
