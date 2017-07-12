$(function(){
    //实例化一个plupload上传对象
    var uploader = new plupload.Uploader({
        browse_button : 'id_upload_xls', //触发文件选择对话框的按钮，为那个元素id
        url : '/seller_student/upload_from_xls', //服务器端的上传页面地址
        flash_swf_url : '/js/qiniu/plupload/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
        silverlight_xap_url : '/js/qiniu/plupload/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
        filters: {
            mime_types : [ //只允许上传图片和zip文件
                { title : "xls files", extensions : "xls" }
            ],
            max_file_size : '40m', //最大只能上传400kb的文件
            prevent_duplicates : true //不允许选取重复文件
        }
    });  
    uploader.init();

    uploader.bind('FilesAdded',
                  function(up, files) {
                      uploader.start();
                  });

    uploader.bind('FileUploaded',
                  function( uploader,file,responseObject) {
                      alert( responseObject.response );
                  });



   
    $('#id_start_date').val(g_start_date);
    $('#id_end_date').val(g_end_date);


    
	//时间控件
	$('#id_start_date').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
	    onChangeDateTime :function(){
		    load_data();
        }
	});
    
	$('#id_end_date').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
		onChangeDateTime :function(){
		    load_data();
        }
	});

	//时间控件-over
	function load_data( ){
        var start_date = $("#id_start_date").val();
        var end_date   = $("#id_end_date").val();
        
	    var url= window.location.pathname+ "?start_date="+start_date+
                "&end_date="+end_date;
	    window.location.href=url;
	}
    
	$(".opt-change").on("change",function(){
		load_data();
	});

});
