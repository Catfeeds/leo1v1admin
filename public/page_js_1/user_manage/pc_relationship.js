$(function(){
   
    $("#id_studentid").val(g_args.studentid);
    $("#id_parentid").val(g_args.parentid);

    $("#id_studentid").admin_select_user({
        "type"   : "student",
        "onChange": function(){
            load_data(  );
        }
    });
    admin_select_user($("#id_parentid"), "parent",function(){
        load_data();
    });


    function load_data(){
        reload_self_page ( {
            studentid : $("#id_studentid").val(),
            parentid  : $("#id_parentid").val()
        });
    }

    $(".opt-set-parentid" ).on("click",function(){
        var userid=$(this).get_opt_data("userid");
        var  parent_type=$(this).get_opt_data("parent_type");
        var id_parentid=$("<input/>");
        var arr=[
            ["parentid", id_parentid]
        ];
        show_key_value_table("修改 parentid", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                do_ajax("/user_manage_new/parent_child_set_parentid",{
                    "userid" :userid,
                    "parent_type" :parent_type,
                    "parentid" : id_parentid.val()
                });
            }
        });
    
    });



});
