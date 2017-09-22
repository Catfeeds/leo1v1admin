/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_lesson_review-test_lesson_review_list.d.ts" />

$(function(){
    var show_name_key="";
    show_name_key="account_name_"+g_adminid;
    function load_data(){
        if ($.trim($("#id_user_info").val()) != g_args.user_info ) {
            $.do_ajax("/user_deal/set_item_list_add",{
                "item_key" :show_name_key,
                "item_name":  $.trim($("#id_user_info").val())
            },function(){});
        }
        $.reload_self_page ( {
            user_info         : $('#id_user_info').val(),
        });
    }
    $( "#id_user_info" ).autocomplete({
        source: "/user_deal/get_item_list?list_flag=1&item_key="+show_name_key,
        minLength: 0,
        select: function( event, ui ) {
            load_data();
        }
    });
    $("#id_user_info").val(g_args.user_info);
    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();
        if(opt_data.aid == opt_data.group_adminid){
            var $group_suc_flag = $("<select/>");
            Enum_map.append_option_list("group_suc_flag",$group_suc_flag ,true );
            $group_suc_flag.val(opt_data.group_suc_flag);
            var arr=[
                ["审核",$group_suc_flag],
            ];
            $.show_key_value_table("排课审核", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.do_ajax("/test_lesson_review/test_lesson_review_group_edit",{
                        "id":opt_data.id,
                        "group_adminid" : opt_data.group_adminid,
                        "group_suc_flag" : $group_suc_flag.val(),
                    })
                }
            })
        }else if(opt_data.aid == opt_data.master_adminid){
            var $master_suc_flag = $("<select/>");
            Enum_map.append_option_list("master_suc_flag",$master_suc_flag ,true );
            $master_suc_flag.val(opt_data.master_suc_flag);
            var arr=[
                ["审核",$master_suc_flag],
            ];
            $.show_key_value_table("排课审核", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.do_ajax("/test_lesson_review/test_lesson_review_master_edit",{
                        "id":opt_data.id,
                        "master_adminid" : opt_data.master_adminid,
                        "master_suc_flag" : $master_suc_flag.val(),
                    })
                }
            })
        }
    });

    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm(
            "要删除id为:" + opt_data.id + "的数据吗？",
            function(val) {
                if (val) {
                    $.do_ajax("/test_lesson_review/test_lesson_review_del", {
                        "id": opt_data.id
                    })
                }
            })
    });

    $('.opt-change').set_input_change_event(load_data);
});
