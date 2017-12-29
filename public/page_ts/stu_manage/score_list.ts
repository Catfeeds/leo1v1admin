/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-score_list.d.ts" />

$(function(){
    //
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

        var $upload_div  = $("<div > <button id=\"id_upload_from_url\" > 上传</button>  <a href=\""+opt_data.file_url+"\" target=\"_blank\" id=\"id_pre_look\"> </a>   </div>");

        var $upload_btn  = $upload_div.find("button") ;
        var $upload_link = $upload_div.find("a") ;

        $upload_link.attr('href',opt_data.file_url);
        if(opt_data.file_url != ''){
            $upload_link.html("查看");
        }

        Enum_map.append_option_list("subject", id_subject, true,[1,2,3,4,5,6,7,8,9,10]);
        Enum_map.append_option_list("stu_score_type", id_stu_score_type, true);
        Enum_map.append_option_list("grade",id_grade,true,[101,102,103,104,105,106,201,202,203,301,302,303]);
        Enum_map.append_option_list("semester",id_semester,true);


        id_subject.val(opt_data.subject);
        id_stu_score_type.val(opt_data.stu_score_type);
        id_score.val(opt_data.score);
        id_rank.val(opt_data.rank);
        id_total_score.val(opt_data.total_score);
        id_grade_rank.val(opt_data.grade_rank);
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
        $.show_key_value_table("修改考试记录", arr, {
            label    :   "确认",
            cssClass :   "btn-warning",
            action   :   function(dialog){
                $.do_ajax('/ajax_deal2/score_edit',{
                    "userid"        : g_sid,
                    "create_time"   : opt_data.create_time,
                    "id"            : opt_data.id,
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
        BootstrapDialog.confirm("要删除学生是["+opt_data.userid+"]的考试信息吗?",function(val){
            if(val){
                $.do_ajax("/ajax_deal2/score_del",{
                    "id" : opt_data.id
                });
            }
        });
    });

    $("#id_add_score_new").on("click", function(){
        var opt_data = $(this).get_opt_data;
        var id_subject        = $("<select/>");  //选择考试科目
        var id_grade          = $("<select/>");  //年级
        var id_semester       = $("<select/>");  //学期
        var id_stu_score_type = $("<select/>");  //选择考试类型
        var id_score          = $("<input placeholder=\"输入考试成绩\" />");   //输入考试分数
        var id_total_score    = $("<input placeholder=\"输入满分分数\" />");   //输入考试总分
        var id_rank           = $("<input placeholder=\"输入班级排名 格式:1/33或者1\" />");   //输入班级排名
        var id_grade_rank     = $("<input placeholder=\"输入年级排名 格式:2/99或者2\" />");   //输入年级排名

        var $upload_div  = $("<div > <button id=\"id_upload_from_url\" > 上传</button>  <a href=\"\" target=\"_blank\"> </a>   </div>");
        var $upload_btn  = $upload_div.find("button") ;
        var $upload_link = $upload_div.find("a") ;
        $upload_link.attr('href',"");
        //$upload_link.attr('href',opt_data.from_url);

        Enum_map.append_option_list("subject", id_subject, true,[1,2,3,4,5,6,7,8,9,10]);
        Enum_map.append_option_list("stu_score_type", id_stu_score_type, true);
        Enum_map.append_option_list("grade",id_grade,true,[101,102,103,104,105,106,201,202,203,301,302,303]);
        Enum_map.append_option_list("semester",id_semester,true);

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
        $.show_key_value_table("增加考试记录", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
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
                              
                $.do_ajax("/ajax_deal2/score_add_new",{
                    "userid"        : g_sid,
                    "create_time"   : '0',
                    "create_adminid": '1',

                    "subject"       : id_subject.val(),
                    "stu_score_type": id_stu_score_type.val(),
                    "stu_score_time": '0',
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
            console.log(id_grade.val())
            id_grade.change(function(){
                console.log($(this).val());
            });
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
                ["png","jpg","jpeg","zip","rar","gz","pdf","doc"] );
        })
    });
    //
})
