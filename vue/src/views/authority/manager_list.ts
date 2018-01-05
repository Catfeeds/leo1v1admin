import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/authority-manager_list"

// @Component 修饰符注明了此类为一个 Vue 组件
@Component({
  // 所有的组件选项都可以放在这里
  template:  require("./manager_list.html" ),
})

export default class extends vtable {

  get_opt_data(obj):self_RowData {return this.get_opt_data_base(obj );}
  get_args() :self_Args  {return  this.get_args_base();}

  data_ex() {
    //扩展的 data  数据
    return {"message": "xx" }
  }
  query_init( $header_query_info): void{
    console.log("init_query");
    var me =this;

    $.admin_ajax_select_user({
      'join_header'  : $header_query_info,
      "user_type"    : "account",
      "field_name"   : "adminid",
      "title"        : "adminid",
      "select_value" : this.get_args().adminid,
    });


    $.admin_query_input({
      'join_header'  : $header_query_info,
      "field_name"    : "user_info" ,
      "placeholder" : "账号 回车查询",
      "length_css" : "col-xs-12 col-md-3",
      "title"        :  "user_info",
      "allway_show_flag" :true,
      "select_value" : this.get_args().user_info,
    });
    $.admin_enum_select({
      'join_header'  : $header_query_info,
      "enum_type"    : "boolean",
      "field_name" : "has_question_user",
      "title" : "题库用户",
      "select_value" : this.get_args().has_question_user,
      "multi_select_flag"     : true,
      "btn_id_config"     : {},
    });

    $.admin_enum_select({
      'join_header'  : $header_query_info,
      "enum_type"    : "boolean",
      "field_name" : "del_flag",
      "title" : "离职",
      "select_value" : this.get_args().del_flag,
      "multi_select_flag"     : true,
      "btn_id_config"     : {},
    });

    $.admin_enum_select({
      'join_header'  : $header_query_info,
      "enum_type"    : "account_role",
      "field_name" : "account_role",
      "title" : "角色",
      "select_value" : this.get_args().account_role,
      "multi_select_flag"     : true,
      "btn_id_config"     : {},
    });

    $.admin_enum_select({
      'join_header'  : $header_query_info,
      "enum_type"    : "boolean",
      "field_name" : "day_new_user_flag",
      "title" : "每日新用户",
      "select_value" : this.get_args().day_new_user_flag,
      "multi_select_flag"     : true,
      "btn_id_config"     : {},
    });

    $.admin_query_input({
      'join_header'  : $header_query_info,
      "field_name"    : "tquin" ,
      "length_css" : "col-xs-6 col-md-2",
      "show_title_flag":true,
      "title"        :  "电话账号",
      "select_value" : this.get_args().tquin,
    });
    $.admin_enum_select({
      'join_header'  : $header_query_info,
      "enum_type"    : "fulltime_teacher_type",
      "field_name" : "fulltime_teacher_type",
      "title" : "全职老师",
      "select_value" : this.get_args().fulltime_teacher_type,
      "multi_select_flag"     : true,
      "btn_id_config"     : {},
    });

    $.admin_enum_select({
      'join_header'  : $header_query_info,
      "enum_type"    : "call_phone_type",
      "field_name" : "call_phone_type",
      "title" : "电话类型",
      "select_value" : this.get_args().call_phone_type,
      "multi_select_flag"     : true,
      "btn_id_config"     : {},
    });


    $.admin_enum_select({
      'join_header'  : $header_query_info,
      "enum_type"    : "seller_level",
      "field_name" : "seller_level",
      "title" : "cc类型",
      "select_value" : this.get_args().seller_level,
      "multi_select_flag"     : true,
      "btn_id_config"     : {},
    });



  }

