/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-admin_main_assign_percent_edit.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }

    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
        
        BootstrapDialog.confirm(
            "要删除:"+ opt_data.group_name  + "?",
            function(val) {
                if  (val)  {
                    $.do_ajax( "/user_deal/admin_main_group_del", {
                        "groupid": opt_data.groupid
                    }) ;
               }
            }
        );

	    
    });

    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
	    var id_main_assign_percent=$("<input/>");
        var  arr=[
            ["比例(请输入整数,如10%,输入10即可)" , id_main_assign_percent]
        ];
        
        $.show_key_value_table("配置比例", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/update_main_assign_percent",{
                    "groupid": opt_data.groupid,
                    "main_assign_percent" : id_main_assign_percent.val()
                });
            }
        });

    });



	$('.opt-change').set_input_change_event(load_data);
});


    
  
