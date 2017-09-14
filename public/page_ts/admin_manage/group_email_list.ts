/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-group_email_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }




    $('.opt-change').set_input_change_event(load_data);


    $("#id_add").on("click",function(){
        var $title=$("<input/>");
        var $email=$("<input/>");
        var arr=[
            ["分组名", $title ] ,
            ["邮件地址",$email ],
        ];


        $.show_key_value_table("添加组邮件", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal2/email_group_add" , {
                    "title" : $title.val(),
                    "email" : $email.val(),
                } );
            }
        });
    });


    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();


        var $title=$("<input/>");
        var arr=[
            ["分组名", $title ] ,
        ];
        $title.val(opt_data.title);


        $.show_key_value_table("添加组邮件", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal2/email_group_edit" , {
                    groupid: opt_data.groupid,
                    "title" : $title.val(),
                } );
            }
        });
    });

    $(".opt-show-user-list").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/admin_manage/group_email_user_list?groupid="+ opt_data.groupid );
    });


});
