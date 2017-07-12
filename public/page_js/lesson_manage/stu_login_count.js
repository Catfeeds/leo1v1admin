$(function(){
    $('#id_start_date').val(g_start_date);
    $('#id_end_date').val(g_end_date);
    $('#id_lesson_type').val(g_args.lesson_type);

    //TODO
	//时间控件
	$('#id_start_date').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
	    onChangeDateTime :function(){
		    load_data();
        }
	});
	$('#id_end_date').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
		onChangeDateTime :function(){
		    load_data();
        }
	});
	//时间控件-over

	function load_data(){
        var start_date  = $("#id_start_date").val();
        var end_date    = $("#id_end_date").val();
        var lesson_type = $("#id_lesson_type").val();
        
	    var url="/lesson_manage/stu_login_count?start_date="+start_date+"&end_date="+end_date+"&lesson_type="+lesson_type;
	    window.location.href=url;
	}
    
	$(".opt-change").on("change",function(){
		load_data();
	});
});
