/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/campus_manage-admin_campus_manage.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }


    $("#id_add_campus").on("click",function(){
        var id_campus_name=$("<input/>");
        var  arr=[
            ["名称" ,  id_campus_name]
        ];
        
        $.show_key_value_table("新增校区", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/campus_manage/add_admin_campus",{
                    "campus_name" :id_campus_name.val(),
                });
            }
        });
        
    });


	$('.opt-change').set_input_change_event(load_data);
});









