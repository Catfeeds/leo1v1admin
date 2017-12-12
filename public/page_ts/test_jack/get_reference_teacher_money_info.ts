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
                    $.do_ajax("/test_jack/ajax_deal_jack",{
                    },function(resp){
                        console.log(resp.data);
                        var data = resp.data;
                        $tr.find(".all_num").text(data.all_num);
                        $tr.find(".one_num").text(data.one_num);
                        $tr.find(".two_num").text(data.two_num);
                        $tr.find(".three_num").text(data.three_num);
                        $tr.find(".four_num").text(data.four_num);
                        $tr.find(".five_num").text(data.five_num);
                        $tr.find(".six_num").text(data.six_num);
                        $tr.find(".other_num").text(data.other_num);
                        $tr.find(".tea_leave_num").text(data.tea_leave_num);
                        $tr.find(".stu_leave_num").text(data.stu_leave_num);



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
