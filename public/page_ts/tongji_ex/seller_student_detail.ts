/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ex-seller_student_detail.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
        $.reload_self_page ( {
        date_type_config:    $('#id_date_type_config').val(),
        date_type:    $('#id_date_type').val(),
        opt_date_type:    $('#id_opt_date_type').val(),
        start_time:    $('#id_start_time').val(),
        end_time:    $('#id_end_time').val(),
        phone_province : $('#id_phone_province').val(),
        origin_level : $('#id_origin_level_list').val(),
        key0 : $('#id_key0_list').val(),
        key1 : $('#id_key1_list').val(),
        key2 : $('#id_key2_list').val(),
        key3 : $('#id_key3_list').val(),
        value : $('#id_value_list').val(),
        });
}
$(function(){

    $('#id_phone_province').val(g_args.phone_province);
    $('#id_origin_level_list').val(g_args.origin_level);
    $('#id_key0_list').val(g_args.key0);
    $('#id_key1_list').val(g_args.key1);
    $('#id_key2_list').val(g_args.key2);
    $('#id_key3_list').val(g_args.key3);
    $('#id_value_list').val(g_args.value);
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


    $('.opt-change').set_input_change_event(load_data);
});