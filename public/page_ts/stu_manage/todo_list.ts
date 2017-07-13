//<reference path="../common.d.ts" />
//<reference path="../g_args.d.ts/stu_manage-todo_list.d.ts" />

$(function(){
    function load_data(){
        
        $.reload_self_page ( {
			sid:	$('#id_sid').val()
        });
    }


    $('#id_add_login_new').click(function(){
        var $userid = $("<input/>");
        var $login = $("<input/>");
        var $nick = $("<input/>");
        var $ip = $("<input/>");
        var $role = $("<input/>");
        var $login_type = $("<input/>");
        var $flag = $("<input/>");

        
        var arr=[
            ["学生ID",  $userid],
            ["登录时间",  $login],
            ["昵称",  $nick],
            ["IP",  $ip],
            ["地址",  $role],
            ["登录方式",$login_type],
            ["方式",  $flag],
        ];

        //Enum_map.append_option_list("grade",$grade, true);
        //Enum_map.append_option_list("subject",$subject, true);

        $.show_key_value_table("新增申请", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                
                $.do_ajax("/stu_manage/todo_list_add",{
                    "userid" : $userid.val(),
                    "login" : $login.val(),
                    "nick" : $nick.val(),
                    "ip" : $ip.val(),
                    "role" : $role.val(),
                    "login_type" : $login_type.val(),
                    "flag" : $falg.val()
                });
             }
        });


    });


	  $('.opt-change').set_input_change_event(load_data);

});
