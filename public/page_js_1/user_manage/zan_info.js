$(function(){
   
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

    
    Enum_map.append_option_list("praise", $("#id_praise_type"),true);
    $('#id_praise_type').val(g_args.praise_type);
	function load_data( ){
        var start_date  = $("#id_start_date").val();
        var end_date    = $("#id_end_date").val();
        var userid      = $("#id_userid").val();
        var praise_type = $("#id_praise_type").val();
        
        reload_self_page({
            "praise_type  ": praise_type,
            "start_date" : start_date,
            "end_date"   : end_date,
            "userid"     : userid,
        });
	}

  
    $('#id_start_date').val(g_args.start_date);
    $('#id_end_date').val(g_args.end_date);
    $('#id_type').val(g_args.type);
    $('#id_userid').val(g_args.userid);

   
	$(".opt-change").on("change",function(){
		load_data();
	});

    $("#id_userid").admin_select_user({
        "type"   : "student",
        "onChange": function(){
            load_data(  );
        }
    });
    
});
