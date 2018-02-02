/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_leo_resource.d.ts" />


function load_data(){
    if ( window["g_load_data_flag"]) {return;}

    if( $('#id_resource_type').val() == 6 && book != []) {
        //$('#id_tag_one').val(-1);
    }

    if(global_mark==0){
        sub_info = {
            order_by_str  : g_args.order_by_str,
            resource_type :	$('#id_resource_type').val(),
            subject       :	$('#id_subject').val(),
            grade         :	$('#id_grade').val(),
            tag_one       :	$('#id_tag_one').val(),
            tag_two       :	$('#id_tag_two').val(),
            tag_three     :	$('#id_tag_three').val(),
            tag_four      :	$('#id_tag_four').val(),
            tag_five      : $('#id_tag_five').val(),
        };
    } else {
        sub_info = {
            order_by_str  : g_args.order_by_str,
            resource_type :	$('#id_resource_type').val(),
            subject       :	$('#id_subject').val(),
            tag_one       :	$('#id_tag_one').val(),
            tag_two       :	$('#id_tag_two').val(),
            tag_three     :	$('#id_tag_three').val(),
            tag_four      :	$('#id_tag_four').val(),
            tag_five      : $('#id_tag_five').val(),
        };
    }
    $.reload_self_page (sub_info);

}
$(function(){
    // console.log(type_list);
    $('#id_resource_type').val(g_args.resource_type);
    $('#id_subject').val(g_args.subject);
    $('#id_grade').val(g_args.grade);
    $('#id_tag_one').val(g_args.tag_one);
    $('#id_tag_two').val(g_args.tag_two);
    $('#id_tag_three').val(g_args.tag_three);
    $('#id_tag_four').val(g_args.tag_four);
    $('#id_tag_five').val(g_args.tag_five);


    //获取学科化标签
    var get_sub_grade_tag = function(subject,grade,obj,opt_type,sel_val){
        obj.empty();
        $.ajax({
            type     : "post",
            //url      : "/resource/get_sub_grade_tag_js",
            url      : "/resource/get_sub_grade_book_tag",
            dataType : "json",
            data : {
                'resource_type' : $('#id_resource_type').val(),
                'subject' : subject,
                'grade'   : grade,
                'bookid'  : $("#id_tag_one").val(),
                'season_id': $("#id_tag_two").val(),
            } ,
            success : function(result){
                if(result.ret == 0){
                    obj.empty();
                    obj.next().remove();
                    var tag_info = result.tag;
                    //console.log(tag_info);
                    if($(tag_info).length == 0) {
                        if(opt_type == 1){
                            obj.append('<option value="-1">全部</option>');
                        } else {
                            obj.after('<p style="color:red;">请先选择科目、年级!</p>');
                        }
                    } else {
                        var tag_str = '<option value="-1">全部</option>';

                        $.each($(tag_info),function(i, val){
                            tag_str = tag_str + '<option value='+val.id
                            +'>'+val.tag+'</option>';
                        });
                        obj.append(tag_str);
                        obj.val(sel_val);
                    }
                } else {
                    alert(result.info);
                }
            }
        });
    }

    var get_province = function(obj,is_true){
        if (is_true == true){
            var pro = '';
        } else {
            var pro = '<option value="-1">[全部]</option>';
        }
        $.each(ChineseDistricts[86],function(i,val){
            pro = pro + '<option value='+i+'>'+val+'</option>'
        });
        $(obj).empty();
        $(obj).append(pro);

    }

    var get_city = function(obj,city_num, is_true){
        if (is_true == true){
            var pro = '';
        } else {
            var pro = '<option value="-1">[全部]</option>';
        }
        if(city_num > 0){
            $.each(ChineseDistricts[city_num],function(i,val){
                pro = pro + '<option value='+i+'>'+val+'</option>'
            });
        }
        $(obj).empty();
        $(obj).append(pro);
    }
    Enum_map.append_option_list("resource_type", $("#id_resource_type"),true,type_list);
    // Enum_map.append_option_list("resource_type", $("#id_resource_type"),true,[1,2,3,4,5,6]);
    Enum_map.append_option_list("subject", $("#id_subject"),true, tea_sub);
    Enum_map.append_option_list("grade", $("#id_grade"),true, tea_gra);
    if(tag_one == 'region_version'){
        Enum_map.append_option_list(tag_one, $("#id_tag_one"), false, book);
    }else if (tag_one != ''){
        Enum_map.append_option_list(tag_one, $("#id_tag_one"));
    } else {
        $("#id_tag_one").append('<option value="-1">全部</option>');
    }

    if(tag_two != ''){
        Enum_map.append_option_list(tag_two, $("#id_tag_two"));
    } else {
        $("#id_tag_two").append('<option value="-1">全部</option>');
    }
    if(tag_four != ''){
        Enum_map.append_option_list(tag_four, $("#id_tag_four"));
    } else {
        $("#id_tag_four").append('<option value="-1">全部</option>');
    }
    if(tag_five != ''){
        Enum_map.append_option_list(tag_five, $("#id_tag_five"));
    } else {
        $("#id_tag_five").append('<option value="-1">全部</option>');
    }

    if(tag_three != ''){
        Enum_map.append_option_list(tag_three, $("#id_tag_three"));
    } else {
        $("#id_tag_three").append('<option value="-1">全部</option>');
    }

    $('#id_use_type').val(g_args.use_type);
    $('#id_resource_type').val(g_args.resource_type);
    $('#id_subject').val(g_args.subject);
    $('#id_grade').val(g_args.grade);
    $('#id_tag_one').val(g_args.tag_one);
    $('#id_tag_four').val(g_args.tag_four);
    $('#id_tag_five').val(g_args.tag_five);

    if($('#id_resource_type').val() == 1 ){
         get_sub_grade_tag($('#id_subject').val(), $('#id_grade').val(), $('#id_tag_four'), 1, g_args.tag_four);
    }else if($('#id_resource_type').val() == 3 ){
        get_sub_grade_tag($('#id_subject').val(), $('#id_grade').val(), $('#id_tag_four'), 1, g_args.tag_four);
    } else if($('#id_resource_type').val() == 6) {
        get_province($('#id_tag_three'));
    } else {
        $("#id_tag_four").append('<option value="-1">全部</option>');
    }

    $('#id_tag_two').val(g_args.tag_two);
    $('#id_tag_three').val(g_args.tag_three);
    $('#id_file_title').val(g_args.file_title);

    var city_num = $('#id_tag_three').val();
    if($('#id_resource_type').val() == 6 && city_num != -1){
        get_city($('#id_tag_four'), city_num);
    }

    // $("#id_select_all").on("click",function(){
    //     $(".opt-select-item").iCheck("check");
    // });

    $("#id_select_other").on("click",function(){
        $(".opt-select-item").each(function(){
            var $item=$(this);
            if ($item.iCheckValue() ) {
                $item.iCheck("uncheck");
            }else{
                $item.iCheck("check");
            }
        } );
    });

    $('.collect').each(function(){

        var is_get = $(this).hasClass('opt-get');
        $(this).on('mouseover',function(){
            if(is_get){
                $(this).text('点击收藏');
            } else {
                $(this).text('取消收藏');
            }
        });

        $(this).on('mouseleave',function(){
            if(is_get){
                $(this).text('未收藏');
            } else {
                $(this).text('已收藏');
            }
        });

        $(this).on('click', function(){
            do_ajax('/teacher_info/do_collect',{
                'is_get'  : is_get,
                'id'      : $(this).data('id'),
                'file_id' : $(this).data('file_id'),
            },function(ret){

                if(ret.ret==0){
                    BootstrapDialog.alert("操作成功！");
                    setTimeout(function(){
                        window.location.reload();
                    },1000);

                }else{
                    alert(ret.info);
                }
            });
        });

    });

    $('.opt-look').on('click',function(){
        var id = $(this).data('file_id');
        var file_type = $(this).data('file_type');
        file_type = file_type.toLowerCase();

        var newTab;
        if( file_type == "mp4" || file_type == "mp3" ){
            newTab = window.open('about:blank');
        }

        do_ajax('/teacher_info/tea_look_resource',{'tea_res_id':id,'tea_flag':0},function(ret){
            if(ret.ret == 0){ 
                if( ret.url.toLowerCase().indexOf(".mp4") > 0 || ret.url.toLowerCase().indexOf(".mp3") > 0){
                    newTab.location.href = ret.url;
                }else{
                    //newTab.close();
                    $('.look-pdf').show();
                    $('.look-pdf-son').mousedown(function(e){
                        if(e.which == 3){
                            return false;
                        }
                    });

                    PDFObject.embed(ret.url, ".look-pdf-son");
                    $('.look-pdf-son').css({'width':'120%','height':'120%','margin':'-10%'});
                }
            } else {
                BootstrapDialog.alert(ret.info);
            }
        });
    });

    $('.opt-look_new').on('click',function(){
        var id = $(this).data('file_id');
        var file_type = $(this).data('file_type');
        file_type = file_type.toLowerCase();

        var newTab;
        if( file_type == "mp4" || file_type == "mp3" ){
            newTab = window.open('about:blank');
        }


        do_ajax('/teacher_info/tea_look_resource',{'tea_res_id':id,'tea_flag':0},function(ret){
            if(ret.ret == 0){ 
                if( ret.url.toLowerCase().indexOf(".mp4") > 0 || ret.url.toLowerCase().indexOf(".mp3") > 0){
                    newTab.location.href = ret.url;
                }else{
                    console.log(ret.url);
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
        });
    });

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

    $('body').on('click', function(){
        // $('.look-pdf').hide().empty();
        $('.look-pdf').hide().children().children().empty();
    });

    var color_id = 0,color_res = 0,color_flag = 0;
    $('.common-table tr').each(function(i){
        if(i>0){
            if($(this).data('resource_id') == color_res){
                $(this).css('background',color_id );
            } else {
                color_res = $(this).data('resource_id');
                (color_flag == 0) ? color_flag = 1: color_flag = 0;
                (color_flag == 0) ? color_id = '#e1f5fa' : color_id = '#eee';
                $(this).css('background',color_id);
            }
        }
    });


    global_mark=0;
    $('#id_subject').on("change",function(){
        global_mark=1;
    });

    $('.opt-change').set_input_change_event(load_data);

    var comment2 = $('.comment');
    //评价
    $('.opt-comment').on('click',function(){
        var file_id = $(this).data('file_id');
        var resource_type  = $(this).data('resource_type');
        var comment = comment2;
        comment.removeClass('hide');
        if( resource_type == 3 ){
            comment.find('.comment_other_listen').remove();
        }else{
            comment.find('.comment_test_listen').remove();
        }
        var arr = [
            ["merge",comment],
        ];
        $.tea_show_key_value_table("讲义评价", arr,{
            label    : '确认',
            cssClass : 'btn-info col-xs-2 margin-lr-20',
            action   : function() {
                var comment_quality = comment.find('.comment_quality').attr('score');  //质量总评
                var comment_help = comment.find('.comment_help').attr('score');  //帮助指数
                var comment_whole = comment.find('.comment_whole').attr('score'); //全面指数
                var comment_detail = comment.find('.comment_detail').attr('score'); //详细指数

                if( comment_quality == 0 || comment_help == 0 || comment_whole == 0 || comment_detail == 0){
                    BootstrapDialog.alert("质量总评、帮助指数、全面指数、详细指数是必须评价的！");
                    return false;
                }

                var con_font = comment.find("input[name='con_font']:checked").val();        //文字大小评价
                var con_spacing = comment.find("input[name='con_spacing']:checked").val();      //间距大小
                var con_img = comment.find("input[name='con_img']:checked").val();      //背景图案
                var con_type = comment.find("input[name='con_type']:checked").val();     //讲义类型
                var con_answer = comment.find("input[name='con_answer']:checked").val();     //答案程度
                var con_stu = comment.find("input[name='con_stu']:checked").val();     //适宜学生
                if( resource_type == 3 ){
                    var con_time = comment.find("input[name='con_test_time']:checked").val();    //试听课时长
                }else{
                    var con_time = comment.find("input[name='con_time']:checked").val();    //精品课时长
                }

                if(!con_font){
                    BootstrapDialog.alert("请评价文字大小！");
                    return false;
                }

                if(!con_spacing){
                    BootstrapDialog.alert("请评价间距大小！");
                    return false;
                }

                if(!con_img){
                    BootstrapDialog.alert("请评价背景图案！");
                    return false;
                }
                if(!con_type){
                    BootstrapDialog.alert("请评价讲义类型！");
                    return false;
                }
                if(!con_answer){
                    BootstrapDialog.alert("请评价答案详细程度！");
                    return false;
                }
                if(!con_stu){
                    BootstrapDialog.alert("请评价适宜学生！");
                    return false;
                }
                if(!con_time){
                    BootstrapDialog.alert("请勾选课程时长！");
                    return false;
                }

                var data = {
                    "comment_quality" : comment_quality,
                    "comment_help" : comment_help,
                    "comment_whole" : comment_whole,
                    "comment_detail" : comment_detail,
                    "con_font" : con_font,
                    "con_spacing" : con_spacing,
                    "con_img" : con_img,
                    "con_type" : con_type,
                    "con_answer" : con_answer,
                    "con_stu" : con_stu,
                    "con_time" : con_time
                };

                $.do_ajax( "/teacher_info/add_leo_resource_evalutation", {
                    "file_id"           :file_id,
                    "resource_type"     :resource_type,
                    "quality_score"     :comment_quality, //质量总评
                    "help_score"        :comment_help,    //帮助指数
                    "overall_score"     :comment_whole,   //全面指数
                    "detail_score"      :comment_detail,  //详细指数
                    "size"              :con_font,        //文字大小
                    "gap"               :con_spacing,     //间距大小
                    "bg_picture"        :con_img,         //背景图案
                    "text_type"         :con_type,        //讲义类型
                    "answer"            :con_answer,      //答案程度
                    "suit_student"      :con_stu,         //适宜学生
                    "time_length"       :con_time,        //时长
                },function(ret){
                    if(ret.ret==0){
                        BootstrapDialog.alert("评价成功!");
                        setTimeout(function(){
                            window.location.reload();
                        },1000);

                    }else{
                        alert(ret.info);
                    }
                });
            }
        },function(){
            comment2 = comment.clone(); 
        },false,800,'padding-right:10px;');

                                   
    })

    var error = $('.error');

    //报错
    $('.opt-error').on('click',function(){
        var file_id = $(this).data('file_id');
        var resource_type  = $(this).data('resource_type');

        var error = $('.error').clone();
        error.removeClass('hide');  
        var arr = [
            ["merge",error],
        ];
        error.find('.error_upload').attr({"id":"error_upload_id"});
        error.find('.error_button').attr({"id":"error_button_id"});

        error.find('.pic_change_01').parent().parent().attr({"id":"error_pic_box_01"});
        error.find('.pic_change_02').parent().parent().attr({"id":"error_pic_box_02"});
        error.find('.pic_change_03').parent().parent().attr({"id":"error_pic_box_03"});
        error.find('.pic_change_04').parent().parent().attr({"id":"error_pic_box_04"});
        error.find('.pic_change_05').parent().parent().attr({"id":"error_pic_box_05"});

        error.find('.pic_change_01').attr({"id":"pic_modify_01"});
        error.find('.pic_change_02').attr({"id":"pic_modify_02"});
        error.find('.pic_change_03').attr({"id":"pic_modify_03"});
        error.find('.pic_change_04').attr({"id":"pic_modify_04"});
        error.find('.pic_change_05').attr({"id":"pic_modify_05"});

        var timestamp = Date.parse(new Date());
        
        $.tea_show_key_value_table("讲义报错", arr,{
            label    : '确认',
            cssClass : 'btn-info col-xs-2 margin-lr-20',
            action   : function() {
                var error_type_01 = error.find('.error_type_01').val();
                var error_type_02 = error.find('.error_type_02').val();
                var error_detail = error.find('.error_detail').val();
                //上传错误的图片
                var img_arr = [];
                if(error.find('.error_pic_box:visible').length > 0){
                    error.find('.error_pic_box:visible').each(function(){
                        var img_link = $(this).find('img').attr("src");
                        if( img_link != '' && img_link != undefined){
                            img_arr.push(img_link);
                        }
                    })
                }

                if( error_type_01 < 0 && error_type_02 < 0){
                    BootstrapDialog.alert("请选择错误类型！");
                    return false;
                }

                var data = {
                    "error_type_01" : error_type_01,
                    "error_type_02" : error_type_02,
                    "error_detail"  : error_detail,
                    "img_arr" : JSON.stringify(img_arr)
                };

                console.log(data);

                $.do_ajax( "/teacher_info/add_leo_resource_error", {
                    "file_id"           :file_id,
                    "resource_type"     :resource_type,

                    "error_type"        :error_type_01,       //错误类型(资料库)
                    "sub_error_type"    :error_type_02,       //错误子类型(资料库)
                    "detail_error"      :error_detail,        //错误描述(资料库)
                    "error_url"         :JSON.stringify(img_arr), //错误文件链接(资料库)
                },function(ret){
                    if(ret.ret==0){
                        BootstrapDialog.alert("报错成功!");
                        setTimeout(function(){
                            window.location.reload();
                        },1000);

                    }else{
                        alert(ret.info);
                    }
                });
            }
        },function(){
            custom_upload(timestamp,"error_button_id","error_upload_id",1);
            custom_upload(timestamp,"pic_modify_01","error_pic_box_01",2);
            custom_upload(timestamp,"pic_modify_02","error_pic_box_02",2);
            custom_upload(timestamp,"pic_modify_03","error_pic_box_03",2);
            custom_upload(timestamp,"pic_modify_04","error_pic_box_04",2);
            custom_upload(timestamp,"pic_modify_05","error_pic_box_05",2);

        },false,700,'padding-right:10px;');

                                   
    })

    //var $img = $('.error_pic_box:hidden:eq(0)');

});

function rate(obj,oEvent){
    // 图片地址设置
    var imgSrc = '/img/x1.png'; //没有填色的星星
    var imgSrc_2 = '/img/x2.png'; //打分后有颜色的星星
    if(obj.rateFlag) return;
    var e = oEvent || window.event;
    var target = e.target || e.srcElement; 
    var imgArray = obj.getElementsByTagName("img");
    var nextObj = obj.nextElementSibling;
    //console.log(nextObj);
    var commentArray = nextObj.getElementsByTagName("span");
    for(var i=0;i<imgArray.length;i++){
        imgArray[i]._num = i;
        imgArray[i].onclick=function(){
            obj.rateFlag=true;
            var cur_num = this._num;
            for(var j=0;j<imgArray.length;j++){
                if(j<=cur_num && imgArray[j].src != imgSrc_2 ){
                    imgArray[j].src= imgSrc_2 ;
                    
                }
                if(j>cur_num && imgArray[j].src != imgSrc ){
                    imgArray[j].src= imgSrc ;
                    
                }
                if( j == cur_num ){
                    commentArray[j].style.color="black";
                }else{
                    commentArray[j].style.color="#948f8f";
                }
            }
            $(this).parent().attr({'score':cur_num + 1});
            //alert(this._num+1); //this._num+1这个数字写入到数据库中,作为评分的依据
        };
    }

    if(target.tagName=="IMG"){
        for(var j=0;j<imgArray.length;j++){
            if(j<=target._num && imgArray[j].src != imgSrc_2 ){
                imgArray[j].src= imgSrc_2 ;
                
            }
            if(j>target._num && imgArray[j].src != imgSrc ){
                imgArray[j].src= imgSrc ;
                
            }

            if( j == target._num ){
                commentArray[j].style.color="black";
            }else{
                commentArray[j].style.color="#948f8f";
            }
        }

    }

    var is_in_area = 0;
    if( $(target).attr('class') != undefined ){
        is_in_area =  $(target).attr('class').indexOf('comment_star') < 0 ? 0 : 1;
    };
    // console.log(is_in_area);
    // console.log(target.tagName);

}

function cancel(obj,oEvent){
    // 图片地址设置
    var imgSrc = '/img/x1.png'; //没有填色的星星
    var imgSrc_2 = '/img/x2.png'; //打分后有颜色的星星
    var e = oEvent || window.event;
    var target = e.target || e.srcElement; 
    var imgArray = $(target).find('.comment_star').children();
    var commentArray = $(target).find('.comment_info').children();
    if($(target).find('.comment_star').attr('score') == 0){
        for(var k=0;k<imgArray.length;k++){
            imgArray[k].src=imgSrc;
            //console.log(k);
            commentArray[k].style.color="#948f8f";
        }
    }    
}

function dele_upload(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    $(target).parent().prev().attr({'src':''});
    $(target).parents('.error_pic_box').addClass('hide');
    var cur_obj = $(target).parents('.error_pic_box').clone();
    var button = $(target).parents('.error_upload').find('.error_button');
    button.removeClass('hide');
    $(target).parents('.error_pic_box').remove();
    button.before(cur_obj);
    
}

function get_err_sec(val){
    var $options;
    var num = parseInt(val);
    switch(num)
    {
    case -1:
        $options  = $.trim($(".err_choose").clone().html());
        break;
    case 0:
        $options  = $.trim($(".err_knowledge").clone().html());
        break;
    case 1:
        $options  = $.trim($(".err_question_answer").clone().html());
        break;
    case 2:
        $options  = $.trim($(".err_code").clone().html());
        break;
    case 3:
        $options  = $.trim($(".err_content").clone().html());
        break;
    case 4:
        $options  = $.trim($(".err_whole").clone().html());
        break;
    case 5:
        $options  = $.trim($(".err_pic").clone().html());
        break;
    case 6:
        $options  = $.trim($(".err_font").clone().html());
        break;
    case 7:
        $options  = $.trim($(".err_difficult").clone().html());
        break;

    default:
        $options  = $.trim($(".err_knowledge").clone().html());
    }
    //console.log($options);

    $(".error_type_02").html($options);
}

function custom_upload(new_flag,btn_id,containerid,obj){

    var domain = 'http://7u2f5q.com2.z0.glb.qiniucdn.com/';
    //var domain =  "http://teacher-doc.qiniudn.com/";
    var qi_niu = ['Qiniu_'+new_flag];
    qi_niu[0] = new QiniuJsSDK();
    //console.log(qi_niu[0]);
    var token = "yPmhHAZNeHlKndKBLvhwV3fw4pzNBVvGNU5ne6Px:SNPUFt8-K2eSlkX70Nb8vzA7lo0=:eyJzY29wZSI6InRlYWNoZXItZG9jIiwiZGVhZGxpbmUiOjE1MTczOTIzOTR9";

    var uploader = qi_niu[0].uploader({
    
        runtimes: 'html5, flash, html4',
        browse_button: btn_id , //choose files id
        uptoken_url: '/upload/pub_token',
        //uptoken: token,
        domain: domain,
        container: containerid,
        drop_element: containerid,
        max_file_size: '4mb',
        dragdrop: true,
        flash_swf_url: '/js/qiniu/plupload/Moxie.swf',
        chunk_size: '4mb',
        filters: {
            mime_types: [
                {title: "仅支持jpeg,jpg,png,gif格式图片", extensions: "jpg,jpeg,png,gif"}
            ]
        },
        unique_names: false,
        save_key: false,
        auto_start: true,
        init: {
            'FilesAdded': function(up, files) {

            },
            'BeforeUpload': function(up, file) {
                if( obj == 1 && $("#"+containerid).find('.error_pic_box:hidden').length == 0 ){
                    BootstrapDialog.alert("最多只能传5张图片！");
                    return false;
                }
                console.log('before uplaod the file');
            },
            'UploadProgress': function(up,file) {

            },
            'UploadComplete': function() {
                // $("#"+btn_id).siblings('div').remove();
                console.log('success');
            },
            'FileUploaded' : function(up, file, info) {
                console.log(file);
                console.log(info);
                var imgSrc = domain + JSON.parse(info.response).key;
                if(obj == 1){
                    var $img_box = $("#"+containerid).find('.error_pic_box:hidden:eq(0)');                   
                    $img_box.find("img").attr("src", imgSrc);
                    $img_box.removeClass("hide");
                    if( $("#"+containerid).find('.error_pic_box:hidden').length == 0){
                        $("#"+containerid).find('.error_button').addClass('hide'); 
                    }
                }else{
                    $('#'+containerid).find('img').attr("src", imgSrc);
                }
            },
            'Error': function(up, err, errTip) {
                console.log('Things below are from Error');
                console.log(up);
                console.log(err);
                console.log(errTip);
            },
            'Key': function(up, file) {
                var key = "";
                var time = (new Date()).valueOf();
                var match = file.name.match(/.*\.(.*)?/);
                this.origin_file_name=file.name;
                var file_name=$.md5(file.name) +time +'.' + match[1];
                var suff = match[1].toLowerCase();         
                console.log('gen file_name:'+file_name);
                return file_name;

            }
        }
    });
}

