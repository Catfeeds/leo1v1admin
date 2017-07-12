// SWITCH-TO: ../../template/student/
$(function(){
    $('#id_start_date').val(g_args.start_date);
    $('#id_end_date').val(g_args.end_date);
    $('#id_praise_type').val(g_args.praise_type);
    
    function load_data(){
        var start_date  = $("#id_start_date").val();
        var end_date    = $("#id_end_date").val();
        var praise_type = $("#id_praise_type").val();
 		var url = "/stu_manage/get_stu_praise?sid="+g_sid+
                "&nick="+g_nick+
                "&start_date="+start_date+
                "&end_date="+end_date+
                "&praise_type="+praise_type;
		window.location.href = url;
	}

    $(".opt-change").on("change",function(){
        load_data();
    });
    
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
});
