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
        var input_arr=[];
        var input_span = [];
        var $group_name= $("<input style='width:180px;'/>" );

        for(var i=1;i<=10;i++){
            input_arr[i] = $("<input flag='"+i+"'style='width:180px;'/><span style='margin-left:15px;color:red;' class='notice_"
                             +i+"'></span>" );
            $(".table").append(input_arr[i]);
        }
        
        $("input").blur(function(){
            var flag = $(this).attr('flag');
            $.do_ajax("/agent_info/check_phone",{
                "phone" : $(this).val(),
                "flag" : flag,
            },function(data){
                // console.log(data);
                if (data.ret!=0) {
                    console.log(data.info);
                    $('.notice_'+data.flag).text(data.info);
                }
            });
            
        });
        var arr=[
            ["团队名称" ,$group_name ],
            ["成员电话" ,input_arr[1] ],
            ["成员电话" ,input_arr[2] ],
            ["成员电话" ,input_arr[3] ],
            ["成员电话" ,input_arr[4] ],
            ["成员电话" ,input_arr[5] ],
            ["成员电话" ,input_arr[6] ],
            ["成员电话" ,input_arr[7] ],
            ["成员电话" ,input_arr[8] ],
            ["成员电话" ,input_arr[9] ],
            ["成员电话" ,input_arr[10] ],
        ] ;

        $.show_key_value_table("创建团队", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/agent_info/create_group",{
                    "group_name" : $group_name.val(),
                    "member1" : input_arr[1].val(),
                    "member2" : input_arr[2].val(),
                    "member3" : input_arr[3].val(),
                    "member4" : input_arr[4].val(),
                    "member5" : input_arr[5].val(),
                    "member6" : input_arr[6].val(),
                    "member7" : input_arr[7].val(),
                    "member8" : input_arr[8].val(),
                    "member9" : input_arr[9].val(),
                    "member10" : input_arr[10].val(),
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
