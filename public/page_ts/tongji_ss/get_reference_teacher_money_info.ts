/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-get_reference_teacher_money_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }
    $("#id_get_money").on("click",function(){
        var row_list=$("#id_tbody tr");
        console.log(row_list);
        var do_index=0;
	    
        function do_one() {
            if (do_index < row_list.length ) {
                var $tr=$(row_list[do_index]);
                var opt_data=$tr.find(".course_plan").get_opt_data();
                var teacherid = opt_data.teacherid;
                if(teacherid>0){
                    $.do_ajax("/teacher_money/get_teacher_total_money",{
                        "teacherid"           : opt_data.teacherid,
                        "type" : "admin",
                        "start_time"       : "2017-06-20",
                        "end_time"         : "2017-08-03",
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
