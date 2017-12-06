/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/self_manage-self_menu_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }


    $(".opt-up").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.do_ajax("/self_manage/self_menu_switch" ,{
            "order_index" :opt_data.order_index,
            "next_flag" :0
        } );

    });

    $(".opt-down").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.do_ajax("/self_manage/self_menu_switch" ,{
            "order_index" :opt_data.order_index,
            "next_flag" :1
        } );
    });



    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.do_ajax("/self_manage/self_menu_del",{"id": opt_data.id});
    });


    $(".opt-edit ").on("click",function(){
        $.do_ajax( "/ajax_deal2/test",{});
    });



    $('.opt-change').set_input_change_event(load_data);
});