  opt_edit_manage(e:MouseEvent) {
    var opt_data = this.get_opt_data(e.target);


    var uid= opt_data.uid;
    var $phone=$("<input/> ").val(opt_data.phone);
    var $email=$("<input/>").val(opt_data.email);
    var $account_role=$("<select/>");
    var $seller_level=$("<select/>");
    var $become_full_member_flag=$("<select/>");
    var $no_update_seller_level_flag=$("<select/>");
    var $day_new_user_flag=$("<select/>");
    var $name=$("<input/>").val(opt_data.name);
    var $tquin=$("<input/>").val(opt_data.tquin);
    var $wx_id=$("<input/>").val(opt_data.wx_id);
    var $up_adminid=$("<input/>").val(opt_data.up_adminid );

    var $call_phone_type =$("<select/>");
    var $main_department =$("<select/>");
    var $call_phone_passwd =$("<input/>").val(opt_data.call_phone_passwd );
    var $become_member_time =$('<input/>').val(opt_data.become_time);

    var need_account_role_list:Array<any>=[];
    if (this.get_args() .assign_account_role>0) {
      need_account_role_list=[ this.get_args().assign_account_role ];
    }
    need_account_role_list.push( opt_data.account_role );
    Enum_map.append_option_list("account_role", $account_role,true,need_account_role_list);
    Enum_map.append_option_list("seller_level", $seller_level,true);
    Enum_map.append_option_list("boolean", $day_new_user_flag,true);
    Enum_map.append_option_list("boolean", $become_full_member_flag,true);
    Enum_map.append_option_list("boolean", $no_update_seller_level_flag,true);
    Enum_map.append_option_list("call_phone_type", $call_phone_type ,true);
    Enum_map.append_option_list("main_department", $main_department ,true);
    $call_phone_type.val(opt_data.call_phone_type);
    $main_department.val(opt_data.main_department);

    $account_role.val(opt_data.account_role);
    $seller_level.val(opt_data.seller_level);
    $day_new_user_flag.val(opt_data.day_new_user_flag);
    $become_full_member_flag.val(opt_data.become_full_member_flag);
    $no_update_seller_level_flag.val(opt_data.no_update_seller_level_flag);

    var arr=[
      ["uid",opt_data.uid] ,
      ["account",opt_data.account] ,
      ["姓名", $name],
      ["电话",$phone] ,
      ["邮件",$email] ,
      ["角色",$account_role] ,
      ["每天新例子",$day_new_user_flag] ,
      //['入职时间',$become_member_time],

      ["-","-"],
      ["打电话类型",$call_phone_type ],
      ["打电话账号id",$tquin], ["打电话密码",$call_phone_passwd ],
      ["-","-"],

      ["咨询师等级",$seller_level] ,
      ["微信号",$wx_id] ,
      //["上级",$up_adminid],
      ["转正",$become_full_member_flag],
      ["部门",$main_department],
      ["不参加升级",$no_update_seller_level_flag],
    ];
    //initPicker($become_member_time);

    $.show_key_value_table("修改用户信息", arr ,{
      label: '确认',
      cssClass: 'btn-warning',
      action: function(dialog) {
        $.do_ajax('/user_deal/update_admin_info', {
          'uid': opt_data.uid,
          'phone': $phone.val(),
          'name': $name.val(),
          'day_new_user_flag': $day_new_user_flag.val(),
          'account_role': $account_role.val(),
          'tquin': $tquin.val(),
          'email': $email.val(),
          'old_seller_level': opt_data.seller_level,
          'seller_level': $seller_level.val(),
         // 'up_adminid': $up_adminid.val(),
          'become_full_member_flag': $become_full_member_flag.val(),
          'call_phone_type': $call_phone_type.val(),
          'call_phone_passwd': $call_phone_passwd.val(),
          //'ytx_phone': $ytx_phone.val(),
          'wx_id': $wx_id.val(),
          'main_department':$main_department.val(),
          'no_update_seller_level_flag': $no_update_seller_level_flag.val(),
          //'become_member_time' : $become_member_time.val(),
        });
      }
    },function(){
      //$.admin_select_user($up_adminid,"admin",function(){}, true );
    });
  }

  opt_set_account_role(e:MouseEvent) {
    var opt_data = this.get_opt_data(e.target);


    var id_account_role=$("<select/>");
    var id_creater_adminid=$("<input/>");

    Enum_map.append_option_list("account_role", id_account_role,true);

    if (opt_data.creater_adminid) {
      id_creater_adminid.val( opt_data.creater_adminid );
    }else{
      id_creater_adminid.val( 287 );
    }

    if ( opt_data.account_role) {
      id_account_role.val( opt_data.account_role);
    }else{
      id_account_role.val( 2);
    }

    var arr               = [
      [ "创建者", id_creater_adminid] ,
      [ "角色", id_account_role] ,
    ];

    $.show_key_value_table("设置角色", arr ,{
      label: '确认',
      cssClass: 'btn-warning',
      action: function(dialog) {

        $.do_ajax('/authority/set_account_role', {
            'uid'      : opt_data.uid ,
            'account_role' : id_account_role.val() ,
            'creater_adminid' : id_creater_adminid.val()
          });
      }
    },function(){
      $.admin_select_user(id_creater_adminid,"admin" );
    });

  }


