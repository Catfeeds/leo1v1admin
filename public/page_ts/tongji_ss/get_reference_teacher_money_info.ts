/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-get_reference_teacher_money_info.d.ts" />

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
                        "teacherid"       : teacherid
                    },function(resp){
                        console.log(resp.data);
                        var data = resp;
                        $tr.find(".first_test").text(data.first_test); 
                        $tr.find(".first_normal").text(data.first_normal); 
                        if(data.first_test==0){
                            $tr.find(".first_test").parent().hide();
                        }
                                                                     
                        
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
