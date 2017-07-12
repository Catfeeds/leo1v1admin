/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/small_class-student_list_new.d.ts" />

$(function(){
        function load_data(){
        $.reload_self_page ( {
			courseid:	$('#id_courseid').val(),
			lessonid:	$('#id_lessonid').val(),
			studentid:	$('#id_studentid').val()
        });
    }


	$('#id_courseid').val(g_args.courseid);
	$('#id_lessonid').val(g_args.lessonid);

    $("#id_studentid").val(g_args.studentid);
    $("#id_studentid").admin_select_user({
        "type"   : "student",
        "onChange": function(studentid,dlg ){
           $.do_ajax_get_nick("student",studentid, function(id, nick){
                BootstrapDialog.confirm("要加入学生［"+nick+"］?",function(result){
                    if (result) {
                        $.do_ajax("/small_class/small_class_add_user",{
                            "lessonid"  : g_args.lessonid,
                            "studentid" : id 
                        });
                        dlg.close(); 
                    }
                });
            });
        },
        "auto_close" :false
    });
    
    $(".opt-show-pdf").on("click",function(){
        show_pdf_file( $(this).get_opt_data("download_url"));
    });
    
    $(".opt-del").on("click",function(){
        
        var nick=$(this).parent().parent().parent().find(".td-student-nick").text();
        var me=this;
        BootstrapDialog.confirm("要让["+nick+"]不上这节课吗？", function(result) {
            if (result ) {
                $.do_ajax("/small_class/small_class_del_user", {
                    "lessonid" :$(me).get_opt_data("lessonid"),
                    "studentid" :$(me).get_opt_data("studentid")
                });
            }
            
        });
        //td-student-nick
	    
    });

	$('.opt-change').set_input_change_event(load_data);
});
