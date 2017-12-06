/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_jack-get_reference_teacher_money_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }
    $("#id_get_money").on("click",function(){
        var row_list=$("#id_tbody tr");
        var do_index=0;
	    
        function do_one() {
            if (do_index < row_list.length ) {
                var $tr=$(row_list[do_index]);
                var opt_data=$tr.find(".row-data");
                var teacherid = opt_data.data("teacherid");
                if(teacherid>0){
                   /* $.do_ajax("/teacher_money/user_deal/get_teacher_interview_info",{
                        "teacherid"           : opt_data.teacherid,
                        "type" : "admin",
                        "start_time"       : "2017-06-20",
                        "end_time"         : "2017-08-018",
                    },function(resp){
                        console.log(resp.data);
                        var lesson_price=0;
                        var a = resp.data;
                        for(var i = 0;i < a.length; i++) {
                            console.log(a[i].lesson_price);
                            lesson_price = lesson_price+a[i].lesson_price;
                        }
                        var final_price = lesson_price*0.09;
                        $tr.find(".lesson_money").text(lesson_price); 
                        $tr.find(".final_money").text(final_price); 
                        
                        do_index++;
                        do_one();
                        });*/
                    $.do_ajax("/ajax_deal2/get_three_month_stu_num",{
                        "teacherid"       : teacherid,
                    },function(resp){
                        console.log(resp.data);
                        var data = resp;
                        $tr.find(".reg_num").text(data.reg_num); 
                        $tr.find(".late_num").text(data.late_num); 
                        $tr.find(".leave_num").text(data.leave_num); 
                        $tr.find(".change_num").text(data.change_num); 
                        $tr.find(".kk_num").text(data.kk_num); 
                        $tr.find(".test_num").text(data.test_num); 
                        $tr.find(".test_late_num").text(data.test_late_num); 
                        $tr.find(".test_kk_num").text(data.test_kk_num); 
                        $tr.find(".test_person_num").text(data.test_person_num); 
                       
                                                                                             
                        
                        do_index++;
                        do_one();
                    });

                }else{
                    do_index++;
                    do_one();
                }
            }else{
            }
        };
        do_one();

    });




	$('.opt-change').set_input_change_event(load_data);
});
