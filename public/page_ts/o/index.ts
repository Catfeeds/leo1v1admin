/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/o-index.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            id: g_args.id
        });
    }

    $("#id_open").on("click",function(){
        $.do_ajax("/ajax_deal2/office_cmd_add",{
            office_device_type : 1,
            device_opt_type : 1 ,
            device_id:  g_args.id
        },function(){
            BootstrapDialog.alert ("请等待10秒, 开机中.. ");
        });
    });

    $("#id_close").on("click",function(){
        $.do_ajax("/ajax_deal2/office_cmd_add",{
            office_device_type : 1,
            device_opt_type : 0 ,
            device_id:  g_args.id
        },function(){
            BootstrapDialog.alert ("请等待10秒, 关机中.. ");
        });
    });
});
