/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-no_type_student_score.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type     : $('#id_date_type').val(),
            opt_date_type : $('#id_opt_date_type').val(),
            start_time    : $('#id_start_time').val(),
            end_time      : $('#id_end_time').val(),
            status        : $('#id_status').val(),
        });
    }

    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        // date_type_config : JSON.parse( g_args.date_type_config), 
        onQuery          : function() {
            load_data();
        }
    });


	$('.opt-change').set_input_change_event(load_data);


    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id_subject        = $("<select/>");  //选择考试科目
        var id_grade          = $("<select/>");  //年级
        var id_semester       = $("<select/>");  //学期
        var id_stu_score_type = $("<select/>");  //选择考试类型

        var id_score          = $("<input/>");   //输入考试分数
        var id_total_score    = $("<input/>");   //输入考试总分
        var id_rank           = $("<input/>");   //输入班级排名
        var id_grade_rank     = $("<input/>");   //输入年级排名
        //var id_file_url       = $("<input/>");   //文件url

        var $upload_div  = $("<div > <button id=\"id_upload_from_url\" > 上传</button>  <a href=\"\" target=\"_blank\"></a>   </div>");
        var $upload_btn  = $upload_div.find("button") ;
        var $upload_link = $upload_div.find("a") ;

        Enum_map.append_option_list("subject", id_subject, true,[1,2,3,4,5,6,7,8,9,10]);
        Enum_map.append_option_list("stu_score_type", id_stu_score_type, true);
        Enum_map.append_option_list("grade",id_grade,true,[101,102,103,104,105,106,201,202,203,301,302,303]);
        Enum_map.append_option_list("semester",id_semester,true);


        id_subject.val(opt_data.subject);
        id_stu_score_type.val(opt_data.stu_score_type);
        id_grade.val(opt_data.grade);
        var arr = [
            ["考试科目", id_subject],
            ["年级",    id_grade],
            ["学期",    id_semester],
            ["考试类型", id_stu_score_type],

            ["考试成绩", id_score],
            ["试卷总分",id_total_score],
            ["班级排名",id_rank],
            ["年级排名",id_grade_rank],
        ];

        arr.push(['学生试卷',$upload_div]);
        $.show_key_value_table("补充考试记录", arr, {
            label    :   "确认",
            cssClass :   "btn-warning",
            action   :   function(dialog){
                if(id_subject.val() <= 0){
                    alert("请选择考试科目");
                    return;
                }
                if(id_score.val() === ''){
                    alert("请输入考试成绩");
                    return;
                }
                if(id_total_score.val() === ''){
                    alert("请输入试卷总分");
                    return;
                }
                if(id_score.val() > id_total_score.val()){
                    alert("考试成绩不能大于总分");
                    return;
                }
                $.do_ajax('/ajax_deal2/score_edit',{
                    "id" : opt_data.id,
                    "subject"       : id_subject.val(),
                    "stu_score_type": id_stu_score_type.val(),
                    "score"         : id_score.val(),
                    "rank"          : id_rank.val(),
                    "file_url"      : $upload_link.attr('href'),
                    "semester"      : id_semester.val(),
                    "total_score"   : id_total_score.val(),
                    "grade"         : id_grade.val(),
                    "grade_rank"    : id_grade_rank.val(),
                    "status"        : 0,

                });
            }
        },function(){
            $.custom_upload_file(
                "id_upload_from_url" ,
                true,function( up, info, file ){
                    var res = $.parseJSON(info);
                    var url=res.key;
                    $.do_ajax("/common_new/get_qiniu_download",{
                        "file_url" :res.key ,
                        "public_flag" :1,
                    }, function(resp){
                        $upload_link.attr("href", resp.url);
                        $upload_link.html("查看");
                    })
                },null,
                ["png","jpg","zip","rar","gz","pdf","doc","jpeg"] );

        });
    }) ;


    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id_reason = $("<textarea/>");         
        var arr = [
            ["取消原因", id_reason]
        ];

        $.show_key_value_table("取消添加学生成绩", arr, {
            label    :   "确认",
            cssClass :   "btn-warning",
            action   :   function(dialog){
                if(id_reason.val() == ''){
                    alert("请输入取消的理由");
                    return;
                }
                $.do_ajax('/ajax_deal2/score_cancel',{
                    "id" : opt_data.id,
                    "reason" : id_reason.val(),
                });
            }
        },function(){
        });
    });
});