  opt_set_openid(e:MouseEvent) {
    var opt_data = this.get_opt_data(e.target);
    $(this).admin_select_dlg_ajax({
      "opt_type" :  "select", // or "list"
      select_no_select_value  :   0, // 没有选择是，设置的值
      select_no_select_title  :   '未设置', // "未设置"
      select_primary_field : "openid",
      select_display       : "",

      "url"          : "/user_deal/get_wx_user_list",
      //其他参数
      "args_ex" : {
      },

      //字段列表
      'field_list' :[
        {
        title:"姓名",
        width :50,
        field_name:"nickname"
      },{
        title:"openid",
        //width :50,
        render:function(val,item) {
          return item.openid;
        }
      },{
        title:"时间",
        //width :50,
        render:function(val,item) {
          return item.update_time;
        }
      }
      ] ,
      //查询列表
      filter_list:[[
        {
          size_class: "col-md-8" ,
          title :"微信姓名",
          'arg_name' :  "nickname"  ,
          type  : "input"
        }
      ]],

      "auto_close"       : true,
      //选择
      "onChange" : function(val) {
        $.do_ajax("/user_deal/binding_wx_to_admin",{
          id: opt_data.uid,
          wx_openid: val
        });
        /*
          $.do_ajax( "/seller_student/set_test_lesson_st_arrange_lessonid",{
          "st_arrange_lessonid" :  val ,
          "phone" :  phone
          });

        */
      },
      //加载数据后，其它的设置
      "onLoadData"       : null
    });
  }
  opt_del(e:MouseEvent) {
    var opt_data = this.get_opt_data(e.target);

    var uid = opt_data.uid;
    var del_flag     = $("<select/>");
    var time         = $("<input>");
    var mydate       = new Date();
    var str          = "" + mydate.getFullYear() + "/";

    str += (mydate.getMonth()+1) + "/";
    str += mydate.getDate() + " ";
    str += mydate.getHours() + ":";
    str += mydate.getMinutes();
    time.datetimepicker();

    Enum_map.append_option_list( "boolean", del_flag,true);
    del_flag.val(opt_data.del_flag);

    if(del_flag.val() == 1){
      time.val(opt_data.leave_member_time);
    }else{
      time.val(opt_data.become_member_time);
    }
    var arr=[
      ["uid",opt_data.uid] ,
      ["account",opt_data.account] ,
      ["是否离职",del_flag],
      ["时间",time]
    ];


    $.show_key_value_table("更改员工状态", arr ,{
      label: '确认',
      cssClass: 'btn-warning',
      action: function(dialog){
        // if(opt_data.del_flag == del_flag.val()){
        //     if(del_flag.val() == 1){
        //         var time_new = opt_data.leave_member_time;
        //     }else{
        //         var time_new = opt_data.become_member_time;
        //     }
        // }else{
        //     var time_new = time.val();
        // }
        $.do_ajax('/authority/del_manager', {
          'uid'          : opt_data.uid,
          'del_flag'     : del_flag.val(),
          'time'         : time.val(),
        });
      }
    });
  }
  opt_power(e:MouseEvent) {
    var opt_data = this.get_opt_data(e.target);


    var uid= opt_data.uid;
    var show_list : Array<any> =[];
    if (this.get_action_str()=="manager_list_for_seller" ) {
      show_list=[57	, 38	, 74	, 77, 80	,];
    }

    var show_all_flag=(this.get_action_str()=="manager_list");

    var permission  = opt_data["permission"];
    $.do_ajax("/authority/get_permission_list",{
      "permission" : permission
    },function(response){
      var data_list:Array<any>   = [];
      var select_list :Array<any> = [];
      $.each( response.data,function(i, item){
        if (  show_all_flag || $.inArray(  parseInt( item["groupid"]),  show_list) != -1 ) {
          data_list.push([item["groupid"], item["group_name"]  ]);
        }

        if (item["has_power"]) {
          select_list.push (item["groupid"]) ;
        }

      });
      console.log(data_list );

      $("<div/>").admin_select_dlg({
        header_list     : [ "id","名称" ],
        data_list       : data_list,
        multi_selection : true,
        select_list     : select_list,
        onChange        : function( select_list,dlg) {
          $.do_ajax("/authority/set_permission",{
            "uid": uid,
            "groupid_list":JSON.stringify(select_list),
            "old_permission": opt_data.old_permission,
          });
        }
      });
    }) ;
  }
  opt_login(e:MouseEvent) {
    var opt_data = this.get_opt_data(e.target);

    $.do_ajax("/login/login_other",{
      "login_adminid" : opt_data.uid
    });
  }
  opt_sync_kaoqin(e:MouseEvent) {
    var opt_data = this.get_opt_data(e.target);

        $.do_ajax(  "/user_deal/get_kaoqin_machine_list",{
            "adminid" : opt_data.uid,
            "page_num":1 ,
            "page_count":100000 ,
        } ,function(resp){
          var data_list :Array<any>=[];
          var select_id_list  :Array<any>=[];
            $.each(resp["data"]["list"],function(i,item){
                data_list.push([item.machine_id, item.title ]);
                if (item.adminid) {
                    select_id_list.push ( item.machine_id);
                }
            }  );


            $("<div></div>").admin_select_dlg({
                'data_list': data_list,
                "header_list":["id","安放位置"] ,
                "onChange": function ( select_list,dlg ){
                    dlg.close();
                    $.do_ajax("/user_deal/sync_kaoqin",{
                        "adminid" : opt_data.uid,
                        "machine_id_list" : JSON.stringify( select_list )
                    });

                },
                "select_list": select_id_list,
                "multi_selection":true
            });

        });
  }

