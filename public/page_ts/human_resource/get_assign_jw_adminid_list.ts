/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-get_assign_jw_adminid_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			page_count:	$('#id_page_count').val(),
			teacherid:	$('#id_teacherid').val(),
			grade_part_ex:	$('#id_grade_part_ex').val(),
			subject:	$('#id_subject').val(),
			second_subject:	$('#id_second_subject').val(),
			jw_adminid:	$('#id_jw_adminid').val(),
			identity:	$('#id_identity').val(),
			class_will_type:	$('#id_class_will_type').val(),
			have_lesson:	$('#id_have_lesson').val(),
			revisit_flag:	$('#id_revisit_flag').val(),
			have_test_lesson_flag:	$('#id_have_test_lesson_flag').val(),
			textbook_flag:	$('#id_textbook_flag').val()
        });
    }

    Enum_map.append_option_list("identity", $("#id_identity") );
    Enum_map.append_option_list("grade_part_ex", $("#id_grade_part_ex") );
    Enum_map.append_option_list("subject", $("#id_subject") );
    Enum_map.append_option_list("subject", $("#id_second_subject") );
    Enum_map.append_option_list("class_will_type", $("#id_class_will_type") );
    Enum_map.append_option_list("boolean", $("#id_have_test_lesson_flag") );


	$('#id_page_count').val(g_args.page_count);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_grade_part_ex').val(g_args.grade_part_ex);
	$('#id_subject').val(g_args.subject);
	$('#id_second_subject').val(g_args.second_subject);
	$('#id_identity').val(g_args.identity);
	$('#id_jw_adminid').val(g_args.jw_adminid);
    $('#id_class_will_type').val(g_args.class_will_type);
	$('#id_have_lesson').val(g_args.have_lesson);
	$('#id_revisit_flag').val(g_args.revisit_flag);
	$('#id_textbook_flag').val(g_args.textbook_flag);
	$('#id_have_test_lesson_flag').val(g_args.have_test_lesson_flag);

    $.admin_select_user( $("#id_teacherid"), "teacher", load_data);

    if(g_adminid != -1){
        $('#id_jw_adminid').parent().parent().hide();
    }
    $(".opt-return-back-new").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        //alert(teacherid);
        var id_revisit_note = $("<textarea />");             
        var id_class_will_type = $("<select />");             
        var id_class_will_sub_type = $("<select />");             
        var id_recover_class_time = $("<input />");           
        Enum_map.append_option_list( "class_will_type",id_class_will_type,true);
        var arr = [
            [ "接课意愿",  id_class_will_type], 
            [ "接课意愿详情",  id_class_will_sub_type], 
            [ "恢复接课时间",  id_recover_class_time], 
            [ "回访信息",  id_revisit_note] 
        ];
        var show_field=function (jobj,show_flag) {
            if ( show_flag ) {
                jobj.parent().parent().show();
            }else{
                jobj.parent().parent().hide();
            }
        };

        var reset_ui=function() {
            var val=id_class_will_type.val();
            if (val==0) {
                show_field( id_class_will_sub_type ,false);
                show_field( id_recover_class_time ,false);
            }else if(val==1){
                show_field( id_class_will_sub_type ,true);
                show_field( id_recover_class_time ,false);
                id_class_will_sub_type.find("option").remove(); 
                Enum_map.append_option_list( "class_will_sub_type",id_class_will_sub_type,true,[1,2]);

            }else if(val==2){
                show_field( id_class_will_sub_type ,true);
                show_field( id_recover_class_time ,false);
                id_class_will_sub_type.find("option").remove();
                Enum_map.append_option_list( "class_will_sub_type",id_class_will_sub_type,true,[3,4,5,6]);               
            }else if(val==3){
                show_field( id_class_will_sub_type ,true);
                show_field( id_recover_class_time ,false);
                id_class_will_sub_type.find("option").remove();
                Enum_map.append_option_list( "class_will_sub_type",id_class_will_sub_type,true,[7,8,9]);

            }


        };
        var reset_ui_sub=function() {
            var sub_type= id_class_will_sub_type.val();
            if ( sub_type ==5 || sub_type==2 ) {
                show_field( id_recover_class_time ,true);
            }else{
                show_field( id_recover_class_time ,false);
            }

        };

        
        
        id_class_will_type.on("change",function(){
            reset_ui();
        });
        id_class_will_sub_type.on("change",function(){
            reset_ui_sub();
        });

        
        $.show_key_value_table("录入回访信息", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/human_resource/add_new_teacher_revisit_record", {
                    "teacherid"               : teacherid,
                    "revisit_note"            : id_revisit_note.val(),
                    "class_will_type"         : id_class_will_type.val(),
                    "class_will_sub_type"     : id_class_will_sub_type.val(),
                    "recover_class_time"      : id_recover_class_time.val()
                });
            }
        },function(){
            reset_ui();
            id_recover_class_time.datetimepicker({
                datepicker : true,
                timepicker : true,
                format     : 'Y-m-d H:i',
                step       : 30
            });
        });
	});

    $(".opt-return-back-list-new").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var teacherid    = opt_data.teacherid;        
       // alert(teacherid);
		$.ajax({
			type     :"post",
			url      :"/human_resource/get_new_teacher_revisit_info",
			dataType :"json",
            size     : BootstrapDialog.SIZE_WIDE,
			data     : {"teacherid":teacherid},
			success  : function(result){
                console.log(result);
				var html_str="<table class=\"table table-bordered table-striped\"  > ";
                html_str+=" <tr><th> 时间  <th> 负责人 <th>内容 </tr>   ";
				$.each( result.revisit_list ,function(i,item){
                    //console.log(item);
                    //return;
                    var revisit_person  ="";
                    if(item.revisit_person  ) {
                        revisit_person  = item.revisit_person;
                    }
					html_str=html_str+"<tr><td>"+item.add_time_str +"</td><td>"+ item.acc +"</td><td>"+item.record_info+" </td></tr>";
				} );

                
                
                var dlg=BootstrapDialog.show({
                    title: '回访记录',
                    message :  html_str , 
                    closable: true, 
                    buttons: [{
                        label: '返回',
                        action: function(dialog) {
                            //dlg.setSize(BootstrapDialog.SIZE_WIDE);
                            dialog.close();
                        }
                    }]
                }); 

                if (!$.check_in_phone()) {
                    dlg.getModalDialog().css("width", "800px");
                }

			}
		});

	});


    $(".opt-teacher-info").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var textbook  = opt_data["teacher_textbook"];
        console.log(textbook);
        $.do_ajax("/user_deal/get_teacher_textbook",{
            "textbook" : textbook
        },function(response){
            var data_list   = [];
            var select_list = [];
            $.each( response.data,function(){
                data_list.push([this["num"], this["textbook"]  ]);

                if (this["has_textbook"]) {
                    select_list.push (this["num"]) ;
                }

            });

            $(this).admin_select_dlg({
                header_list     : [ "id","教材版本" ],
                data_list       : data_list,
                multi_selection : true,
                select_list     : select_list,
                onChange        : function( select_list,dlg) {
                    //alert(JSON.stringify(select_list));return;
                    $.do_ajax("/user_deal/set_teacher_textbook",{
                        "teacherid": opt_data.teacherid,
                        "textbook_list":JSON.stringify(select_list),
                        "old_textbook": opt_data.teacher_textbook,
                    });
                }
            });
        }) ;
 
    });

	$('.opt-change').set_input_change_event(load_data);
});







