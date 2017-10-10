/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_level_goal-seller_level_goal_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            seller_level : $('#id_seller_level').val(),
            level_goal   : $('#id_level_goal').val(),
            level_face   : $('#id_level_face').val(),
        });
    }


    $('#id_seller_level').val(g_args.seller_level);
    $('#id_level_goal').val(g_args.level_goal);
    $('#id_level_face').val(g_args.level_face);

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


    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var $id_seller_level  = $("<select disabled='disabled' />");
        var $id_level_goal  = $("<input/>");
        var $id_level_face = $("<div><input class=\"change_level_face_url\" id=\"level_face_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_level_face\" href=\"javascript:;\">上传</a></span></div>");
        Enum_map.append_option_list("seller_level",$id_seller_level,true);
        $id_seller_level.val(opt_data.seller_level);
        $id_level_goal.val(opt_data.level_goal);
        $id_level_face.val(opt_data.level_face);
        var arr=[
            ["销售等级",  $id_seller_level],
            ["等级目标",  $id_level_goal ],
            ["等级头像 ", $id_level_face],
        ];

        $.show_key_value_table("添加销售等级信息", arr ,[{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/seller_level_goal/edit_seller_level_goal",{
                    'seller_level' : opt_data.seller_level,
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

    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm(
            "要删除seller_level为:" + opt_data.seller_level + "的配置吗？",
            function(val) {
                if (val) {
                    $.do_ajax("/seller_level_goal/del_seller_level_goal", {
                        "seller_level": opt_data.seller_level,
                    })
                }
            })
    });

    $('.opt-change').set_input_change_event(load_data);
});
