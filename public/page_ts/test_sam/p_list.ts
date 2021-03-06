/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/authority-manager_list.d.ts" />

$(function(){
    var show_name_key = "";
    show_name_key = "account_name_"+g_adminid;
    function load_data(){
        if($.trim($("#id_user_info").val()) != g_args.user_info){
            $.do_ajax("/user_deal/set_item_list_add",{
                "item_key" : show_name_key,
                "item_name" : $.trim($("#id_user_info").val())
            },function(){});
        }
        $.reload_self_page({
            user_info             : $('#id_user_info').val(),
            has_question_user     : $('#id_has_question_user').val(),
            cardid                : $('#id_cardid').val(),
            account_role          : $('#id_account_role').val(),
            tquin                 : $('#id_tquin').val(),
            seller_level          : $('#id_seller_level').val(),
            day_new_user_flag     : $('#id_day_new_user_flag').val(),
            del_flag              : $('#id_del_flag').val(),
            adminid               : $('#id_adminid').val()
        });
    }
    $("#id_user_info").autocomplete({
        source:"/user_deal/get_item_list?list_flag=1&item_key="+show_name_key,
        minLength: 0,
        select: function(event, ui){
            load_data();
        }
    });

    Enum_map.append_option_list("boolean",$('#id_day_new_user_flag'));
    Enum_map.append_option_list("account_role", $('#id_account_role'));
    $('#id_account_role').val(g_args.account_role);
    $('#id_user_info').val(g_args.user_info);
    $('#id_has_question_user').val(g_args.has_question_user);
    $('#id_del_flag').val(g_args.del_flag);
    $('#id_cardid').val(g_args.cardid);
    $('#id_tquin').val(g_args.tquin);

    $('#id_seller_level').val(g_args.seller_level);
    $.enum_multi_select($('#id_seller_level'), 'seller_level', function(){load_data();})

    $('#id_adminid').val(g_args.adminid);
    $.admin_select_user($('#id_adminid'), 'admin', load_data);

    $('#id_day_new_user_flag').val(g_args.day_new_user_flag);

    $('#id_add_manage').on("click", function(){
        var account = $("#id_account").val();
        var name = $('#id_real_name').val();
        var email = $('#id_email').val();
        var phone = $('#id_phone').val();
        var passwd = $('#id_passwd').val();
        $.ajax({
            url: '/test_sam/add_manager',
            type: 'POST',
            data:{
                'account'  : account,
                'name'     : name,
                'email'    : email,
                'phone'    : phone,
                'passwd'   : passwd
            },
            dataType: 'json',
            success: function(data){
                if(data['ret'] == 0){
                    alert("插入成功");
                }else if(data['ret'] != 0){
                    alert(data['info']);
                }
            }
        });
    });

    $('.opt-del').on('click',function(){
        var opt_data = $(this).get_opt_data();
        var uid = opt_data.uid;
        var del_flag = $("<select/>");
        Enum_map.append_option_list("boolean", del_flag, true);
        del_flag.val(opt_data.del_flag);
        var arr = [
            ['uid', opt_data.uid],
            ['account', opt_data.account],
            ['是否离职', del_flag]
        ];
        $.show_key_value_table("更改员工状态", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog){
                $.do_ajax('/test_sam/del_manager',{
                    'uid': opt_data.uid,
                    'del_flag': del_flag.val()
                });
            }
        });

        $(".opt-set-passwd").on("click", function(){
            var $passwd = $('<input />');
            var account = $(this).get_opt_data("account");
            var arr = [
                ['account', account],
                ['passwd', $passwd]
            ];
            $passwd.val("123");
            var me = this;

            $.show_key_value_table("修改密码", arr, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog){
                    $.do_ajax('/test_sam/set_passwd',{
                        'account' : account,
                        'passwd' : $passwd.val()
                    }, function(resp){
                        $(me).parent().find(".opt-sync-kaoqin").click();
                    });
                }
            });
        });


        $("#id_fix_passwd").on("click", function(){
            var account = $(this).data("account");
            var new_passwd = $('#id_new_passwd').val();
            if(new_passwd == ''){
                alert("请输入新密码");
            }else{
                $.ajax({
                    url: '/test_sam/set_passwd',
                    type: 'POST',
                    data:{
                        'account' : account,
                        'passwd' : new_passwd
                    },
                    dataType: 'json',
                    success: function(data){
                        if(data['ret'] != 0){
                            alert(data['info']);
                        }else{
                            window.location.href = '/test_sam/manager_list';
                        }
                    }
                });
            }
        });

        $(".add_palyer").on("click", function(){
            var $account = $("<input/>");
            var $phone = $("<input/>");
            var $email = $("<input/>");
            var $role = $("<select/>");
            var $account_role = $("<select/>");

            $.do_ajax("/user_deal/admin_group_list_js",{},function(resp){
                var str = "";
                $(resp.data).each(function(){
                    if(g_args.assign_groupid > 0){
                        if(g_args.assign_groupid == this.groupid){
                            str += "<option value="+this.groupid+"> "+this.group_name+"</option>";
                        }
                    }
                    else{
                        str += "<option value="+this.groupid+"> "+this.group_name+"</option>";
                    }
                });
                $role.append(str);
                $role.val(38);
            });

            var need_account_role_list = null;
            if(g_args.assign_account_role>0){
                need_account_role_list = [g_args.assign_account_role];
            }
            Enum_map.append_option_list('account_role',$account_role, true, need_account_role_list);
            var arr=[
                ['account', $account],
                ['电话', $phone],
                ['email', $email],
                ['角色', $account_role],
                ['权限', $role],
            ];
            $.show_key_value_table("新增用户", arr, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog){
                    var account = $.trim($account.val());
                    if(account.length < 2){
                        alert("账户名太短");
                        return;
                    }
                    $.do_ajax('/test_sam/add_manager',{
                        'account': account,
                        'name': account,
                        'email': $.trim($email.val().split("@")[0]) + "@leoedu.com",
                        'phone': $phone.val(),
                        'groupid': $role.val(),
                        'passwd': account,
                        'account_role': $account_role.val()
                    });
                }
            });
        });


        $(".edit-manage").on("click", function(){
            var opt_data = $(this).get_opt_data();
            var uid = opt_data.uid;
            var $phone = $("<input/>").val(opt_data.phone);
            var $email = $("<input/>").val(opt_data.email);
            var $account_role = $("<select/>");
            var $seller_level = $("<select/>");
            var $become_full_member_flag = $("<select/>");
            var $day_new_user_flag = $("<select/>");
            var $name = $('<input/>').val(opt_data.name);
            var $tquin = $("<input/>").val(opt_data.tquin);
            var $cardid = $("<input/>").val(opt_data.cardid);
            var $wx_id = $("<input/>").val(opt_data.wx_id);
            var $up_adminid = $("<input/>").val(opt_data.up_adminid);

            var $call_phone_type = $("<select/>");
            var $main_department = $("<select/>");
            var $call_phone_passwd = $("<input/>").val(opt_data.call_phone_passwd);

            var need_account_role_list = null;
            if(g_args.assign_account_role > 0){
                need_account_role_list = [g_args.assign_account_role];
            }
            Enum_map.append_option_list("account_role", $account_role, true, need_account_role_list);
            Enum_map.append_option_list("seller_level", $seller_level, true);
            Enum_map.append_option_list("boolean", $day_new_user_flag, true);
            Enum_map.append_option_list("boolean",$become_full_member_flag, true);
            Enum_map.append_option_list("call_phone_type", $call_phone_type, true);
            Enum_map.append_option_list("main_department", $main_department, true);
            $call_phone_type.val(opt_data.call_phone_type);
            $main_department.val(opt_data.main_department);

            $account_role.val(opt_data.account_role);
            $seller_level.val(opt_data.seller_level);
            $day_new_user_flag.val(opt_data.day_new_user_flag);
            $become_full_member_flag.val(opt_data.become_full_member_flag);

            var arr=[
                ['uid', opt_data.uid],
                ['account', opt_data.account],
                ['姓名', $name],
                ['电话', $phone],
                ['邮件', $email],
                ['角色', $account_role],
                ['每天新例子',$day_new_user_flag],
                ['考勤卡id', $cardid],

                ['-','-'],
                ['打电话类型', $call_phone_type],
                ['打电话账户id', $tquin],
                ['打电话密码', $call_phone_passwd],
                ['-','-'],

                ['咨询师等级', $seller_level],
                ['微信号', $wx_id],
                ['上级', $up_adminid],
                ['转正', $become_full_member_flag],
                ['部门', $main_department]
            ];

            $.show_key_value_table("修改用户信息", arr, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog){
                    $.do_ajax('/test_sam/update_admin_info',{
                        'uid' : opt_data.uid,
                        'phone' : $phone.val(),
                        'name': $name.val(),
                        'day_new_user_flag': $day_new_user_flag.val(),
                        'cardid': $cardid.val(),
                        'account_role': $account_role.val(),
                        'tquin': $tquin.val(),
                        'email': $email.val(),
                        'seller_level': $seller_level.val(),
                        'up_adminid': $up_adminid.val(),
                        'become_full_member_flag': $become_full_member_flag.val(),
                        'call_phone_type': $call_phone_type.val(),
                        'call_phone_passwd': $call_phone_passwd.val(),
                        'wx_id': $wx_id.val(),
                        'main_department': $main_department.val()
                    });
                }
            },function(){
                $.admin_select_user($up_adminid,'admin', function(){}, true);
            });
        });

        $(".opt-power").on("click", function(){
            var opt_data = $(this).get_opt_data();
            var uid = opt_data.uid;
            var show_list = [];
            if($.get_action_str() == "manager_list_for_seller"){
                show_list = [57, 38, 74, 77, 80];
            }
            var show_all_flag = ($.get_action_str() == "manage_list");
            var permission = opt_data['permission'];
            $.do_ajax("/test_sam/get_permission_list", {
                "permission" : permission
            }, function(response){
                var data_list = [];
                var select_list = [];
                $.each(response.data, function(){
                    if(show_all_flag||$.inArray(parseInt(this['groupid']), show_list) != -1){
                        data_list.push([this['groupid'], this['group_name']]);
                    }
                    if(this['has_power']){
                        select_list.push(this['groupid']);
                    }
                });
                $(this).admin_select_dlg({
                    header_list : ['id', '名称'],
                    data_list : data_list,
                    multi_selection: true,
                    select_list    : select_list,
                    onChange       : function(select_list, dlg){
                        $.do_ajax("/test_sam/set_permission",{
                            'uid': uid,
                            'groupid_list': JSON.stringify(select_list)
                        });
                    }
                });
            });
        });

        $(".opt_set_openid").on("click", function(){
            var opt_data = $(this).get_opt_data();
            $(this).admin_select_dlg_ajax({
                "opt_type" : "select",
                select_no_select_value: 0,
                select_no_select_title: '未设置',
                select_primary_field: 'openid',
                select_display: "",
                'url' : '/user_deal/get_wx_user_list',
                'args_ex':{
                    
                },

                'field_list':[
                    {
                        title:"姓名",
                        width: 50,
                        field_name: 'nickname'
                    },
                    {
                        title:"openid",
                        render:function(val, item){
                            return item.openid;
                        }
                    },
                    {
                        title:"时间",
                        render:function(val, item){
                            return item.update_time;
                        }
                    }
                ],
                filter_list:[
                    [
                        {
                            size_class : 'col-md-8',
                            title: "微信姓名",
                            'arg_name' : 'nickname',
                            type: 'input'
                        }
                    ]
                ],
                'auto_close' : true,
                "onChange" : function(val){
                    $.do_ajax("/user_deal/binding_wx_to_admin",{
                        id: opt_data.uid,
                        wx_openid:val
                    });
                },
                'onLoadData' : null
            });
        })

        $(".set-account-role").on("click", function(){
            var opt_data = $(this).get_opt_data();
            var id_account_role = $("<select/>");
            var id_creater_adminid = $("<input/>");

            Enum_map.append_option_list("account_role", id_account_role, true);
            if(opt_data.creater_adminid){
                id_creater_adminid.val(opt_data.creater_adminid);
            }else{
                id_creater_adminid.val(287);
            }

            if(opt_data.account_role){
                id_account_role.val(opt_data.account_role);
            }else{
                id_account_role.val(2);
            }
            var arr = [
                ['创建者', id_creater_adminid],
                ['角色', id_account_role],
            ];
            $.show_key_value_table("设置角色", arr, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog){
                    $.ajax({
                        url: '/test_sam/set_account_role',
                        type:'POST',
                        dataType:'json',
                        data:{
                            'uid' : opt_data.uid,
                            'account_role' : id_account_role.val(),
                            'creater_adminid': id_creater_adminid.val()
                        },
                        success:function(data){
                            if(data.ret != -1){
                                window.location.reload();
                            }
                        }
                    });
                }
            },function(){
                $.admin_select_user(id_creater_adminid,'admin');
            });
        });
        if(g_args.assign_groupid){
            $(".set-account-role").hide();
        }
        if(window.location.pathname != '/test_sam/manager_list'){
            
        }
        if(window.location.pathname == '/test_sam/manager_list_for_ass'){
            $(".add_player").hide();
        }

        if($.get_action_str() == "manager_list_for_kaoqin"){
            $(".add_player").hide();
            var a_list = $(".common-table .div-row-data a");
            $.each(a_list, function(){
                var $item = $(this);
                if($item.hasClass('opt-del')
                   || $item.hasClass('opt-sync-kaoqin')
                   || $item.hasClass('opt-set-passwd')
                  ){

                }else{
                    $item.hide();
                }
            });

        }
        $('.opt-change').set_input_change_event(load_data);
        $('.opt-login').on("click", function(){
            var opt_data = $(this).get_opt_data();
            $.do_ajax("/login/login_other",{
                "login_adminid" : opt_data.uid
            });
        });

        $('.opt-change').set_input_change_event(load_data);
        $('.opt-login').on("click", function(){
            var opt_data = $(this).get_opt_data();
            $.do_ajax("/login/login_other", {
                "login_adminid" : opt_data.uid
            });
        });

        $('.opt-change-account').on("click", function(){
            var opt_data $(this).get_opt_data();
            var uid = opt_data.uid;
            var $account = $("<input />");
            $account.val(opt_data.account);
            var arr = [
                ['uid', opt_data.uid],
                ['account', $account],
            ];

            $.show_key_value_table("更改员工账户",arr , {
                label: "确认",
                cssClass: 'btn-warning',
                action: function(diglog){
                    $.do_ajax('/user_deal/set_account', {
                        'uid': opt_data.uid,
                        'account' : $account.val()
                    });
                }
            });
        });

        $(".opt-sync-kaoqin").on("click", function(){
            var opt_data = $(this).get_opt_data();
            $.do_ajax("/user_deal/get_kaoqin_machine_list",{
                "adminid": opt_data.uid,
                'page_num':1,
                'page_count': 10000,
            }, function(resp){
                var data_list=[];
                var select_id_list=[];
                $.each(resp['data']['list'], function(i, item){
                    data_list.push([item.machine_id, item.title]);
                    if(item.adminid){
                        select_id_list.push(item.machine_id);
                    }
                });

                $('<div></div>').admin_select_dlg({
                    'data_list': data_list,
                    'header_list':['id', '安放位置'],
                    'onChange' : function(select_list ,dlg){
                        dlg.close();
                        $.do_ajax("/user_deal/sync_kaoqin", {
                            'adminid' : opt_data.uid,
                            'machine_id_list': JSON.stringify(select_list)
                        });
                    },
                    'select_list' : select_id_list,
                    'multi_selection': true
                });
            });
        });

        $("#id_email_list").on("click", function(){
            var mail_arr = [];
            $('.common-table .opt_set_openid').each(function(){
                var opt_data = $(this).get_opt_data();
                mail_arr.push(opt_data.email);
            });
            BootstrapDialog.alert(mail_arr.join(",\n"));
        });

        $(".opt-email").on('click', function(){
            var opt_data = $(this).get_opt_data();
            var email = opt_data.email;
            var name = opt_data.name;
            var arr = [
                ['邮箱',email],
                ['备注',name]
            ];
            $.show_key_value_table("邮箱信息", arr, [{
                label: '同步账户',
                cssClass: 'btn-warning',
                action: function(dialog){
                    $.do_ajax("/ajax_deal2/sync_email",{
                        'email' : email,
                        'title' : name,
                    });
                    alert("更新中(一般5秒)");
                }
            },{
                label: '重置密码:111111',
                cssClass: 'btn-warning',
                action: function(dialog){
                    BootstrapDialog.confirm("重置密码:111111",function (val){
                        $.do_ajax("/ajax_deal2/set_email_passwd",{
                            'email': email,
                        });
                      alert("更新中(一般5秒)"); 
                    });
                }
            }]);
        });

    });
});
