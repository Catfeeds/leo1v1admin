/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-score_list.d.ts" />

$(function(){
    //
    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id_subject        = $("<select/>");  //选择考试科目
        var id_stu_score_type = $("<select/>");  //选择考试类型
        var id_stu_score_time = $("<input/>");   //输入考试日期
        var id_score          = $("<input/>");   //输入考试分数
        var id_rank           = $("<input/>");   //输入考试排名
        //var id_file_url       = $("<input/>");   //文件url

        var $upload_div  = $("<div > <button id=\"id_upload_from_url\" > 上传</button>  <a href=\"\" target=\"_blank\">查看 </a>   </div>");
        var $upload_btn  = $upload_div.find("button") ;
        var $upload_link = $upload_div.find("a") ;

        $upload_link.attr('href',opt_data.file_url);
        id_stu_score_time.datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Y-m-d'
        });
        Enum_map.append_option_list("subject", id_subject, true);
        Enum_map.append_option_list("stu_score_type", id_stu_score_type, true);

        id_subject.val(opt_data.subject);
        id_stu_score_type.val(opt_data.stu_score_type);
        id_stu_score_time.val(opt_data.stu_score_time);
        id_score.val(opt_data.score);
        id_rank.val(opt_data.rank);
        var arr = [
            ["考试科目", id_subject],
            ["考试类型", id_stu_score_type],
            ["考试日期", id_stu_score_time],
            ["考试分数",id_score],
            ["考试排名",id_rank],
        ];

        arr.push(['考试文件',$upload_div]);
        $.show_key_value_table("修改考试记录", arr, {
            label    :   "确认",
            cssClass :   "btn-warning",
            action   :   function(dialog){
                $.do_ajax('/ajax_deal2/score_edit',{
                    "id" : opt_data.id,
                    "subject"       : id_subject.val(),
                    "stu_score_type": id_stu_score_type.val(),
                    "stu_score_time": id_stu_score_time.val(),
                    "score"         : id_score.val(),
                    "rank"          : id_rank.val(),
                    "file_url"      : $upload_link.attr('href'),
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
                    })
                },null,
                ["png","jpg","zip","rar","gz","pdf","doc"] );

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
        var id_stu_score_type = $("<select/>");  //选择考试类型
        var id_stu_score_time = $("<input/>");   //输入考试日期
        var id_score          = $("<input placeholder=\"\" />");   //输入考试分数
        var id_rank           = $("<input  placeholder=\"排名/总人数(1/50)\" />");   //输入考试排名

        //var id_file_url       = $("<input/>");
        var $upload_div  = $("<div > <button id=\"id_upload_from_url\" > 上传</button>  <a href=\"\" target=\"_blank\">查看 </a>   </div>");
        var $upload_btn  = $upload_div.find("button") ;
        var $upload_link = $upload_div.find("a") ;
        $upload_link.attr('href',"");
        //$upload_link.attr('href',opt_data.from_url);

        Enum_map.append_option_list("subject", id_subject, true);
        Enum_map.append_option_list("stu_score_type", id_stu_score_type, true);

        id_stu_score_time.datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Y-m-d'
        });

        var arr = [
            ["考试科目", id_subject],
            ["考试类型", id_stu_score_type],
            ["考试日期", id_stu_score_time],
            ["考试分数",id_score],
            ["考试排名",id_rank],
            ];
        arr.push(['考试文件',$upload_div]);
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
                    "file_url"      : $upload_link.attr('href'),
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
                    })
                },null,
                ["png","jpg","zip","rar","gz","pdf","doc"] );
        })
    });


    //

})
