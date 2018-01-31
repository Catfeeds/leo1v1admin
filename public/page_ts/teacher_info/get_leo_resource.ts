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

    //评价
    $('.opt-comment').on('click',function(){
        var comment = $('.comment').clone();
        comment.removeClass('hide');
        if( resource_type == 3 ){
            comment.find('.comment_other_listen').remove();
        }else{
            comment.find('.comment_test_listen').remove();
        }
        var arr = [
            ["merge","评价"],
            ["merge",comment],
        ];
        $.tea_show_key_value_table("讲义评价", arr,{
            label    : '确认',
            cssClass : 'btn-info col-xs-2 margin-lr-20',
            action   : function() {
                
            }
        },'',false,800,'padding-right:60px;');

                                   
    })

    var error = $('.error');

    //报错
    $('.opt-error').on('click',function(){
        error.removeClass('hide');  
        var arr = [
            ["merge","报错"],
            ["merge",error],
        ];
        $.tea_show_key_value_table("讲义报错", arr,{
            label    : '确认',
            cssClass : 'btn-info col-xs-2 margin-lr-20',
            action   : function() {
                
            }
        },null,false,700,'padding-right:60px;');

                                   
    })

    custom_upload($('.error_button')[0],$('.error_pic_box')[0],1);

    custom_upload($('.pic_change_01')[0],$('.error_pic_box')[0],$(".pic_change_01"));
    custom_upload($('.pic_change_02')[0],$('.error_pic_box')[0],$(".pic_change_02"));
    custom_upload($('.pic_change_03')[0],$('.error_pic_box')[0],$(".pic_change_03"));
    custom_upload($('.pic_change_04')[0],$('.error_pic_box')[0],$(".pic_change_04"));
    custom_upload($('.pic_change_05')[0],$('.error_pic_box')[0],$(".pic_change_05"));
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

function get_err_sec(val){
    var $options;
    var num = parseInt(val);
    switch(num)
    {
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

    default:
        $options  = $.trim($(".err_knowledge").clone().html());
    }
    //console.log($options);
    $(".error_type_02").html($options);

}

function custom_upload(btn_id,containerid,obj){
    console.log(1);
    var $img = $('.error_pic_box:hidden:eq(0)');
    console.log($img);
    if($img.length == 0){
        return false;
    }
    var domain = "http://file-store.leo1v1.com/";
    $.do_ajax("/teacher_info/get_upload_token",{
        "dir" : ""
    },function(resp){
        var upload_token=resp.upload_token;
        var pre_dir= resp.pre_dir;
        var uploader = Qiniu.uploader({

            runtimes: 'html5, flash, html4',
            browse_button: btn_id , //choose files id
            //uptoken_url: uptoken_url,
            uptoken:  upload_token ,
            domain: domain,
            container: containerid,
            drop_element: containerid,
            max_file_size: '100mb',
            dragdrop: true,
            flash_swf_url: '/js/qiniu/plupload/Moxie.swf',
            chunk_size: '4mb',
            unique_names: false,
            save_key: false,
            auto_start: true,
            init: {
                'FilesAdded': function(up, files) {

                    BootstrapDialog.show({
                        title: '上传进度',
                        message: $('<div class="progress progress-sm active">' +
                                   '<div id="id_upload_process_info" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">'+'<span class="sr-only">0% Complete</span>  </div> </div>'),
                    });

                    plupload.each(files, function(file) {
                        var progress = new FileProgress(file, 'process_info');
                        console.log('waiting...');
                    });
                },
                'BeforeUpload': function(up, file) {
                    console.log('before uplaod the file');
                },
                'UploadProgress': function(up,file) {
                    var progress = new FileProgress(file, 'process_info');
                    progress.setProgress(file.percent + "%", up.total.bytesPerSec, btn_id);
                    console.log('upload progress');
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
                        $img.find("img").attr("src", imgSrc);
                        $img.removeClass("hide");
                    }else{
                        obj.parent().prev().attr("src", imgSrc);
                    }
                },
                'Error': function(up, err, errTip) {
                    console.log('Things below are from Error');
                    console.log(up);
                    console.log(err);
                    console.log(errTip);
                },
                'Key': function(up, file) {
                    var time = (new Date()).valueOf();
                    var key= pre_dir+time;
                    console.log(key);
                    return key;
                }
            }
        });


    } );

}

