

$(function(){

    function load_data(){

        reload_self_page({
            lesson_count_start : $("#id_lesson_count_start").val(),
            lesson_count_end   : $("#id_lesson_count_end").val(),
            assistantid        : $("#id_assistantid").val(),
            grade              : $("#id_grade").val(),
            start_time         : $("#id_start_time").val(),
            end_time           : $("#id_end_time").val()
        });
    }


	//init  input data
	$("#id_start_time").val(g_args.start_time);
	$("#id_end_time").val(g_args.end_time);

	$("#id_grade").val(g_args.grade);
    $("#id_assistantid").val(g_args.assistantid);
	$("#id_lesson_count_start").val(g_args.lesson_count_start);
	$("#id_lesson_count_end").val(g_args.lesson_count_end);

    Enum_map.append_option_list("grade", $("#id_grade"));

    admin_select_user($("#id_assistantid"), "assistant",function(){
        load_data();
    });
 
	$(".stu_sel" ).on( "change",function(){
		load_data();
	});


    set_input_enter_event( $(".opt-change"), load_data );

	//时间控件
	$('#id_start_time').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
        onChangeDateTime :function(){
            load_data();
		}
	});
	
	$('#id_end_time').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
        onChangeDateTime :function(){
            load_data(
			);
		}
	});


	//点击进入个人主页
	$('.opt-user').on('click',function(){
		var userid = $(this).parent().data("userid");
        wopen(
            '/stu_manage?sid='+ userid+"&return_url="+ encodeURIComponent(window.location.href)
        );
	});

});
