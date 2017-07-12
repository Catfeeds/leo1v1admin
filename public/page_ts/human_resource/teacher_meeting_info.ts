/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_meeting_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val()
        });
    }


    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config), 
        onQuery :function() {
            load_data();
        }
    });

    $("#id_add_meeting").on("click",function(){
        var id_address=$("<input/>");
        var id_create_time=$("<input/>");
        var id_theme=$("<input/>");
        var id_summary=$("<textarea/>");
        var id_moderator=$("<input/>");
        
        
	    id_create_time.datetimepicker({
		    lang:'ch',
		    timepicker:true,
		    format:'Y-m-d H:i',
            "onChangeDateTime" : function() {
            }
	    });
        

        var arr=[
            ["会议时间", id_create_time],
            ["会议地点", id_address],
            ["会议主题", id_theme],
            ["主持人", id_moderator],
            ["会议纪要", id_summary ],
        ];
        $.show_key_value_table("新增会议记录", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {

                $.do_ajax( '/teacher_info/add_meeting_info',
                           {
                               "summary" : id_summary.val(),
                               "theme" : id_theme.val(),
                               "moderator" : id_moderator.val(),
                               "create_time" : id_create_time.val(),
                               "address" : id_address.val()                              
                           });
                
            }
            
        });
        

    });

    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;

        var id_address=$("<input/>");
        var id_create_time=$("<input readonly/>");
        var id_theme=$("<input/>");
        var id_summary=$("<textarea/>");
        var id_moderator=$("<input/>");
        var arr=[
            ["会议时间", id_create_time],
            ["会议地点", id_address],
            ["会议主题", id_theme],
            ["主持人", id_moderator],
            ["会议纪要", id_summary ],
        ];
        id_address.val(opt_data.address);
        id_summary.val(opt_data.summary);
        id_theme.val(opt_data.theme);
        id_moderator.val(opt_data.moderator);
        id_create_time.val(opt_data.create_time_str);
        $.show_key_value_table("修改会议记录", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {

                $.do_ajax( '/teacher_info/update_meeting_info',
                           {
                               "id":id,
                               "summary" : id_summary.val(),
                               "theme" : id_theme.val(),
                               "moderator" : id_moderator.val(),
                               "create_time" : id_create_time.val(),
                               "address" : id_address.val()                              
                           });
                
            }
            
        });
        

    });

    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;
        BootstrapDialog.confirm("确定要删除？", function(val){
            if (val) {
                $.do_ajax( '/teacher_info/teacher_meeting_info_del', {
                    'id'         : id
                });
            } 
        });



    });

    $(".opt-yuhui").on('click',function(){
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;
        var teacher_join_info = opt_data.teacher_join_info;
        
                
        $.ajax({
            url: '/teacher_info/get_tea_arr_list',
            type: 'POST',
            data: {
			},
            dataType: 'json',
            success: function(data) {
                var arr = [];
                var i =0;
                var sel = "<select class=\"select_cc\"><option value =\"1\" >出席</option><option value =\"2\">请假</option><option value =\"3\">缺席</option></select>";
                for(var s in data){
                    arr[i] = [data[s],sel];
                    i = i +1;
                }
                for(var s in teacher_join_info){
                    if(teacher_join_info[s].substr(7,1) ==1){
                        arr[s][1] =  arr[s][1].substr(0,45) + " "+"selected"+" " +arr[s][1].substr(45);
                    }else if(teacher_join_info[s].substr(7,1) ==2){
                        arr[s][1] =  arr[s][1].substr(0,75) + " "+"selected"+" " +arr[s][1].substr(75);
                    }else{
                        arr[s][1] =  arr[s][1].substr(0,105) + " "+"selected"+" " +arr[s][1].substr(105);
                    }
                }
                
                $.show_key_value_table("教师出席会议信息", arr ,{
                    label    : '确认',
                    cssClass : 'btn-warning',
                    action   : function(dialog) {
                        var meeting_info ="";
                        var j = 0;
                        var arr_join = [];
                        for(var s in data){
                            meeting_info = "{"+s+":"+$(".select_cc").eq(j).val() + "}";
                            arr_join.push(meeting_info);
                            j = j+1;
                        }
                        $.do_ajax("/teacher_info/set_teacher_meeting_info", {
                            "teacher_join_info":JSON.stringify(arr_join),
                            "id":id
                        });
                    }
                });
                

                
            }
        });

        
    });


	$('.opt-change').set_input_change_event(load_data);
});

