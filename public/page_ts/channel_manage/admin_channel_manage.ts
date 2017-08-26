/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/channel_manage-admin_channel_manage.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }
    $("#id_add_channel").on("click",function(){
        var id_channel_name=$("<input/>");
        var  arr=[
            ["名称" ,  id_channel_name]
        ];
        
        $.show_key_value_table("新增渠道", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/channel_manage/add_channel",{
                    "channel_name" :id_channel_name.val(),
                });
            }
        });
        
    });


    $('.opt-change').set_input_change_event(load_data);
});

