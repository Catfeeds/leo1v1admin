/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-ass_all.d.ts" />
$(function(){
    function load_data(){
        $.reload_self_page ( {
            seller_groupid_ex: $('#id_seller_groupid_ex').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val()
        });
    }

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

    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var score = $("<input/>");
        var rank = $("<input/>");
        var uid = $("<input id= 'id_uid'/>" );
        score.val(opt_data.score);
        rank.val(opt_data.rank);
        uid.val(opt_data.uid);
        var arr=[
            ["销售" ,uid],
            ["业绩" ,score],
            ["排名" ,rank],
        ];
        $.show_key_value_table("修改销售排名", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/tongji2/cc_day_top_update",{
                    "id":opt_data.id,
                    "score" : score.val(),
                    "rank" :rank.val(), 
                    "uid" :uid.val()
                });
            }
        },function(){
            $.admin_select_user($('#id_uid'),"admin");
        });


    });

    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();

        $.do_ajax("/tongji2/cc_day_top_del",{
            "id" : opt_data.id,
        });

    });
    //添加销售排名信息
    $('#id_add').on('click',function(){
        var score = $("<input/>");
        var rank = $("<input/>");
        var uid = $("<input id= 'id_uid_add'/>" );
        var arr=[
            ["销售" ,uid],
            ["业绩" ,score],
            ["排名" ,rank],
        ];
        $.show_key_value_table("添加销售日排名", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/tongji2/cc_day_top_add",{
                    "score" : score.val(),
                    "rank" :rank.val(),
                    "uid" :uid.val()
                });
            }
        },function(){
            $.admin_select_user($('#id_uid_add'),"admin");
        });

    })

});
