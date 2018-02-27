/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_lesson_list_new.d.ts" />
$(function(){

    var sel_val = [];
    var get_sel_val = function(){
        sel_val = [];
        $('.leo-resource_type select,.leo-subject select,.leo-grade select,.leo-tag_one select').each(function(){
            sel_val.push( parseInt( $(this).val() ) );
        });
    }

    $('#id_start_date').val(g_args.start_date);
    $('#id_end_date').val(g_args.end_date);
    $('#id_lesson_type').val(g_args.lesson_type);
    $('#id_student').val(g_args.userid);

    //时间插件
    $("#id_start_date").datetimepicker({
        lang:'ch',
        datepicker:true,
        timepicker:false,
        format:'Y-m-d',
        step:30,
        onChangeDateTime :function(){
            load_data();
        }
    });

    $(".opt-add").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var $cc_id     = $("<input readonly='readonly' />");
        var $lesson_id = $("<input/>");
        var $question_type = $("<select><option  value ='0'>请选择</option> <option value ='1'>试听需求</option><option value ='2'>试卷</option><option value='3'>上课时间调整</option><option value='4'>是否转化</option><option value='5'>是否重上</option><option value='6'>其他</option></select>");
        var $question_content = $("<input/>");
        if(opt_data.lesson_type == 2){
            $cc_id.val(opt_data.cc_account);
        }else{
            $cc_id.val(opt_data.ass_nick);
        }
        $lesson_id.val(opt_data.lessonid);
        //TODO
        //$question_type.val(opt_data.question_type);
        //$question_content.val(opt_data.question_content);
        var arr=[
            ["联系人",  $cc_id],
            ["问题类型",  $question_type],
            ["问题描述",  $question_content],
        ];
        $.show_key_value_table("讲师申请帮助", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/teacher_info/teacher_apply_add",
                          {
                              //TODO
                              //"cc_id"            : opt_data.cc_id,
                              "stu_nick"         : opt_data.stu_nick,
                              "lesson_time"      : opt_data.lesson_time,
                              "lesson_type"      : opt_data.lesson_type,
                              "lessonid"         : $lesson_id.val(),
                              "question_type"    : $question_type.val(),
                              "question_content" : $question_content.val(),
                          },
                          function($ret){
                              alert('您的帮助申请已提交，教务老师会在第一时间进行电话回访，请保持手机畅通');
                              load_data();
                          }
                         )
            }
        })
    });

    $("#id_end_date").datetimepicker({
        lang:'ch',
        datepicker:true,
        timepicker:false,
        format:'Y-m-d',
        step:30,
        onChangeDateTime :function(){
            load_data();
        }
    });
    $('.opt-change').set_input_change_event(load_data);

    function load_data(){
        $.reload_self_page ( {
            start_date  : $('#id_start_date').val(),
            end_date    : $('#id_end_date').val(),
            lesson_type : $('#id_lesson_type').val(),
            userid      : $('#id_student').val()
        });
        loadpopup();

    }

    var upload_status_show=function(id_item, status){
        if (status) {
            id_item.find(".upload" ).text("重传").removeClass("btn-warning").addClass("btn-primary") ;
            id_item.find(".show" ).parent().show();
            id_item.find(".del" ).parent().show();
        }else{
            id_item.find(".upload" ).text("上传").removeClass("btn-primary").addClass("btn-warning");
            id_item.find(".show" ).parent().hide();
            id_item.find(".del" ).parent().hide();
        }
    };

    var next_td_show = function(btn_id, file_name){
        if($('#'+btn_id).parent().prev().hasClass('tea_cw_ex')) {
            $('#'+btn_id).parent().parent().parent().parent().next().show();
            $('#'+btn_id).parent().prev().children().children().val(file_name);
        }

    }

    //获取学科化标签
    var get_sub_grade_tag = function(subject,grade,booid,resource_type,season_id,obj,opt_type){
        obj.empty();
        //console.log(season_id);
        $.ajax({
            type     : "post",
            url      : "/teacher_info/get_sub_grade_book_tag",
            dataType : "json",
            data : {
                'subject' : subject,
                'grade'   : grade,
                'bookid'  : booid,
                'resource_type' : resource_type,
                'season_id'  : season_id
            } ,
            success : function(result){
                if(result.ret == 0){
                    obj.empty();
                    obj.parent().find('span.tag_warn').remove();
                    //console.log(result);
                    var tag_info = result.tag;
             
                    if($(tag_info).length == 0) {                      
                        if( subject > 0 && grade > 0){
                            obj.append('<option value="-1">暂无标签</option>');
                        }else{
                            obj.append('<option value="-1">资源类型、科目和年级是必选</option>');
                            $('#id_tag_four').css({'color':"#a2a2a2"});
                        }                        
                    } else {           
                        var tag_str = '<option value="-1">全部</option>';                                               
                        $.each($(tag_info),function(i,item){                        
                            tag_str = tag_str + '<option value='+item.id+'>'+item.tag+'</option>';
                        });
                        obj.append(tag_str);
                        if(opt_type == 1){
                            obj.val(g_args.tag_four);
                        }
                    }
                } else {
                    alert(result.info);
                }
            }
        });
    }


    var gen_upload_item = function(btn_id ,status, file_name_fix, get_url_fun, set_url_fun, bucket_info, noti_origin_file_func, back_flag,clear_file_id,look_pdf, allow_arr){
        if(!allow_arr ) {
            allow_arr = ['pdf'];
        }
        if(btn_id == 'id_teacher_upload'){
            allow_arr = ['pdf','ppt','pptx'];
        }
        var id_item = $(
            "<div class=\"row\"> "+
                "<div class=\" col-md-2\">" +
                "<button class=\"btn btn-primary btn-width upload \" id=\""+btn_id+"\">上传</button>"+
                "</div>"+
                "<div class=\" col-md-2\">" +
                "<button class=\"btn btn-primary show\">查看 </button>"+
                "</div>"+
                "<div class=\" col-md-2\">" +
                "<button class=\"btn btn-warning del\">清空</button>"+
                "</div>"+
                "</div>"
        );
        id_item.find(".show").on("click",function(){
            look_pdf(btn_id);
        });

        id_item.find(".del").on("click",function(){
            set_url_fun("");
            id_item.find("input").val("");
            upload_status_show(id_item,0);
            clear_file_id(btn_id);
        });

        upload_status_show(id_item,status);
        id_item["onshown_init"]=function () {
            if ( back_flag ) {

                $.self_upload_process(btn_id,"/common/upload_qiniu",[] ,allow_arr,{
                    "file_name_fix":file_name_fix
                }, function( ret,ctminfo){
                    set_url_fun(ret.file_name);
                    upload_status_show(id_item,1);
                    clear_file_id(btn_id);
                    next_td_show(btn_id,ret.file_name);
                });
            }else{
                try {
                    $.custom_upload_file_process(
                        btn_id, 0,
                        function(up, info, file, lesson_info) {
                            var res = $.parseJSON(info);
                            if(res.key!=''){
                                set_url_fun(res.key);
                                upload_status_show(id_item,1);
                            }
                            clear_file_id(btn_id);
                            next_td_show(btn_id,file.name);

                        }, [], allow_arr, bucket_info, noti_origin_file_func);
                }catch(e){
                    $.self_upload_process(btn_id,
                                          "/common/upload_qiniu",[] ,
                                          allow_arr,
                                          {
                                              "file_name_fix":file_name_fix
                                          }, function( ret,ctminfo){
                                              set_url_fun(ret.file_name);
                                              upload_status_show(id_item,1);
                                              clear_file_id(btn_id);
                                              next_td_show(btn_id,ret.file_name);

                                          });
                }
            }
        }
        return id_item;
    };


    var upload_info = function( opt_data, back_flag){
        var use_res_id_list = [];
        var new_res_id_list = [];
        var tea_cw_file_id  = opt_data.tea_cw_file_id;
        var stu_cw_file_id  = opt_data.stu_cw_file_id;
        var issue_file_id   = opt_data.issue_file_id;
        var tea_cw_origin   = opt_data.tea_cw_origin;
        var stu_cw_origin   = opt_data.stu_cw_origin;
        var issue_origin    = opt_data.issue_origin;
        if(tea_cw_file_id > 0){
            use_res_id_list.push(tea_cw_file_id);
        }
        if(stu_cw_file_id > 0){
            use_res_id_list.push(stu_cw_file_id);
        }
        if(issue_file_id > 0){
            use_res_id_list.push(issue_file_id);
        }
        var tea_cw_url  = opt_data.tea_cw_url;
        var stu_cw_url  = opt_data.stu_cw_url;
        var issue_url   = opt_data.issue_url;
        var lesson_name = opt_data.lesson_name;
        var lesson_type = opt_data.lesson_type;
        var tea_status  = opt_data.tea_status;
        var stu_status  = opt_data.stu_status;
        var homework_status = opt_data.homework_status;
        var tea_more_cw_url = opt_data.tea_more_cw_url;
        var tea_cw_pic_flag = opt_data.tea_cw_pic_flag;

        var point_arr = (""+opt_data.lesson_intro).split("|");

        var btn_teacher_upload_id = "id_teacher_upload";
        var btn_student_upload_id = "id_student_upload";
        var btn_issue_upload_id   = "id_issue_upload";

        var tea_cw_url_list = tea_more_cw_url;
        $.do_ajax("/common/get_bucket_info",{
            is_public : 0
        },function(ret){
            var id_student = gen_upload_item(
                btn_student_upload_id,stu_status,
                "l_stu_"+opt_data.lessonid,
                function(){return stu_cw_url; },
                function(url) {stu_cw_url=url;}, ret ,function(file_name) {
                },back_flag,clear_file_id,look_pdf
            );
            var id_teacher_list      = [];
            var id_teacher_desc_list = [];
            for (var i=0;i<11;i++) {

                var gen_item = function  ( i ) {
                    var id_teacher_desc = $("<div class=\"tea_cw_ex col-md-4\" >"
                                            +"<div class=\"input-group\">"
                                            +"<input style=\"\" placeholder=\"文件说明\" />"
                                            +"</div></div>"
                                           );
                    id_teacher_desc_list.push(id_teacher_desc);
                    if (!$.isArray( tea_cw_url_list[i]) ) {
                        tea_cw_url_list[i]=["",""];
                    }
                    id_teacher_desc.find("input").val(tea_cw_url_list[i][1]);

                    var type_arr = '';
                    if(!(lesson_type>=1000 && lesson_type <2000) || lesson_type==1100){
                        if(i>0) {
                            var type_arr = ['pdf','mp3','mp4'];
                        }
                    }
                    //if(lesson_type <= 2000){
                    //    type_arr = ['mp3','mp4'];
                    //}
                    var item = gen_upload_item(
                        btn_teacher_upload_id+"_"+i,
                        !! tea_cw_url_list[i][0],
                        "l_"+i+"_"+opt_data.lessonid,
                        function(){
                            return tea_cw_url_list[i][0];
                        },function(url){
                            tea_cw_url_list[i][0] = url;
                        },ret,function(origin_file_name){
                            id_teacher_desc.find("input").val(origin_file_name);
                        },back_flag,clear_file_id,look_pdf,type_arr
                    );

                    if(tea_cw_url_list[i][3] != undefined){
                        use_res_id_list.push( parseInt(tea_cw_url_list[i][3]) );
                    }
                    item.prepend(id_teacher_desc);
                    return item;
                }
                var item=gen_item(i);
                id_teacher_list.push(item);
            }


            console.log(id_teacher_desc_list);
            var id_teacher=gen_upload_item(
                btn_teacher_upload_id,
                tea_status,
                "l_" + opt_data.lessonid,
                function(){
                    return tea_cw_url;
                },function(url) {
                    tea_cw_url=url;
                },ret,function(origin_file_name) {
                },back_flag,clear_file_id,look_pdf
            );
            var id_issue = gen_upload_item(
                btn_issue_upload_id ,homework_status, "l_hw_" + opt_data.lessonid,
                function(){return issue_url; },
                function(url){issue_url=url;},ret,function(origin_file_name) {
                },back_flag,clear_file_id,look_pdf
            );
            var id_lesson_name        = $("<input/>");
            var id_point1             = $("<input/>");
            var id_point2             = $("<input/>");
            var id_pdf_question_count = $("<input/>");
            var id_tea_cw_pic         = $("<select class='hide'/>");


            var id_change = $("<input disabled='disabled' />");
            var id_change_time = $("<input disabled='disabled' />");

            // id_teacher.append("是否启用批量平铺功能");
            id_teacher.append(id_tea_cw_pic);

            Enum_map.append_option_list("boolean",id_tea_cw_pic,true);
            id_tea_cw_pic.val(tea_cw_pic_flag);

            id_pdf_question_count.val(opt_data.pdf_question_count);
            id_lesson_name.val(lesson_name);
            id_point1.val(point_arr[0]);
            if (point_arr[1]) {
                id_point2.val(point_arr[1]);
            }

            var red_tip = '<span style="color:red;">&nbsp;*</span>';
            var arr= [
                ["----","课堂信息"],
                ["课堂标题"+red_tip,  id_lesson_name],
                ["知识点1",  id_point1],
                ["知识点2",  id_point2],
                ["----","上传课堂讲义"],
                ["授课课件"+red_tip, id_teacher],
                ["授课课件平铺状态", id_change],
                ["授课课件上传时间", id_change_time],
           ];

            $.each(id_teacher_list,function(i,item){
                if(!(lesson_type>=1000 && lesson_type <2000) || lesson_type==1100){
                    arr.push(["其他资料_"+i,item]);
                } else {
                    arr.push(["其他资料_"+(i+1),item]);
                }
            });
            arr.push(['学生讲义'+red_tip, id_student]);

            if(!(lesson_type>=1000 && lesson_type <2000) || lesson_type==1100){
                arr.push(["----","上传课堂作业"]);
                arr.push(["学生作业",id_issue]);
                arr.push(["作业题目数",id_pdf_question_count]);
            }

            $.show_key_value_table("课堂信息", arr ,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    var point1  = $.trim(id_point1.val());
                    var point2  = $.trim(id_point2.val());
                    lesson_name = $.trim(id_lesson_name.val());
                    var pdf_question_count = id_pdf_question_count.val()*1;

                    if (!point1) {
                        point1=point2;
                        point2="";
                    }

                    // if (issue_url) {
                        // check_lesson_info(id_pdf_question_count,'0');
                    // }
                    // if (lesson_type<1000) {
                        check_lesson_info(id_lesson_name,"");
                    // }

                    //检查教师讲义
                    // $('#id_teacher_upload_0').val(tea_cw_url_list[0][0]);
                    // check_lesson_info($('#id_teacher_upload_0'),'',1);

                    id_teacher.val(tea_cw_url);
                    check_lesson_info(id_teacher,'');
                    id_student.val(stu_cw_url);
                    check_lesson_info(id_student,'');
                    if ($(".false").length>0) {
                        BootstrapDialog.alert("请完善信息");
                        return false;
                    }

                    var tmp_arr=[];
                    new_res_id_list.push( parseInt(tea_cw_file_id) );
                    new_res_id_list.push( parseInt(stu_cw_file_id) );
                    new_res_id_list.push( parseInt(issue_file_id) );
                    $.each(tea_cw_url_list,function(i,tmp_url){
                        var desc=id_teacher_desc_list[i].find("input").val();

                        if (tmp_url[0]) {
                            tea_cw_url_list[i][1] = desc;
                            tmp_arr.push(tea_cw_url_list[i]);
                        }
                        if(tmp_url[2] > 1){
                            new_res_id_list.push( parseInt(tmp_url[3]) );
                        }
                    });

                    var tea_more_cw_url = JSON.stringify(tmp_arr);
                    use_res_id_list = JSON.stringify(use_res_id_list);
                    new_res_id_list = JSON.stringify(new_res_id_list);
                    tea_cw_pic_flag = id_tea_cw_pic.val();
                    $.do_ajax("/teacher_info/update_teacher_student_pdf",{
                        "lessonid"           : opt_data.lessonid,
                        "lesson_name"        : lesson_name,
                        "tea_cw_url"         : tea_cw_url,
                        "old_tea_cw_time"    : opt_data.tea_cw_upload_time,
                        "stu_cw_url"         : stu_cw_url,
                        "old_stu_cw_time"    : opt_data.stu_cw_upload_time,
                        "lesson_intro"       : point1+"|"+point2,
                        "issue_url"          : issue_url,
                        "old_issue_time"     : opt_data.issue_time,
                        "pdf_question_count" : pdf_question_count,
                        "tea_more_cw_url"    : tea_more_cw_url,
                        "tea_cw_pic_flag"    : tea_cw_pic_flag,
                        "old_tea_cw_url"     : opt_data.tea_cw_url,
                        "tea_cw_origin"      : tea_cw_origin,
                        "stu_cw_origin"      : stu_cw_origin,
                        "issue_origin"       : issue_origin,
                        "tea_cw_file_id"     : tea_cw_file_id,
                        "stu_cw_file_id"     : stu_cw_file_id,
                        "issue_file_id"      : issue_file_id,
                        "use_res_id_list"    : use_res_id_list,
                        "new_res_id_list"    : new_res_id_list,

                    });

                }
            },function(){
                id_student["onshown_init"]();
                id_teacher["onshown_init"]();
                $.each( id_teacher_list,function(i,item){
                    item["onshown_init"]();
                });
                id_issue["onshown_init"]();

                if(tea_more_cw_url[0][0]){
                    var show_flag = 0;
                    $.each(id_teacher_list,function(i,item){
                        if( i>1 && !tea_more_cw_url[i][0]){
                            if(show_flag > 0){
                                item.parent().parent().hide();
                            } else {
                                show_flag++;
                            }
                        }
                    });

                }else{
                    $.each(id_teacher_list,function(i,item){
                        if(i>1){
                            item.parent().parent().hide();
                        }
                    });
                }
                //添加上传文件新选项
                if(!(lesson_type>=1000 && lesson_type <2000) || lesson_type==1100){
                    $('#id_teacher_upload,#id_student_upload,#id_issue_upload').hover(function(){
                        $('.opt-leo-res').removeClass('unbind');
                        add_upload_select($(this),lesson_type);
                    },function(){
                        $('.opt-select-file').hover(function(){
                            $(this).show();
                        },function(){
                            $(this).hide();
                        });
                        $('.opt-select-file').hide();
                    });

                    // $('.tea_cw_ex').first().parent().parent().prev().text('教师讲义').append(red_tip);
                    $('.tea_cw_ex').first().parent().parent().prev().text('教师讲义');
                    $('.tea_cw_ex').first().parent().parent().parent().show();
                    for(var l=0; l<11;l++){
                        if(l == 0){
                            $('.opt-leo-res').removeClass('unbind');
                            $('#id_teacher_upload_0').hover(function(){
                                add_upload_select($(this),lesson_type);
                            },function(){
                                $('.opt-select-file').hover(function(){
                                    $(this).show();
                                },function(){
                                    $(this).hide();
                                });
                                $('.opt-select-file').hide();
                            });
                        } else {

                            $('#id_teacher_upload_'+l).hover(function(){
                                add_upload_select($(this),lesson_type);
                                $('.opt-leo-res').addClass('unbind');
                            },function(){
                                $('.opt-select-file').hover(function(){
                                    $(this).show();
                                },function(){
                                    $(this).hide();
                                });
                                $('.opt-select-file').hide();
                            });
                        }
                    }
                }

                $.do_ajax("/common/check_change_flag",{
                    lessonid : opt_data.lessonid,
                },function(ret){
                    var data = ret.data;
                    var change_time_str = data.create_time;
                    id_change.val(data.tea_cw_pic);
                    id_change_time.val(change_time_str);
                });


            },false,900);
        });

        var clear_file_id = function(btn_id){
            if(btn_id == 'id_teacher_upload') {
                tea_cw_file_id = 0;
                tea_cw_origin = 0;
            } else if(btn_id == 'id_student_upload'){
                stu_cw_file_id = 0;
                stu_cw_origin = 0;
            } else if (btn_id == 'id_issue_upload'){
                issue_file_id = 0;
                issue_origin = 0;
            } else {
                var n = parseInt( btn_id.slice(-1) );
                tea_cw_url_list[n][2] = 0;
                tea_cw_url_list[n][3] = 0;
            }
        }
        //这是原来的方法
        var get_pdf_info_origin = function(is_tea_flag, file_url, res_id, tea_flag){
            //console.log(file_url);
            var ret_func = function(ret){
                 if(ret.ret == 0){
                        console.log(ret.url);
                     // if(is_tea_flag>0){

                         $('.look-pdf').show();
                         $('.look-pdf-son').mousedown(function(e){
                             if(e.which == 3){
                                 return false;
                             }
                         });
                         PDFObject.embed(ret.url, ".look-pdf-son");
                         $('.look-pdf-son').css({'width':'120%','height':'120%','margin':'-10%'});
                     // } else {
                     //     $.wopen(ret.url);
                     // }

                 } else {
                    BootstrapDialog.alert(ret.info);
                }
            }

            if(is_tea_flag > 0){
                do_ajax('/teacher_info/tea_look_resource', {'tea_res_id':res_id,'tea_flag':tea_flag}, ret_func);
            } else {
                do_ajax('/teacher_info/get_pdf_download_url_new',{'file_url':file_url},ret_func);
            }
        };

        var get_pdf_info = function(is_tea_flag, file_url, res_id, tea_flag){
            //console.log(file_url);
            var ret_func = function(ret){
                 if(ret.ret == 0){
                     if( ret.url.toLowerCase().indexOf(".mp4") > 0 || ret.url.toLowerCase().indexOf(".mp3") > 0){
                        newTab = window.open('about:blank');
                        newTab.location.href = ret.url;
                    }else{
                        var arr_url = ret.url.split("?");
                        var pdf = GetUrlRelativePath(ret.url);
                        var app = arr_url[1];
                        var pdf_name = pdf.split(".");
                        pdf_name = pdf_name[0];
                        var type = 0;
                        if(ret.url.indexOf("7tszue.com2.z0.glb.qiniucdn.com")!=-1){
                            type = 4;
                        }   
                        if(ret.url.indexOf("ebtest.qiniudn.com")!=-1){
                            type = 3;
                        }
                        if(ret.url.indexOf("teacher-doc.leo1v1.com")!=-1){
                            type = 2;
                        }
                        $.wopen("/teacher_info/look?"+app+"&url="+pdf_name+"&type="+type);
                        return false;
                    }
                 } else {
                    BootstrapDialog.alert(ret.info);
                }
            }

            if(is_tea_flag > 0){
                do_ajax('/teacher_info/tea_look_resource', {'tea_res_id':res_id,'tea_flag':tea_flag}, ret_func);
            } else {
                do_ajax('/teacher_info/get_pdf_download_url_new',{'file_url':file_url},ret_func);
            }
        };

        function GetUrlRelativePath(url)
    　　 {
    　　　　var arrUrl = url.split("//");

    　　　　var start = arrUrl[1].indexOf("/");
    　　　　var relUrl = arrUrl[1].substring(start);

    　　　　if(relUrl.indexOf("?") != -1){
    　　　　　　relUrl = relUrl.split("?")[0];
    　　　　}
    　　　　return relUrl;
    　　 }


        var get_resource_type_show = function(val){
            //1对1精品课程
            if(val == 1){
                $('.leo-tag_two span').text('春暑秋寒');
                $('.leo-tag_two select').empty();
                Enum_map.append_option_list("resource_season",$('.leo-tag_two select'));
                $('.leo-tag_two').show();

                $('.leo-tag_five span').text('难度类型');
                $('.leo-tag_five select').empty();
                Enum_map.append_option_list("resource_diff_level",$('.leo-tag_five select'));
                $('.leo-tag_five').show();

                $('.leo-tag_three').hide();
                $('.leo-tag_three select').val(-1);

                var leo_sub = $('.leo-subject select').val();
                var leo_gra = $('.leo-grade select').val();

                get_sub_grade_tag(leo_sub, leo_gra,-1,1,-1,$('.leo-tag_four select') );
                $('.leo-tag_four').show();

            }

            //标准试听课
            if(val == 3){
                $('.leo-tag_two span').text('试听类型');
                $('.leo-tag_two select').empty();
                Enum_map.append_option_list("resource_free",$('.leo-tag_two select'));
                $('.leo-tag_two').show();

                $('.leo-tag_three span').text('难度类型');
                $('.leo-tag_three select').empty();
                Enum_map.append_option_list("resource_diff_level",$('.leo-tag_three select'));
                $('.leo-tag_three').show();

                $('.leo-tag_five').hide();
                $('.leo-tag_five select').val(-1);

                var leo_sub = $('.leo-subject select').val();
                var leo_gra = $('.leo-grade select').val();

                get_sub_grade_tag(leo_sub, leo_gra,-1,3,-1,$('.leo-tag_four select') );
                $('.leo-tag_four').show();

            
            }

            //电子教材
            if(val == 5){
                $('.leo-tag_two').hide();
                $('.leo-tag_two select').val(-1);

                $('.leo-tag_three select').empty();
                $('.leo-tag_three').hide();
                $('.leo-tag_three select').val(-1);

                $('.leo-tag_four select').empty();
                $('.leo-tag_four').hide();
                $('.leo-tag_four select').val(-1);

                $('.leo-tag_five span').text('上下册');
                $('.leo-tag_five select').empty();
                Enum_map.append_option_list("resource_volume",$('.leo-tag_five select'));
                $('.leo-tag_five').show();
            }

            //试卷库
            if( val == 6 ){

                $('.leo-tag_two span').text('年份');
                $('.leo-tag_two select').empty();
                Enum_map.append_option_list("resource_year",$('.leo-tag_two select'));
                $('.leo-tag_two').show();

                $('.leo-tag_three select').empty();
                $('.leo-tag_three').hide();
                $('.leo-tag_three select').val(-1);

                $('.leo-tag_four select').empty();
                $('.leo-tag_four').hide();
                $('.leo-tag_four select').val(-1);

                $('.leo-tag_five span').text('上下册');
                $('.leo-tag_five select').empty();
                Enum_map.append_option_list("resource_volume",$('.leo-tag_five select'));
                $('.leo-tag_five').show();
            }
            console.log('get_resource_type_show');
        }

        var get_tag_by_select = function(){
            $('.leo-subject select,.leo-grade select,.leo-tag_one select,.leo-tag_two select').change(function(){
                if( $('.leo-resource_type select').val()  == 1 ){
                    console.log('1对1精品课程查看标签');

                    var leo_sub = $('.leo-subject select').val();
                    var leo_gra = $('.leo-grade select').val();
                    var leo_book = $('.leo-tag_one select').val();
                    var leo_season = $('.leo-tag_two select').val();
                    get_sub_grade_tag(leo_sub, leo_gra,leo_book,1,leo_season,$('.leo-tag_four select') );

                }

                if( $('.leo-resource_type select').val()  == 3 ){
                    console.log('标准试听课查看标签');

                    var leo_sub = $('.leo-subject select').val();
                    var leo_gra = $('.leo-grade select').val();
                    var leo_book = $('.leo-tag_one select').val();
                    get_sub_grade_tag(leo_sub, leo_gra,leo_book,3,-1,$('.leo-tag_four select') );
                }
                
            });

        }

        var get_public_tag_show = function(res_type_list,tea_sub_info,tea_gra_info,book_info,resource,subject,grade,book){
            Enum_map.append_option_list("resource_type",$('.leo-resource_type select'),true,res_type_list);
            Enum_map.append_option_list("subject",$('.leo-subject select'), true, tea_sub_info);
            Enum_map.append_option_list("grade",$('.leo-grade select'), true, tea_gra_info);
            Enum_map.append_option_list("region_version",$('.leo-tag_one select'), false, book_info);

            if( resource > 0 ){
                console.log('资源：'+resource);
                $('.leo-resource_type select').val(resource);
            }
            if( subject > 0 ){
                 console.log('科目：'+subject);
                $('.leo-subject select').val(subject);
            }
            if( grade > 0 ){
                console.log('年级：'+grade);
                $('.leo-grade select').val(grade);
            }
            if( book > 0 ){
                console.log('教材：'+book);
                $('.leo-tag_one select').val(book);
            }
        }

        var look_pdf = function(btn_id){
            //console.log(btn_id);
            if(btn_id == 'id_teacher_upload') {
                get_pdf_info(tea_cw_origin,tea_cw_url,tea_cw_file_id,tea_cw_origin);
            } else if(btn_id == 'id_student_upload'){
                get_pdf_info(stu_cw_origin, stu_cw_url, stu_cw_file_id,stu_cw_origin);
            } else if (btn_id == 'id_issue_upload'){
                get_pdf_info(issue_origin, issue_url, issue_file_id,issue_origin);
            } else {
                var n = parseInt( btn_id.slice(-1) );
                get_pdf_info(tea_cw_url_list[n][2],tea_cw_url_list[n][0], tea_cw_url_list[n][3],tea_cw_url_list[n][2]);
            }
        }

        var upload_my_collect = function(btn_type){
            var my_file_id = $('.warning').data('file_id');
            if(btn_type == 'id_teacher_upload'){
                tea_cw_url = $('.warning').data('link');
                if( my_file_id > 0 ) {
                    tea_cw_origin  = 2;
                    tea_cw_file_id = my_file_id;
                } else {
                    tea_cw_origin  = 1;
                }
            } else if (btn_type == 'id_student_upload'){
                stu_cw_url = $('.warning').data('link');
                if( my_file_id > 0 ) {
                    stu_cw_origin  = 2;
                    stu_cw_file_id = my_file_id;
                } else {
                    stu_cw_origin  = 1;
                }
            } else if (btn_type == 'id_issue_upload') {
                issue_url = $('.warning').data('link');
                if( my_file_id > 0 ) {
                    issue_origin  = 2;
                    issue_file_id = my_file_id;
                } else {
                    issue_origin  = 1;
                }

            } else {

                var n = parseInt( btn_type.slice(-1) );
                tea_cw_url_list[n][0] = $('.warning').data('link');
                if( my_file_id >0 ){//收藏文件
                    tea_cw_url_list[n][2] = 2;
                    tea_cw_url_list[n][3] = my_file_id;
                } else {//自己上传
                    tea_cw_url_list[n][2] = 1;
                }

                var file_detail = $('.warning').children().first().text()+'.'+$('.warning').children().eq(2).text();
                $('.tea_cw_ex input').eq(n).val( file_detail );
                $('.tea_cw_ex').eq(n).parent().parent().parent().show();
                $('.tea_cw_ex').eq(n).parent().parent().parent().next().show();

            }
            $('#'+btn_type).removeClass('btn-warning').addClass('btn-primary');
            $('#'+btn_type).text('重传');
            $('#'+btn_type).parent().nextAll().show();

        }

        var add_upload_select = function(obj, num_flag){
            var X = obj.offset().top;
            var Y = obj.offset().left;
            var H = obj.outerHeight();
            var top = X+H;
            $('.opt-select-file').css({'top':top,'left':Y});
            $('.opt-select-file').show();
            $('.opt-local').unbind('click');
            $('.opt-local').on('click', function(){
                //console.log(obj);
                obj.click();
            });
            if(num_flag == 1100){
                $('.modal-dialog').mouseenter(function(){
                    $('.opt-my-res').parent().hide();
                });
                $('.opt-my-res,.opt-leo-res').attr('disabled', true);
                $('.opt-my-res,.opt-leo-res').css({'background': '#aaa', 'opacity':1});
            } else {
                $('.opt-my-res,.opt-leo-res').attr('disabled', false);
                $('.opt-my-res,.opt-leo-res').css('background','#fff');
                $('.opt-my-res,.opt-leo-res').attr('upload_id', obj.attr('id'));
            }
        }
        var ret_data = {},book_info = [],tea_sub_info = [], tea_gra_info = [], res_type_list = [];
        var dlg_tr = {};
        var get_res = function(ajax_url,opt_type,btn_type,dir_id,data){
            var args_ex =   {
                'is_js'  : 1,
                'dir_id' : dir_id,
            };
            var args = $.extend(data, args_ex);
            console.log(args);
            $("<div></div>").tea_select_res_ajax({
                "opt_type" :  "select", // or "list"
                "url"      :  ajax_url,
                //其他参数
                "args_ex" : args,
                //字段列表
                'field_list' :[
                    {
                    title:"文件名",
                    width:200,
                    render:function(val,item) {
                        return item.file_title;
                    }
                },{
                    title:"创建日期",
                    render:function(val,item) {
                        return item.create_time;
                    }
                },{
                    title:"文件格式",
                    class:"type-mark",
                    render:function(val,item) {
                        $(this).addClass(item.file_type);
                        return item.file_type;
                    }
                },{
                    title:"文件类型",
                    class:"type-mark",
                    render:function(val,item) {
                        return item.file_use_type_str;
                    }
                },{
                    title:"文件大小",
                    //width :50,
                    render:function(val,item) {
                        return item.file_size ;
                    }
                }] ,
                filter_list: [[{
                    title:"资源类型",
                    size_class: "col-md-6 leo-resource_type",
                    type  : "select" ,
                    'arg_name' :  "resource_type"  ,
                },{
                    title:"科目",
                    size_class: "col-md-3 leo-subject" ,
                    type  : "select" ,
                    'arg_name' :  "subject"  ,
                },{
                    title:"年级",
                    size_class: "col-md-3 leo-grade" ,
                    type  : "select" ,
                    'arg_name' :  "grade"  ,
                },{
                    title:"教材版本",
                    size_class: "col-md-4 leo-tag_one",
                    type  : "select" ,
                    'arg_name' :  "tag_one"  ,
                },{
                    title:"春暑秋寒",
                    size_class: "col-md-4 leo-tag_two",
                    type  : "select" ,
                    'arg_name' :  "tag_two"  ,
                },{
                    title:"难度类型",
                    size_class: "col-md-4 leo-tag_three",
                    type  : "select" ,
                    'arg_name' :  "tag_three"  ,
                },{
                    title:"学科化标签",
                    size_class: "col-md-4 leo-tag_four",
                    type  : "select" ,
                    'arg_name' :  "tag_four"  ,
                },{
                    title:"上下册",
                    size_class: "col-md-4 leo-tag_five",
                    type  : "select" ,
                    'arg_name' :  "tag_five"  ,
                }]] ,
                "auto_close"       : true,
                //选择
                "onChange"         : null,
                //加载数据后，其它的设置
                "onLoadData"       : function(dlg, ret){
                    book_info     = [];
                    //科目类型
                    tea_sub_info  = [];
                    //年级类型
                    tea_gra_info  = [];
                    //资源类型
                    res_type_list = [];

                    dlg_tr = ret.crumbs;

                    if(ret.type_list!=undefined){
                        var res_type_arr = ret.type_list.split(',');
                        $.each($(res_type_arr),function(i,val){
                            res_type_list.push(parseInt(val));
                        });
                    }               

                    if(ret.tea_sub!=undefined){
                        var tea_sub_arr = ret.tea_sub.split(',');
                        $.each($(tea_sub_arr),function(i,val){
                            tea_sub_info.push(parseInt(val));
                        });
                    }             

                    if(ret.tea_gra!=undefined){
                        var tea_gra_arr = ret.tea_gra.split(',');
                        $.each($(tea_gra_arr),function(i,val){
                            tea_gra_info.push(parseInt(val));
                        });
                    }             

                    if(ret.book!=undefined){
                        var book_arr = ret.book.split(',');
                        $.each($(book_arr),function(i,val){
                            book_info.push(parseInt(val));
                        });
                    }                

                    console.log(1);
                    $('.leo-resource_type select,.leo-subject select,.leo-grade select,.leo-tag_one select').empty();
                    console.log(2);
                    
                    var resource =  ret.resource_type > 0 ? ret.resource_type : res_type_list[0];
                    var subject =  ret.subject > 0  ? ret.subject : tea_sub_info[0];
                    var grade = ret.grade != undefined ? ret.grade : tea_gra_info[0];
                    var book = ret.tag_one != undefined ? ret.tag_one : book_info[0];
                    args.resource_type = resource;
                    args.subject = subject;
                    args.grade = grade;
                    args.tag_one = book;
                    get_public_tag_show(res_type_list,tea_sub_info,tea_gra_info,book_info,resource,subject,grade,book);

                },"onshown" : function(dlg){

                    if(opt_type == 'my'){
                        
                        $('.my-mark').empty();
                        var cru_str = '<div class="col-xs-12">';
                        $.each($(dlg_tr), function(i,val){
                            cru_str = cru_str + '<a class="opt-dir" data-id='+val.dir_id+' >'+val.name+'</a>&nbsp;/&nbsp;';
                        });
                        cru_str = cru_str + '</div>';
                        $('.my-mark').append(cru_str);
                        $('.my-mark a').css('cursor','pointer');
                        $('.my-mark a').on('click',function(){
                            dlg.$modalHeader.find('.close').click()
                            get_res(ajax_url, opt_type, btn_type,$(this).data('id'),{});
                        });
                        $('.tr_mark').each(function(){
                            $(this).on('click', function(){
                                if( $(this).children().eq(2).text() == '文件夹' ){
                                    dlg.$modalHeader.find('.close').click()
                                    get_res(ajax_url, opt_type, btn_type, $(this).data('id'),{});
                                }
                            });
                        });
                    } else {
                        get_public_tag_show(res_type_list,tea_sub_info,tea_gra_info,book_info,args.resource_type,args.subject,args.grade,args.tag_one);
                        
                        get_resource_type_show(args.resource_type);

                        get_tag_by_select();

                        $('.leo-resource_type select').change(function(){
                            console.log('变动'+$(this).val());
                            get_resource_type_show($(this).val());                          
                        });
                    }
                },
                "custom"           : function(){
                    if(opt_type == 'leo'){//三个绑定
                        var res_id = $('.warning').data('res_id');
                        $.do_ajax("/teacher_info/get_res_files_js",{
                            "res_id": res_id
                        },function(ret){
                            if(ret.ret==0){
                                var file_obj = ret.data;
                                tea_cw_origin = 3;
                                stu_cw_origin = 3;
                                issue_origin = 3;

                                console.log(ret);
                                if( file_obj[0]['resource_type'] == 1 ||  file_obj[0]['resource_type'] == 3 ){
                                    $.each($(file_obj), function(i,val){
                                        if(val.file_use_type == 0){
                                            tea_cw_url = val.file_link;
                                            tea_cw_file_id = val.file_id;
                                        } else if (val.file_use_type == 1){
                                            //教师讲义
                                            tea_cw_url_list[0][0] = val.file_link;
                                            tea_cw_url_list[0][1] = $('.tea_cw_ex input').first().val();
                                            tea_cw_url_list[0][2] = 3;
                                            tea_cw_url_list[0][3] = val.file_id;
                                            $('#id_teacher_upload_0').removeClass('btn-warning').addClass('btn-primary');
                                            $('#id_teacher_upload_0').text('重传');
                                            $('#id_teacher_upload_0').parent().nextAll().show();
                                            $('.tea_cw_ex input').first().val(val.file_title+'.'+val.file_type);
                                        } else if (val.file_use_type == 2){
                                            stu_cw_url = val.file_link;
                                            issue_url = val.file_link;
                                            stu_cw_file_id = val.file_id;
                                            issue_file_id = val.file_id;
                                        } else if(val.file_use_type == 3){
                                            var num = parseInt( val.ex_num);
                                            tea_cw_url_list[num][0] = val.file_link;
                                            tea_cw_url_list[num][1] = $('.tea_cw_ex input').first().val();
                                            tea_cw_url_list[num][2] = 3;
                                            tea_cw_url_list[num][3] = val.file_id;
                                            $('#id_teacher_upload_'+num).removeClass('btn-warning').addClass('btn-primary');
                                            $('#id_teacher_upload_'+num).text('重传');
                                            $('#id_teacher_upload_'+num).parent().nextAll().show();
                                            $('.tea_cw_ex input').eq(num).val(val.file_title+'.'+val.file_type);
                                            $('.tea_cw_ex').eq(num).parent().parent().parent().show();
                                            $('.tea_cw_ex').eq(num).parent().parent().parent().next().show();

                                        }
                                    });
                                    $('#id_teacher_upload,#id_student_upload,#id_issue_upload').removeClass('btn-warning').addClass('btn-primary');
                                    $('#id_teacher_upload,#id_student_upload,#id_issue_upload').text('重传');
                                    $('#id_teacher_upload,#id_student_upload,#id_issue_upload').parent().nextAll().show();

                                }

                                if( file_obj[0]['resource_type'] == 5 ||  file_obj[0]['resource_type'] == 6 ){
                                    console.log('按钮'+btn_type);
                                    upload_my_collect(btn_type);
                                }

                            } else {
                                BootstrapDialog.alert(ret.info);
                            }
                        });

                    } else if(opt_type == 'my'){//教师自己的资料库

                        upload_my_collect(btn_type);

                    } else if(opt_type == 'leo_one'){//额外的讲义

                        var n = parseInt( btn_type.slice(-1) );
                        tea_cw_url_list[n][0] = $('.warning').data('link');
                        if($('.warning').data('res_id') != undefined){//资料库
                            tea_cw_url_list[n][2] = 3;
                            tea_cw_url_list[n][3] = $('.warning').data('file_id');
                        } else if($('.warning').data('file_id') > 0){//收藏
                            tea_cw_url_list[n][2] = 2;
                            tea_cw_url_list[n][3] = $('.warning').data('file_id');
                        } else {//自己的资料库
                            tea_cw_url_list[n][2] = 1;
                        }
                        var file_detail = $('.warning').children().first().text()+'.'+$('.warning').children().eq(2).text();
                        $('.tea_cw_ex input').eq(n).val( file_detail );
                        $('.tea_cw_ex').eq(n).parent().parent().parent().show();
                        $('.tea_cw_ex').eq(n).parent().parent().parent().next().show();

                        $('#'+btn_type).removeClass('btn-warning').addClass('btn-primary');
                        $('#'+btn_type).text('重传');
                        $('#'+btn_type).parent().nextAll().show();
                    }

                }
            });
        };

        $('.opt-leo-res,.opt-my-res').unbind('click');
        $('.opt-leo-res').on('click',function(){
            //console.log(search_args);
            var data = {
                'subject':search_args.subject,
                'grade' : search_args.grade,
                
            };
            if($(this).hasClass('unbind')){
                get_res('/teacher_info/get_leo_resource_new', 'leo_one',$(this).attr('upload_id'),null,data);
            }else {
                get_res('/teacher_info/get_leo_resource_new', 'leo',$(this).attr('upload_id'),null,data);
            }
        });

        $('.opt-my-res').on('click',function(){
            var data = {
                'subject':search_args.subject,
                'grade' : search_args.grade,
                
            };
            get_res('/teacher_info/tea_resource', 'my',$(this).attr('upload_id'),null,data);
        });

    };

    var search_args = {};
    $(".opt-teacher-pdf").on("click", function( ){
        var opt_data = $(this).get_opt_data();
        search_args = opt_data;
        upload_info(opt_data,false);
    });


    $(".opt-teacher-pdf-back").on("click", function( ){
        var opt_data = $(this).get_opt_data();
        search_args = opt_data;
        upload_info(opt_data,false);
    });


    var isArray = function(obj) {
        return Object.prototype.toString.call(obj) === '[object Array]';
    }

    var check_lesson_info = function(obj,value,par_flag){
        var str = $.trim(obj.val());
        if(par_flag==1){
            var obj_name    = obj.parent().parent().parent().siblings().text();
            var html_notice = "<div class=\"false\">"+obj_name+"不能为空</div>";
        }else {
            var obj_name    = obj.parent().siblings().text();
            var html_notice = "<div class=\"false\">"+obj_name+"不能为空</div>";
        }
        if(str==value){
            if(!obj.parent().find("div").hasClass("false")){
                obj.parent().append(html_notice);
            }
        }else{
            if(obj.parent().find("div").hasClass("false")){
                obj.parent().find(".false").removeClass("false").addClass("hide");
            }
        }
    }

    $(".opt-get_stu_performance").each(function(){
        var opt_data=$(this).get_opt_data();
        if(!(opt_data.lesson_type<1000 || (opt_data.lesson_type==1100 && opt_data.train_type==4))){
            $(this).hide();
        }
    });

    $(".opt-get_stu_performance").on("click",function(){
        var opt_data    = $(this).get_opt_data();
        var lessonid    = opt_data.lessonid;
        var lesson_type = opt_data.lesson_type;
        var tea_comment = opt_data.tea_comment;

        if(opt_data.lesson_type==1100 && opt_data.train_type==4){
            set_stu_performance_for_seller(lessonid,tea_comment);
        }else if(lesson_type!=2 ){
            set_stu_performance(lessonid);
        }else{
            set_stu_performance_for_seller(lessonid,tea_comment);
        }

    });

    var set_stu_performance = function(lessonid){
        var $total_judgement    = $("<select></select>");
        var $homework_situation = $("<select></select>");
        var $content_grasp      = $("<select></select>");
        var $lesson_interact    = $("<select></select>");
        var $point_note_list    = $("<textarea/>");
        var $point_note_list2   = $("<textarea/>");
        var $stu_comment        = $("<textarea/>");
        var point_name          = '';
        var point_name2         = '';
        var point_stu_desc      = '';
        var point_stu_desc2     = '';

        Enum_map.append_option_list( "performance", $total_judgement,true);
        Enum_map.append_option_list( "homework_situation", $homework_situation,true);
        Enum_map.append_option_list( "content_grasp", $content_grasp,true);
        Enum_map.append_option_list( "lesson_interact", $lesson_interact,true);

        $.do_ajax("/tea_manage/new_get_stu_performance",{
            "lessonid":lessonid
        },function(result){
            if(result.total_judgement){
                $total_judgement.val(result.total_judgement);
                $homework_situation.val(result.homework_situation);
                $content_grasp.val(result.content_grasp);
                $lesson_interact.val(result.lesson_interact);
                $stu_comment.val(result.stu_comment);

                if(result.point_name[0]){
                    point_name      = result.point_name[0];
                    point_stu_desc  = result.point_stu_desc[0];
                    if(result.point_name[1]){
                        point_name2     = result.point_name[1];
                        point_stu_desc2 = result.point_stu_desc[1];
                    }
                }
                $point_note_list.val(point_stu_desc);
                $point_note_list2.val(point_stu_desc2);
            }else{
                point_name=result[0];
                if(result[1]){
                    point_name2=result[1];
                }
            }

            var arr=[
                ["课堂评价", $total_judgement] ,
                ["作业情况", $homework_situation] ,
                ["内容掌握情况", $content_grasp] ,
                ["课堂互动情况", $lesson_interact] ,
                ["总体评价", $stu_comment] ,
            ];

            if(point_name!=''){
                arr.push(["", "对知识点进行评价"]);
                arr.push([point_name, $point_note_list]);
                if(point_name2!=''){
                    arr.push([point_name2, $point_note_list2]);
                }
            }

            $.show_key_value_table("课堂评价", arr ,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    var point_stu_desc  = $point_note_list.val();
                    var point_stu_desc2 = $point_note_list2.val();
                    var stu_comment     = $stu_comment.val();
                    if(point_stu_desc=='' && point_stu_desc2=='' && stu_comment==''){
                        BootstrapDialog.alert("请填写至少一个评价内容!");
                        return ;
                    }
                    var homework_situation = $homework_situation.val();
                    var content_grasp      = $content_grasp.val();
                    var lesson_interact    = $lesson_interact.val();
                    if(homework_situation==null || content_grasp==null || lesson_interact==null){
                        BootstrapDialog.alert("请正确选择学生的情况评价!");
                        return ;
                    }

                    $.do_ajax("/tea_manage/new_set_stu_performance", {
                        "lessonid"           : lessonid,
                        "total_judgement"    : $total_judgement.val(),
                        "homework_situation" : homework_situation,
                        "content_grasp"      : content_grasp,
                        "lesson_interact"    : lesson_interact,
                        "point_name"         : point_name,
                        "point_stu_desc"     : point_stu_desc,
                        "point_name2"        : point_name2,
                        "point_stu_desc2"    : point_stu_desc2,
                        "stu_comment"        : stu_comment
                    },function(result){
                        window.location.reload();
                    });
                }
            },function(){
                if(point_name==''){
                    $point_note_list.parent().parent().hide();
                }
                if(point_name2==''){
                    $point_note_list2.parent().parent().hide();
                }
            });
        });
    };

    var set_stu_performance_for_seller = function(lessonid,tea_comment){
        var html_node = $('<div></div>').html($.dlg_get_html_by_class('dlg_set_stu_performance_for_seller'));
        html_node.find(".icheckbox_minimal").on("click",function(){
            $(this).toggleClass("checked");
        });
        click_or_change_other("stu_lesson_content",2,html_node);
        click_or_change_other("stu_advantages",1,html_node);
        click_or_change_other("stu_disadvantages",1,html_node);
        click_or_change_other("stu_lesson_plan",2,html_node);
        click_or_change_other("stu_teaching_direction",2,html_node);

        if(tea_comment>0){
            $.do_ajax('/tea_manage/get_stu_performance_for_seller',{
                "lessonid":lessonid
            },function(result){
                var stu_performance=result.data;
                if(stu_performance.stu_lesson_content=='顺利完成'){
                    html_node.find("#stu_lesson_content").val(stu_performance.stu_lesson_content);
                }else{
                    html_node.find("#stu_lesson_content").val("未顺利完成");
                    html_node.find("#stu_lesson_content_more").show();
                    html_node.find("#stu_lesson_content_more").val(stu_performance.stu_lesson_content);
                }
                html_node.find("#stu_lesson_status").val(stu_performance.stu_lesson_status);
                html_node.find("#stu_study_status").val(stu_performance.stu_study_status);

                var stu_advantages=stu_performance.stu_advantages.split(",");
                get_checkbox(stu_advantages,"stu_advantages",html_node);
                var stu_disadvantages=stu_performance.stu_disadvantages.split(",");
                get_checkbox(stu_disadvantages,"stu_disadvantages",html_node);
                var stu_lesson_plan=stu_performance.stu_lesson_plan.split(",");
                get_option(stu_lesson_plan,"stu_lesson_plan",html_node,"高");
                var stu_teaching_direction=stu_performance.stu_teaching_direction.split(",");
                get_option(stu_teaching_direction,"stu_teaching_direction",html_node,"高");

                html_node.find("#stu_advice").val(stu_performance.stu_advice);
            });
        }

        BootstrapDialog.show({
            title           : "评价",
            message         : html_node,
            closable        : true,
            closeByBackdrop : false,
            buttons         : [{
                label    : '关闭',
                cssClass : 'btn',
                action   : function(dialog) {
                    dialog.close();
                }
            },{
                label    : '确定',
                cssClass : 'btn-primary',
                action   : function(dialog){
                    var stu_lesson_content     = get_value("stu_lesson_content",2,html_node,"未顺利完成");
                    var stu_lesson_status      = html_node.find("#stu_lesson_status").val();
                    var stu_study_status       = html_node.find("#stu_study_status").val();
                    var stu_advantages         = get_value("stu_advantages",1,html_node,"其他");
                    var stu_disadvantages      = get_value("stu_disadvantages",1,html_node,"其他");
                    var stu_lesson_plan        = get_value("stu_lesson_plan",3,html_node,"其他");
                    var stu_teaching_direction = get_value("stu_teaching_direction",3,html_node,"课外知识");
                    var stu_advice             = html_node.find("#stu_advice").val();

                    if(stu_lesson_content=='' || stu_advantages=='' || stu_disadvantages=='' ||
                       stu_lesson_plan=='' || stu_teaching_direction=='' || stu_advice==''){
                        BootstrapDialog.alert("请确认所有输入框是否有输入内容!");
                        return ;
                    }
                    if(stu_advice.length<50){
                        BootstrapDialog.alert("建议字数不得少于50字!");
                        return ;
                    }

                    $.do_ajax("/tea_manage/set_stu_performance_for_seller",{
                        "lessonid"               : lessonid,
                        "stu_lesson_content"     : stu_lesson_content,
                        "stu_lesson_status"      : stu_lesson_status,
                        "stu_study_status"       : stu_study_status,
                        "stu_advantages"         : stu_advantages,
                        "stu_disadvantages"      : stu_disadvantages,
                        "stu_lesson_plan"        : stu_lesson_plan,
                        "stu_teaching_direction" : stu_teaching_direction,
                        "stu_advice"             : stu_advice
                    },function(result){
                        if(result.ret==0){
                           BootstrapDialog.alert("评价成功！");
                            setTimeout(function(){
                                window.location.reload();
                            },2000);
                        }
                    });
                }
            }]
        });
    };

    var get_value = function(name,on_type,html,check_value){
        var value = '';
        if(on_type==1){
            html.find("[name='"+name+"']").each(function(){
                if($(this).is(":checked")){
                    if($(this).val() != check_value){
                        if(value==""){
                            value += $(this).val();
                        }else{
                            value += ","+$(this).val();
                        }
                    }else{
                        var other_value = html.find("#"+name+"_more").val();
                        if(other_value != ''){
                            value+=other_value;
                        }else{
                            value=other_value;
                        }
                    }
                }
            });
        }else if(on_type==2){
            value = html.find("#"+name).val();
            if(value == check_value){
                value = html.find("#"+name+"_more").val();
            }
        }else if(on_type==3){
            value = html.find("#"+name).val();
            if(value != check_value){
                var grade = html.find("#"+name+"_grade").val()+"年级";
                var book  = html.find("#"+name+"_book").val()+"册";
                value     = html.find("#"+name).val()+","+grade+book;
            }else{
                value = html.find("#"+name+"_more").val();
            }
        }
        return value;
    };

    var get_checkbox = function(arr,name,html){
        if(arr instanceof String){
            var num=0;
            html.find("[name='"+name+"']").each(function(){
                if(arr==$(this).val()){
                    $(this).attr("checked","checked");
                }else{
                    num++;
                }
            });
            if(num>=4){
                // html.find("[name='"+name+"']:last").parent().addClass("checked");
                html.find("[name='"+name+"']:last").attr("checked","checked");
                html.find("#"+name+"_more").show();
                html.find("#"+name+"_more").val(arr);
            }
        }else{
            var length = arr.length;
            $.each(arr,function(k,v){
                var num=0;
                html.find("[name='"+name+"']").each(function(){
                    if(v==$(this).val()){
                        $(this).attr("checked","checked");
                    }else{
                        num++;
                    }
                });
                if(k==length-1 && num>4){
                    html.find("[name='"+name+"']:last").attr("checked","checked");
                    html.find("#"+name+"_more").show();
                    html.find("#"+name+"_more").val(v);
                }
            });
        }
    };

    var get_option = function(arr,name,html,check_value){
        if(arr[1]){
            html.find("#"+name).val(arr[0]);
            var grade=arr[1].substr(0,1);
            if(grade==check_value){
                grade=arr[1].substr(0,2);
            }
            html.find("#"+name+"_grade").val(grade);
            var book=arr[1].substr(-2,1);
            html.find("#"+name+"_book").val(book);
        }else{
            html.find("#"+name+" option:last").attr("selected","selected");
            html.find("#"+name+"_more").show();
            html.find("."+name+"_select").hide();
            html.find("#"+name+"_more").val(arr);
        }
    };

    var click_or_change_other = function(name,on_type,html){
        if(on_type==1){
            html.find("[name="+name+"]:last").on("click",function(){
                if($(this).is(":checked")){
                    html.find("#"+name+"_more").show();
                }else{
                    html.find("#"+name+"_more").hide();
                }
            });
        }else if(on_type==2){
            html.find("#"+name).on("change",function(){
                if($(this).val()==$("#"+name+" option:last").val()){
                    html.find("#"+name+"_more").show();
                    html.find("."+name+"_select").hide();
                }else{
                    html.find("#"+name+"_more").hide();
                    html.find("."+name+"_select").show();
                }
            });
        }
    };

    $.each($(".opt-download-test-paper"),function(i,item){
        var opt_data= $(this).get_opt_data();
        if( opt_data["stu_test_paper"] || opt_data.stu_test_paper ){
            $(this).show();
        }else{
            $(this).hide();
        }
    });

    $(".opt-download-test-paper").on("click", function () {
        var opt_data = $(this).get_opt_data();
        $.do_ajax("/user_deal/set_stu_test_paper_download", {
            "lessonid"               : opt_data.lessonid,
            "test_lesson_subject_id" : opt_data.test_lesson_subject_id
        }, function (result) {
            var stu_test_paper = opt_data.stu_test_paper;
            $.custom_show_pdf(stu_test_paper,"/teacher_info/get_pdf_download_url");
        });
    });

    $(".opt-set_lesson_time").on("click",function(){
        var data = $(this).get_opt_data();
        var id_lesson_start = $("<input>");

        id_lesson_start.datetimepicker({
            lang:'ch',
            timepicker:true,
            format:'Y-m-d H:i',
            minDate:"-1970/01/01",
            maxDate:"+1970/01/07",
        });

        var arr = [
            ["选择开始时间",id_lesson_start],
        ];

        $.show_key_value_table("设置课程时间",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/teacher_info/set_train_lesson_time",{
                    "lessonid"   : data.lessonid,
                    "start_date" : id_lesson_start.val(),
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })
            }
        });
    });
    /*
    $(".lesson_data").each(function(){
        var lesson_start = $(this).data("lesson_start");
        var lesson_type  = $(this).data("lesson_type");
        var train_type   = $(this).data("train_type");
        if(train_type==4 && lesson_type==1100 && lesson_start==0 ){
            $(this).parents("tr").addClass("bg_train_lesson");
            BootstrapDialog.alert("您有一节模拟试听课需要完成。模拟试听课程通过后，您将获得20元开课红包，赶紧开始吧。(才可以接正常试听课，老师加油！)");
        }
    });
    */


    function get_cookie(name) {
        var search = name+"=";
        var returnvalue = "";
        if(document.cookie.length>0) {
            offset = document.cookie.indexOf(search);
            if(offset!=-1) {
                offset = search.length;
                end = document.cookie.indexOf(";",offset);
                if(end==-1) {
                    end = document.cookie.length;
                }
                returnvalue = unescape(document.cookie.substring(offset,end));
            }
        }
        return returnvalue;
    }

    var num = 0;
    $(".lesson_data").each(function(){
        var lesson_start = $(this).data("lesson_start");
        var lesson_type  = $(this).data("lesson_type");
        var train_type   = $(this).data("train_type");
        
        if(train_type==4 && lesson_type==1100 && lesson_start==0 ){
            $(this).parents("tr").addClass("bg_train_lesson");
            num = num + 1;
        }
        
    });
    if(num > 0){
        if(get_cookie("popped")=='') {
            var info = "您有"+num+"节模拟试听课需要完成。模拟试听课程通过后，您将获得20元开课红包，赶紧开始吧。(才可以接正常试听课，老师加油！)"
            BootstrapDialog.alert(info);
            var date=new Date(); 
            date.setTime(date.getTime()+60*60*1000); 
            document.cookie = "popped=yes" + ';expires=' + date.toGMTString();
        }
    }

    
    $(".opt-complaint").on("click",function(){
        var data                = $(this).get_opt_data();
        var id_complaint_info   = $("<textarea>");
        var arr             = [
            ["投诉内容",id_complaint_info],
        ];

        $.show_key_value_table("教师投诉",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/teacher_info/add_complaint_info",{
                    "complaint_info"    : id_complaint_info.val(),
                    "lessonid"          : data.lessonid
                },function(result){
                    BootstrapDialog.alert(result.info);
                    dialog.close();
                    load_data();
                });
            }
        });

    });

    $(".opt-change-lesson-time").each(function(){
        var opt_data=$(this).get_opt_data();
        if(opt_data.lesson_type==1100 && opt_data.train_type==4 && opt_data.lesson_status==0 && opt_data.lesson_start>0){

        }else{
            $(this).hide();
        }
    });

    $(".opt-change-lesson-time").on("click",function(){
        var data = $(this).get_opt_data();
        var id_lesson_start = $("<input>");

        id_lesson_start.datetimepicker({
            lang:'ch',
            timepicker:true,
            format:'Y-m-d H:i',
            minDate:"-1970/01/01",
            maxDate:"+1970/01/07",
        });

        var arr = [
            ["选择开始时间",id_lesson_start],
        ];

        $.show_key_value_table("设置课程时间",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/teacher_info/change_train_lesson_time",{
                    "lessonid"   : data.lessonid,
                    "start_date" : id_lesson_start.val(),
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })
            }
        });

    });


    $('body').on('click', function(){
        $('.look-pdf').hide().children().children().empty();
    });

});
