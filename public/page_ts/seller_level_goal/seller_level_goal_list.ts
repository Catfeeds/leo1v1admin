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
        var $id_seller_level  = $("<select/>");
        var $id_level_goal  = $("<input/>");
        var $id_level_face = $("<div><input class=\"change_level_face_url\" id=\"level_face_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_level_face\" href=\"javascript:;\">上传</a></span></div>");

        Enum_map.append_option_list("seller_level",$id_seller_level,true);
        var arr=[
            ["销售等级",  $id_seller_level],
            ["等级目标",  $id_level_goal ],
            ["等级头像 ", $id_level_face],
        ];

        $.show_key_value_table("添加销售等级信息", arr ,[{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/seller_level_goal/add_seller_level_goal",{
                    'seller_level' : $id_seller_level.val(),
                    'level_goal'   : $id_level_goal.val(),
                    'level_face'   : $id_level_face.find("#level_face_url").val(),
                });
            }
        }],function(){
            $.custom_upload_file('id_upload_level_face',true,function (up, info, file) {
                var res = $.parseJSON(info);
                $("#level_face_url").val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);
        });
    });

    $('.opt-change').set_input_change_event(load_data);
});
