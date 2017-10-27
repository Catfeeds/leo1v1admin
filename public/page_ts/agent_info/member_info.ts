/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent_info-get_agent_group_list.d.ts" />
$(function(){

    // $('#id_start_date').val(g_args.start_date);
    // $('#id_end_date').val(g_args.end_date);
    // $('#id_lesson_type').val(g_args.lesson_type);
    // $('#id_student').val(g_args.userid);

    function load_data(){
        $.reload_self_page ( {
            start_date  : $('#id_start_date').val(),
            end_date    : $('#id_end_date').val(),
            lesson_type : $('#id_lesson_type').val(),
            userid      : $('#id_student').val()
        });
    }

    //建优学优享团
    $("#id_add").on("click",function(){
        var $group_name= $("<input/>" );
        var $member1= $("<input/>" );
        var $member2= $("<input/>" );
        var $member3= $("<input/>" );
        var $member4= $("<input/>" );
        var $member5= $("<input/>" );
        var $member6= $("<input/>" );
        var $member7= $("<input/>" );
        var $member8= $("<input/>" );
        var $member9= $("<input/>" );
        var $member10= $("<input/>" );
        var arr=[
            ["团队名称" ,$group_name ],
            ["成员电话" ,$member1 ],
            ["成员电话" ,$member2 ],
            ["成员电话" ,$member3 ],
            ["成员电话" ,$member4 ],
            ["成员电话" ,$member5 ],
            ["成员电话" ,$member6 ],
            ["成员电话" ,$member7 ],
            ["成员电话" ,$member8 ],
            ["成员电话" ,$member9 ],
            ["成员电话" ,$member10 ]
        ] ;

        $.show_key_value_table("创建团队", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/agent_info/create_group",{
                    "group_name" : $group_name.val(),
                    "member1" : $member1.val(),
                    "member2" : $member2.val(),
                    "member3" : $member3.val(),
                    "member4" : $member4.val(),
                    "member5" : $member5.val(),
                    "member6" : $member6.val(),
                    "member7" : $member7.val(),
                    "member8" : $member8.val(),
                    "member9" : $member9.val(),
                    "member10" : $member10.val(),
                });
            }
        });

    });
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
