$(function(){
    $('#id_start_date').val(g_start_date);
    $('#id_end_date').val(g_end_date);
    $("#id_phone").val(g_args.phone);

 	function load_data( ){
        var start_date = $("#id_start_date").val();
        var end_date   = $("#id_end_date").val();
        var phone      = $("#id_phone").val();
        
	    var url= window.location.pathname+"?start_date="+start_date+
                "&end_date="+end_date+'&phone='+phone;
	    window.location.href=url;
	}
   
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

    //查找信息回车
    $("#id_phone").on("keypress",function(e){
        if(e.keyCode == 13){
            var phone = $("#id_phone").val();
            if(phone == ""){
                alert("请输入所需查找学校信息！");
            }else{
                load_data();
            }
        }
    });
    
	$('.opt-user').on('click',function(){
		var userid=$(this).get_opt_data ("userid");
        if (userid) {
	        wopen( '/stu_manage?sid='+ userid+"&return_url="+ encodeURIComponent(window.location.href));
        }else{
            alert("无用户信息");
        }
	});
 
    //添加合同
	$('.opt-add-contract').on("click",function(){
        var phone = $(this).get_opt_data("phone");
        do_ajax("/user_manage/contract_get_student_info", {
            phone : phone
        },function(result){
            if (result.ret == 0 ) {
                var data=result.data;
                show_add_contract(data.userid, data.grade, data.stu_nick, data.parent_nick  , data.parent_phone, data.address);
            }else{
                BootstrapDialog.alert(result.info);
            }
        });
    });

    //加合同
    var show_add_contract=function(  userid, grade ,stu_nick, parent_nick  , parent_phone, address){
        var html_node=dlg_need_html_by_id( "id_dlg_add_contract");
        html_node.find("#id_stu_grade").val(grade);

        html_node.find("#id_user_nick").val(stu_nick );
        html_node.find("#id_contact_phone").val(parent_phone);
        html_node.find("#id_parent_nick").val(parent_nick);
        if (!parent_nick){
            html_node.find("#id_user_nick").val("");
            html_node.find("#id_contact_phone").val(stu_nick);
        }

        html_node.find("#id_user_addr").val(address );

        html_node.find("#id_small_class" ).admin_select_course({
            "course_type": 3001 
        });

        html_node.find('#id_con_type').on("change",function(){
	        var val = $(this).val();
            html_node.find(".test-listen").hide();
            html_node.find(".opt-con-type-div").hide();
            
	        if(val == 1){
                html_node.find(".test-listen").show();
                html_node.find(".count_block").show();
            }else if(val == 0 || val == 3){
                html_node.find(".count_block").show();
            }else if(val == 3001 ){ //small_class
                html_node.find(".small-class-div").show();
            }else if(val == 0 || val == 4){

            }
        });

        BootstrapDialog.show({
            title: '新增合同',
            message : html_node, 
            closable: true, 
            buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            },{
                label: '增加',
                cssClass: 'btn-warning',
                action: function(dialog) {
		            var stu_nick         = $.trim(html_node.find("#id_user_nick").val());
		            var grade            = html_node.find("#id_stu_grade").val();
		            var subject          = html_node.find("#id_stu_subject").val();
		            var parent_nick      = $.trim(html_node.find("#id_parent_nick").val());
		            var phone            = $.trim(html_node.find("#id_contact_phone").val());
		            var address          = $.trim(html_node.find("#id_user_addr").val());
		            var lesson_total     = $.trim(html_node.find("#id_lesson_count").val());
		            var contract_type    = html_node.find("#id_con_type").val();
		            var need_receipt     = html_node.find("#id_need_receipt").val();
		            var title            = $.trim(html_node.find("#id_receipt_title").val());
		            var requirement      = $.trim(html_node.find("#id_lesson_requirement").val());
		            var presented_reason = $.trim(html_node.find("#id_presented_reason").val());
		            var should_refund    = html_node.find("#id_should_refund").val();
                    var config_courseid  = html_node.find("#id_small_class").val(); 

                    if(contract_type == -1){
                        alert("请选择合同类型");
                        return;
                    }
                    
                    if ( contract_type==3001  ) { //small class
                        if ( !(config_courseid>0) ){
                            alert("请选择小班课");
                            return;
                        }
                    }else{
                        if(contract_type != 2  && !isNumber(lesson_total) ){
                            alert("课程总数应该为数字");
                            return;
                        }
                    }
                    
		            $.ajax({
			            type     :"post",
			            url      :"/user_manage/add_contract",
			            dataType :"json",
			            data     :{
                            'userid'           : userid,
                            'stu_nick'         : stu_nick,
                            'grade'            : grade,
                            'subject'          : subject,
                            'parent_nick'      : parent_nick,
                            'parent_phone'     : phone,
                            'address'          : address,
                            'lesson_total'     : lesson_total,
                            'need_receipt'     : need_receipt,
                            'title'            : title,
                            'requirement'      : requirement,
                            'contract_type'    : contract_type,
                            'presented_reason' : presented_reason,
                            'should_refund'    : should_refund ,
                            'config_courseid'  : config_courseid
                        },
			            success  : function(result){
				            if(result.ret != 0){
					            alert(result.info);
				            }else{
					            alert("插入成功！");
                                window.location.reload();    
				            }
			            }
		            });
                }
            }]
        });
    };
});
