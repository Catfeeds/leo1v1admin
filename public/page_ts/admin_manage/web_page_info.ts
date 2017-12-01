/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-web_page_info.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        del_flag:	$('#id_del_flag').val(),
        date_type_config:	$('#id_date_type_config').val(),
        date_type:	$('#id_date_type').val(),
        opt_date_type:	$('#id_opt_date_type').val(),
        start_time:	$('#id_start_time').val(),
        end_time:	$('#id_end_time').val()
    });
}
$(function(){


    Enum_map.append_option_list("boolean",$("#id_del_flag"));
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

    $('#id_del_flag').val(g_args.del_flag);

    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var $title=$("<input/>");
        var $del_flag=$("<select/>");
        var arr=[
            ["标题",   $title  ],
            ["删除",   $del_flag ],
        ];

        Enum_map.append_option_list("boolean",$del_flag,true );
        $title.val( opt_data.title);
        $del_flag.val( opt_data.del_flag);
        $.show_key_value_table("编辑", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                if ( !$title.val() || !$title.val()   ) {
                    alert("不可为空");
                    return;
                }
                $.do_ajax( "/ajax_deal2/web_page_info_edit", {
                    "web_page_id" : opt_data.web_page_id,
                    "title" : $title.val(),
                    "del_flag" : $del_flag.val(),
                });

            }
        });
    });


    $("#id_add").on("click",function(){
        var $title=$("<input/>");
        var $url=$("<input/>");
        var arr=[
            ["标题",   $title  ],
            ["网址",   $url ],
        ];
        $.show_key_value_table("新增活动页", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                if ( !$title.val() || !$title.val()   ) {
                    alert("不可为空");
                    return;
                }
                $.do_ajax( "/ajax_deal2/web_page_info_add", {
                    "title" : $title.val(),
                    "url" : $url.val(),
                });

            }
        });
    });


    $('.opt-change').set_input_change_event(load_data);


    $(".opt-share").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/admin_manage/web_page_share?web_page_id="+opt_data.web_page_id );
    });

    $(".opt-tongji").on("click",function(){
        var opt_data=$(this).get_opt_data();
        //$.wopen("/admin_manage/web_page_admin_info?web_page_id="+opt_data.web_page_id );
        $.wopen("/admin_manage/web_page_new?web_page_id="+opt_data.web_page_id );
    });

    $(".opt-log").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/admin_manage/web_page_log?web_page_id="+opt_data.web_page_id );
    });



});
