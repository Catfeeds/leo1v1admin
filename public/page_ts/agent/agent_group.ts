/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_group.d.ts" />
$(function(){

    $('#id_group_colconel').val(g_args.group_colconel);

    function load_data(){
        $.reload_self_page ( {
            group_colconel : $('#id_group_colconel').val()
        });
    }

    $('.opt-change').set_input_change_event(load_data);
    
    //@desn:修改优学优享团名称
    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var $group_name= $("<input/>" );
        var arr=[
            ["团队名称" ,$group_name  ],
        ] ;
        $group_name.val(opt_data.group_name );

        $.show_key_value_table("修改团队名称", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/agent_info/update_group_name",{
                    "group_id" : opt_data.group_id,
                    "group_name" : $group_name.val()
                });
            }
        });

    });
    //@desn:添加团成员
    $(".opt-user").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var $member_phone = $("<input/>");
        var arr=[
            ["成员电话" ,$member_phone  ],
        ] ;

        $.show_key_value_table("添加团队成员", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/agent_info/add_member",{
                    "group_id" : opt_data.group_id,
                    "member_phone" : $member_phone.val()
                });
            }
        });

    });
    
});
