$(function(){
    // 录入回访
    $(".opt-return-back").on("click",function(){
        var userid=$(this).parent().data("userid");
        BootstrapDialog.show({
            title: '回访录入',
            message  : dlg_need_html_by_id( "id_add_return_record_dlg") ,
            closable : false,
            buttons  : [{

                label: '查看全部',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.wopen("/stu_manage/return_record?sid="+userid);
                }
            },{

                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {

                    var revisit_type   = $.trim(dlg_get_val_by_id("id_return_record_type") );
                    var revisit_person = $.trim(dlg_get_val_by_id("id_return_record_person") );
                    var operator_note  = $.trim(dlg_get_val_by_id("id_return_record_record"));
                    if (operator_note =="" ){
                        alert("还没有内容") ;
                    }
                    $.ajax({
                        type     :"post",
                        url      :"/revisit/add_revisit_record",
                        dataType :"json",
                        data     :{'userid':userid,
                                   'operator_note':operator_note,
                                   'revisit_person':revisit_person,
                                   'revisit_type':revisit_type
                                  },
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
    
    //回访记录
    $(".opt-return-back-list").on("click",function(){
        //优学优享会员、学员用标识
        var opt_data=$(this).get_opt_data();
        if(opt_data.agent_info){
            if(opt_data.test_lessonid == 0)
                return false;
        }
        //添加跳转销售回访列表判断标识
        var cc_flag = $(this).hasClass('cc-flag');
        var agent_user_link = $(this).parent().data("agent_user_link");
        if(agent_user_link){
            //  /agent/agent_user_link  专用
            var p1_userid = $(this).parent().data("p1_userid");
            var p2_userid = $(this).parent().data("p2_userid");
            var p1_phone = $(this).parent().data("p1_phone");
            var p2_phone = $(this).parent().data("p2_phone");
            var userid = p1_userid == null ? p2_userid : p1_userid;
            var phone = p1_phone == null ? p2_phone : p1_phone;
        }else{
            var userid=$(this).parent().data("userid");
            var phone=$(this).parent().data("phone");
        }
        $.ajax({
            type     : "post",
            url      : "/revisit/get_revisit_info",
            dataType : "json",
            size     : BootstrapDialog.SIZE_WIDE,
            data     : {"userid":userid,phone:phone},
            success  : function(result){
                var html_str=$("<div id=\"div_table\"><table class = \"table table-bordered table-striped\"  > <tr><th> 时间  <th> 回访类型 <th>回访路径 <th> 负责人 <th>对象 <th>内容 <th>详情 </tr> </table></div>");
                $.each( result.revisit_list ,function(i,item){
                    //console.log(item);
                    //return;

                    var revisit_person = "";
                    if(item.revisit_person  ) {
                        revisit_person = item.revisit_person;
                    }
                    var userid     = item["userid"];
                    var revisit_time  = item["revisit_time"];
                    if(userid){
                        var html                                  = "<tr><td>"+item.revisit_time +"</td><td>"+
                            item.revisit_type+"</td><td>"+item.revisit_path+"<td>"+ 
                            item.sys_operator +"</td><td>"+item.revisit_person+"</td><td>"+
                            item.operator_note+"</td><td><a class = \"opt_detail\" data-userid=\""+userid+"\" data-revisit_time=\""+revisit_time+"\">详情</a></td></tr>";
                    }else{
                        var html                                  = "<tr><td>"+item.revisit_time +"</td><td>"+
                            item.revisit_type+"</td><td>"+item.revisit_path+"<td>"+ 
                            item.sys_operator +"</td><td>"+item.revisit_person+"</td><td>"+
                            item.operator_note+"</td><td></td></tr>";
                    }
                    html_str.find("table").append(html);
                } );

                var dlg = BootstrapDialog.show({
                    title    : '回访记录',
                    message  : html_str ,
                    closable : true,
                    buttons  : [{
                        label: '查看全部',
                        cssClass : 'btn-warning',
                        action   : function(dialog) {
                            if (cc_flag == true) {
                                $.wopen("/stu_manage/return_book_record?sid="+userid);
                            }else {
                                $.wopen("/stu_manage/return_record?sid="+userid);
                            }
                        }
                    },{
                        label  : '返回',
                        action : function(dialog) {
                            dialog.close();
                        }
                    }],onshown:function(){
                        $(".opt_detail").on("click",function(){
                            var userid = $(this).data("userid");
                            var revisit_time = $(this).data("revisit_time");
                            revisit_time = strtotime(revisit_time);
                            $.ajax({
                            type     : "post",
                            url      : "/revisit/get_revisit_info_by_revisit_time",
                            dataType : "json",
                            data     : {"userid":userid,"revisit_time":revisit_time},
                            success  : function(result){
                                if(result.info == "success" && result.ret_info != null){
                                    var ret_info = result.ret_info;
                                    var revisit_type = ret_info[0]['revisit_type'];
                                    if(revisit_type == '停课月度回访'){
                                        var revisit_path = ret_info[0]['revisit_path'];
                                        var revisit_person = ret_info[0]['revisit_person'];
                                        var operator_note  = ret_info[0]['operator_note'];
                                        var html_node_ha = $("<div style=\"text-align:center;\"> "
                                                        +"<div id=\"drawing_list\" style=\"width:100%\">"
                                                        +"</div><audio preload=\"none\"></audio></div>"
                                                        +"<div id=\"div_table\"><table class = \"table table-bordered table-striped\"  > "
                                                        +"<tr><td>回访路径</td><td>"+revisit_path+"</td></tr>"
                                                        +"<tr><td>回访对象</td><td>"+revisit_person+"</td></tr>"
                                                        +"<tr><td>回访记录</td><td>"+operator_note+"</td></tr>"
                                                        +"</table></div>"
                                                    );
                                        BootstrapDialog.show({
                                          title    : '停课月度回访',
                                          message  : html_node_ha,
                                          closable : true,
                                          onhide   : function(dialogRef){
                                          }
                                        });
                                    }
                                    else if(revisit_type == '首次课后回访'){
                                        revisit_path = ret_info[0]['revisit_path'];
                                        revisit_person = ret_info[0]['revisit_person'];
                                        operator_note  = ret_info[0]['operator_note'];
                                        var operation_satisfy_flag =  ret_info[0]['operation_satisfy_flag'];
                                        if(operation_satisfy_flag < 2){
                                            var operation_satisfy_flag_str = ret_info[0]['operation_satisfy_flag_str'];
                                            var operation_satisfy = "<tr><td>软件操作是否满意</td><td>"+operation_satisfy_flag_str+"</td></tr>";
                                        }else{
                                            operation_satisfy_flag_str = ret_info[0]['operation_satisfy_flag_str'];
                                            var operation_satisfy_type_str = ret_info[0]['operation_satisfy_type_str'];
                                            var operation_satisfy_info = ret_info[0]['operation_satisfy_info'];
                                            operation_satisfy = "<tr><td>软件操作是否满意</td><td>"+operation_satisfy_flag_str+"</td></tr><tr><td>软件操作不满意的类型</td><td>"+operation_satisfy_type_str+"<tr><td>软件操作不满意的具体描述</td><td>"+operation_satisfy_info+"</td></tr>";
                                        }

                                        var child_class_performance_flag = ret_info[0]['child_class_performance_flag'];
                                        if(child_class_performance_flag < 3){
                                            var child_class_performance_flag_str = ret_info[0]['child_class_performance_flag_str'];
                                            var child_class_performance = "<tr><td>孩子课堂表现</td><td>"+child_class_performance_flag_str+"</td></tr>";
                                        }else{
                                            child_class_performance_flag_str = ret_info[0]['child_class_performance_flag_str'];
                                            var child_class_performance_type_str = ret_info[0]['child_class_performance_type_str'];
                                            var child_class_performance_info = ret_info[0]['child_class_performance_info'];
                                            child_class_performance = "<tr><td>孩子课堂表现</td><td>"+child_class_performance_flag_str+"</td></tr><tr><td>孩子课堂表现不好的类型</td><td>"+child_class_performance_type_str+"</td></tr><tr><td>孩子课堂表现不好的具体描述</td><td>"+child_class_performance_info+"</td></tr>";
                                        }

                                        var tea_content_satisfy_flag = ret_info[0]['tea_content_satisfy_flag'];
                                        if(tea_content_satisfy_flag < 3){
                                            var tea_content_satisfy_flag_str = ret_info[0]['tea_content_satisfy_flag_str'];
                                            var tea_content_satisfy = "<tr><td>对于老师or教学是否满意</td><td>"+tea_content_satisfy_flag_str+"</td></tr>";
                                        }else{
                                            tea_content_satisfy_flag_str = ret_info[0]['tea_content_satisfy_flag_str'];
                                            var tea_content_satisfy_type_str = ret_info[0]['tea_content_satisfy_type_str'];
                                            var tea_content_satisfy_info = ret_info[0]['tea_content_satisfy_info'];
                                            tea_content_satisfy = "<tr><td>对于老师or教学是否满意</td><td>"+tea_content_satisfy_flag_str+"</td></tr><tr><td>对于老师or教学不满意的类型</td><td>"+tea_content_satisfy_type_str+"</td></tr><tr><td>对于老师or教学不满意的具体描述"+"</td><td>"+tea_content_satisfy_info+"</td></tr>";
                                        }
                                        var other_parent_info = ret_info[0]['other_parent_info'];
                                        var other_warning_info = ret_info[0]['other_warning_info'];
                                        


                                        html_node_ha = $("<div style=\"text-align:center;\"> "
                                                         +"<div id=\"drawing_list\" style=\"width:100%\">"
                                                         +"</div><audio preload=\"none\"></audio></div>"
                                                         +"<div id=\"div_table\"><table class = \"table table-bordered table-striped\"  > "
                                                         +"<tr><td>回访路径</td><td>"+revisit_path+"</td></tr>"
                                                         +"<tr><td>回访对象</td><td>"+revisit_person+"</td></tr>"
                                                         +operation_satisfy
                                                         +child_class_performance
                                                         +tea_content_satisfy
                                                         +"<tr><td>家长意见或建议</td><td>"+other_parent_info+"</td></tr>"
                                                         +"<tr><td>其他预警问题</td><td>"+other_warning_info+"</td></tr>"
                                                         +"</table></div>"
                                                    );
                                        BootstrapDialog.show({
                                          title    : '停课月度回访',
                                          message  : html_node_ha,
                                          closable : true,
                                          onhide   : function(dialogRef){
                                          }
                                        });
                                    }
                                    else if(revisit_type == '首次课前回访'){
                                        var revisit_path = ret_info[0]['revisit_path'];
                                        var revisit_person = ret_info[0]['revisit_person'];
                                        var self_intro   = ret_info[0]['self_intro'];
                                        var check_lesson = ret_info[0]['check_lesson'];
                                        var bulid_wx     = ret_info[0]['bulid_wx'];
                                        var parent_intro = ret_info[0]['parent_intro'];
                                        var parent_wx_intro = ret_info[0]['parent_wx_intro'];
                                        var homework_method = ret_info[0]['homework_method'];
                                        var leave_send   = ret_info[0]['leave_send'];
                                        var educate_system = ret_info[0]['educate_system'];
                                        var grade        = ret_info[0]['grade'];
                                        var subject      = ret_info[0]['subject'];
                                        var textbook     = ret_info[0]['textbook'];
                                        var radio = '';
                                        if(self_intro > 0){
                                            radio += "<tr><td>自我介绍</td><td><input type=\"checkbox\" checked /></td></tr>";
                                        }
                                        if(check_lesson > 0){
                                            radio += "<tr><td>上课时间核对</td><td><input type=\"checkbox\" checked /></td></tr>";
                                        }
                                        if(bulid_wx  > 0){
                                            radio += "<tr><td>微信群建立</td><td><input type=\"checkbox\" checked /></td></tr>";
                                        }
                                        if(parent_intro  > 0){
                                            radio += "<tr><td>家长端介绍</td><td><input type=\"checkbox\" checked /></td></tr>";
                                        }
                                        if(parent_wx_intro  > 0){
                                            radio += "<tr><td>家长微信公众号介绍</td><td><input type=\"checkbox\" checked /></td></tr>";
                                        }
                                        if(homework_method  > 0){
                                            radio += "<tr><td>做作业方式</td><td><input type=\"checkbox\" checked /></td></tr>";
                                        }
                                        if(leave_send  > 0){
                                            radio += "<tr><td>请假制度发送</td><td><input type=\"checkbox\" checked /></td></tr>";
                                        }
                                        if(educate_system  > 0){
                                            radio += "<tr><td>学制确认</td><td><input type=\"checkbox\" checked /></td></tr>";
                                        }
                                        if(grade  > 0){
                                            radio += "<tr><td>年级确认</td><td><input type=\"checkbox\" checked /></td></tr>";
                                        }
                                        if(subject  > 0){
                                            radio += "<tr><td>科目确认</td><td><input type=\"checkbox\" checked /></td></tr>";
                                        }
                                        if(textbook  > 0){
                                            radio += "<tr><td>教材版本确认</td><td><input type=\"checkbox\" checked /></td></tr>";
                                        }
                                        var parent_guidance_except  = ret_info[0]['parent_guidance_except'];
                                        var tutorial_subject_info   = ret_info[0]['tutorial_subject_info'];
                                        var other_subject_info      = ret_info[0]['other_subject_info'];
                                        var html_node_ha = $("<div style=\"text-align:center;\"> "
                                                        +"<div id=\"drawing_list\" style=\"width:100%\">"
                                                        +"</div><audio preload=\"none\"></audio></div>"
                                                        +"<div id=\"div_table\"><table class = \"table table-bordered table-striped\"  > "
                                                        +"<tr><td>回访路径</td><td>"+revisit_path+"</td></tr>"
                                                        +"<tr><td>回访对象</td><td>"+revisit_person+"</td></tr>"
                                                        +radio
                                                        +"<tr><td>家长辅导预期</td><td>"+parent_guidance_except+"</td></tr>"
                                                        +"<tr><td>辅导科目情况</td><td>"+tutorial_subject_info+"</td></tr>"
                                                        +"<tr><td>其他科目情况</td><td>"+other_subject_info+"</td></tr>"
                                                        +"</table></div>"
                                                    );
                                        BootstrapDialog.show({
                                          title    : '首次课前回访',
                                          message  : html_node_ha,
                                          closable : true,
                                          onhide   : function(dialogRef){
                                          }
                                        });
                                    }
                                    else if(revisit_type == '其他回访'){
                                        var revisit_path = ret_info[0]['revisit_path'];
                                        var revisit_person = ret_info[0]['revisit_person'];
                                        var recent_learn_info  = ret_info[0]['recent_learn_info'];
                                        var recover_time  = ret_info[0]['recover_time'];
                                        var other_parent_info = ret_info[0]['other_parent_info'];
                                        var other_warning_info = ret_info[0]['other_warning_info'];
                                        var html_node_ha = $("<div style=\"text-align:center;\"> "
                                                        +"<div id=\"drawing_list\" style=\"width:100%\">"
                                                        +"</div><audio preload=\"none\"></audio></div>"
                                                        +"<div id=\"div_table\"><table class = \"table table-bordered table-striped\"  > "
                                                        +"<tr><td>回访路径</td><td>"+revisit_path+"</td></tr>"
                                                        +"<tr><td>回访对象</td><td>"+revisit_person+"</td></tr>"
                                                        +"<tr><td>其他情况说明</td><td>"+recent_learn_info+"</td></tr>"
                                                        +"<tr><td>复课时间</td><td>"+recover_time+"</td></tr>"
                                                        +"<tr><td>家长意见或建议</td><td>"+other_parent_info+"</td></tr>"
                                                        +"<tr><td>其他预警问题</td><td>"+other_warning_info+"</td></tr>"
                                                        +"</table></div>"
                                                    );
                                        BootstrapDialog.show({
                                          title    : '其他回访',
                                          message  : html_node_ha,
                                          closable : true,
                                          onhide   : function(dialogRef){
                                          }
                                        });
                                    }
                                    else if(revisit_type == '学情回访' || revisit_type == '首次回访' || revisit_type == '月度回访' || revisit_type == '系统'){

                                        var revisit_person = ret_info[0]['revisit_person'];
                                        var operator_note  = ret_info[0]['operator_note'];
                                        var operation_satisfy_flag =  ret_info[0]['operation_satisfy_flag'];
                                        if(operation_satisfy_flag < 2){
                                            var operation_satisfy_flag_str = ret_info[0]['operation_satisfy_flag_str'];
                                            var operation_satisfy = "<tr><td>软件操作是否满意</td><td>"+operation_satisfy_flag_str+"</td></tr>";
                                        }else{
                                            operation_satisfy_flag_str = ret_info[0]['operation_satisfy_flag_str'];
                                            var operation_satisfy_type_str = ret_info[0]['operation_satisfy_type_str'];
                                            var operation_satisfy_info = ret_info[0]['operation_satisfy_info'];
                                            operation_satisfy = "<tr><td>软件操作是否满意</td><td>"+operation_satisfy_flag_str+"</td></tr><tr><td>软件操作不满意的类型</td><td>"+operation_satisfy_type_str+"<tr><td>软件操作不满意的具体描述</td><td>"+operation_satisfy_info+"</td></tr>";
                                        }
                                        var child_class_performance_flag = ret_info[0]['child_class_performance_flag'];
                                        if(child_class_performance_flag < 3){
                                            var child_class_performance_flag_str = ret_info[0]['child_class_performance_flag_str'];
                                            var child_class_performance = "<tr><td>孩子课堂表现</td><td>"+child_class_performance_flag_str+"</td></tr>";
                                        }else{
                                            child_class_performance_flag_str = ret_info[0]['child_class_performance_flag_str'];
                                            var child_class_performance_type_str = ret_info[0]['child_class_performance_type_str'];
                                            var child_class_performance_info = ret_info[0]['child_class_performance_info'];
                                            child_class_performance = "<tr><td>孩子课堂表现</td><td>"+child_class_performance_flag_str+"</td></tr><tr><td>孩子课堂表现不好的类型</td><td>"+child_class_performance_type_str+"</td></tr><tr><td>孩子课堂表现不好的具体描述</td><td>"+child_class_performance_info+"</td></tr>";
                                        }
                                        var school_score_change_flag = ret_info[0]['school_score_change_flag'];
                                        if(school_score_change_flag < 2){
                                            var school_score_change_flag_str = ret_info[0]['school_score_change_flag_str'];
                                            var school_score_change = "<tr><td>学校成绩变化</td><td>"+school_score_change_flag_str+"</td></tr>";
                                        }else{
                                            school_score_change_flag_str = ret_info[0]['school_score_change_flag_str'];
                                            var school_score_change_info  = ret_info[0]['school_score_change_info'];
                                            school_score_change = "<tr><td>学校成绩变化</td><td>"+school_score_change_flag_str+"</td></tr><tr><td>学校成绩变差的具体描述</td><td>"+school_score_change_info+"</td></tr>";
                                        }

                                        var school_work_change_flag  = ret_info[0]['school_work_change_flag'];
                                        if(school_work_change_flag < 1 || school_work_change_flag > 1){
                                            var school_work_change_flag_str = ret_info[0]['school_work_change_flag_str'];
                                            var school_work_change = "<tr><td>学业变化</td><td>"+school_work_change_flag_str+"</td></tr>";
                                        }else{
                                            school_work_change_flag_str = ret_info[0]['school_work_change_flag_str'];
                                            var school_work_change_type_str = ret_info[0]['school_work_change_type_str'];
                                            var school_work_change_info  = ret_info[0]['school_work_change_info'];
                                            school_work_change = "<tr><td>学业变化</td><td>"+school_work_change_flag_str+"</td></tr><tr><td>学业变化的类型</td><td>"+school_work_change_type_str+"</td></tr><tr><td>学业变化的具体描述</td><td>"+school_work_change_info+"</td></tr>";
                                            
                                        }
                                        var tea_content_satisfy_flag = ret_info[0]['tea_content_satisfy_flag'];
                                        if(tea_content_satisfy_flag < 3){
                                            var tea_content_satisfy_flag_str = ret_info[0]['tea_content_satisfy_flag_str'];
                                            var tea_content_satisfy = "<tr><td>对于老师or教学是否满意</td><td>"+tea_content_satisfy_flag_str+"</td></tr>";
                                        }else{
                                            tea_content_satisfy_flag_str = ret_info[0]['tea_content_satisfy_flag_str'];
                                            var tea_content_satisfy_type_str = ret_info[0]['tea_content_satisfy_type_str'];
                                            var tea_content_satisfy_info = ret_info[0]['tea_content_satisfy_info'];
                                            tea_content_satisfy = "<tr><td>对于老师or教学是否满意</td><td>"+tea_content_satisfy_flag_str+"</td></tr><tr><td>对于老师or教学不满意的类型</td><td>"+tea_content_satisfy_type_str+"</td></tr><tr><td>对于老师or教学不满意的具体描述"+"</td><td>"+tea_content_satisfy_info+"</td></tr>";
                                        }
                                        var other_parent_info = ret_info[0]['other_parent_info'];
                                        var other_warning_info = ret_info[0]['other_warning_info'];
                                        var html_node_ha = $("<div style=\"text-align:center;\"> "
                                                        +"<div id=\"drawing_list\" style=\"width:100%\">"
                                                        +"</div><audio preload=\"none\"></audio></div>"
                                                        +"<div id=\"div_table\"><table class = \"table table-bordered table-striped\"  > "
                                                        +"<tr><td>回访对象</td><td>"+revisit_person+"</td></tr>"
                                                        +"<tr><td>回访记录</td><td>"+operator_note+"</td></tr>"
                                                        +operation_satisfy
                                                        +child_class_performance
                                                        +school_score_change
                                                        +school_work_change
                                                        +tea_content_satisfy
                                                        +"<tr><td>家长意见或建议</td><td>"+other_parent_info+"</td></tr>"
                                                        +"<tr><td>其他预警问题</td><td>"+other_warning_info+"</td></tr>"
                                                        +"</table></div>"
                                                    );
                                        BootstrapDialog.show({
                                          title    : '学情回访',
                                          message  : html_node_ha,
                                          closable : true,
                                          onhide   : function(dialogRef){
                                          }
                                        });
                                    }

                                }                                                               
                            }
                        });


                        });
                    }
                });

                if (!$.check_in_phone()) {
                    dlg.getModalDialog().css("width", "800px");
                }
            }
        });

    });

});
