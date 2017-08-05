/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            parentid:	$('#id_parentid').val(),
            userid:	$('#id_userid').val(),
            phone:	$('#id_phone').val(),
            wx_openid:	$('#id_wx_openid').val(),
            bankcard:	$('#id_bankcard').val(),
            idcard:	$('#id_idcard').val(),
            bank_address:	$('#id_bank_address').val(),
            bank_account:	$('#id_bank_account').val(),
            bank_phone:	$('#id_bank_phone').val(),
            bank_province:	$('#id_bank_province').val(),
            bank_city:	$('#id_bank_city').val(),
            bank_type:	$('#id_bank_type').val(),
            zfb_name:	$('#id_zfb_name').val(),
            zfb_account:	$('#id_zfb_account').val(),
        })
    };


    $('#id_userid').val(g_args.userid);
    $('#id_parentid').val(g_args.parentid);
    $('#id_phone').val(g_args.phone);
    $('#id_wx_openid').val(g_args.wx_openid);
    $('#id_bankcard').val(g_args.bankcard);
    $('#id_idcard').val(g_args.idcard);
    $('#id_bank_address').val(g_args.bank_address);
    $('#id_bank_account').val(g_args.bank_account);
    $('#id_bank_phone').val(g_args.bank_phone);
    $('#id_bank_province').val(g_args.bank_province);
    $('#id_bank_city').val(g_args.bank_city);
    $('#id_bank_type').val(g_args.bank_type);
    $('#id_zfb_name').val(g_args.bank_type);
    $('#id_zfb_account').val(g_args.bank_type);

    $("#id_add").on("click",function(){
        var $parentid  = $("<input/>");
        var $userid    = $("<input/>");
        var $phone     = $("<input/>");
        var $wx_openid = $("<input/>");
        var $bankcard     = $("<input/>");
        var $idcard     = $("<input/>");
        var $bank_address     = $("<input/>");
        var $bank_account     = $("<input/>");
        var $bank_phone     = $("<input/>");
        var $bank_province     = $("<input/>");
        var $bank_city     = $("<input/>");
        var $bank_type     = $("<input/>");
        var $zfb_name     = $("<input/>");
        var $zfb_account     = $("<input/>");

        var arr=[
            ["上级id",  $parentid],
            ["用户id",  $userid],
            ["手机",  $phone],
            ["微信openid",  $wx_openid],
            ["银行卡号",  $bankcard],
            ["身份证号码",  $idcard],
            ["开户行和支行",  $bank_address],
            ["持卡人姓名",  $bank_account],
            ["银行预留手机号",  $bank_phone],
            ["银行卡开户省",  $bank_province],
            ["银行卡开户市",  $bank_city],
            ["银行卡类型",  $bank_type],
            ["支付宝姓名",  $zfb_name],
            ["支付宝账户",  $zfb_account],
        ];
        $.show_key_value_table("新增代理", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal/agent_add",{
                    "parentid"      : $parentid.val(),
                    "phone"         : $phone.val(),
                    "userid"        : $userid.val(),
                    "wx_openid"     : $wx_openid.val(),
                    "bankcard"      : $bankcard.val(),
                    "idcard"        : $idcard.val(),
                    "bank_address"  : $bank_address.val(),
                    "bank_account"  : $bank_account.val(),
                    "bank_phone"    : $bank_phone.val(),
                    "bank_province" : $bank_province.val(),
                    "bank_city"     : $bank_city.val(),
                    "bank_type"     : $bank_type.val(),
                    "zfb_name"     : $zfb_name.val(),
                    "zfb_account"     : $zfb_account.val(),
                })
            }
        })
    });


    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var $phone         = $("<input/>");
        var $wx_openid     = $("<input/>");
        var $parentid      = $("<input/>");
        var $bankcard      = $("<input/>");
        var $idcard        = $("<input/>");
        var $bank_address  = $("<input/>");
        var $bank_account  = $("<input/>");
        var $bank_phone    = $("<input/>");
        var $bank_province = $("<input/>");
        var $bank_city     = $("<input/>");
        var $bank_type     = $("<input/>");
        var $zfb_name     = $("<input/>");
        var $zfb_account     = $("<input/>");

        $phone.val(opt_data.phone );
        $wx_openid.val(opt_data.wx_openid);
        $parentid.val(opt_data.parentid);
        $bankcard.val(opt_data.bankcard);
        $idcard.val(opt_data.idcard);
        $bank_address.val(opt_data.bank_address);
        $bank_account.val(opt_data.bank_account);
        $bank_phone.val(opt_data.bank_phone);
        $bank_province.val(opt_data.bank_province);
        $bank_city.val(opt_data.bank_city);
        $bank_type.val(opt_data.bank_type);
        $zfb_name.val(opt_data.zfb_name);
        $zfb_account.val(opt_data.zfb_account);
        var arr=[
            ["上级id",  $parentid],
            ["电话",  $phone],
            ["微信",  $wx_openid],
            ["银行卡号",  $bankcard],
            ["身份证号码",  $idcard],
            ["开户行和支行",  $bank_address],
            ["持卡人姓名",  $bank_account],
            ["银行预留手机号",  $bank_phone],
            ["银行卡开户省",  $bank_province],
            ["银行卡开户市",  $bank_city],
            ["银行卡类型",  $bank_type],
            ["支付宝姓名",  $zfb_name],
            ["支付宝账户",  $zfb_account],
        ];

        $.show_key_value_table("修改代理信息", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal/agent_edit",{
                    "id"            : opt_data.id,
                    "phone"         : $phone.val() ,
                    "wx_openid"     : $wx_openid.val(),
                    "parentid"      : $parentid.val(),
                    "bankcard"      : $bankcard.val(),
                    "idcard"        : $idcard.val(),
                    "bank_address"  : $bank_address.val(),
                    "bank_account"  : $bank_account.val(),
                    "bank_phone"    : $bank_phone.val(),
                    "bank_province" : $bank_province.val(),
                    "bank_city"     : $bank_city.val(),
                    "bank_type"     : $bank_type.val(),
                    "zfb_name"     : $zfb_name.val(),
                    "zfb_account"     : $zfb_account.val(),
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
