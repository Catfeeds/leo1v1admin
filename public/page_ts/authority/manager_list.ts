/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/authority-manager_list.d.ts" />
$(function(){
    var show_name_key="";
    show_name_key="account_name_"+g_adminid;

    function load_data(){
        if ($.trim($("#id_user_info").val()) != g_args.user_info ) {
            $.do_ajax("/user_deal/set_item_list_add",{
                "item_key" :show_name_key,
                "item_name":  $.trim($("#id_user_info").val())
            },function(){});
        }

        $.reload_self_page ({
            user_info         : $('#id_user_info').val(),
            has_question_user : $('#id_has_question_user').val(),
            cardid            :	$('#id_cardid').val(),
            account_role      : $('#id_account_role').val(),
            tquin             :	$('#id_tquin').val(),
            seller_level      :	$('#id_seller_level').val(),
            day_new_user_flag :	$('#id_day_new_user_flag').val(),
            del_flag          : $('#id_del_flag').val(),
            fulltime_teacher_type:	$('#id_fulltime_teacher_type').val(),
            call_phone_type:	$('#id_call_phone_type').val(),
            seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
            adminid           :	$('#id_adminid').val()
        });
    }


    $( "#id_user_info" ).autocomplete({
        source: "/user_deal/get_item_list?list_flag=1&item_key="+show_name_key,
        minLength: 0,
        select: function( event, ui ) {
            load_data();
        }
    });



    $('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);

    $("#id_seller_groupid_ex").init_seller_groupid_ex(window["g_adminid_right"]);

    Enum_map.append_option_list("boolean",$("#id_day_new_user_flag"));
    Enum_map.append_option_list("account_role", $('#id_account_role'));
    Enum_map.append_option_list("fulltime_teacher_type", $('#id_fulltime_teacher_type'));
    Enum_map.append_option_list("call_phone_type", $('#id_call_phone_type'));
    $('#id_account_role').val(g_args.account_role);
    $("#id_user_info").val(g_args.user_info);
    $("#id_has_question_user").val(g_args.has_question_user);
    $("#id_del_flag").val(g_args.del_flag);
    $('#id_cardid').val(g_args.cardid);
    $('#id_tquin').val(g_args.tquin);
    $('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);
    $('#id_call_phone_type').val(g_args.call_phone_type);

    $('#id_seller_level').val(g_args.seller_level);
    $.enum_multi_select( $('#id_seller_level'), 'seller_level', function(){load_data();} )



    $('#id_adminid').val(g_args.adminid);
    $.admin_select_user( $('#id_adminid'), "admin", load_data );


    $('#id_day_new_user_flag').val(g_args.day_new_user_flag);



    if (window.location.pathname=="/authority/manager_list_for_qz" || window.location.pathname=="/authority/manager_list_for_qz/"){
    }else{
        $("#id_fulltime_teacher_type").parent().parent().hide();
        $(".opt-set-fulltime-teacher-type").hide();
    }

    $("#id_add_manager").on("click",function(){
        var account = $("#id_account").val();
        var name = $("#id_real_name").val();
        var email = $("#id_email").val();
        var phone = $("#id_phone").val();
        var passwd = $("#id_passwd").val();
        $.ajax({
            url: '/authority/add_manager',
            type: 'POST',
            data: {
                'account' : account,
                'name'    : name,
                'email'   : email,
                'phone'   : phone,
                'passwd'  : passwd
            },
            dataType: 'json',
            success: function(data) {
                if (data['ret'] == 0) {
                    alert("插入成功");
                    window.location.reload();
                }else if(data['ret'] != 0){
                    alert(data['info']);
                }
            }
        });
    });

    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
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
    });

    $(".opt-set-passwd").on("click", function(){
        var $passwd = $("<input/>");
        var account = $(this).get_opt_data("account");
        var uid     = $(this).get_opt_data("uid");
        var arr =[
            ["account", account ] ,
            ["passwd", $passwd]
        ];
        $passwd.val("123456");
        var me=this;

        $.show_key_value_table("修改密码", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax('/authority/set_passwd', {
                    'account' : account,
                    'uid'     : uid,
                    'passwd'  : $passwd.val()
                },function(resp){
                    $(me).parent().find(".opt-sync-kaoqin ").click();
                });
            }
        });
    });

    $(".opt-set-fulltime-teacher-type").on("click", function(){
        var opt_data=$(this).get_opt_data();
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
    });


    $("#id_fix_passwd").on("click",function(){
        var account = $(this).data("account");
        var new_passwd = $("#id_new_passwd").val();

        if(new_passwd == ""){
            alert("请输入新密码");
        }else{
            $.ajax({
                url: '/authority/set_passwd',
                type: 'POST',
                data: {
                    'account': account,
                    'passwd': new_passwd
                },
                dataType: 'json',
                success: function(data) {
                    if(data['ret'] != 0){
                        alert(data['info']);
                    }else{
                        window.location.href = "/authority/manager_list";
                    }
                }
            });
        }
    });

    $(".add_player").on("click", function(){
        var $account=$("<input/>");
        var $phone=$("<input/>");
        var $email=$("<input/>");
        var $role=$("<select/>");
        var $account_role=$("<select/>");
        var date = new Date();
        var currentdate = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
        var $become_member_time=$("<input value=" + currentdate + " >");

        $.do_ajax("/user_deal/admin_group_list_js",{},function(resp){
            var str="";
            $(resp.data).each(function(){
                if (g_args.assign_groupid >0   ) {
                    if(g_args.assign_groupid == this.groupid ) {
                        str+="<option value="+this.groupid+"> " + this.group_name+ "</option>" ;
                    }
                }else{
                    str+="<option value="+this.groupid+"> " + this.group_name+ "</option>" ;
                }
            });
            $role.append(str);
            $role.val(38);
        });

        var need_account_role_list=null;

        if (g_args.assign_account_role>0) {
            need_account_role_list=[ g_args.assign_account_role ];
        }
        Enum_map.append_option_list("account_role", $account_role,true,need_account_role_list);

        var arr=[
            ["account",$account] ,
            ["电话", $phone],
            ["email", $email],
            ["角色", $account_role],
            ["权限", $role ],
            ['入职时间', $become_member_time]
        ];
        initPicker($become_member_time);

        $.show_key_value_table("新增用户", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var account=$.trim($account.val());
                if (account.length <2) {
                    alert("账号名太短");
                    return ;
                }

                $.do_ajax("/authority/add_manager",{
                    'account': account,
                    'name': account,
                    'email': $.trim($email.val().split("@")[0]) +"@leoedu.com",
                    'phone': $phone.val(),
                    'groupid': $role.val(),
                    'passwd': account,
                    'account_role': $account_role.val(),
                    'become_member_time':$become_member_time.val()
                });
            }
        });
    });

    $(".edit-manage").on("click",function(){
        var opt_data=$(this).get_opt_data();
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
        var $seller_student_assign_type=$("<select/>");
        var $main_department =$("<select/>");
        var $call_phone_passwd =$("<input/>").val(opt_data.call_phone_passwd );
        var $become_member_time =$('<input/>').val(opt_data.become_time);

        var need_account_role_list=null;
        if (g_args.assign_account_role>0) {
            need_account_role_list=[ g_args.assign_account_role ];
        }
        Enum_map.append_option_list("account_role", $account_role,true,need_account_role_list);
        Enum_map.append_option_list("seller_level", $seller_level,true);
        Enum_map.append_option_list("boolean", $day_new_user_flag,true);
        Enum_map.append_option_list("boolean", $become_full_member_flag,true);
        Enum_map.append_option_list("boolean", $no_update_seller_level_flag,true);
        Enum_map.append_option_list("call_phone_type", $call_phone_type ,true);
        Enum_map.append_option_list("main_department", $main_department ,true);
        Enum_map.append_option_list("seller_student_assign_type", $seller_student_assign_type,true);
        $call_phone_type.val(opt_data.call_phone_type);
        $main_department.val(opt_data.main_department);

        $seller_student_assign_type.val(opt_data.seller_student_assign_type );
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
            ["例子分配规则",$seller_student_assign_type] ,

            //['入职时间',$become_member_time],

            ["-","-"],
            ["打电话类型",$call_phone_type ],
            ["打电话账号id",$tquin], ["打电话密码",$call_phone_passwd ],
            ["-","-"],

            ["咨询师等级",$seller_level] ,
            ["微信号",$wx_id] ,
            ["上级",$up_adminid],
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
                    "seller_student_assign_type" : $seller_student_assign_type.val(),
                    'tquin': $tquin.val(),
                    'email': $email.val(),
                    'old_seller_level': opt_data.seller_level,
                    'seller_level': $seller_level.val(),
                    'up_adminid': $up_adminid.val(),
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
            $.admin_select_user($up_adminid,"admin",function(){}, true );
        });


    });

    //超级更改权限组
    $(".opt-power").on("click",function(){
        var opt_data=$(this).get_opt_data();

        // alert(g_adminid);

        // alert(opt_data.old_permission);
        var uid= opt_data.uid;
        var show_list=[];
        if ($.get_action_str()=="manager_list_for_seller" ) {
            show_list=[57	, 38	, 74	, 77, 80	,];
        }
        var show_all_flag=($.get_action_str()=="manager_list");

        var permission  = opt_data["old_permission"];
        $.do_ajax("/authority/get_permission_list",{
            "permission" : permission
        },function(response){
            var data_list   = [];
            var select_list = [];
            $.each( response.data,function(){
                if (  show_all_flag || $.inArray(  parseInt( this["groupid"]),  show_list) != -1 ) {
                    data_list.push([this["groupid"],this["account_role_str"], this["group_name"]  ]);
                }

                if (this["has_power"]) {
                    select_list.push (this["groupid"]) ;
                }

            });

            $(this).admin_select_dlg({
                header_list     : [ "id","角色","名称" ],
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
    });

    //一般更改权限组
    $(".opt-power2").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var uid= opt_data.uid;
        var show_list=[];
        if ($.get_action_str()=="manager_list_for_seller" ) {
            show_list=[57	, 38	, 74	, 77, 80	,];
        }
        var show_all_flag=($.get_action_str()=="manager_list");

        var permission  = opt_data["old_permission"];
        $.do_ajax("/user_power/get_permission_list",{
            "permission" : permission,
            "account_role":opt_data['account_role']
        },function(response){
            var data_list   = [];
            var select_list = [];
            var forbid_arr = [];
            var permit = "";
            var forbid = "";
            $.each( response.data,function(){
                data_list.push([this["groupid"],this["account_role_str"], this["group_name"]]);

                if (this["has_power"]) {
                    select_list.push (this["groupid"]) ;
                };
                if( this['forbid'] == 1 ){
                    forbid_arr.push (this["groupid"]) ;
                }
                if( this['forbid'] == 0 ){
                    permit = this["account_role_str"];
                }
            });
            var forbid_title = "该用户属角色:"+permit+",只有该角色下的权限和通用权限可以添加或者移除,其他角色下的权限只有超级管理员可以编辑";
            //console.log(forbid_arr);
            $(this).admin_select_dlg_forbid({
                header_list     : [ "id","角色","名称" ],
                data_list       : data_list,
                multi_selection : true,
                select_list     : select_list,
                forbid_arr      : forbid_arr,
                forbid_title    : forbid_title,
                onChange        : function( select_list,dlg) {
                    $.do_ajax("/authority/set_permission",{
                        "uid": uid,
                        "groupid_list":JSON.stringify(select_list),
                        "old_permission": permission,
                    });
                }
            });

        }) ;
    });

    $(".opt_set_openid").on("click",function(){
        var opt_data=$(this).get_opt_data();

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
            filter_list:[
                [
                    {
                        size_class: "col-md-8" ,
                        title :"微信姓名",
                        'arg_name' :  "nickname"  ,
                        type  : "input"
                    }

                ]
            ],

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



    });

    $(".set-account-role").on("click", function(){
        var opt_data= $(this).get_opt_data();
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

                $.ajax({
                    url: '/authority/set_account_role',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'uid'      : opt_data.uid ,
                        'account_role' : id_account_role.val() ,
                        'creater_adminid' : id_creater_adminid.val()
                    },
                    success: function(data) {
                        if(data.ret != -1){
                            window.location.reload();
                        }
                    }
                });
            }
        },function(){
            $.admin_select_user(id_creater_adminid,"admin" );
        });

    });
    if (g_args.assign_groupid) {
        $(".set-account-role").hide();
    }
    if ( window.location.pathname =="/authority/manager_list_for_ass" ) {
        $(".add_player").hide();
    }


    if ( $.get_action_str() =="manager_list_for_kaoqin" ) {
        $(".add_player").hide();
        var a_list=$(".common-table .div-row-data a");
        $.each(a_list, function()  {
            var $item=$(this);
            if ($item.hasClass("opt-del")
                || $item.hasClass("opt-sync-kaoqin")
                || $item.hasClass("opt-set-passwd")
            ) {

            }else{
                $item.hide();
            }
        });
    }


    $('.opt-change').set_input_change_event(load_data);

    $(".opt-login").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.do_ajax("/login/login_other",{
            "login_adminid" : opt_data.uid
        });

    });


    $(".opt-change-account").on("click",function(){
        var opt_data=$(this).get_opt_data();

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


    });



    $(" .opt-sync-kaoqin ").on("click",function(){
        var opt_data=$(this).get_opt_data();

        $.do_ajax(  "/user_deal/get_kaoqin_machine_list",{
            "adminid" : opt_data.uid,
            "page_num":1 ,
            "page_count":100000 ,
        } ,function(resp){
            var data_list=[];
            var select_id_list=[];
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

    });



    $("#id_email_list").on("click",function(){
        var mail_arr=[];
        $(".common-table .opt_set_openid").each(function(){
            var opt_data=$(this).get_opt_data();
            mail_arr.push( opt_data.email);
        } ) ;
        BootstrapDialog.alert(mail_arr.join(",\n")) ;
    });


    $(".opt-email").on("click",function(){
        var opt_data = $(this).get_opt_data();
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
    });

    //用户手机号绑定
    $(".opt-set-user-phone").on("click", function(){
        var opt_data = $(this).get_opt_data();
        console.log(opt_data);
        var account  = $(this).get_opt_data("account");
        var phone    = $(this).get_opt_data("phone");
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
                    if(resp.ret==0){
                        BootstrapDialog.alert("帐号生成成功!");
                    }else{
                        BootstrapDialog.alert("系统异常!");
                    }
                   // alert(resp['success']);
                });
            }
        });
    });


    $(".opt-gen-ass").on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm( "要生成助教账号:"+opt_data.account+"?", function(val){
            if (val) {
                $.do_ajax('/ajax_deal2/gen_ass_from_account', {
                    'adminid' : opt_data.uid
                });
            }
        } );
    });

    $(".opt-delete-permission").on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm( "要清除该用户所有权限:"+opt_data.account+"?", function(val){
            if (val) {
                $.do_ajax('/ajax_deal2/delete_permission_by_uid', {
                    'adminid' : opt_data.uid
                });
            }
        } );

    });

    $(".opt-change-permission-new").on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm( "要更换权限:"+opt_data.account+"?", function(val){
            if (val) {
                $.do_ajax('/ajax_deal2/change_permission_by_uid_new', {
                    'adminid' : opt_data.uid
                });
            }
        } );

    });



    $(".opt-log").on("click",function(){
        var opt_data=$(this).get_opt_data();
        window.open('/authority/seller_edit_log_list?adminid='+ opt_data.uid) ;
    });

    $(".opt-refresh_call_end").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.do_ajax('/authority/update_lesson_call_end_time', {
            'adminid' : opt_data.uid
        },function(resp){
            if(resp){
                alert('刷新成功!');
            }else{
                alert('有试听成功未回访!');
                window.open('/seller_student_new/no_lesson_call_end_time_list?adminid='+ opt_data.uid) ;
            }
        });
    });

    $(".opt-set-train-through-time").on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm( "要同步老师档案入职时间吗?", function(val){
            if (val) {
                $.do_ajax('/ajax_deal2/set_teacher_train_through_info', {
                    'phone' : opt_data.phone,
                    'adminid' : opt_data.uid
                });
            }
        } );

    });

    $(".opt-set-teacher-level").on("click",function(){
        var opt_data=$(this).get_opt_data();
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

    });

    $('.opt-ower-permission').on('click', function() {
        var opt_data=$(this).get_opt_data();
        console.log(parseInt(opt_data.uid));
        $.do_ajax('/company_wx/get_ower_power',{
            'uid':parseInt(opt_data.uid)
        }, function(res) {
            var permission = res.data
        $.do_ajax("/authority/get_permission_list",{
            "permission" : permission
        },function(response){
            var data_list   = [];
            var select_list = [];

            var perm = permission.split(",");

            $.each( response.data,function(){
                data_list.push([this["groupid"], this["group_name"]  ]);


                for(var i=0; i<perm.length; i++) {
                    if (perm[i] == this['groupid']) {
                        //data_list.push([this["groupid"], this["group_name"]  ]);
                        select_list.push (this["groupid"]) ;
                    }
                }

            });
            $(this).admin_select_dlg({
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
    });

    $("#id_flush_power").on("click", function() {
        $.do_ajax("/user_manage_new/flush_power",{

        });
    });

    //实例化一个plupload上传对象
    var uploader = $.plupload_Uploader({
        browse_button : 'id_upload_xls', //触发文件选择对话框的按钮，为那个元素id
        url : '/ss_deal/upload_permission_info_from_xls', //服务器端的上传页面地址
        flash_swf_url : '/js/qiniu/plupload/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
        silverlight_xap_url : '/js/qiniu/plupload/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
        filters: {
            mime_types : [ //只允许上传图片和zip文件
                { title : "xls files", extensions : "xls" },
                { title : "xlsx files", extensions : "xlsx" }
            ],
            max_file_size : '40m', //最大只能上传400kb的文件
            prevent_duplicates : true //不允许选取重复文件
        }
    });

    uploader.init();
    uploader.bind('FilesAdded',function(up, files) {
        uploader.start();
    });
    if(g_account=='龚隽'){
        window["download_show"]();
    }



});
    function initPicker(obj)
    {
      obj.datetimepicker({
            lang       : 'ch',
            datepicker : true,
            timepicker : false,
            format     : 'Y-m-d',
            step       : 30,
            onChangeDateTime :function(){
              $(this).hide();
            }
        });
    }
