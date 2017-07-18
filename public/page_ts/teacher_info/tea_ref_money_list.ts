/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-tea_ref_money_list.ts" />
$(function(){
    if(teacher_ref_type==0){
        $(".content").empty();
    }else{
        function load_data(){
            $.reload_self_page ( {
			    teacherid  : $('#id_teacherid').val(),
			    start_date : $('#id_start_date').val(),
            });
        }

	    $('.opt-change').set_input_change_event(load_data);
	    $('#id_start_date').val(g_args.start_date);
	    $('#id_teacherid').val(g_args.teacherid);
        $.admin_select_user($("#id_teacherid"), "teacher_ref", load_data);
    } 
});
