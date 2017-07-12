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
            "praise_type" : praise_type,
            "start_date"  : start_date,
            "end_date"    : end_date,
            "lessonid"    : $("#id_lessonid").val(),
            "userid"      : userid
        });
	}
  
    $('#id_start_date').val(g_args.start_date);
    $('#id_end_date').val(g_args.end_date);
    $('#id_type').val(g_args.type);
    $('#id_userid').val(g_args.userid);
    $('#id_lessonid').val(g_args.lessonid);

	$(".opt-change").on("change",function(){
		load_data();
	});


    $("#id_userid").admin_select_user({
        "type"   : "student",
        "onChange": function(){
            load_data(  );
        }
    });

    $("#id_add_mypraise").on("click",function(){
        var id_userid     = $("<input />");
        var id_praise_num = $("<input/>");
        var id_type       = $("<select/>");
        var id_reason     = $("<textarea />");
        var type_html="<option value=\"1099\">后台增加</option><option value=\"2003\">退费减赞</option>";
        id_type.append(type_html);

        var arr           = [
            ['用户',id_userid],
            ['类型',id_type],
            ['获赞数量',id_praise_num],
            ['获赞原因',id_reason]
        ];

        show_key_value_table("添加赞记录",arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var userid     = id_userid.val();
                var praise_num = id_praise_num.val();
                var reason     = id_reason.val();
                if( userid == '' || praise_num == '' || reason == ''){
                    alert("请填写全部信息！");
                    return ;
                }

                if(isNaN(praise_num)){
                    alert("赞的数量必须为数字!");
                    return;
                }

                do_ajax("/user_manage/add_praise", {
                    "userid"     : userid,
                    "type"       : id_type.val(),
                    "praise_num" : praise_num,
                    "reason"     : reason
                },function(result){
                    if(result.ret!=0){
                        alert(result.info);
                    }else{
                        load_data();
                    }
                });
            }
        });

        id_userid.admin_select_user({
            "type":"student"
        });

    });
});
