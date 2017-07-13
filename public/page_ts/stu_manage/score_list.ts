/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-course_list.d.ts" />

$(function(){
    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id_subject        = $("<select/>");  //选择考试科目
        var id_stu_score_type = $("<select/>");  //选择考试类型
        var id_stu_score_time = $("<input/>");   //输入考试日期
        var id_score          = $("<input/>");   //输入考试分数
        var id_rank           = $("<input/>");   //输入考试排名
        var id_file_url       = $("<input/>");   //文件url

        Enum_map.append_option_list("subject", id_subject, true);
        Enum_map.append_option_list("stu_score_type", id_stu_score_type, true);
         id_stu_score_time.datetimepicker({
            lang:'ch',
            timepicker:true,
            format:'Y-m-d'
        });


        id_subject.val(opt_data.subject_str);
        id_stu_score_type.val(opt_data.stu_score_type_str);
        id_stu_score_time.val(opt_data.stu_score_time);
        id_score.val(opt_data.score);
        id_rank.val(opt_data.rank);
        id_file_url.val(opt_data.file_url);

        var arr = [
            ["考试科目", id_subject],
            ["考试类型", id_stu_score_type],
            ["考试日期", id_stu_score_time],
            ["考试分数",id_score],
            ["考试排名",id_rank],
            ["文件附件",id_file_url],
        ];

        $.show_key_value_table("修改考试记录", arr, {
            label    :   "确认",
            cssClass :   "btn-warning",
            action   :   function(dialog){
                $.do_ajax('/ajax_deal2/score_edit',{
                    "subject"       : id_subject.val(),
                    "stu_score_type": id_stu_score_type.val(),
                    "stu_score_time": id_stu_score_time.val(),
                    "score"         : id_score.val(),
                    "rank"          : id_rank.val(),
                    "file_url"      : id_file_url.val()
                });
            }
        },function(){
            
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
        var id_subject        = $("<select/>");  //选择考试科目
        var id_stu_score_type = $("<select/>");  //选择考试类型
        var id_stu_score_time = $("<input/>");   //输入考试日期
        var id_score          = $("<input/>");   //输入考试分数
        var id_rank           = $("<input/>");   //输入考试排名
        var id_file_url       = $("<input/>");   //文件url

        Enum_map.append_option_list("subject", id_subject, true);
        Enum_map.append_option_list("stu_score_type", id_stu_score_type, true);

        id_stu_score_time.datetimepicker({
            lang:'ch',
            timepicker:true,
            format:'Y-m-d'
        });

        var arr = [
            ["考试科目", id_subject],
            ["考试类型", id_stu_score_type],
            ["考试日期", id_stu_score_time],
            ["考试分数",id_score],
            ["考试排名",id_rank],
            ["文件附件",id_file_url],
        ];

        $.show_key_value_table("增加考试记录", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
                if(id_subject.val() <= 0 || id_stu_score_type.val() <= 0){
                    alert("请填写完整!");
                    return;
                }
	        $.do_ajax("/ajax_deal2/score_add_new",{
                    "userid"        : g_sid,
                    "create_time"   : '1',
                    "create_adminid": '1',
                    "subject"       : id_subject.val(),
                    "stu_score_type": id_stu_score_type.val(),
                    "stu_score_time": id_stu_score_time.val(),
                    "score"         : id_score.val(),
                    "rank"          : id_rank.val(),
                    "file_url"      : id_file_url.val()
                });
            }
        }, function(){
            
        });
    });
});
