/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/news_info-stu_message_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
        });
    }

    $('.add_message_info').on("click",function(){
        var $id_type         = $("<select/>");
        var $message_content = $("<textarea></textarea>");
        var $value           = $("<input/>");

        Enum_map.append_option_list("role",$id_type,true);

        var arr=[
            ["推送给", $id_type] ,
            ["推送内容", $message_content] ,
            ["跳转地址", $value] ,
        ];
        
        $.show_key_value_table("添加系统推送",arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog){
                var message_content = $message_content.val();
                var value           = $value.val();

                if(message_content==''){
                    BootstrapDialog.alert("推送内容不能为空!");
                }else{
                    $.do_ajax("/news_info/add_stu_message_content", {
                        "type"            : $id_type.val(),
                        "message_content" : message_content,
                        "value"           : value
                    },function(result){
                        window.location.reload();
                    });
                }
            }
        });
    });

    $(".opt-del").on("click",function(){
        var messageid=$(this).get_opt_data("messageid");
        $.do_ajax("/news_info/del_baidu_push_msg",{
            "messageid" : messageid
        },function(result){
            window.location.reload();
        });
    });

    //$('.opt-change').set_input_change_event(load_data);
});

