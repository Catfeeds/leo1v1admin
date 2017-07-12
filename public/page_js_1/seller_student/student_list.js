function load_data( ){
    var start_date        = $("#id_start_date").val();
    var end_date          = $("#id_end_date").val();
    var status            = $("#id_revisit_status").val();
    var phone             = $.trim($("#id_phone").val());
    var nick              = $.trim($("#id_nick").val());
    var phone_location    = $.trim($("#id_phone_location").val());
    var origin            = $.trim( $("#id_origin").val());
    var admin_revisiterid = $("#id_admin_revisiterid").val();
    var subject           = $("#id_subject").val();
    var grade             = $("#id_grade").val();
    var opt_date_type     = $("#id_opt_date_type").val();
    var page_count        = $("#id_page_count").val();
    var origin_ex         = $("#id_origin_ex").val();
    
    reload_self_page( {
        page_count        : page_count,
        start_date        : start_date,
        end_date          : end_date, 
        phone             : phone, 
        nick              : nick, 
        phone_location    : phone_location, 
        admin_revisiterid : admin_revisiterid, 
        origin            : origin, 
        grade             : grade, 
        subject           : subject, 
        status            : status, 
        origin_ex         : origin_ex, 
        opt_date_type     : opt_date_type
    });
}

$(function(){
    //实例化一个plupload上传对象

    var uploader = new plupload.Uploader({
        browse_button : 'id_upload_xls', //触发文件选择对话框的按钮，为那个元素id
        url : '/seller_student/upload_from_xls', //服务器端的上传页面地址
        flash_swf_url : '/js/qiniu/plupload/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
        silverlight_xap_url : '/js/qiniu/plupload/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
        filters: {
            mime_types : [ //只允许上传图片和zip文件
                { title : "xls files", extensions : "xls" },
                { title : "xlsx files", extensions : "xlsx" }
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


    var upload_func=function(id,url) {
        var j_uploader = new plupload.Uploader({
            browse_button : id, //触发文件选择对话框的按钮，为那个元素id
            url : url, //服务器端的上传页面地址
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


        j_uploader.init();

        j_uploader.bind('FilesAdded',
                        function(up, files) {
                            
                            j_uploader.start();
                        });

        j_uploader.bind('FileUploaded',
                        function( uploader,file,responseObject) {
                            alert( responseObject.response );
                            window.location.reload();
                        });

    };

    upload_func( "id_upload_xls_jinshuju", "/seller_student/upload_from_xls_jinshuju" );
    upload_func( "id_upload_xls_youzan", "/seller_student/upload_from_xls_youzan" );




//    Enum_map.append_option_list("book_status",$(".update_user_status"),true,[0,1,2,3,4,5,10,11,12]);
    Enum_map.append_option_list("book_grade",$(".book_grade_list"));
    Enum_map.append_option_list("book_status",$("#id_revisit_status"));
    Enum_map.append_option_list("grade",$("#id_grade"));
    Enum_map.append_option_list("subject",$("#id_subject"));
    
    $('#id_start_date').val(g_args.start_date);
    $('#id_end_date').val(g_args.end_date);
    $('#id_grade').val(g_args.grade);
    $('#id_subject').val(g_args.subject);
    $('#id_revisit_status').val(g_args.status);
    $("#id_phone").val(g_args.phone);
    $("#id_phone_location").val(g_args.phone_location);
    $("#id_origin").val(g_args.origin);
    $("#id_page_count").val(g_args.page_count);
    $("#id_origin_ex").val(g_args.origin_ex);

    var seller_list=[];

    var do_select_seller=function(onchange_func){
        $(this).admin_select_dlg({
            header_list:[ "adminid","admin_nick" ],
            data_list:seller_list,
            onChange:onchange_func
        });
    };



    do_ajax( "/authority/get_group_user_list_ex", {
        groupid:1
    },function(data){
        var user_list=data.user_list;
        var $select=$("#id_admin_revisiterid" );
        $.each(user_list, function(i,item){
            $select.append( "<option value="+item.adminid+"> "+item.admin_nick+ " </option>" );

            seller_list.push ([item.adminid,item.admin_nick]);
        });
        $("#id_admin_revisiterid").val(g_args.admin_revisiterid);
    });

    var select_check=function( flag) {
        $.each ($( ".common-table .opt-select-user " )  ,function(i,item) {

            var $div=$(item).parent();
            if (flag==0) {
                if ($div.hasClass("checked" ) ) {
                    
                }else{
                    $div.addClass ("checked");
                }
            }else{
                if ($div.hasClass("checked" ) ) {
                    $div.removeClass("checked");
                }else{
                    $div.addClass ("checked");
                }
            }
        });
            
    };
    
    $("#id_select_all").on("click",function(){
        select_check(0);
    });

    $("#id_select_other").on("click",function(){
        select_check(1);
    });

    
    $("#id_assign_seller_del").on("click",function(){
        if (confirm("要批量删除所选列表?" ) ) {
            $.each ($( ".common-table .opt-select-user " )  ,function(i,item) {
                var $div=$(item).parent();
                if ($div.hasClass("checked" )) {
                    var phone=$div.closest("tr").find(".opt-div").data("phone");
		            $.ajax({
			            type     :"post",
			            url      :"/seller_student/del_student",
			            dataType :"json",
			            data     :{
                            'phone':phone
                        },
			            success  : function(result){
			            }
		            });
                }
                //phone_list.push( $div.ha  );
            });
            //load_data();
        }

    });

    $("#id_assign_seller_select").on("click",function(){
        var phone_list=[];
        $.each ($( ".common-table .opt-select-user " )  ,function(i,item) {
            var $div=$(item).parent();
            if ($div.hasClass("checked" )) {
                var phone=$div.closest("tr").find(".opt-div").data("phone");
                phone_list.push(  phone);
                
            }
            //phone_list.push( $div.ha  );
        });
        
        do_select_seller(function( val){
            if (val>0) {
                do_ajax(
                    '/seller_student/set_admin_revisiterid',
                    {
                        'phone_list'        : JSON.stringify( phone_list),
                        'admin_revisiterid' : val
                    }, function(result) {
                        alert("succ");
                        window.location.reload();
                    }
                );
            }
        });

    });

    
    $("#id_opt_date_type").val(g_args.opt_date_type);
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

       


    
	$(".opt-change").on("change",function(){
		load_data();
	});

    $("#id_class_time").on("change",function(){
		load_data();
	});

    $("#id_add_user").on("click",function(){

        var id_phone        = $("<input/>");
        var id_nick         = $("<input/>");
        var id_origin       = $("<input/>");
        var id_grade        = $("<select/>");
        var id_subject      = $("<select/>");
        var id_status       = $("<select/>");
        var id_consult_desc = $("<textarea/>");

        
        var arr                = [
            [ "电话",  id_phone] ,
            [ "姓名",  id_nick] ,
            [ "渠道",  id_origin] ,
            [ "年级",  id_grade] ,
            [ "科目",  id_subject] ,
            [ "回访状态",   id_status] ,
            [ "用户备注",   id_consult_desc] ,
        ];

        Enum_map.append_option_list("book_status", id_status,true );
        Enum_map.append_option_list("subject", id_subject,true );
        Enum_map.append_option_list("grade", id_grade,true );
        id_subject.val(2);
        id_status.val(3);

        show_key_value_table("新增电话用户", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var nick         = $.trim(id_nick.val());
                var phone        = $.trim(id_phone.val());
                var origin       = $.trim(id_origin.val());
                var grade        = id_grade.val();
                var subject      = id_subject.val();
                var status       = id_status.val();
                var consult_desc = id_consult_desc.val();
                if (phone.length < 11  ) {
                    BootstrapDialog.alert('电话不是11位的!');
                    return;
                }
                if (nick.length <2  ) {
                    BootstrapDialog.alert('姓名不对');
                    return;
                }


                do_ajax( '/seller_student/add_stu_info',
                         {
                             'phone': phone,
                             'grade': grade,
                             'subject': subject,
                             'nick': nick,
                             'status': status,
                             'origin': origin,
                             'consult_desc': consult_desc
                         },function(data)  {
                             if (data.ret!=0) {
                                 alert(data.info);
                             }else{
                                 window.location.reload();
                             }
                         }
                       );

			    dialog.close();

            }
        });
        

	    
    });
    //core dumped
    $('.opt-add-revisit-record').on('click', function(){
        var html_node=$('<div></div>').html(dlg_get_html_by_class('dlg-add-revisit'));
        html_node.find('.show-user-phone').text($(this).get_opt_data("phone") ); 

        BootstrapDialog.show({
	        title: "添加回访记录",
	        message : html_node ,
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    var phone   = html_node.find('.show-user-phone').text();
                    var op_note = html_node.find('.opt-add-record').val();
                    $.ajax({
                        url: '/seller_student/update_book_revisit',
                        type: 'POST',
                        data: {
                            'phone': phone,
                            'op_note': op_note
                        },
                        dataType: 'json',
                        success: function(result){
                            BootstrapDialog.alert(result['info']);
                            window.location.reload();
                        }
                    });
			        dialog.close();
		        }
	        }]
        });
    });
    // lala
      $('.opt-set-status').on('click', function(){
        var html_node=$('<div></div>').html(dlg_get_html_by_class('dlg-set-status'));
        html_node.find('.show-user-phone').text($(this).get_opt_data("phone") ); 

        BootstrapDialog.show({
	        title: "设置学生状态",
	        message : html_node ,
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    var phone   = html_node.find('.show-user-phone').text();
                    var status  = html_node.find('.update_user_status').val();
                    $.ajax({
                        url: '/seller_student/set_status',
                        type: 'POST',
                        data: {
                            'phone': phone,
                            'status': status
                        },
                        dataType: 'json',
                        success: function(result){
                            BootstrapDialog.alert(result['info']);
                            window.location.reload();
                        }
                    });
			        dialog.close();
		        }
	        }]
        });
    });

    


    $('.opt-show-revisit-record').on('click',function(){
        var html_node=$('<div></div>').html(dlg_get_html_by_class('dlg-show-revisit'));
        var phone = $(this).get_opt_data("phone");

        BootstrapDialog.show({
	        title: "查看回访记录",
	        message : function(dialog) {
                $.ajax({
                    url: '/user_book/get_book_revisit',
                    type: 'POST',
                    data: {
                        'phone': phone
                    },
                    dataType: 'json',
                    success: function(result) {
                        if (result['ret'] == 0) {
                            for(i=0; i<result['revisit_list'].length; i++) {
                                html_node.find('table').append('<tr><td>' + result['revisit_list'][i]['revisit_time'] +
                                                               '</td><td>'+ result['revisit_list'][i]['op_note'] +
                                                               '</td></tr>');
                            }
                        }
                    }
                });
                return html_node;
            },
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }]
        });

    });
    
    $.each(  $(".opt-div") ,function(i,item){
        var $item=$(item);
        var sys_operator=$item.data("sys_operator");
        if (sys_operator == "" ) {
            //可以拍下；
            $item.find(" .show-in-no-select " ).show() ;
            $item.find(" .show-in-select " ).hide() ;
        }else {
            if (sys_operator == g_account) {
                $item.find(" .show-in-no-select " ).hide() ;
                $item.find(" .show-in-select " ).show() ;
            }else{
                $item.find(" .show-in-no-select " ).hide() ;
                $item.find(" .show-in-select " ).hide() ;
            }
        }

        var $opt_lesson_open= $item.find(".opt-lesson-open");

        if ($opt_lesson_open.css("display") != "none") {
            if (g_args.register_flag==1 ) {
                $opt_lesson_open.hide();
            }
        }
        
    });

    
    $(".opt-set_sys_operator").on("click",function(){

        var phone  = $(this).get_opt_data("phone");
        do_ajax(
            "/user_book/set_sys_operator", {
                "phone" : phone 
            }
        );
        
    });


    function getPadType(pad_type)
    {
        switch(pad_type) {
        case 1:
            return 'iPad';
        case 2:
            return 'Android Pad';
        case 3:
            return 'no Pad';
        default:
            return '';
            
        }
    }

    function getTrialType(trial_type)
    {
        switch(trial_type){
        case 0:
            return '1v1';
        case 1:
            return '小班课';
        default:
            return '';
        }
    }

    $('.opt-add_book_time_next').on('click',function(){
        var html_node = $('<div></div>').html(dlg_get_html_by_class('dlg-add_book_time_next'));
        var phone     = $(this).get_opt_data("phone");
        
	    html_node.find('.update_book_time_next').datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d'
	    });

        BootstrapDialog.show({
            title: '添加下次回访时间',
            message : html_node,
            closable: true, 
            closeByBackdrop:false,
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
                        var update_book_time_next = html_node.find(".update_book_time_next").val();
                        
                        $.ajax({
			                type     : "post",
			                url      : "/user_book/update_book_time_next",
			                dataType : "json",
			                data : {
                                "phone"          : phone,
                                "book_time_next" : update_book_time_next
                            },
			                success : function(result){
                                window.location.reload();
			                }
                        });
                        dialog.close();
                    }
                },{
                    label: '清除回访',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        $.ajax({
                            url:  '/user_book/clear_book_time',
                            type: 'POST',
                            data: {
                                'phone':phone
                            },
                            dataType: 'json',
                            success: function(result){
                                window.location.reload();
                            }
                        });
                    }
                },{
                    label: '取消',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
        });
    });



    //未回访
    $('body').on('change','.update_user_status',function(){
		var status=$(this).val();
        if (status == 0) 
            alert('状态无效请重新输入');
	});

     $('.opt-lesson-open').on('click', function(){
        var phone          = $(this).get_opt_data("phone");
        var id             = $(this).get_opt_data("id");
        var id_lesson_open = $("<input>");

        id_lesson_open. datetimepicker({
            format: "Y-m-d H:i",
            autoclose: true,
            todayBtn: true
        });
        
        var arr                = [
            ["电话", phone],
            [ "开课时间",  id_lesson_open] ,
        ];

        show_key_value_table("安排课程时间", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var class_time= id_lesson_open.val();
                //alert(courseid);
                do_ajax('/user_book/set_class_time', {
                    'id':id,
                    'class_time':class_time
                },function(){
                    alert('设置成功' );
                    window.location.reload();
                });
			    dialog.close();
            }
        });
    });


    
    $("#id_download").on("click",function(){

        if ($(".page-opt-show-all").length >0 ) {
            $(".page-opt-show-all").click();
            return ;
        } 
        

        var list_data=[];
        var $tr_list=$(".common-table").find("tr" );
        var map={
            
        };
        $.each($tr_list ,function(i,tr_item )  {
            var row_data= [];
            var $td_list= $(tr_item ).find("td");
            var phone = $(tr_item).find(".td-opt  >div" ).data("phone");
            if (!map[phone] ) {
                
                $.each(  $td_list, function( i, td_item)  {
                    if ( i>0 && i< $td_list.length-1 ) {
                        if (i==1
                            || i==2
                            || i==5
                            || i==6
                            || i==7
                            || i==9
                            || i==10
                            || i==11
                           ){
                               row_data.push( $(td_item).text() );
                           }
                    }
                });
                list_data.push(row_data);
                map[phone]=true;
            }

        });
        
        do_ajax ( "/common_new/upload_xls_data",{
            xls_data :  JSON.stringify(list_data )
        },function(data){
            // window.open("/common_new/download_xls",true);
            window.location.href= "/common_new/download_xls";
        });

    });

    $("#id_upload_form_submit").on("click",function(){
        alert("xx");
        $("#id_upload_form").submit(
            function(e){
                alert("Submitted");
            }
        );
    });


    //删除
    $(".done_t").on("click", function(){
		var id = $(this).parent().data("id");
		var phone= $(this).parent().data("phone");
        BootstrapDialog.show({
            title: '系统提示',
            message : '确认从预约管理中删除该学生及其相关信息',
            closable: true, 
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-danger',
                    action: function(dialog) {
		                $.ajax({
			                type     :"post",
			                url      :"/seller_student/del_student",
			                dataType :"json",
			                data     :{'id':id,
                                       'phone':phone
                                      },
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

    $('.opt-alloc-seller').on('click',  function(){
        var phone =  $(this).get_opt_data("phone");
        do_select_seller(function( val){
            if (val>0) {
                do_ajax(
                    '/seller_student/set_admin_revisiterid',
                    {
                        'phone_list'      : JSON.stringify( [phone]),
                        'admin_revisiterid'   : val
                    }, function(result) {
                        alert("succ");
                        window.location.reload();
                    }
                );
            }
        });
    });
    



    //添增
    $('.opt-update_user_info').on('click',function(){
        //修改部分
        var html_node = $('<div></div>').html(dlg_get_html_by_class('dlg-update_user_info'));
        var phone  = $(this).get_opt_data("phone");
        var status = $(this).parent().data('status');
        var note   = $(this).parents('td').siblings('.user-desc').text();
        html_node.find(".update_user_phone").val(phone);
        html_node.find(".update_user_status").val(status);
        html_node.find(".update_user_note").val(note);
        html_node.find('.show-user-phone').text($(this).get_opt_data("phone") ); 

        var next_status_list=[ 11,12,6,7,8,10,14 ];
        if( $.inArray( status , next_status_list  ) == -1 ) { //no find
            if (status==9) {
                next_status_list=[];
            }else{
                next_status_list=[0,1,2,3,4,5];
            }
        }
        next_status_list.push(status);

        Enum_map.append_option_list("book_status",html_node.find(".update_user_status"),true,next_status_list);


        BootstrapDialog.show({
            
            title: '回访信息',
            message : html_node,
            closable: true, 
            closeByBackdrop:false,
            buttons: [{
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
                        var update_phone  = html_node.find(".update_user_phone").val();
                        var update_status = html_node.find(".update_user_status").val();
                        var update_note   = html_node.find(".update_user_note").val();
                        var phone         = html_node.find('.show-user-phone').text();
                        var op_note       = html_node.find('.update_user_record').val();
                        if(update_status == 0){
                            alert('用户状态错误');
                            return false;
                        }
                            
                        //alert(op_note); 
                        $.ajax({
			                type     : "post",
			                url      : "/seller_student/update_user_info",
			                dataType : "json",
			                data : {
                                "phone"   : update_phone 
                                ,"status" : update_status 
                                ,"note"   : update_note
                                ,'op_note': op_note
                            },
			                success : function(result){
                                BootstrapDialog.alert(result['info']);
                                window.location.reload();
			                }
                        });
                        

                        dialog.close();
                        return true;
                    }
                }, {
                    label: '取消',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
        });
    });


    
});