  opt_set_fulltime_teacher_type (e:MouseEvent) {
    var opt_data = this.get_opt_data(e.target);

    var uid= opt_data.uid;

    var $fulltime_teacher_type =$("<select/>");
    Enum_map.append_option_list("fulltime_teacher_type",$fulltime_teacher_type,true);
    var arr =[
      ["全职老师类型", $fulltime_teacher_type]
    ];
    $fulltime_teacher_type.val(opt_data.fulltime_teacher_type);
    $.show_key_value_table("设置全职老师类型", arr ,{
      label: '确认',
      cssClass: 'btn-warning',
      action: function(dialog) {
        $.do_ajax('/authority/set_fulltime_teacher_type', {
          'uid': uid,
          'fulltime_teacher_type': $fulltime_teacher_type.val()
        });
      }
    });

  }

  opt_email( e:MouseEvent) {
    var opt_data = this.get_opt_data(e.target);

    var  email   = opt_data.email;
    var name     = opt_data.name;
    var arr=[
      ["邮箱",  email ],
      ["备注",  name ],
    ];
    $.show_key_value_table("邮箱信息", arr,[{
      label: '同步账号',
      cssClass: 'btn-warning',
      action: function(dialog) {
        $.do_ajax("/ajax_deal2/sync_email",{
          "email" :  email,
          "title" :  name,
        });
        alert ("更新中(一般5秒)...");
      }
    },{
      label: '重置密码:111111',
      cssClass: 'btn-warning',
      action: function(dialog) {
        BootstrapDialog.confirm("重置密码:111111", function (val){
          $.do_ajax("/ajax_deal2/set_email_passwd",{
            "email" :  email,
          });
          alert ("更新中(一般5秒)...");
        } );
      }
    }]);
  }

  opt_set_user_phone (e:MouseEvent) {
    var opt_data = this.get_opt_data(e.target);

    var account  = opt_data.account
    var phone    = opt_data.phone
    var arr = [
      ["account", account ] ,
      ["电话",phone],
      ['说明','生成相应的学生,家长,老师信息']
    ];

    $.show_key_value_table("生成对应的相应的学生，家长，老师信息", arr ,{
      label: '确认',
      cssClass: 'btn-warning',
      action: function(dialog) {
        $.do_ajax('/ajax_deal2/register_student_parent_account', {
          'account' : account,
          'phone'   : phone,
        },function(resp){
          alert(resp['success']);
        });
      }
    });
  }


  opt_change_account(e:MouseEvent) {
    var opt_data = this.get_opt_data(e.target);


    var uid = opt_data.uid;
    var $account= $("<input/>");
    $account.val( opt_data.account);
    var arr=[
      ["uid",opt_data.uid] ,
      ["account",$account] ,
    ];

    $.show_key_value_table("更改员工账号", arr ,{
      label: '确认',
      cssClass: 'btn-warning',
      action: function(dialog) {
        $.do_ajax('/user_deal/set_account', {
          'uid': opt_data.uid,
          "account": $account.val()
        });
      }
    });
  }
  opt_gen_ass( e:MouseEvent){
    var opt_data = this.get_opt_data(e.target);
    BootstrapDialog.confirm( "要生成助教账号:"+opt_data.account+"?", function(val){
      if (val) {
        $.do_ajax('/ajax_deal2/gen_ass_from_account', {
          'adminid' : opt_data.uid
        });
      }
    } );
  }
  opt_delete_permission(e:MouseEvent ){
    var opt_data = this.get_opt_data(e.target);
    BootstrapDialog.confirm( "要清除该用户所有权限:"+opt_data.account+"?", function(val){
      if (val) {
        $.do_ajax('/ajax_deal2/delete_permission_by_uid', {
          'adminid' : opt_data.uid
        });
      }
    });
  }
  opt_change_permission_newn(e:MouseEvent){
    var opt_data = this.get_opt_data(e.target);
    BootstrapDialog.confirm( "要更换权限:"+opt_data.account+"?", function(val){
      if (val) {
        $.do_ajax('/ajax_deal2/change_permission_by_uid_new', {
          'adminid' : opt_data.uid
        });
      }
    });

  }
  opt_log( e:MouseEvent){
    var opt_data = this.get_opt_data(e.target);
    $.wopen('/authority/seller_edit_log_list?adminid='+ opt_data.uid) ;
  }
  opt_refresh_call_end( e:MouseEvent){
    var opt_data = this.get_opt_data(e.target);
    $.do_ajax('/authority/update_lesson_call_end_time', {
      'adminid' : opt_data.uid
    },function(resp){
      if(resp.reset_ret){
        alert('刷新成功!');
      }else{
        alert('有试听成功未回访!');
        $.wopen('/seller_student_new/no_lesson_call_end_time_list?adminid='+ opt_data.uid) ;
      }
    });
  }


