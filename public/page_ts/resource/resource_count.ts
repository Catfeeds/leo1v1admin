/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource-resource_count.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        order_by_str : g_args.order_by_str,
        date_type_config:	$('#id_date_type_config').val(),
        date_type:	$('#id_date_type').val(),
        opt_date_type:	$('#id_opt_date_type').val(),
        grade:      $('#id_grade').val(),
        subject:    $('#id_subject').val(),
        resource_type: $('#id_resource_type').val(),
        start_time:	$('#id_start_time').val(),
        end_time:	$('#id_end_time').val(),
        teacherid:  $('#id_teacherid').val(),
        type:       $('#id_type').val(),
    });
}
$(function(){

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

    Enum_map.append_option_list("subject",$("#id_subject"));
    Enum_map.append_option_list("grade",$("#id_grade"));
    Enum_map.append_option_list("resource_type",$("#id_resource_type"));
    $('#id_subject').val(g_args.subject);
    $("#id_grade").val(g_args.grade);
    $("#id_resource_type").val(g_args.resource_type);
    $('#id_teacherid').val(g_args.teacherid);
    $('#id_type').val(g_args.type);
    $.admin_select_user( $("#id_teacherid"), "research_teacher", load_data);
    $('#id_order_by_str').val(g_args.order_by_str);

    $('.mark').each(function(i){
        if($(this).data('mark') != 1){
            $(this).hide();
        }
    });

    // $('.key1').each(function(i){
    //     var key1 = $(this).data('key1');

    //     $(this).on('click',function(){
    //         $('[key1='+key1+']').each(function(i){
    //             if(i != 0){
    //                 $(this).toggle();
    //             }
    //         });
    //    });
    // });

    $('.key2').each(function(i){
        $(this).css({color: "#3c8dbc", cursor:"pointer"});
        var key2 = $(this).data('key2');

        $(this).on('click',function(){
            $('[key2='+key2+']').each(function(i){
                if(i != 0){
                    $(this).toggle();
                }
            });
       });
    });
    $('.opt-change').set_input_change_event(load_data);
});
