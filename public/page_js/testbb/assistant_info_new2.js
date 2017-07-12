/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/testbb-assistant_info_new2.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            parentid:	$('#id_parentid').val(),
            userid:	$('#id_userid').val(),
            grade:	$('#id_grade').val(),
            phone:	$('#id_phone').val(),
            wx_openid:	$('#id_wx_openid').val(),
        })
    };

    Enum_map.append_option_list("grade",$("#id_grade"));

    $('#id_userid').val(g_args.userid);
    $('#id_parentid').val(g_args.parentid);
    $('#id_grade').val(g_args.grade);
    $('#id_phone').val(g_args.phone);
    $('#id_wx_openid').val(g_args.wx_openid);

    $.admin_select_user($('#id_userid'), "student", load_data);
    $.admin_select_user($('#id_parentid'), "assistant", load_data);

    $("#id_add").on("click",function(){
        var $parentid  = $("<input/>");
        var $userid    = $("<input/>");
        var $phone     = $("<input/>");
        var $wx_openid = $("<input/>");

        var arr=[
            ["上级id",  $parentid],
            ["用户id",  $userid],
            ["手机",  $phone],
            ["微信openid",  $wx_openid],
        ];

        $.show_key_value_table("新增代理", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal/agent_add",{
                    "parentid"  : $parentid.val(),
                    "phone"     : $phone.val(),
                    "userid"    : $userid.val(),
                    "wx_openid" : $wx_openid.val()
                })
            }
        })
    });


    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();

        var $phone=$("<input/>");
        var $wx_openid=$("<input/>");
        var $parentid=$("<input/>");
        $phone.val(opt_data.phone );
        $wx_openid.val(opt_data.wx_openid);
        $parentid.val(opt_data.parentid);
        var arr=[
            ["上级id",  $parentid],
            ["电话",  $phone],
            ["微信",  $wx_openid],
        ];

        $.show_key_value_table("修改代理信息", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal/agent_edit",{
                    "id":opt_data.id,
                    "phone" : $phone.val() ,
                    "wx_openid" : $wx_openid.val(),
                    "parentid" : $parentid.val()
                })
            }
        })
    });

    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();

        BootstrapDialog.confirm(
            "要删除手机为:" + opt_data.phone + "的代理吗？",
            function(val) {
                if (val) {
                    $.do_ajax("/ajax_deal/agent_del", {
                        "id": opt_data.id
                    })
                }
            })
    });

    $('.opt-change').set_input_change_event(load_data);
});
