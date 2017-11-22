/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-parent_archive.d.ts" />

$(function(){
    function load_data(){
        var nick     = $("#id_user_name").val();
        var phone    = $("#id_phone").val();

        $.reload_self_page({
            nick        : nick,
            phone       : phone
        });
    }

    // function load_data(){
    //     $.reload_self_page ( {
	// 		parentid:	$('#id_parentid').val(),
	// 		gender:	$('#id_gender').val(),
	// 		nick:	$('#id_nick').val(),
	// 		phone:	$('#id_phone').val(),
	// 		last_modified_time:	$('#id_last_modified_time').val(),
	// 		assistantid:	$('#id_assistantid').val()
    //     });
    // }


	$('#id_parentid').val(g_args.parentid);
	$('#id_gender').val(g_args.gender);
	$('#id_nick').val(g_args.nick);
	$('#id_phone').val(g_args.phone);
	$('#id_last_modified_time').val(g_args.last_modified_time);
	$('#id_assistantid').val(g_args.assistantid);


	$('.opt-change').set_input_change_event(load_data);

    $("#id_phone").val(g_args.phone);
    $("#id_user_name").val(g_args.nick);

    //跳转
    $('.opt-user').on('click',function(){
        var userid = $(this).parent().data("parentid");
        window.open(
            '/user_manage/pc_relationship?studentid=-1&parentid='+userid
        );
    });


    // 设置学生临时密码
    $(".opt-modify").on("click", function(){
        var html_node =$("<div></div>").html($.dlg_get_html_by_class('dlg_set_dynamic_passwd'));
        var phone=$(this).parent().data("phone");
        html_node.find(".stu_phone").text($(this).parents("td").siblings(".user_phone").text());
        html_node.find(".stu_nick").text($(this).parents("td").siblings(".user_nick").text());
        html_node.find(".dynamic_passwd").val("123456");

        BootstrapDialog.show({
            title: '设置学生动态登陆密码',
            message : html_node,
            closable: true,
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action: function(dialog) {
                        var passwd = html_node.find(".dynamic_passwd").val();

                        $.ajax({
                            type     : "post",
                            url      : "/user_manage/set_dynamic_passwd",
                            dataType : "json",
                            data     : { "phone":phone, "passwd": passwd, "role": 4 },
                            success  : function(result){
                                BootstrapDialog.alert(result['info']);
                            }
                        });
                        dialog.close();
                    }
                },
                {
                    label: '取消',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
        });
    });


    $(".opt-edit").on('click',function(){
        var parentid = $(this).get_opt_data("parentid");
        $.do_ajax("/user_manage/get_parent_list",{
            "parentid" : parentid
        },function(result){
            var data                  = result.ret_info;
            var id_parentid           = $("<input display:none></input>");
            var id_nick               = $("<input ></input>");
            var id_phone              = $("<input ></input>");
            var id_gender             = $.obj_copy_node("#id_gender");
            var id_has_login          = $.obj_copy_node("#id_has_login");
            var id_last_modified_time = $("<input ></input>");
            var id_wx_openid = $("<input ></input>");

            var arr = [
                ["姓名",id_nick],
                ["联系电话",id_phone],
                ["性别",id_gender],
                ["wx_openid",id_wx_openid],
                ["最后修改时间",id_last_modified_time],
                ["登陆情况",id_has_login],
            ];
            id_last_modified_time.datetimepicker({
                format: "Y-m-d H:i",
                autoclose: true,
                todayBtn: true
            });
            id_parentid.val(data.parentid);
            id_nick.val(data.nick);
            id_phone.val(data.phone);
            id_gender.val(data.gender);
            id_last_modified_time.val(data.last_modified_time);
            id_has_login.val(data.has_login);
            id_wx_openid.val(data.wx_openid);

            $.show_key_value_table("更改家长信息",arr,{
                label: '确认',
                cssClass: 'btn-warning',
                action  : function(dialog){
                    var parentid=id_parentid.val();
                    var nick=id_nick.val();
                    var phone=id_phone.val();
                    var gender=id_gender.val();
                    var last_time=id_last_modified_time.val();
                    var has_login=id_has_login.val();

                    $.ajax({
                        url:'/user_manage/update_parent_info',
                        type:'POST',
                        dataType:'json',
                        data:{
                            'parentid'  : parentid,
                            'nick'      : nick,
                            'phone'     : phone ,
                            'gender'    : gender ,
                            'last_time' : last_time,
                            'has_login' : has_login,
                            'wx_openid' :  id_wx_openid.val()
                        },
                        success:function(data){
                            if(data.ret != -1){
                                window.location.reload();
                            }
                        }
                    });
                }
            });
        });
    });

    $(".for_input").on("change",function(){
        load_data();
    });

    $(".show_phone").on("click",function(){
        var phone = $(this).data("phone");
        BootstrapDialog.alert(phone);
    });

    download_hide();
});
