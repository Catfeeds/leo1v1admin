/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-kaoqin_machine.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }



    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var $open_door_flag=$("<select/>");
        var $title =$("<input/>");
        var $desc=$("<input/>");
        Enum_map.append_option_list( "boolean", $open_door_flag, true );

        $open_door_flag.val( opt_data.open_door_flag );
        $title.val( opt_data.title);
        $desc.val( opt_data.desc);
        var arr=[
            ["sn" ,  opt_data.sn ],
            ["操作门禁" ,  $open_door_flag ],
            ["名字" ,  $title ],
            ["说明" ,  $desc],
        ];


        $.show_key_value_table("修改", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/set_kaoqin_machine",{
                    "machine_id" : opt_data.machine_id,
                    "admin_manage" : $open_door_flag.val() ,
                    "title" : $title.val() ,
                    "desc" : $desc.val() ,
                    "open_door_flag" :$open_door_flag.val()
                });
            }
        });

    });

    $(".opt-unlock").on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm("要开锁吗?", function(val){
            if (val) {
                $.do_ajax("/ajax_deal/unlock_door",{
                    "machine_id" : opt_data.machine_id,
                });
            }
        });
    });


    $(".opt-reboot").on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm("要重启吗?", function(val){
            if (val) {
                $.do_ajax("/ajax_deal/kaoqin_reboot",{
                    "machine_id" : opt_data.machine_id,
                });
            }
        });
    });




  $('.opt-change').set_input_change_event(load_data);
});
