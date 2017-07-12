/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-get_question_tongji.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
        });
    }

      $("#id_question_tongji").on("click",function(){
        var row_list=$("#id_tbody tr");
        var do_index=0;
	    
        function do_one() {
            if (do_index < row_list.length ) {
                var $tr=$(row_list[do_index]);
                var opt_data=$tr.find(".question_info").get_opt_data();
                $.do_ajax("/user_deal/get_question_info_by_noteid",{
                    "noteid"  : opt_data.note_id,
                },function(resp){                    
                    $tr.find(".id_grade").text(resp.grade_str);
                    $tr.find(".id_subject").text(resp.subject_str);
                    $tr.find(".id_main_note_name").text(resp.main_note_name);
                    $tr.find(".id_second_note_name").text(resp.second_note_name);
                    $tr.find(".id_select_count").text(resp.select_count);
                    $tr.find(".id_tk_count").text(resp.tk_count);
                    $tr.find(".id_wd_count").text(resp.wd_count);
                    $tr.find(".id_zsd_count").text(resp.zsd_count);
                    
                    if(resp.grade == null){
                        $tr.css("display","none");
                    }
                    do_index++;
                    do_one();                                     
                }); 
            }else{
            }
        };
        do_one();

    });
 


	$('.opt-change').set_input_change_event(load_data);
});

