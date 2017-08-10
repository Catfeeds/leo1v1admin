/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_list_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type_config:    $('#id_date_type_config').val(),
            date_type:    $('#id_date_type').val(),
            opt_date_type:    $('#id_opt_date_type').val(),
            start_time:    $('#id_start_time').val(),
            end_time:    $('#id_end_time').val(),
            userid:    $('#id_userid').val(),
            phone:    $('#id_phone').val(),
            grade:    $('#id_grade').val(),
            parentid:    $('#id_parentid').val(),
            wx_openid:    $('#id_wx_openid').val(),
            bankcard:    $('#id_bankcard').val(),
            idcard:    $('#id_idcard').val(),
            bank_address:    $('#id_bank_address').val(),
            bank_account:    $('#id_bank_account').val(),
            bank_phone:    $('#id_bank_phone').val(),
            bank_province:    $('#id_bank_province').val(),
            bank_city:    $('#id_bank_city').val(),
            bank_type:    $('#id_bank_type').val(),
            zfb_name:    $('#id_zfb_name').val(),
            zfb_account:    $('#id_zfb_account').val(),
            agent_type:    $('#id_agent_type').val()
        });
    }

    Enum_map.append_option_list("grade",$("#id_grade"));

    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
    $('#id_userid').val(g_args.userid);
    $('#id_phone').val(g_args.phone);
    $('#id_grade').val(g_args.grade);
    $('#id_parentid').val(g_args.parentid);
    $('#id_wx_openid').val(g_args.wx_openid);
    $('#id_bankcard').val(g_args.bankcard);
    $('#id_idcard').val(g_args.idcard);
    $('#id_bank_address').val(g_args.bank_address);
    $('#id_bank_account').val(g_args.bank_account);
    $('#id_bank_phone').val(g_args.bank_phone);
    $('#id_bank_province').val(g_args.bank_province);
    $('#id_bank_city').val(g_args.bank_city);
    $('#id_bank_type').val(g_args.bank_type);
    $('#id_zfb_name').val(g_args.zfb_name);
    $('#id_zfb_account').val(g_args.zfb_account);
    $('#id_agent_type').val(g_args.agent_type);


    $('.opt-change').set_input_change_event(load_data);
});
