/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/authority-seller_edit_log_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }
    
    $("#id_add").on("click",function(){
        var $adminid = $("<input/>");
        var $uid     = $("<input/>");
        var $type    = $("<select><option value='0' select='selected'>请选择</option><option value='1'>更改权限组</option><option value='2'>修改咨询老师等级</option><select/>");
        var $old     = $("<input/>");
        var $new     = $("<input/>");

        var arr=[
            ["修改人",  $adminid],
            ["被修改人",  $uid],
            ["修改类型",  $type],
            ["修改前",  $old],
            ["修改后",  $new],
        ];
        $.show_key_value_table("新增日志", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal/seller_edit_log_add",{
                    "adminid" : $adminid.val(),
                    "uid"     : $uid.val(),
                    "type"    : $type.val(),
                    "old"     : $old.val(),
                    "new"     : $new.val(),
                })
            }
        })
    });


    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm(
            "要删除id为:" + opt_data.id + "的日志吗？",
            function(val) {
                if (val) {
                    $.do_ajax("/ajax_deal/seller_edit_log_del", {
                        "id" : opt_data.id,
                    })
                }
            })
    });



    $('.opt-change').set_input_change_event(load_data);
});
