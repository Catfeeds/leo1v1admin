$(function(){

    $('#id_start_date').val(g_start_date);
    $('#id_end_date').val(g_end_date);
    $('#id_user_grade').val(g_args.grade);
    $("#id_phone").val(g_args.phone);
    $("#id_origin").val(g_args.origin);


    
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

	function load_data( ){
        var start_date = $("#id_start_date").val();
        var end_date   = $("#id_end_date").val();
        var phone      = $("#id_phone").val();
        var origin     = $("#id_origin").val();
        
        reload_self_page({
            start_date : start_date,
            end_date   : end_date,
            origin     : origin,
            phone      : phone
        });
	}
    
	$(".opt-change").on("change",function(){
		load_data();
	});

    $("#id_class_time").on("change",function(){
		load_data();
	});

});