  opt_set_train_through_time(  e:MouseEvent){
    var opt_data = this.get_opt_data(e.target);
    BootstrapDialog.confirm( "要同步老师档案入职时间吗?", function(val){
      if (val) {
        $.do_ajax('/ajax_deal2/set_teacher_train_through_info', {
          'phone' : opt_data.phone,
          'adminid' : opt_data.uid
        });
      }
    } );
  }

  opt_change_permission_new (e:MouseEvent) {
    var opt_data = this.get_opt_data(e.target);
    BootstrapDialog.confirm( "要更换权限:"+opt_data.account+"?", function(val){
      if (val) {
        $.do_ajax('/ajax_deal2/change_permission_by_uid_new', {
          'adminid' : opt_data.uid
        });
      }
    } );

  }

  opt_set_teacher_level (e:MouseEvent) {
    var opt_data = this.get_opt_data(e.target);
    $.do_ajax('/ajax_deal2/get_teacherid_by_phone', {
      'phone' : opt_data.phone,
    },function(resp){
      if(resp.ret !=0){
        alert(resp.info);
        return;
      }else{
        var data = resp.data;
        // alert(data.teacherid);
        var id_teacher_money_type = $("<select/>");
        var id_level              = $("<select/>");
        var id_start_time         = $("<input/>");

        Enum_map.append_option_list("level", id_level, true );
        Enum_map.append_option_list("teacher_money_type", id_teacher_money_type, true );

        id_teacher_money_type.val(data.teacher_money_type);
        id_level.val(data.level);
        id_start_time.datetimepicker({
          datepicker:true,
          timepicker:false,
          format:'Y-m-d'
        });

        var arr = [
          ["工资类别", id_teacher_money_type],
          ["等级", id_level],
          ["时间不填则不会重置课程时间",""],
          ["重置课程开始时间", id_start_time],
        ];

        $.show_key_value_table("修改等级", arr ,{
          label    : '确认',
          cssClass : 'btn-warning',
          action   : function(dialog) {
            $.do_ajax('/tea_manage_new/update_teacher_level',{
              "teacherid"          : data.teacherid,
              "start_time"         : id_start_time.val(),
              "level"              : id_level.val(),
              "teacher_money_type" : id_teacher_money_type.val()
            });
          }
        });

      }
    });
  }

  opt_ower_permission (e:MouseEvent) {
    var opt_data = this.get_opt_data(e.target);
    console.log(parseInt(opt_data.uid));
    $.do_ajax('/company_wx/get_ower_power',{
      'uid':parseInt(opt_data.uid)
    }, function(res) {
      var permission = res.data
      $.do_ajax("/authority/get_permission_list",{
        "permission" : permission
        },function(response){
          var data_list:Array<any>   = [];
          var select_list:Array<any>  = [];

          var perm = permission.split(",");

          $.each( response.data,function(_,item){
            data_list.push([item["groupid"], item["group_name"]  ]);
            for(var i=0; i<perm.length; i++) {
              if (perm[i] == item['groupid']) {
                //data_list.push([this["groupid"], this["group_name"]  ]);
                select_list.push (item["groupid"]) ;
              }
            }
          });

            $.admin_select_dlg({
                header_list     : [ "id","名称" ],
                data_list       : data_list,
                multi_selection : true,
                select_list     : select_list,
                // onChange        : function( select_list,dlg) {
                //     $.do_ajax("/company_wx/set_permission",{
                //         "userid": userid,
                //         "groupid_list":JSON.stringify(select_list),
                //     });
                // }
            });
        }) ;
    });
        // alert('wel');
  }

}
