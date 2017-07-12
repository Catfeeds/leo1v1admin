$(function(){
    $('#id_start_date').val(g_start_date);
    $('#id_end_date').val(g_end_date);
    $('#id_authority_flag').val(g_flag);
    $("#id_login_info").val(g_args.login_info);

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
        var end_date   = $("#id_end_date").val();
        var flag       = $("#id_authority_flag").val();
        var login_info = $("#id_login_info").val();
       
        
	    var url="/authority/get_login_list?start_date="+start_date
            +"&end_date="+end_date
            +"&flag="+flag
            +"&login_info="+login_info;
	    window.location.href=url;
	}
    
	$(".opt-change").on("change",function(){
		load_data();
	});

    $("#id_search_login").on("click",function(){
        load_data();
    });
    
    $("#id_login_info").on("keypress",function(e){
        if(e.keyCode == 13){
            load_data();
        }
    });


});
