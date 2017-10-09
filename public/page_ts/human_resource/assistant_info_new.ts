/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-assistant_info_new.d.ts" />

$(function(){
  $("#id_add_birth").datetimepicker({
    lang:'ch',
    timepicker:false,
    format:'Y-m-d'
  });

  $("#id_edit_birth").datetimepicker({
    lang:'ch',
    timepicker:false,
    format:'Y-m-d'
  });



    function load_data(){
        $.reload_self_page ( {
      is_part_time:	$('#id_is_part_time').val(),
      rate_score:	$('#id_rate_score').val(),
      assistantid:	$('#id_assistantid').val()

        });
    }

    Enum_map.append_option_list( "star_level", $("#id_rate_score"));
    Enum_map.append_option_list( "assistant_type", $("#id_is_part_time"));

  $('#id_is_part_time').val(g_args.is_part_time);
  $('#id_rate_score').val(g_args.rate_score);

    $("#id_assistantid").val(g_args.assistantid);
    $("#id_assistantid").admin_select_user({
        "type"   : "assistant",
        "onChange": function(){
            load_data();
        }
    });

  $(".hr_change").on("change", function(){
    var is_part_time =  $("#id_is_part_time").val();
    var score = $("#id_rate_score").val();
    load_data();
  });
    $(".id_add_ass").on("click", function(){
        var opt_data=$(this).get_opt_data();
        var $ass_name=$("<input/>");
        var $gender=$("<select/>");
        var $birth=$("<input/>");
        var $work_year      = $("<input/>");
        var $phone          = $("<input/>");
        var $email          = $("<input/>");
        var $assistant_type = $("<select/>");
        var $school         = $("<input/>");

        Enum_map.append_option_list("gender", $gender);
        Enum_map.append_option_list("assistant_type", $assistant_type);
        $birth.datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Y-m-d',
            "onChangeDateTime" : function() {
            }

        });
        var birth=""+opt_data.birth;
        birth= birth.substr(0,4)+"-"+ birth.substr(4,2) + "-"+ birth.substr(6,2) ;


        var arr=[
            ["姓名",$ass_name] ,
            ["性别", $gender],
            ["生日", $birth],
            ["工作年限", $work_year],
            ["电话", $phone],
            ["email", $email],
            ["类型", $assistant_type],
            ["学校", $school ],
        ];
        $.show_key_value_table("新增助教", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var birth=""+ $birth.val();

                $.do_ajax ('/human_resource/add_ass_new', {
                    'ass_nick': $ass_name.val(),
                    'gender': $gender.val(),
                    'birth': birth,
                    'work_year': $work_year.val(),
                    'phone': $phone.val(),
                    'email': $email.val(),
                    'assistant_type': $assistant_type.val(),
                    'school': $school.val(),

                });
            }
        });
    });

  $(".done_t").on("click", function(){
    var assistantid = $(this).parent().data("assistantid");
        BootstrapDialog.show({
            title: '系统提示',
            message : '确认从助教档案中删除该助教及其相关信息',
            closable: true,
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-danger',
                    action: function(dialog) {
                    $.ajax({
                      type     :"post",
                      url      :"/human_resource/delete_teacher",
                      dataType :"json",
                      data     :{'teacherid':assistantid,'teacher_type':1},
                      success  : function(result){
                                if(result['ret'] != 0){
                                    alert(result['info']);
                                }else{
                                    window.location.reload();
                                }
                      }
                    });
                        dialog.close();
                    }
                },
                {
                    label: '返回',
                    cssClass: 'btn-primary',
                    action: function(dialog) {
                        dialog.close();
                    }
                },

            ]
        });
  });

  $("#id_delete_ass").on("click", function(){
    var assistantid= $(this).data("assistantid");
    $.ajax({
      type     :"post",
      url      :"/human_resource/delete_teacher",
      dataType :"json",
      data     :{'teacherid':assistantid,'teacher_type':1},
      success  : function(result){
                if(result['ret'] != 0){
                    alert(result['info']);
                }else{
                    window.location.reload();
                }
      }
    });
  });

    function get_ass_detail(assistantid){
        $.ajax({
            type     :"post",
            url      :"/human_resource/ass_detail_info",
            dataType :"json",
            data     :{'assistantid':assistantid},
            success  : function(result){
                var info = result.info;
                $("#id_detail_name").html(info.ass_nick);
                $("#id_ass_gender").html(info.gender);
                $("#id_ass_gender").attr('gender_num', info.gender_num);
                $("#id_ass_birth").html(info.birth);
                $("#id_ass_work_year").html(info.work_year);
                $("#id_detail_phone").html(info.phone);
                $("#id_ass_email").html(info.email);
                $("#id_ass_school").html(info.school);
                $("#id_ass_type").html(info.is_part_time);
                $("#id_ass_type").attr('ass_type', info.ass_type);
                $("#id_ass_score").html(info.rate_score);
                $("#id_ass_style").html(info.ass_style);
                $("#id_ass_prize").html(info.prize);
                $("#id_ass_achievement").html(info.achievement);
                $("#id_ass_base_intro").html(info.base_intro);
                $(".header_img").html(info.face);

            }
        });
        $('.teach_mesg').show().siblings('.teacher_list').hide();
    }

  $(".done_o").on("click", function(){
    var assistantid = $(this).parent().data("assistantid");
    $("#id_save_info").data("assistantid",assistantid);
        get_ass_detail(assistantid);
        $(".cont_box").hide();
  });

  $("#id_back_to_main").on("click",function(){
    $('.teach_mesg').hide();
        $('.cont_box').show();
  });

  $("#id_save_info").on("click",function(){
    var assistantid = $("#id_save_info").data("assistantid");
    var ass_nick = $("#id_edit_name").val();
    var work_year = $("#id_edit_work_year").val();
    var birth = $("#id_edit_birth").val();
    var phone = $("#id_edit_phone").val();
    var email = $("#id_edit_email").val();
    var school = $("#id_edit_school").val();
    var assistant_type = $("#tea_job").val();
    var ass_style = $("#id_edit_style").val();
    var achievement = $("#id_edit_achievement").val();
    var base_intro = $("#id_edit_base_intro").val();
    var prize = $("#id_edit_prize").val();
    var gender = $("#tea_sexy").val();
    $.ajax({
      type     :"post",
      url      :"/human_resource/update_assistant_info",
      dataType :"json",
      data     :{'assistantid':assistantid,'ass_nick':ass_nick,'gender':gender,'work_year':work_year,'birth':birth,'phone':phone,'email':email,'school':school,'assistant_type':assistant_type,'ass_style':ass_style,'achievement':achievement,'base_intro':base_intro,'prize':prize},
      success  : function(result){
                if(result['ret'] != 0){
                    alert(result['info']);
                }else{
                    window.location.reload();
                }
      }
    });
    if(gender == 1){
      $("#id_ass_gender").html('男');
    }else{
      $("#id_ass_gender").html('女');
    }

    if(assistant_type == 1){
      $("#id_ass_type").html('兼职');
    }else{
      $("#id_ass_type").html('全职');
    }
  });

    var uploader = Qiniu.uploader({
        runtime: 'html5, flash, html4',
        browse_button: 'id_upload', //choose files
        uptoken_url: '/upload/pub_token',
        domain: 'http://ebtestpub.qiniudn.com/',
        container: 'id_container',
        drop_element: 'id_container',
        max_file_size: '2mb',
        dragdrop: true,
        chunk_size: '4mb',
        unique_names: false,
        save_key: false,
        auto_start: true,
        filters: {
            mime_types : [
                {title:"image", extensions: "jpg"},
                {title:"image", extensions: "jpeg"},
                {title:"image", extensions: "png"},
                {title:"image", extensions: "bmp"},
                {title:"image", extensions: "gif"},
            ]
        },
    init: {
      'FilesAdded': function(up, files) {
        console.log('files added');
        plupload.each(files, function(file) {
                    var progress = new FileProgress(file, 'process_info');
                    console.log('waiting...');
                });
      },

      'BeforeUpload': function(up, file) {
        console.log('before uplaod the file');
        var progress = new FileProgress(file, 'process_info');
      },

      'UploadProgress': function(up,file) {
        console.log('upload progress');
        //TODO add the processing info
        var progress = new FileProgress(file, 'process_info');
                progress.setProgress(file.percent + "%", up.total.bytesPerSec);
      },

      'UploadComplete': function() {
        console.log('success');
      },

      'FileUploaded' : function(up, file, info) {
        console.log('Things below are from FileUploaded');
        var progress = new FileProgress(file, 'process_info');
          if(info.response){
               progress.setComplete(up, info.response, file);
          }else{
               progress.setComplete(up, info, file);
          }

               // progress.setComplete(up, info, file);//function(up, info, file)
      },

      'Error': function(up, err, errTip) {
        console.log('Things below are from Error');
        console.log(err);
                console.log(err.code);

                switch(err.code) {
                    case -600:
                        alert("请上传2M以内图片");
                        break;
                    default:
                        alert("上传错误,请确认图片大小在2M以内以及图片格式正确");
                }
      },
      'Key': function(up, file) {
        //generate the key
                time = (new Date()).valueOf();
        return $.md5(file.name) +time;
      }
    }

  });

    function FileProgress(file, targetID)
    {
      this.fileProgressID = file.id;
      this.file = file;
      var fileSize = plupload.formatSize(file.size).toUpperCase();
      this.fileProgressWrapper = $('#' + this.fileProgressID);
      file_size = get_file_size(file.size);
      this.setTimer(null);
    }

    FileProgress.prototype.setTimer = function(timer) {
        this.fileProgressWrapper.FP_TIMER = timer;
    };

    FileProgress.prototype.getTimer = function(timer) {
        return this.fileProgressWrapper.FP_TIMER || null;
    };

    FileProgress.prototype.setProgress = function(percentage, speed) {

        var file = this.file;
        var uploaded = file.loaded;

        var size = plupload.formatSize(uploaded).toUpperCase();
        var formatSpeed = plupload.formatSize(speed).toUpperCase();
        var file_size = get_file_size(size);

        percentage = parseInt(percentage, 10);
        if (file.status !== plupload.DONE && percentage === 100) {
            percentage = 99;
        }

    };

    FileProgress.prototype.setComplete = function(up, info, file) {
        var upload_succ = true;
      var fileTargetID = file.id;
        var res = $.parseJSON(info);
        var url;
        if (res.url) {
      // when this condition is true, I should know all the info.
      // This case may not appear a lot, that's why when it appears, the client sends a message to me.
        } else {
            var domain = up.getOption('domain');
            url = domain + encodeURI(res.key);
            var link = domain + res.key;
            console.log('Aaron Else Test: ' + info);

        //add_upload_client
            var file_name = file.name;
            var urlkey    = res.key;
            var file_md5  = res.hash;
            var size      = file.size;
            var page_num  = file.page_num;
        var assistantid = $("#id_save_info").data("assistantid");
            //TODO: MILLIONS 上传成功修改老师头像URL
            $.ajax({
              url: '/human_resource/set_assistant_face',
              type: 'POST',
              data: {'key': urlkey,'assistantid':assistantid},
          dataType: 'json',
          success: function(data) {
            if (data['ret'] == 0) {
                        alert('上传成功');
                        get_ass_detail(assistantid);
                    } else if(data['ret'] == -1) {
                        alert('上传失败，请重新上传');
                    } else {
              console.log(data);
            }
          }
            });
        }
    };

    function get_file_size(file_size)
    {
      if (file_size > 1024 && file_size < 1024 * 1024) {
        size = (file_size / 1024).toFixed(2);
        return size + ' KB';
      } else if (file_size > 1024 * 1024) {
        size = ((file_size / 1024) / 1024).toFixed(2);
        return size + ' MB';
      } else {
        return file_size;
      }
    }

    function cphone(str) {
        var re = /^1\d{10}$/;
        if (re.test(str)) {
        } else {
            alert("请正确填写手机号码");
            return;
        }
    }
    function ce_name(str){
        var re = /^([A-Za-z]+\s?)*[A-Za-z]$/;
        if(re.test(str)){
        }else{
            alert("请正确填写英文名");
            return;
        }
    }

    function cemail(str){
        var re = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
        if(re.test(str)){
        }else{
            alert("请正确填写邮箱");
            return;
        }
    }
    $("#id_modify").on("click", function(){
        var html_node = $("<div></div>").html(dlg_get_html_by_class('dlg_modify_assistant'));
        html_node.find("#id_edit_birth").datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Y-m-d'
        });
        html_node.find("#id_edit_name").val($("#id_detail_name").text());
        html_node.find("#id_edit_work_year").val($("#id_ass_work_year").text());
        html_node.find("#id_edit_birth").val($("#id_ass_birth").text());
        html_node.find("#id_edit_email").val($("#id_ass_email").text());
        html_node.find("#id_edit_school").val($("#id_ass_school").text());
      /*  html_node.find("#tea_job").children().filter(function(){
            return $(this).text().localeCompare($("#id_ass_type").text());
            }).attr("selected", true);*/
        html_node.find("#tea_job").val($("#id_ass_type").attr("ass_type"));
       // alert($("#id_ass_type").attr("ass_type"));
        html_node.find("#id_edit_style").val($("#id_ass_style").text());
        html_node.find("#id_edit_achievement").val($("#id_ass_achievement").text());
        html_node.find("#id_edit_base_intro").val($("#id_ass_base_intro").text());
        html_node.find("#id_edit_prize").val($("#id_ass_prize").text());
       /* html_node.find("#tea_sexy").children().filter(function(){
            return $(this).text().localeCompare( $("#id_ass_gender").text());
            }).attr("selected", true);*/
       html_node.find("#tea_sexy").val($("#id_ass_gender").attr("gender_num")); 


        BootstrapDialog.show({
            title: '更改助教信息',
            message : html_node,
            closable: true,
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-danger',
                    action: function(dialog) {
                    var assistantid    = $("#id_save_info").data("assistantid");
                    var ass_nick       = html_node.find("#id_edit_name").val();
                    var work_year      = html_node.find("#id_edit_work_year").val();
                    var birth          = html_node.find("#id_edit_birth").val();
                    var email          = html_node.find("#id_edit_email").val();
                    var school         = html_node.find("#id_edit_school").val();
                    var assistant_type = html_node.find("#tea_job").val();
                    var ass_style      = html_node.find("#id_edit_style").val();
                    var achievement    = html_node.find("#id_edit_achievement").val();
                    var base_intro     = html_node.find("#id_edit_base_intro").val();
                    var prize          = html_node.find("#id_edit_prize").val();
                    var gender         = html_node.find("#tea_sexy").val();
                    $.ajax({
                      type     :"post",
                      url      :"/human_resource/update_assistant_info",
                      dataType :"json",
                      data     :{'assistantid':assistantid,'ass_nick':ass_nick,'gender':gender,'work_year':work_year,'birth':birth,'email':email,'school':school,'assistant_type':assistant_type,'ass_style':ass_style,'achievement':achievement,'base_intro':base_intro,'prize':prize},
                      success  : function(result){
                                if(result['ret'] != 0){
                                    alert(result['info']);
                                }else{
                                    window.location.reload();
                                }
                      }
                    });
                        dialog.close();
                    }
                },
                {
                    label: '返回',
                    cssClass: 'btn-primary',
                    action: function(dialog) {
                        dialog.close();
                    }
                },

            ]

        });
    });

    //修改
    $(".opt-update-news").on("click",function(){
        var opt_data=$(this).get_opt_data();
        console.log(opt_data);
        var id_assistantid = $("<input/ >") ;
        var id_name        = $("<input/>") ;
        var id_birth       = $("<input/>") ;
        var id_years       = $("<input/>") ;
        var id_school      = $("<input/>") ;
        var id_phone       = $("<input/>") ;
        var id_email       = $("<input/>") ;
        var id_sex       = $("<select/>") ;
        var id_gender       = $("<select/>") ;
        var id_job     = $("<select/>") ;

        Enum_map.append_option_list("gender", id_sex);
        Enum_map.append_option_list("gender", id_gender);
        Enum_map.append_option_list("assistant_type", id_job);

        id_birth.datetimepicker({
        lang:'ch',
        timepicker:false,
        format:'Y-m-d',
            "onChangeDateTime" : function() {
            }

      });
        var birth=""+opt_data.birth;
        birth= birth.substr(0,4)+"-"+ birth.substr(4,2) + "-"+ birth.substr(6,2) ;

        id_assistantid.val(opt_data.assistantid);
        id_name.val(opt_data.nick);
        // id_birth.val(opt_data.birth);
        id_birth.val(birth);
        // id_years.val(opt_data.age);
        id_years.val(opt_data.work_year);
        id_school.val(opt_data.school);
        id_phone.val(opt_data.phone);
        id_email.val(opt_data.email);
        id_gender.val(opt_data.gender);
        if(opt_data.gender == "男"){
           id_sex.val(1);
        }else{
            id_sex.val(2);
        }
        id_job.val(opt_data.assistant_type);

        var arr            = [
            [ "id",  id_assistantid ] ,
            [ "姓名",  id_name ] ,
            // [ "性别",  id_sex ] ,
            [ "性别",  id_gender ] ,
            [ "生日",  id_birth ] ,
            [ "工龄",  id_years] ,
            [ "学校",  id_school ] ,
            [ "手机",  id_phone ] ,
            [ "邮箱",  id_email ] ,
            [ "兼/职",  id_job ] ,
        ];

        $.show_key_value_table("修改助教信息", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {

                var birth=""+ id_birth.val();
                birth=birth.substr(0,4)+birth.substr(5,2)+birth.substr(8,2);

                $.do_ajax( '/human_resource/update_assistant_info2', {
                    'assistantid' : id_assistantid.val(),
                    'name'        : id_name.val(),
                    'birth'       : id_birth.val(),
                    'years'       : id_years.val(),
                    'school'      : id_school.val(),
                    'phone'       : id_phone.val(),
                    'email'       : id_email.val(),
                    // 'sex'         : id_sex.val(),
                    'sex'         : id_gender.val(),
                    'job'         : id_job.val()

                });
            }

        });



    });


    $(".opt-update-passwd").on("click",function(){
      //
        var assistantid = $(this).get_opt_data("assistantid");

        $.show_input ("修改密码" , 142857, function(val){
            $.do_ajax("/user_deal/user_set_passwd",{
                "userid": assistantid,
                "passwd":val
            });
        });
    });




  $('.opt-change').set_input_change_event(load_data);
});
