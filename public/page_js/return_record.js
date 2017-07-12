// SWITCH-TO:   ../../template/student/
$(function(){
	//按钮
	
	  // 录入回访  
	  $("#id_return_record_add").on("click",function(){
          //
          var userid= g_sid; 
          BootstrapDialog.show({
            title: '回访录入',
            message :   dlg_need_html_by_id( "id_add_return_record_dlg") ,
            closable: false, 
            buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
		            var revisit_person = $.trim(dlg_get_val_by_id("id_return_record_person") );
		            var operator_note  = $.trim(dlg_get_val_by_id("id_return_record_record"));
                    if (operator_note =="" ){
                        alert("还没有内容") ;
                    }
  		            $.ajax({
			            type     :"post",
			            url      :"/revisit/add_revisit_record",
			            dataType :"json",
			            data     :{'userid':userid,'operator_note':operator_note,'revisit_person':revisit_person},
			            success  : function(result){
                            if(result.ret != 0){
                                alert(result.info);
                            }else{
				                window.location.reload();
                            }
			            }
		            });
                }
            }]
        });

	  });

    videojs.options.flash.swf = "video-js.swf";
    function loadAudio(){
        $(".playAudio").each(function(i, item){
            var This = $(item);
            var src = This.data('src');
            do{
                if(src == "")
                    break;
                var type = src.substr(src.lastIndexOf('.')+1);
                if(type != 'wav')
                    break;
                This.show();
            }while(0)
        });
    }
    loadAudio();

});
