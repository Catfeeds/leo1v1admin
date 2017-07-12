/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-index_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacherid:	$('#id_teacherid').val()
        });
    }

	$('#id_teacherid').val(g_args.teacherid);
    $.admin_select_user( $("#id_teacherid"), "teacher", load_data);

    

    
    $("#id_add_teacher").on("click",function(){
        var id_tea_nick=$("<input/>");
        var id_gender=$("<select/>");
        var id_birth=$("<input/>");
        var id_work_year=$("<input/>");
        var id_phone=$("<input/>");
        var id_email=$("<input/>");
        var id_teacher_type=$("<select/>");
        Enum_map.append_option_list("gender", id_gender, true );

        id_work_year.val("1");
        id_birth.val("19890101");
        
	    id_birth.datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Ymd',
            "onChangeDateTime" : function() {
            }
	    });
        
        Enum_map.append_option_list("boolean", id_teacher_type, true );

        var arr=[
            ["电话", id_phone],
            ["全职", id_teacher_type],
            ["姓名", id_tea_nick],
            ["性别", id_gender],
            ["出生年月", id_birth],
            ["工作年限", id_work_year],
            ["电子邮件", id_email],
        ];
        $.show_key_value_table("新增老师", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                $.do_ajax( '/tea_manage/add_teacher',
                    {
                        "tea_nick" : id_tea_nick.val(),
                        "gender" : id_gender.val(),
                        "birth" : id_birth.val(),
                        "work_year" : id_work_year.val(),
                        "phone" : id_phone.val(),
                        "email" : id_email.val(),
                        "teacher_type" : id_teacher_type.val()
                    });
            }
        });

        /*
*/


	    
    });


	$('.opt-change').set_input_change_event(load_data);


    
    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
	    
        var id_tea_nick=$("<input/>");
        var id_gender=$("<select/>");
        var id_birth=$("<input/>");
        var id_work_year=$("<input/>");
        var id_email=$("<input/>");
        var id_realname=$("<input/>");
        var id_teacher_type=$("<select/>");
        Enum_map.append_option_list("gender", id_gender, true );

        
	    id_birth.datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Ymd',
            "onChangeDateTime" : function() {
            }
	    });
        
        Enum_map.append_option_list("boolean", id_teacher_type, true );

        id_tea_nick.val(opt_data.nick);
        id_gender.val(opt_data.gender);
        id_birth.val(opt_data.birth);
        id_work_year.val(opt_data.work_year );
        id_realname.val(opt_data.realname );

        id_email.val(opt_data.email);
        id_teacher_type.val(opt_data.teacher_type);


        var arr=[
            ["全职", id_teacher_type],
            ["昵称", id_tea_nick],
            ["姓名", id_realname],
            ["性别", id_gender],
            ["出生年月", id_birth],
            ["工作年限", id_work_year],
            ["电子邮件", id_email],
        ];
        $.show_key_value_table("新增老师", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                $.do_ajax( '/tea_manage_new/update_teacher_info',
                    {
                        "teacherid" : opt_data.teacherid,
                        "tea_nick" : id_tea_nick.val(),
                        "realname" : id_realname.val(),
                        "gender" : id_gender.val(),
                        "birth" : id_birth.val(),
                        "work_year" : id_work_year.val(),
                        "email" : id_email.val(),
                        "teacher_type" : id_teacher_type.val()
                    });
            }
        });



    });
    $.each( $(".opt-show-lessons"), function(i,item ){
        $(item).admin_select_teacher_free_time({
            "teacherid":   $(item).get_opt_data("teacherid")
        });
    });
    $(".opt-set-tmp-passwd").on("click", function(){
        var opt_data=$(this).get_opt_data();
        var id_tmp_passwd=$("<input/>");
        id_tmp_passwd.val("123456");



        var arr=[
            ["姓名",  opt_data.realname ],
            ["电话",  opt_data.phone ],
            ["临时密码", id_tmp_passwd ],
        ];
        $.show_key_value_table("临时密码", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
		        $.ajax({
			        type     :"post",
			        url      :"/user_manage/set_dynamic_passwd",
			        dataType :"json",
			        data     :{"phone":opt_data.phone , "passwd": id_tmp_passwd.val() , "role": 2 },
			        success  : function(result){
                        BootstrapDialog.alert(result['info']);
                        window.location.reload();
			        }
                });
            }
        });


    
    });




});


