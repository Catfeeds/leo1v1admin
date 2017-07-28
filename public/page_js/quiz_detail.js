$(function(){
     g_qiniu_domain = "[$qiniu_domain]";
   $("#id_teacherid").data("teacherid", g_args.teacherid);
   $("#id_is_checked").val(g_args.quiz_status);
   if(g_args.stu_nick != ""){
     $("#id_stu_list").val(g_args.stu_nick);
   }



    function init_upload(type){
        var opt_up;
        if(type == 'tea'){
            opt_up = $(".done_ff");
        }else if(type == 'ass'){
            opt_up = $(".done_ff_02");
        }
        $.each(opt_up, function(i, item){
            var This = $(item);
            var id = 'id_'+type+'_'+This.parent().data('itemid');
            This.attr('id', id);
            var par_id = id+'_par';
            This.parent().attr('id', par_id);
            var uploader = Qiniu.uploader({
            runtimes: 'html5, flash, html4',
            browse_button: id , //choose files id
            uptoken_url: '/upload/private_token',
            domain: g_qiniu_domain,
            container: par_id,
            drop_element: par_id,
            max_file_size: '30mb',
            dragdrop: true,
        flash_swf_url: '/js/qiniu/plupload/Moxie.swf',
            chunk_size: '4mb',
            unique_names: false,
            save_key: false,
            auto_start: true,
            init: {
              'FilesAdded': function(up, files) {
                plupload.each(files, function(file) {
                            var tmp = item.button;
                            console.log('waiting...');
                        });
              },
              'BeforeUpload': function(up, file) {
                console.log('before uplaod the file');

                if (!check_type(file.type)) {
                  alert('请上传PDF文件');
                  return;
                        }
              },
              'UploadProgress': function(up,file) {
                console.log('upload progress');
              },
              'UploadComplete': function() {
                console.log('success');
              },
              'FileUploaded' : function(up, file, info) {
                console.log('Things below are from FileUploaded');
                        setComplete(up, info, file, id);
              },
              'Error': function(up, err, errTip) {
                console.log('Things below are from Error');
                console.log(up);
                console.log(err);
                console.log(errTip);
              },
              'Key': function(up, file) {
                var key = "";
                //generate the key
                        var time = (new Date()).valueOf();
                return $.md5(file.name) +time+'.pdf';
              }
            }
          });
        });
    }
    init_upload('tea');
    init_upload('ass');


  $(".done_ss").on('click',function(){
    var file_url = $(this).parent().data("url");
        if(file_url == "")
            alert("老师尚未完成批阅!");
    if(file_url != ""){
      $.ajax({
        type     :"post",
        url      :"/upload/get_download_url/",
        dataType :"json",
        data     :{"file_url":file_url},
        success  : function(result){
          if(result.ret == 0){
            window.open(result.download_url);
          }
        }
      });
    }
  });

  $(".done_ss_02").on('click',function(){
    var file_url = $(this).parent().data("url");
        if(file_url == "")
            alert("教研老师尚未上传!");

    if(file_url != ""){
      $.ajax({
        type     :"post",
        url      :"/upload/get_download_url/",
        dataType :"json",
        data     :{"file_url":file_url},
        success  : function(result){
          if(result.ret == 0){
            window.open(result.download_url);
          }
        }
      });
    }
  });

  function load_data( $stu_nick, $quiz_status, $teacherid){
    var url="/tea_manage/quiz_detail?stu_nick="+$stu_nick+"&quiz_status="+$quiz_status+"&teacherid="+$teacherid;
    window.location.href=url;
  }

  $(".hw_change").on("change",function(){
    var stu_nick = $("#id_stu_list").val();
    var teacherid = $("#id_teacherid").data("teacherid");
    var quiz_status = $("#id_is_checked").val();
    load_data(stu_nick, quiz_status, teacherid);
  });

 setComplete = function(up, info, file, id) {

        var upload_succ = true;
      var fileTargetID = file.id;
        var res = $.parseJSON(info);
        var url;
        if (res.url) {

        } else {
            var domain = up.getOption('domain');
            url = domain + encodeURI(res.key);
            var link = domain + res.key;
            console.log('MILLIONS else Test: ' + info);
            var urlkey    = res.key;
            $.ajax({
              url: '/tea_manage/upload_research',
              type: 'POST',
              data: {'urlkey': urlkey, 'id':id},
          dataType: 'json',
          success: function(data) {
            if (data['ret'] == 0) {
                        alert("上传成功");
            } else {
                        alert(data['info']);
            }
          }
            });
        }
    };


    function check_type(file_type)
    {
      return file_type == 'application/pdf' ? true : false;
    }


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

    function get_time()
    {
      var myDate = new Date();

      var year   = myDate.getFullYear();
      var month  = myDate.getMonth();
      var day    = myDate.getDate();
      var hour   = myDate.getHours();
      var mimute = myDate.getMinutes();

      return year + '-' + month + '-' + day +
        ' ' + hour + ':' + mimute;
    }
});
