$(function(){
    Enum_map.td_show_desc("grade", $(".td-grade"));
    Enum_map.td_show_desc("subject", $(".td-subject"));
    $('.opt-alloc-teacher').on('click',  function(){
        var lessonid = $(this).get_opt_data("lessonid");
        $(this).admin_select_user({
            "show_select_flag":true,
            "onChange":function(val){
                var teacherid = val;

                do_ajax('/lesson_manage/set_lesson_teacherid', {
                    'teacherid': teacherid, 'lessonid': lessonid 
                });

            }
        });
        
    });


    $('#add_small_class_course').on('click', function(){
        var id_course_name = $("<input>");
        var id_grade = $("<select/>");
        var id_subject= $("<select/>");
        var id_lesson_count = $("<input>");
        var id_stu_total= $("<input>");
        var id_teacherid= $("<input>");

        Enum_map.append_option_list("grade", id_grade,true);
        Enum_map.append_option_list("subject", id_subject,true);
        var arr                = [
            [ "名称",  id_course_name] ,
            [ "年级",  id_grade] ,
            [ "科目",  id_subject] ,
            [ "课次",   id_lesson_count] ,
            [ "人数",   id_stu_total ] ,
        ];

        show_key_value_table("新增小班课", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var course_name  = id_course_name.val();
                var lesson_total = id_lesson_count.val();
                var grade        = id_grade.val();
                var subject      = id_subject.val();
                var stu_total    = id_stu_total.val();
                if (!course_name) {
                    BootstrapDialog.alert('课程名称不可以为空');
                    html_node.find('.course_name').addClass('warning');
                    return;
                }

                if (parseInt(lesson_total) <= 0 || isNaN(parseInt(lesson_total))) {
                    BootstrapDialog.alert('课次总数不可以为零');
                    return;
                }

                if (parseInt(stu_total) <= 0 || isNaN(parseInt(stu_total))) {
                    BootstrapDialog.alert('学生总数不可以为零');
                    return;
                }

                $.ajax({
                    url: '/small_class/add_lesson_course',
                    type: 'POST',
                    data: {
                        'grade': grade, 'subject': subject, 'lesson_total': lesson_total,
                        'course_name': course_name, 'stu_total': stu_total
                    },
                    dataType: 'json',
                    success:   ajax_default_deal_func
                });

			    dialog.close();

            }
        });
        


    });
    var goto_student_list= function ( lessonid ){
        if ( window.location.pathname =="/small_class/lesson_list" ) {
            window.location.href='/small_class/student_list?courseid='+g_args.courseid+"&studentid=-1&lessonid="+lessonid+"&return_url="+ encodeURIComponent(window.location.href);
        }else{
            window.location.href='/small_class/student_list_ass?courseid='+g_args.courseid+"&studentid=-1&lessonid="+lessonid+"&return_url="+ encodeURIComponent(window.location.href);
        }	 
    };

    
    $(".opt-student-list").on("click",function(){
        //g_args.courseid
        var lessonid=$(this).get_opt_data("lessonid");
        goto_student_list(lessonid );
    });


    $.each( $(".opt-set-time"),function(i,item)  {
        var $item=$(item);
        var lessonid=$item.get_opt_data("lessonid");
        $item.admin_set_lesson_time({lessonid :lessonid});
    });

});



