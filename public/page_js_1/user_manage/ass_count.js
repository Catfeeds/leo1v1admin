$(function(){
    Enum_map.append_option_list("grade", $("#id_grade"));
    Enum_map.append_option_list("test_user", $("#id_test_user"));
    Enum_map.append_option_list("stu_origin", $("#id_originid"));
    Enum_map.td_show_desc("grade", $(".td-grade"));
    Enum_map.td_show_desc("relation_ship", $(".td-parent-type"));

    $("#id_assistantid").val(g_args.assistantid);
	$("#id_grade").val(g_args.grade);
	$("#id_originid").val(g_args.originid);
	$("#id_test_user").val(g_args.test_user);
	$("#id_origin").val(g_args.originid);
	$("#id_user_name").val(g_args.user_name);
	$("#id_phone").val(g_args.phone);
	$(".stu_sel" ).on( "change",function(){
		load_data();
	});

	$(".for_input").on ("keypress",function(e){
		if (e.keyCode==13){
			var field_name=$(this).data("field");
			var value=$(this).val();
			if (field_name=="user_name" ){
				load_data();
			}else{
				load_data();
			}
		}
	});
    
    $("#id_search_user").on("click",function(){
        var value=$("#id_user_name").val();
		load_data();
    });

    $("#id_search_tel").on("click",function(){
        var value=$("#id_phone").val();
		load_data();
    });

    admin_select_user($("#id_assistantid"), "assistant",function(){
        load_data();
    });
    
	function load_data(){
        reload_self_page({
            test_user   : $("#id_test_user").val(),
            originid    : $("#id_originid").val(),
            grade       : $("#id_grade").val(),
            user_name   : $("#id_user_name").val(),
            phone       : $("#id_phone").val(),
            assistantid : $("#id_assistantid").val()
        });
	}

    //设置是否为测试用户
    Enum_map.append_option_list("test_user",$("#id_set_channel"),true);
    
    //设置是否为渠道来源
    Enum_map.append_option_list("stu_origin",$("#id_stu_origin"),true);

});
