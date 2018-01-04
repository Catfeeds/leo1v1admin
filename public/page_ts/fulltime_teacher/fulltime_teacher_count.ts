/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/fulltime_teacher-fulltime_teacher_count.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type     : $('#id_date_type').val(),
            opt_date_type : $('#id_opt_date_type').val(),
            start_time    : $('#id_start_time').val(),
            end_time      : $('#id_end_time').val(),
            status        : $('#id_status').val(),
		        actual_flag:	$('#id_actual_flag').val(),
		        fulltime_teacher_type:	$('#id_fulltime_teacher_type').val()
        });
    }

    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        // date_type_config : JSON.parse( g_args.date_type_config), 
        onQuery          : function() {
            load_data();
        }
    });

    Enum_map.append_option_list("fulltime_teacher_type", $("#id_fulltime_teacher_type"),false,[1,2]);

    $('#id_actual_flag').val(g_args.actual_flag);
	  $('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);


    // $("#id_data").on("click",function(){
    //     var config_list=["fulltime_teacher_count","fulltime_teacher_student","fulltime_teacher_pro","fulltime_teacher_student_pro","fulltime_teacher_lesson_count","fulltime_teacher_cc_per","part_teacher_lesson_count","part_teacher_cc_per","fulltime_teacher_lesson_count_per","platform_teacher_cc_per","fulltime_normal_stu_num","fulltime_normal_stu_pro"];

        
    //     $.each( config_list,  function(){
    //         var config_type=this; 
    //         $.do_ajax("/ajax_deal2/fulltime_teacher_count_data_with_type",{
		// 	          start_time: g_args.start_time	,
		// 	          end_time: g_args.end_time	,
    //             "type" :  config_type,
    //         } ,function(resp){
    //             $("#"+config_type).text(resp.value) ;
    //         } );

    //     } );
        
    // });
    $("#id_data").on("click",function(){
        var row_list=$("#id_tbody td ");
        console.log(row_list.length);
        var do_index=0;
        function do_once(){
            if(do_index < row_list.length){
                var $td =  $(row_list[do_index]);
                console.log($($td));
                var tid = $td.attr("id");
                if(tid != undefined){
                    $.do_ajax("/ajax_deal2/fulltime_teacher_count_data_with_type",{
			                  start_time: g_args.start_time	,
			                  end_time: g_args.end_time	,
                        "type" :  tid,
                    } ,function(resp){
                        $("#"+tid).text(resp.value) ;
                        do_index++;
                        do_once();

                    } );


                }else{
                    do_index++;
                    do_once();

                }
               // alert(tid);
                // do_index++;
                // do_once();
            }
            
        }
        do_once();

       

    });


    if(g_args.actual_flag ==1){
        $("#id_opt_date_type").hide();
    }
   

    $('.opt-change').set_input_change_event(load_data);
});

