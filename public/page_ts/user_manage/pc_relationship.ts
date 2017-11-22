/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-pc_relationship.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            studentid : $("#id_studentid").val(),
            parentid  : $("#id_parentid").val()
        });
    }

    $("#id_studentid").val(g_args.studentid);
    $("#id_parentid").val(g_args.parentid);



    $.admin_select_user($("#id_parentid"), "parent",function(){
        load_data();
    });


    $.admin_select_user($("#id_studentid"), "student",function(){
        load_data();
    });

    $(".opt-set-parentid" ).on("click",function(){
        var userid=$(this).get_opt_data("userid");
        var parent_type=$(this).get_opt_data("parent_type");
        var id_parentid=$("<input/>");
        var arr=[
            ["parentid", id_parentid]
        ];
        $.show_key_value_table("修改 parentid", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_manage_new/parent_child_set_parentid",{
                    "userid" :userid,
                    "parent_type" :parent_type,
                    "parentid" : id_parentid.val()
                });
            }
        });
    });

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-pc_relationship.d.ts" />


  $('sss').set_input_change_event(load_data);

    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm("要删除吗?!",function(val ){
            if (val) {
                $.do_ajax("/user_manage_new/parent_child_del",{
                    "userid" : opt_data.userid ,
                    "parent_type" :opt_data.parent_type,
                    "parentid" :opt_data.parentid ,
                });
            }
        });

    });

    $(".show_phone").on("click",function(){
        var phone = $(this).data("phone");
        BootstrapDialog.alert(phone);
    });

    download_hide();

});
