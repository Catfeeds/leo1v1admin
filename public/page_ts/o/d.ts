/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/o-d.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            sn: g_args.sn
        });
    }

    $("#id_open").on("click",function(){
        BootstrapDialog.confirm( "要开门吗?!",function(v){
            if (v) {
                $.do_ajax("/ajax_deal2/office_open_door",{
                    "sn"  :  g_args.sn,
                },function(resp){
                    if (resp.ret==0 ) {
                        BootstrapDialog.alert ("请等待10秒, 开门中.. , 开门后,务必<font color=red>关门!! </font> 不然将会取消你的权限 ");
                    }else{
                        alert(resp.info);
                    }
                });

            }
        } );

    });

});
