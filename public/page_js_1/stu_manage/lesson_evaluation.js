$(function(){
    $("#id_date_start").val(g_args.start_time);
    $("#id_date_end").val(g_args.end_time);

    var load_data=function(){
        reload_self_page({
            sid        : g_sid,
            start_time : $("#id_date_start").val(),
            end_time   : $("#id_date_end").val()
        });
    };

    //TODO
	//时间控件
	$('#id_date_start').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
	    onChangeDateTime :function(){
		    load_data();
        }
	});
	$('#id_date_end').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
		onChangeDateTime :function(){
		    load_data();
        }
	});
	//时间控件-over
    $.each(".lesson_point",function(i,item){
        item.replace("&nbsp;","<br/>");
    });
}); 







