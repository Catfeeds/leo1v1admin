/// <reference path="../common.d.ts" />
    
$(function(){

	//冻结排课操作 
    $(".opt-teacher-freeze-new").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        
        $.getJSON('/tea_manage_new/get_teacher_grade_range_new', {
            'teacherid': teacherid
        }, function(result){
            // var grade_range = result.data;
            if(result.data=="" && result.list==0) {
                alert("该老师未设置年级段!");
                return;
            }
            var id_freeze_reason=$("<textarea/>");        
            var id_is_freeze=$("<select>");        
            var id_seller_require_flag=$("<select/>");
            var id_grade_range=$("<select/>");
            Enum_map.append_option_list("boolean", id_seller_require_flag, true );
            Enum_map.append_option_list("is_freeze", id_is_freeze, true,[1,2] );
            id_seller_require_flag.val(0);
            var arr=[
                ["类型",id_is_freeze],
                ["年级段", id_grade_range],
                ["理由", id_freeze_reason],
                ["是否CC要求", id_seller_require_flag],
            ];
            id_seller_require_flag.on("change",function(){
                if(id_seller_require_flag.val()==1 && id_is_freeze.val()==2){
                    id_freeze_reason.val("因咨询老师排课需求，暂时解除老师冻结课程，但系统仍将继续记录老师试听课数与转化率，若转化率未出现提升，后续仍将按照相关规则进行限课或冻结处理，希望老师认真备课，钻研学生试听需求，结合线上教学特色，制定出完善的课程计划，体现1对1个性化教学风格。老师加油！");
                }else{
                    id_freeze_reason.val("");
                }
            });

            id_is_freeze.on("change",function(){
                if(id_is_freeze.val()==1){
                    id_grade_range.find("option").remove(); 
                    Enum_map.append_option_list("grade_range", id_grade_range, true,result.data );
                }else{
                    id_grade_range.find("option").remove(); 
                    Enum_map.append_option_list("grade_range", id_grade_range, true,result.list );
                }
            });


            $.show_key_value_table("冻结/解冻操作", arr ,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {                   
                    $.do_ajax( '/ss_deal/update_freeze_teacher_info_new', {
                        "teacherid"     : teacherid,
                        "is_freeze"     : id_is_freeze.val(),
                        "grade_range"     : id_grade_range.val(),
                        "freeze_reason" : id_freeze_reason.val(),
                        "seller_require_flag":id_seller_require_flag.val()
                    });
                }
            },function(){
                if(id_is_freeze.val()==1){
                    id_grade_range.find("option").remove(); 
                    Enum_map.append_option_list("grade_range", id_grade_range, true,result.data );
                }else{
                    id_grade_range.find("option").remove(); 
                    Enum_map.append_option_list("grade_range", id_grade_range, true,result.list );

                }

            });

        });

        
        
    });

    //限制排课操作
    $(".opt-limit-plan-lesson-new").on("click",function(){
        var opt_data                    = $(this).get_opt_data();
        var teacherid                   = opt_data.teacherid;
        $.getJSON('/tea_manage_new/get_grade_range_limit_list', {
            'teacherid': teacherid
        }, function(result){
            var id_limit_plan_lesson_type   = $("<select/>");
            var id_grade_range   = $("<select/>");
            var id_limit_plan_lesson_reason = $("<textarea/>");
            var id_seller_require_flag=$("<select />");
            var id_limit_type=$("<select><option value=\"-1\">不设置</option><option value=\"1\">CC要求限课</option><option value=\"2\">恢复CC要求的限课</option></select>");
            var grade_list = result.list;
            //alert(grade_list);
            if(grade_list==""){
                alert("该老师未设置年级段!");
                return;
            }
            Enum_map.append_option_list("boolean", id_seller_require_flag, true );
            Enum_map.append_option_list("grade_range", id_grade_range, true,grade_list);
            id_seller_require_flag.val(0);

            Enum_map.append_option_list("limit_plan_lesson_type",id_limit_plan_lesson_type,true);
            var arr= [
                ["年级段",id_grade_range],
                ["限制类型",id_limit_plan_lesson_type],
                ["限制原因",id_limit_plan_lesson_reason],
                ["更改类型",id_limit_type],
                ["是否CC要求",id_seller_require_flag]
            ];
            id_grade_range.on("change",function(){
                id_limit_plan_lesson_type.val(result.data[id_grade_range.val()].limit_type);
            });

            id_limit_type.on("change",function(){
                if(id_limit_type.val()==1){
                    id_limit_plan_lesson_reason.val("因咨询老师排课需求，暂时开放老师每周接课权限，但系统仍将继续记录老师试听课数与转化率，若转化率未出现提升，后续仍将按照相关规则进行限课或冻结处理，希望老师认真备课，钻研学生试听需求，结合线上教学特色，制定出完善的课程计划，体现1对1个性化教学风格。老师加油！");
                    id_seller_require_flag.val(1);  
                }else if(id_limit_type.val()==2){
                    id_limit_plan_lesson_reason.val("咨询部老师排课相关操作已执行完毕，现恢复之前限课状态，希望老师认真备课，钻研学生试听需求，结合线上教学特色，制定出完善的课程计划，体现1对1个性化教学风格，老师加油！");
                    id_seller_require_flag.val(0);
                }else{
                    id_limit_plan_lesson_reason.val("");
                    id_seller_require_flag.val(0);
                }
            });

            $.show_key_value_table("排课限制", arr ,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    $.do_ajax( '/ss_deal/set_teacher_limit_plan_lesson_new', {
                        "teacherid"          : opt_data.teacherid,
                        "limit_plan_lesson_type":id_limit_plan_lesson_type.val(),
                        "limit_plan_lesson_reason":id_limit_plan_lesson_reason.val(),
                        "seller_require_flag":id_seller_require_flag.val(),
                        "grade_range"     :id_grade_range.val()
                    });
                }
            },function(){
                id_limit_plan_lesson_type.val(result.data[id_grade_range.val()].limit_type);
            });

        });

        
    });


    //冻结/解冻操作记录
    $(".opt-freeze-list-new").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var title = "冻结/解冻记录";
        var html_node = $("<div id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>时间</td><td>年级</td><td>操作</td><td>理由</td><td>操作人</td><td>是否CC要求</td><tr></table></div>");                     

        $.do_ajax("/user_deal/get_teacher_free_list",{
            "teacherid" : teacherid,
            "type"      : 4
        },function(result){
            if(result.ret!=0){
                BootstrapDialog.alert(result.info);
                return ;
            }

            $.each(result.data,function(i,item){
                html_node.find("table").append("<tr><td>"+item['add_time_str']+"</td><td>"+item['grade_range_str']+"</td><td>"+item['is_freeze_str']+"</td><td>"+item['record_info']+"</td><td>"+item['acc']+"</td><td>"+item['seller_require_flag_str']+"</td></tr>");
                

            });

            var dlg=BootstrapDialog.show({
                title:title, 
                message :  html_node   ,
                closable: true, 
                buttons:[{
                    label: '返回',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();

                    }
                }],
                onshown:function(){
                    
                }

            });

            dlg.getModalDialog().css("width","1024px");

        });
        

    });

    //限制排课记录
    $(".opt-limit-plan-lesson-list-new").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var title = "限课记录";
        var html_node = $("<div id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>时间</td><td>年级</td><td>操作前</td><td>操作后</td><td>理由</td><td>操作人</td><td>是否CC要求</td><tr></table></div>");
        
        

        $.do_ajax("/user_deal/get_teacher_limit_change_list",{
            "teacherid" : teacherid,
            "type"      : 3
        },function(result){
            if(result.ret!=0){
                BootstrapDialog.alert(result.info);
                return ;
            }

            $.each(result.data,function(i,item){
                html_node.find("table").append("<tr><td>"+item['add_time_str']+"</td><td>"+item['grade_range_str']+"</td><td>"+item['limit_plan_lesson_type_old_str']+"</td><td>"+item['limit_plan_lesson_type_str']+"</td><td>"+item['record_info']+"</td><td>"+item['acc']+"</td><td>"+item['seller_require_flag_str']+"</td></tr>");

            });
            var dlg=BootstrapDialog.show({
                title:title, 
                message :  html_node   ,
                closable: true, 
                buttons:[{
                    label: '返回',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();

                    }
                }],
                onshown:function(){
                    
                }

            });

            dlg.getModalDialog().css("width","1024px");

            
        });
        

    });
});



