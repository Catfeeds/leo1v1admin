/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-ass_counts.d.ts" />

$(function(){
    Enum_map.append_option_list("grade", $("#id_grade"));
    Enum_map.append_option_list("test_user", $("#id_test_user"));
    Enum_map.append_option_list("revisit_type", $("#id_revisit_type"));
    Enum_map.td_show_desc("grade", $(".td-grade"));
    Enum_map.td_show_desc("relation_ship", $(".td-parent-type"));

    $('#id_start_date').val(g_args.start_date);
	$("#id_revisit_type").val(g_args.revisit_type);
    $('#id_end_date').val(g_args.end_date);
    $("#id_assistantid").val(g_args.assistantid);
    $("#id_revisit_assistantid").val(g_args.revisit_assistantid);
	$("#id_grade").val(g_args.grade);
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
    admin_select_user($("#id_revisit_assistantid"), "assistant",function(){
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
 

	function load_data(){
        reload_self_page({
            start_date  : $("#id_start_date").val(),
            end_date    : $("#id_end_date").val(),
            test_user   : $("#id_test_user").val(),
            grade       : $("#id_grade").val(),
            user_name   : $("#id_user_name").val(),
            phone       : $("#id_phone").val(),
            assistantid : $("#id_assistantid").val(),
            revisit_assistantid : $("#id_revisit_assistantid").val(),
            revisit_type: $("#id_revisit_type").val()
        });
	}

    //设置是否为测试用户
    Enum_map.append_option_list("test_user",$("#id_set_channel"),true);
    
    //设置是否为渠道来源
    Enum_map.append_option_list("stu_origin",$("#id_stu_origin"),true);

});




/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_date</span>
                <input class="opt-change form-control" id="id_start_date" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_date</span>
                <input class="opt-change form-control" id="id_end_date" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_user</span>
                <input class="opt-change form-control" id="id_test_user" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">originid</span>
                <input class="opt-change form-control" id="id_originid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">user_name</span>
                <input class="opt-change form-control" id="id_user_name" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">revisit_assistantid</span>
                <input class="opt-change form-control" id="id_revisit_assistantid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">revisit_type</span>
                <input class="opt-change form-control" id="id_revisit_type" />
            </div>
        </div>
*/
