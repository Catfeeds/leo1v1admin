/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-production_department_memeber_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			post:	$('#id_post').val(),
			department:	$('#id_department').val(),
			department_group:	$('#id_department_group').val(),
			user_info:	$('#id_user_info').val(),
			adminid:	$('#id_adminid').val()
        });
    }


    Enum_map.append_option_list("post", $("#id_post"));
    $.do_ajax( "/user_manage_new/get_department_and_group_info",{
        "main_department" : g_args.main_department 
    },function(resp){
        Enum_map.append_option_list("department", $("#id_department"),false,resp.data.department_list);
        Enum_map.append_option_list("department_group", $("#id_department_group"),false,resp.data.department_group_list);
    });
    

   // Enum_map.append_option_list("department", $("#id_department"));
    //Enum_map.append_option_list("department_group", $("#id_department_group"));
	$('#id_post').val(g_args.post);
	$('#id_department').val(g_args.department);
	$('#id_department_group').val(g_args.department_group);
	$('#id_user_info').val(g_args.user_info);
	$('#id_adminid').val(g_args.adminid);

    $.admin_select_user(
        $('#id_adminid'),
        "admin", load_data);

    $(".edit-manage").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var uid= opt_data.uid;
        var $email=$("<input/>").val(opt_data.email);
        var $personal_email=$("<input/>").val(opt_data.personal_email);
        var $employee_level=$("<select/>");
        var $become_full_member_time=$("<input/>");
        var $order_end_time=$("<input/>");
        var $company=$("<select/>");
        var $gender=$("<select/>");
        var $education=$("<select/>");
        var $post=$("<select/>");
        var $department=$("<select/>");
        var $main_department=$("<select/>");
        var $department_group=$("<select/>");
        var $name=$("<input/>").val(opt_data.name);
        var $gra_school=$("<input/>").val(opt_data.gra_school);
        var $gra_major=$("<input/>").val(opt_data.gra_major);
        var $identity_card=$("<input/>").val(opt_data.identity_card);
        var $basic_pay=$("<input/>").val(opt_data.basic_pay/100 );
        var $merit_pay=$("<input/>").val(opt_data.merit_pay/100 );
        var $post_basic_pay=$("<input/>").val(opt_data.post_basic_pay/100 );
        var $post_merit_pay=$("<input/>").val(opt_data.post_merit_pay/100 );
        var $desc =$("<input/>").val(opt_data.personal_desc );
        var id_resume_url = $("<div><input class=\"resume_url\" id=\"resume_url\" type=\"text\"readonly ><div><span ><a class=\"upload_gift_pic\" id=\"id_upload_resume_url\" href=\"javascript:;\">上传</a></span><span><a style=\"margin-left:20px\" href=\"javascript:;\" id=\"id_del_resume_url\">删除</a></span></div></div>");
        var need_account_role_list=null;
        var main_department_list = [0,g_args.main_department];
        Enum_map.append_option_list("gender", $gender,true);
        Enum_map.append_option_list("employee_level", $employee_level,true);
        Enum_map.append_option_list("company", $company,true);
        Enum_map.append_option_list("education", $education,true);
        Enum_map.append_option_list("post", $post ,true);
        $.do_ajax( "/user_manage_new/get_department_and_group_info",{
            "main_department" : g_args.main_department 
        },function(resp){
            Enum_map.append_option_list("department", $department ,true,resp.data.department_list);
            Enum_map.append_option_list("department_group", $department_group ,true,resp.data.department_group_list);
        });

        Enum_map.append_option_list("main_department", $main_department ,true,main_department_list);
        $gender.val(opt_data.gender);
        $employee_level.val(opt_data.employee_level);
        $company.val(opt_data.company);
        $education.val(opt_data.education);
        $post.val(opt_data.post);
        $department_group.val(opt_data.department_group);
        $department.val(opt_data.department);
        $main_department.val(opt_data.main_department);
        $become_full_member_time.datetimepicker({
		    datepicker:true,
		    timepicker:false,
		    format:'Y-m-d',
		    step:30 
	    });
	    $order_end_time.datetimepicker({
		    datepicker:true,
		    timepicker:false,
		    format:'Y-m-d',
		    step:30
	    });
        if(opt_data.become_full_member_time>0){
            
            $become_full_member_time.val(opt_data.become_full_member_time_str);
        }
        if(opt_data.order_end_time>0){
            
            $order_end_time.val(opt_data.order_end_time_str);
        }

       
        var arr=[
            ["中文名", $name],
            ["性别",$gender] ,
            ["所属公司",$company] ,
            ["学历",$education] ,
            ["员工级别",$employee_level] ,
            //["-","-"],
            ["毕业院校",$gra_school ],
            ["专业",$gra_major],
            ["身份证号码",$identity_card ],
           // ["-","-"],
            ["转正日期",$become_full_member_time] ,
            ["合同结束日期",$order_end_time] ,
            ["隶属部门",$main_department] ,
            ["分部门",$department],
            ["岗位",$post],
            ["小组",$department_group],
          //  ["基本工资",$basic_pay],
           // ["绩效工资",$merit_pay],
           // ["转正基本工资",$post_basic_pay],
           // ["转正绩效工资",$post_merit_pay],
            ["公司邮箱",$email],
            ["个人邮箱",$personal_email],
            ["备注",$desc],
            ["上传简历",  id_resume_url ]
        ];
        id_resume_url.find("#resume_url").val(opt_data.rurl);      

        id_resume_url.find("#id_del_resume_url").on("click",function(){
            id_resume_url.find("#resume_url").val("");
        });

        $.show_key_value_table("修改用户信息", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax('/user_deal/update_admin_info_new', {
                    'uid': opt_data.uid,
                    'name': $name.val(),
                    'gender': $gender.val(),
                    'company': $company.val(),
                    'education': $education.val(),
                    'employee_level': $employee_level.val(),
                    'gra_school': $gra_school.val(),
                    'gra_major': $gra_major.val(),
                    'identity_card': $identity_card.val(),
                    'become_full_member_time': $become_full_member_time.val(),
                    'order_end_time': $order_end_time.val(),
                    'department': $department.val(),
                    'main_department': $main_department.val(),
                    'post': $post.val(),
                    'department_group': $department_group.val(),
                   // 'basic_pay': $basic_pay.val()*100,
                   // 'merit_pay': $merit_pay.val()*100,
                   // 'post_basic_pay': $post_basic_pay.val()*100,
                   // 'post_merit_pay': $post_merit_pay.val()*100,
                    'email':$email.val(),
                    'personal_email':$personal_email.val(),
                    'desc':$desc.val(),
                    "resume_url": id_resume_url.find("#resume_url").val()
                });
            }
        },function(){
            $.custom_upload_file('id_upload_resume_url',true,function (up, info, file) {
                var res = $.parseJSON(info);

                $("#resume_url").val(res.key);
            }, null,["doc", "docx","xls",'pdf']);

        });
             
    });
    
     
    $(".read_resume").on('click',function(){
        var opt_data=$(this).get_opt_data();
        if(opt_data.resume_url==""){
            alert("简历未上传!");
        }else{
            $.wopen(opt_data.resume_url);
        }
    });


	$('.opt-change').set_input_change_event(load_data);
});










