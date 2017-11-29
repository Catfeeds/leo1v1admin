/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/aliyun_oss-upload_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        date_type_config:   $('#id_date_type_config').val(),
        date_type:  $('#id_date_type').val(),
        opt_date_type:  $('#id_opt_date_type').val(),
        start_time: $('#id_start_time').val(),
        end_time:   $('#id_end_time').val()
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
    $('#id_add_file').on('click',function(){
        var opt_data=$(this).get_opt_data();
        window.open(
              '/aliyun_oss/upload_page'
          );
    });

    $("#id_add_file1").on("click",function(){
        var opt_data = $(this).get_opt_data;
        var id_file_type        = $("<select/>");  //文件类型
        var id_file_name        = $("<input/>");  //文件名
        var id_file_url         = $("<input type=\"file\"/>");
        Enum_map.append_option_list("file_type", id_file_type, true);
        var arr = [
            ["类型",id_file_type],
            ["文件名",id_file_name],
            ["上传",id_file_url],
        ];
        $.show_key_value_table("增加考试记录", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){

                $.do_ajax("/aliyun_oss/add_file",{
                    "file_type" : id_file_type.val(),
                    "file_name" : id_file_name.val(),
                    "file_url"  : id_file_url.val(),
                });
            }
        },function(){
        })
        alert(2);
    });
    $('.opt-change').set_input_change_event(load_data);
});
