$(function(){
    var load_data=function() {
         reload_self_page({
             "start_time" : $("#id_start_time").val(),
             "end_time" : $("#id_end_time").val(),
             "adminid" : $("#id_adminid").val(),
             "sql_str" : $("#id_sql_str").val()
        });
    };
    
    $("#id_start_time").val(g_args.start_time);
    $("#id_end_time").val(g_args.end_time);
    $("#id_adminid").val(g_args.adminid);
    $("#id_sql_str").val(g_args.sql_str);

    admin_select_user($("#id_adminid"),"admin",function(){
        load_data();
    });
    
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
		    load_data();
        }
	});
    set_input_enter_event($("#id_sql_str"),load_data );


});
