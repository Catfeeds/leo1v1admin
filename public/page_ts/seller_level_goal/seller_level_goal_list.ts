/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_level_goal-seller_level_goal_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            orderid:    $('#id_orderid').val(),
            start_time:    $('#id_start_time').val(),
            end_time:    $('#id_end_time').val(),
            aid:    $('#id_aid').val(),
            pid:    $('#id_pid').val(),
            p_price:    $('#id_p_price').val(),
            ppid:    $('#id_ppid').val(),
            pp_price:    $('#id_pp_price').val(),
            userid:    $('#id_userid').val()
        });
    }


    $('#id_orderid').val(g_args.orderid);
    $('#id_start_time').val(g_args.start_time);
    $('#id_end_time').val(g_args.end_time);
    $('#id_aid').val(g_args.aid);
    $('#id_pid').val(g_args.pid);
    $('#id_p_price').val(g_args.p_price);
    $('#id_ppid').val(g_args.ppid);
    $('#id_pp_price').val(g_args.pp_price);
    $('#id_userid').val(g_args.userid);

    $("#id_add").on("click",function(){
        var $seller_level = $("<select></select>");
        var $level_goal   = $("<input/>");
        var $level_face   = $("<input id='id_upload_level_face' /> <button value='1' class='btn  btn-primary upload_change_reason_url' title='上传'>上传</button>");
        Enum_map.append_option_list("seller_level",$seller_level);
        $level_face.find(".upload_change_reason_url").attr("id","id_upload_level_face_url");
        var th = setTimeout(function(){
            $.custom_upload_file('id_upload_level_face_url',true,function (up, info, file) {
                var res = $.parseJSON(info);
                console.log(res);
                $level_face.find("#id_upload_level_face").val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);
            clearTimeout(th);
        }, 1000);

        var arr=[
            ["销售等级",  $seller_level],
            ["等级目标",  $level_goal],
            ["等级头像",  $level_face],
        ];
        $.show_key_value_table("新增转介绍订单", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal/seller_level_goal_add",{
                    "seller_level" : $seller_level.val(),
                    "level_goal"   : $level_goal.val(),
                    "level_face"   : $level_face.val(),
                })
            }
        })
    });

    $('.opt-change').set_input_change_event(load_data);
});
