/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-user_regular_course_check_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			assistantid:	$('#id_assistantid').val(),
			userid:	$('#id_userid').val(),
			teacherid:	$('#id_teacherid').val()
        });
    }


   	$('#id_assistantid').val(g_args.assistantid);
	$('#id_userid').val(g_args.userid);
	$('#id_teacherid').val(g_args.teacherid);
    $.admin_select_user($("#id_assistantid"),"assistant", load_data);
    $.admin_select_user($("#id_teacherid"),"teacher", load_data);
    $.admin_select_user($("#id_userid"),"student", load_data);
    $('.opt-user').on('click',function(){
        var opt_data=$(this).get_opt_data();
        window.open(
            '/stu_manage?sid='+ opt_data.userid +"&return_url="+ encodeURIComponent(window.location.href)
        );
	});


	$('.opt-change').set_input_change_event(load_data);
});







