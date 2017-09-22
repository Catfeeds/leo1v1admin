    
$(function(){

	//冻结排课操作 
    $(".opt-teacher-freeze").on("click",function(){
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


            $.show_key_value_table("冻结/解冻操作", arr ,[
                {
                    label    : '一键冻结',
                    cssClass : 'btn-danger',
                    action   : function(dialog) {
                        var $input=$("<textarea   placeholder=\"冻结理由\"/>");
                        $.show_input(
                            "确定要冻结该老师的全部年级?! ",
                            "",function(val){
                                $.do_ajax("/ss_deal/freeze_all_teacher_grade",{
                                    'teacherid'       : opt_data.teacherid,
                                    'freeze_type'     :1,
                                    'freeze_reason'       :val
                                });
                            }, $input  );
                        $input.val("");

                    }
                },{
                    label    : '一键解冻',
                    cssClass : 'btn-primary',
                    action   : function(dialog) {
                        var $input=$("<textarea   placeholder=\"解冻理由\"/>");
                        $.show_input(
                            "确定要解冻该老师的全部年级?! ",
                            "",function(val){
                                $.do_ajax("/ss_deal/freeze_all_teacher_grade",{
                                    'teacherid'       : opt_data.teacherid,
                                    'freeze_type'     :2,
                                    'freeze_reason'       :val,
                                    "seller_require_flag":id_seller_require_flag.val()
                                });
                            }, $input  );
                        $input.val("");                       

                    }
                },{
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
                }],function(){
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
    $(".opt-limit-plan-lesson").on("click",function(){
        var opt_data                    = $(this).get_opt_data();
        var teacherid                   = opt_data.teacherid;
        var id_limit_plan_lesson_type   = $("<select/>");
        var id_limit_plan_lesson_reason = $("<textarea/>");
        var id_seller_require_flag=$("<select />");
        var id_limit_type=$("<select><option value=\"-1\">不设置</option><option value=\"1\">CC要求限课</option><option value=\"2\">恢复CC要求的限课</option></select>");
        Enum_map.append_option_list("boolean", id_seller_require_flag, true );
        id_seller_require_flag.val(0);

        Enum_map.append_option_list("limit_plan_lesson_type",id_limit_plan_lesson_type,true);
        var arr= [
            ["限制类型",id_limit_plan_lesson_type],
            ["限制原因",id_limit_plan_lesson_reason],
            ["更改类型",id_limit_type],
            ["是否CC要求",id_seller_require_flag]
        ];        
        id_limit_plan_lesson_type.val(opt_data.limit_plan_lesson_type);
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
                $.do_ajax( '/human_resource/set_teacher_limit_plan_lesson', {
                    "teacherid"          : opt_data.teacherid,
                    "limit_plan_lesson_type":id_limit_plan_lesson_type.val(),
                    "limit_plan_lesson_reason":id_limit_plan_lesson_reason.val(),
                    "seller_require_flag":id_seller_require_flag.val()
                });
            }
        });
        
    });


    //冻结/解冻操作记录
    $(".opt-freeze-list").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var title = "冻结/解冻记录";
        var html_node = $("<div id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>时间</td><td>年级</td><td>操作</td><td>理由</td><td>操作人</td><td>是否CC要求</td></tr></table></div>");                     

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
    $(".opt-limit-plan-lesson-list").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var title = "限课记录";
        var html_node = $("<div id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>时间</td><td>年级</td><td>操作前</td><td>操作后</td><td>理由</td><td>操作人</td><td>是否CC要求</td></tr></table></div>");
        
        

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

    //反馈
    $(".opt-set-teacher-record-old").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        $.do_ajax("/tongji_ss/get_week_test_lesson_list",{
            "teacherid" : teacherid
        },function(response){
            //console.log(response.data);return;
            var data_list   = [];
            var select_list = [];
            $.each( response.data,function(){
                data_list.push([this["lessonid"], this["nick"],this["lesson_start_str"],this["subject_str"],this["grade_str"]]);               
            });

            $(this).admin_select_dlg({
                header_list     : [ "lessonid","学生","时间","科目","年级"],
                data_list       : data_list,
                multi_selection : true,
                select_list     : select_list,
                onChange        : function( select_list,dlg) {
                    console.log(teacherid);
                    dlg.close();
                    /*$.do_ajax("/authority/set_permission",{
                     "uid": uid,
                     "groupid_list":JSON.stringify(select_list)
                     });*/
                    
                    var id_have_kj =  $("<div><span >类型:</span><select id=\"teacher_have_kj\"><option value=\"1\" selected>有无基本上课讲义</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_have_kj_score\" class=\"class_score\" /></div></div>");
                    var id_bk_pp =  $("<div><span >类型:</span><select id=\"teacher_bk_pp\"><option value=\"0\">请选择</option><option value=\"1\">匹配度极差</option><option value=\"2\">匹配度一般</option><option value=\"3\">匹配度良好</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_bk_pp_score\" class=\"class_score\" /></div></div>");
                    var id_kj_zl =  $("<div><span >类型:</span><select id=\"teacher_kj_zl\"><option value=\"0\">请选择</option><option value=\"1\">课件内容层次不清，逻辑混乱</option><option value=\"2\">课件内容层次基本合理，符合教学逻辑</option><option value=\"3\">课件内容层次清晰，难度上循序渐进，重点突出</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_kj_zl_score\" class=\"class_score\" /></div></div>");
                    var id_tea_pro =  $("<div><span >类型:</span><select id=\"teacher_tea_pro\"><option value=\"0\">请选择</option><option value=\"1\">单纯讲练习，缺少相应技巧和知识点讲解</option><option value=\"2\">知识点讲解过多，缺少对应练习和方法技巧归纳</option><option value=\"3\">方法技巧、知识点讲解与对应练习比例得当，课程系统性良好</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_tea_pro_score\" class=\"class_score\" class=\"class_score\" /></div></div>");
                    var id_kt_fw =  $("<div><span >类型:</span><select id=\"teacher_kt_fw\"><option value=\"0\">请选择</option><option value=\"1\">填鸭式教学，鲜少询问学生接受情况</option><option value=\"2\">有互动，但互动方式和引导时机把握不合理，课堂氛围枯燥平淡</option><option value=\"3\">教师引导积极，师生互动紧密，课堂氛围融洽</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_kt_fw_score\" class=\"class_score\" /></div></div>");
                    var id_bs =  $("<div><span >类型:</span><select id=\"teacher_bs\"><option value=\"0\">请选择</option><option value=\"1\">必要板书缺乏，圈画标示过少</option><option value=\"2\">有板书圈画书写，但书写堆砌凌乱，影响教学专业性</option><option value=\"3\">板书规范性良好，内容详实，要点清晰</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_bs_score\" class=\"class_score\" /></div></div>");
                    var id_kcjz =  $("<div><span >类型:</span><select id=\"teacher_kcjz\"><option value=\"0\">请选择</option><option value=\"1\">讲课节奏过慢</option><option value=\"2\">讲课节奏过快</option><option value=\"3\">讲课节奏适中</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_kcjz_score\" class=\"class_score\" /></div></div>");
                    var id_jtff =  $("<div><span >类型:</span><select id=\"teacher_jtff\"><option value=\"1\" selected>讲题方法思路正误</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_jtff_score\" class=\"class_score\" /></div></div>");
                    var id_zsd =  $("<div><span >类型:</span><select id=\"teacher_zsd\"><option value=\"1\" selected>知识点讲解正误</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_zsd_score\" class=\"class_score\" /></div></div>");
                    var id_znd =  $("<div><span >类型:</span><select id=\"teacher_znd\"><option value=\"1\" selected>重难点把握是否到位</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_znd_score\" class=\"class_score\" /></div></div>");
                    var id_kbnr =  $("<div><span >类型:</span><select id=\"teacher_kbnr\"><option value=\"1\" selected>课本内容是否熟悉</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_kbnr_score\" class=\"class_score\" /></div></div>");
                    var id_tmjd =  $("<div><span >类型:</span><select id=\"teacher_tmjd\"><option value=\"1\" selected>题目解答正误</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_tmjd_score\" class=\"class_score\" /></div></div>");
                    var id_yy =  $("<div><span >类型:</span><select id=\"teacher_yy\"><option value=\"0\">请选择</option><option value=\"1\">语言表达能力差，表述不清</option><option value=\"2\">语言表达尚可，但语言组织能力平庸，欠缺感染力</option><option value=\"3\">语言能力良好，讲解生动形象，富有感染力</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_yy_score\" class=\"class_score\" /></div></div>");
                    var id_jxtd =  $("<div><span >类型:</span><select id=\"teacher_jxtd\"><option value=\"0\">请选择</option><option value=\"1\">教学态度恶劣，侮辱谩骂学生，打击学生自信心</option><option value=\"2\">教学态度散漫随意，如疲态明显，哈欠连天或课堂随意嬉笑等</option><option value=\"3\">教学态度一般，无上课激情或带敷衍态度</option><option value=\"4\">教学态度基本端正，认真负责</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_jxtd_score\" class=\"class_score\" /></div></div>");
                    var id_jxzzd =  $("<div><span >类型:</span><select id=\"teacher_jxzzd\"><option value=\"0\">请选择</option><option value=\"1\">讲课过程中从事教学无关事务如吃东西、 接打电话、 闲聊等</option><option value=\"2\">讲课过程大量留白，延迟回答学生问题、无故让学生等待耽误上课时间</option><option value=\"3\">教学状态良好，课堂专注力佳</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_jxzzd_score\" class=\"class_score\" /></div></div>");
                    var id_jxsg =  $("<div><span >类型:</span><select id=\"teacher_jxsg\"><option value=\"0\">请选择</option><option value=\"1\">推荐其他机构，贬低公司价值</option><option value=\"2\">课件全程无理优logo或使用明显带有其他机构logo的资料</option><option value=\"3\">议论其他员工、泄露公司相关信息</option><option value=\"4\">课程顺利完成，无相关教学事故</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_jxsg_score\" class=\"class_score\" /></div></div>");
                    var id_rjcz =  $("<div><span >类型:</span><select id=\"teacher_rjcz\"><option value=\"0\">请选择</option><option value=\"1\">讲义无截图，纯拍照上传</option><option value=\"2\">讲义截图不清晰且放置位置不合理</option><option value=\"3\">讲义截图清晰但放置位置不合理</option><option value=\"4\">截图清晰且位置放置合理</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_rjcz_score\" class=\"class_score\" /></div></div>");
                    var id_kcyc =  $("<div><span >类型:</span><select id=\"teacher_kcyc\"><option value=\"0\">请选择</option><option value=\"1\">课中遇到网络卡断，音频问题，异常闪退或课程延迟时，慌乱抱怨</option><option value=\"2\">面对课程异常情况，虽有着手处理但处理过于缓慢，耽误上课时间</option><option value=\"3\">面对异常情况，及时冷静处理，顺利解决</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_kcyc_score\" class=\"class_score\" /></div></div>");
                    var id_hj =  $("<div><span >类型:</span><select id=\"teacher_hj\"><option value=\"0\">请选择</option><option value=\"1\">教学环境嘈杂；网络音频状况不佳，影响课程体验</option><option value=\"2\">教学环境安静，教学设备状况调试良好</option></select><div style=\"float:right\"><span >评分:</span><select id=\"teacher_hj_score\" class=\"class_score\" /></div></div>");
                    var id_sshd=$("<label><input name=\"Fruit\" type=\"checkbox\" value=\"6\" />幽默风趣 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"7\" />生动活泼 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"8\" />循循善诱 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"9\" />细致耐心 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"10\" />考纲熟悉 </label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"11\" />善于互动</label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"12\" />没有口音</label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"13\" />经验丰富</label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"14\" />功底扎实</label> ");


                    Enum_map.append_option_list("teacher_lecture_score",id_have_kj.find("#teacher_have_kj_score"),false,[0,1,2,3,4,5]);
                    Enum_map.append_option_list("teacher_lecture_score",id_jtff.find("#teacher_jtff_score"),false,[0,1,2,3,4,5]);
                    Enum_map.append_option_list("teacher_lecture_score",id_zsd.find("#teacher_zsd_score"),false,[0,1,2,3,4,5]);
                    Enum_map.append_option_list("teacher_lecture_score",id_znd.find("#teacher_znd_score"),false,[0,1,2,3,4,5]);
                    Enum_map.append_option_list("teacher_lecture_score",id_kbnr.find("#teacher_kbnr_score"),false,[0,1,2,3,4,5]);
                    Enum_map.append_option_list("teacher_lecture_score",id_tmjd.find("#teacher_tmjd_score"),false,[0,1,2,3,4,5]);
                    Enum_map.append_option_list("teacher_lecture_score",id_bk_pp.find("#teacher_bk_pp_score"));
                    Enum_map.append_option_list("teacher_lecture_score",id_kj_zl.find("#teacher_kj_zl_score"));
                    Enum_map.append_option_list("teacher_lecture_score",id_tea_pro.find("#teacher_tea_pro_score"));
                    Enum_map.append_option_list("teacher_lecture_score",id_kt_fw.find("#teacher_kt_fw_score"));
                    Enum_map.append_option_list("teacher_lecture_score",id_bs.find("#teacher_bs_score"));
                    Enum_map.append_option_list("teacher_lecture_score",id_kcjz.find("#teacher_kcjz_score"));
                    Enum_map.append_option_list("teacher_lecture_score",id_yy.find("#teacher_yy_score"));
                    Enum_map.append_option_list("test_lesson_score",id_jxtd.find("#teacher_jxtd_score"),false,[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);
                    Enum_map.append_option_list("test_lesson_score",id_jxzzd.find("#teacher_jxzzd_score"),false,[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);
                    Enum_map.append_option_list("test_lesson_score",id_jxsg.find("#teacher_jxsg_score"));
                    Enum_map.append_option_list("test_lesson_score",id_rjcz.find("#teacher_rjcz_score"));
                    Enum_map.append_option_list("test_lesson_score",id_kcyc.find("#teacher_kcyc_score"),false,[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30]);
                    Enum_map.append_option_list("test_lesson_score",id_hj.find("#teacher_hj_score"),false,[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]);
                    var id_score = $("<input readonly/>");
                    var id_rank = $("<input readonly/>");
                    var id_record = $("<textarea />");
                    var id_jkqk = $("<textarea />");

                    var arr=[
                        ["有无课件", id_have_kj],
                        ["备课内容与试听需求匹配", id_bk_pp],
                        ["课件质量", id_kj_zl],
                        ["教学过程设计", id_tea_pro],
                        ["课堂氛围", id_kt_fw],
                        ["板书书写", id_bs],
                        ["课程节奏", id_kcjz],
                        ["讲题方法思路", id_jtff],
                        ["知识点讲解", id_zsd],
                        ["重难点把握", id_znd],
                        ["课本内容熟悉程度", id_kbnr],
                        ["题目解答", id_tmjd],
                        ["语言表达和组织能力", id_yy],
                        ["教学态度", id_jxtd],
                        ["教学专注度", id_jxzzd],
                        ["教学事故", id_jxsg],
                        ["软件操作", id_rjcz],
                        ["课程异常情况处理", id_kcyc],
                        ["周边环境", id_hj],
                        ["总分",id_score],
                        ["等级",id_rank],
                        ["监课情况",id_jkqk],
                        ["意见或建议",id_record],
                        ["标签",id_sshd]
                    ];
                    
                    id_bk_pp.find("#teacher_bk_pp").on("change",function(){
                        if($(this).val() == 1){
                            id_bk_pp.find("#teacher_bk_pp_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_bk_pp.find("#teacher_bk_pp_score"),false,[3,4,5]);
                        }else if($(this).val() == 2){
                            id_bk_pp.find("#teacher_bk_pp_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_bk_pp.find("#teacher_bk_pp_score"),false,[6,7]);
                        }else if($(this).val() == 3){
                            id_bk_pp.find("#teacher_bk_pp_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_bk_pp.find("#teacher_bk_pp_score"),false,[8,9,10]);
                        }else{
                            id_bk_pp.find("#teacher_bk_pp_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_bk_pp.find("#teacher_bk_pp_score"));
                        }

                    });
                    id_kj_zl.find("#teacher_kj_zl").on("change",function(){
                        if($(this).val() == 1){
                            id_kj_zl.find("#teacher_kj_zl_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kj_zl.find("#teacher_kj_zl_score"),false,[3,4,5]);
                        }else if($(this).val() == 2){
                            id_kj_zl.find("#teacher_kj_zl_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kj_zl.find("#teacher_kj_zl_score"),false,[6,7]);
                        }else if($(this).val() == 3){
                            id_kj_zl.find("#teacher_kj_zl_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kj_zl.find("#teacher_kj_zl_score"),false,[8,9,10]);
                        }else{
                            id_kj_zl.find("#teacher_kj_zl_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kj_zl.find("#teacher_kj_zl_score"));
                        }

                    });
                    id_tea_pro.find("#teacher_tea_pro").on("change",function(){
                        if($(this).val() == 1){
                            id_tea_pro.find("#teacher_tea_pro_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_tea_pro.find("#teacher_tea_pro_score"),false,[4,5,6]);
                        }else if($(this).val() == 2){
                            id_tea_pro.find("#teacher_tea_pro_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_tea_pro.find("#teacher_tea_pro_score"),false,[4,5,6]);
                        }else if($(this).val() == 3){
                            id_tea_pro.find("#teacher_tea_pro_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_tea_pro.find("#teacher_tea_pro_score"),false,[7,8,9,10]);
                        }else{
                            id_tea_pro.find("#teacher_tea_pro_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_tea_pro.find("#teacher_tea_pro_score"));
                        }
                    });
                    id_kt_fw.find("#teacher_kt_fw").on("change",function(){
                        if($(this).val() == 1){
                            id_kt_fw.find("#teacher_kt_fw_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kt_fw.find("#teacher_kt_fw_score"),false,[3,4,5]);
                        }else if($(this).val() == 2){
                            id_kt_fw.find("#teacher_kt_fw_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kt_fw.find("#teacher_kt_fw_score"),false,[6,7]);
                        }else if($(this).val() == 3){
                            id_kt_fw.find("#teacher_kt_fw_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kt_fw.find("#teacher_kt_fw_score"),false,[8,9,10]);
                        }else{
                            id_kt_fw.find("#teacher_kt_fw_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kt_fw.find("#teacher_kt_fw_score"));
                        }

                    });
                    id_bs.find("#teacher_bs").on("change",function(){
                        if($(this).val() == 1){
                            id_bs.find("#teacher_bs_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_bs.find("#teacher_bs_score"),false,[3,4,5]);
                        }else if($(this).val() == 2){
                            id_bs.find("#teacher_bs_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_bs.find("#teacher_bs_score"),false,[6,7]);
                        }else if($(this).val() == 3){
                            id_bs.find("#teacher_bs_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_bs.find("#teacher_bs_score"),false,[8,9,10]);
                        }else{
                            id_bs.find("#teacher_bs_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_bs.find("#teacher_bs_score"));
                        }

                    });
                    id_kcjz.find("#teacher_kcjz").on("change",function(){
                        if($(this).val() == 1){
                            id_kcjz.find("#teacher_kcjz_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kcjz.find("#teacher_kcjz_score"),false,[4,5,6]);
                        }else if($(this).val() == 2){
                            id_kcjz.find("#teacher_kcjz_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kcjz.find("#teacher_kcjz_score"),false,[4,5,6]);
                        }else if($(this).val() == 3){
                            id_kcjz.find("#teacher_kcjz_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kcjz.find("#teacher_kcjz_score"),false,[7,8,9,10]);
                        }else{
                            id_kcjz.find("#teacher_kcjz_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_kcjz.find("#teacher_kcjz_score"));
                        }

                    });
                    id_yy.find("#teacher_yy").on("change",function(){
                        if($(this).val() == 1){
                            id_yy.find("#teacher_yy_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_yy.find("#teacher_yy_score"),false,[3,4,5]);
                        }else if($(this).val() == 2){
                            id_yy.find("#teacher_yy_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_yy.find("#teacher_yy_score"),false,[6,7]);
                        }else if($(this).val() == 3){
                            id_yy.find("#teacher_yy_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_yy.find("#teacher_yy_score"),false,[8,9,10]);
                        }else{
                            id_yy.find("#teacher_yy_score").find("option").remove(); 
                            Enum_map.append_option_list("teacher_lecture_score",id_yy.find("#teacher_yy_score"));
                        }

                    });
                    id_jxtd.find("#teacher_jxtd").on("change",function(){
                        if($(this).val() == 1){
                            id_jxtd.find("#teacher_jxtd_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxtd.find("#teacher_jxtd_score"),true,[0]);
                        }else if($(this).val() == 2){
                            id_jxtd.find("#teacher_jxtd_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxtd.find("#teacher_jxtd_score"),false,[5,6,7,8,9,10]);
                        }else if($(this).val() == 3){
                            id_jxtd.find("#teacher_jxtd_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxtd.find("#teacher_jxtd_score"),false,[10,15,20]);
                        }else if($(this).val() == 4){
                            id_jxtd.find("#teacher_jxtd_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxtd.find("#teacher_jxtd_score"),false,[20,21,22,23,24,25]);

                        }else{
                            id_jxtd.find("#teacher_jxtd_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxtd.find("#teacher_jxtd_score"),false,[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);
                        }

                    });
                    id_jxzzd.find("#teacher_jxzzd").on("change",function(){
                        if($(this).val() == 1){
                            id_jxzzd.find("#teacher_jxzzd_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxzzd.find("#teacher_jxzzd_score"),false,[5,6,7,8,9,10]);
                        }else if($(this).val() == 2){
                            id_jxzzd.find("#teacher_jxzzd_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxzzd.find("#teacher_jxzzd_score"),false,[10,15,20]);      
                        }else if($(this).val() == 3){
                            id_jxzzd.find("#teacher_jxzzd_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxzzd.find("#teacher_jxzzd_score"),false,[20,21,22,23,24,25]);          
                        }else{
                            id_jxzzd.find("#teacher_jxzzd_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxzzd.find("#teacher_jxzzd_score"),false,[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]);
                            
                        }

                    });
                    id_jxsg.find("#teacher_jxsg").on("change",function(){
                        if($(this).val() == 1){
                            id_jxsg.find("#teacher_jxsg_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxsg.find("#teacher_jxsg_score"),true,[0]);
                        }else if($(this).val() == 2){
                            id_jxsg.find("#teacher_jxsg_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxsg.find("#teacher_jxsg_score"),false,[10,15,20]);
                        }else if($(this).val() == 3){
                            id_jxsg.find("#teacher_jxsg_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxsg.find("#teacher_jxsg_score"),false,[20,25,30,35,40]);
                        }else if($(this).val() == 4){
                            id_jxsg.find("#teacher_jxsg_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxsg.find("#teacher_jxsg_score"),true,[50]);
                        }else{
                            id_jxsg.find("#teacher_jxsg_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_jxsg.find("#teacher_jxsg_score"));
                        }

                    });
                    id_rjcz.find("#teacher_rjcz").on("change",function(){
                        if($(this).val() == 1){
                            id_rjcz.find("#teacher_rjcz_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_rjcz.find("#teacher_rjcz_score"),false,[0,1,2,3,4,5,6,7,8,9,10]);
                        }else if($(this).val() == 2){
                            id_rjcz.find("#teacher_rjcz_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_rjcz.find("#teacher_rjcz_score"),false,[10,15,20]);     
                        }else if($(this).val() == 3){
                            id_rjcz.find("#teacher_rjcz_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_rjcz.find("#teacher_rjcz_score"),false,[20,25,30,35,40]);         
                        }else if($(this).val() == 4){
                            id_rjcz.find("#teacher_rjcz_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_rjcz.find("#teacher_rjcz_score"),false,[40,45,50]);         
                        }else{
                            id_rjcz.find("#teacher_rjcz_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_rjcz.find("#teacher_rjcz_score"));
                            
                        }

                    });

                    id_kcyc.find("#teacher_kcyc").on("change",function(){
                        if($(this).val() == 1){
                            id_kcyc.find("#teacher_kcyc_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_kcyc.find("#teacher_kcyc_score"),false,[5,6,7,8,9,10]);
                        }else if($(this).val() == 2){
                            id_kcyc.find("#teacher_kcyc_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_kcyc.find("#teacher_kcyc_score"),false,[10,15,20]);
                        }else if($(this).val() == 3){
                            id_kcyc.find("#teacher_kcyc_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_kcyc.find("#teacher_kcyc_score"),false,[20,25,30]);
                        }else{
                            id_kcyc.find("#teacher_kcyc_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_kcyc.find("#teacher_kcyc_score"),false,[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30]);
                        }

                    });
                    id_hj.find("#teacher_hj").on("change",function(){
                        if($(this).val() == 1){
                            id_hj.find("#teacher_hj_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_hj.find("#teacher_hj_score"),false,[5,6,7,8,9,10]);
                        }else if($(this).val() == 2){
                            id_hj.find("#teacher_hj_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_hj.find("#teacher_hj_score"),false,[10,15,20]);
                        }else{
                            id_hj.find("#teacher_hj_score").find("option").remove(); 
                            Enum_map.append_option_list("test_lesson_score",id_hj.find("#teacher_hj_score"),false,[5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]);
                        }

                    });

                    $.show_key_value_table("试听评价", arr,{
                        label    : '确认',
                        cssClass : 'btn-warning',
                        action   : function(dialog) {
                            var record_info = id_record.val();
                            if(record_info==""){
                                BootstrapDialog.alert("请填写评价内容!");
                                return ;
                            }
                            console.log(record_info.length);
                            if(record_info.length>150){
                                BootstrapDialog.alert("评价内容不能超过150字!");
                                return ;
                            }

                            var sshd_good=[];
                            id_sshd.find("input:checkbox[name='Fruit']:checked").each(function(i) {
                                sshd_good.push($(this).val());
                            });
                            if(sshd_good.length==0){
                                BootstrapDialog.alert("请选择老师标签");
                                return false;
                            }

                            $.do_ajax("/human_resource/set_teacher_record_info",{
                                "teacherid"    : teacherid,
                                "type"         : 1,
                                "courseware_flag"              : id_have_kj.find("#teacher_have_kj").find("option:selected").text(),
                                "courseware_flag_score"        : id_have_kj.find("#teacher_have_kj_score").val(),
                                "lesson_preparation_content"   : id_bk_pp.find("#teacher_bk_pp").find("option:selected").text(),
                                "lesson_preparation_content_score"   : id_bk_pp.find("#teacher_bk_pp_score").val(),        
                                "courseware_quality"          : id_kj_zl.find("#teacher_kj_zl").find("option:selected").text(),
                                "courseware_quality_score"    : id_kj_zl.find("#teacher_kj_zl_score").val(),
                                "tea_process_design"          : id_tea_pro.find("#teacher_tea_pro").find("option:selected").text(),
                                "tea_process_design_score"    : id_tea_pro.find("#teacher_tea_pro_score").val(),     
                                "class_atm"                   : id_kt_fw.find("#teacher_kt_fw").find("option:selected").text(),
                                "class_atm_score"             : id_kt_fw.find("#teacher_kt_fw_score").val(),     
                                "knw_point"                   : id_zsd.find("#teacher_zsd").find("option:selected").text(),
                                "knw_point_score"             : id_zsd.find("#teacher_zsd_score").val(),     
                                "dif_point"                   : id_znd.find("#teacher_znd").find("option:selected").text(),
                                "dif_point_score"             : id_znd.find("#teacher_znd_score").val(),     
                                "teacher_blackboard_writing"         : id_bs.find("#teacher_bs").find("option:selected").text(),
                                "teacher_blackboard_writing_score"   : id_bs.find("#teacher_bs_score").val(),     
                                "tea_rhythm"             : id_kcjz.find("#teacher_kcjz").find("option:selected").text(),
                                "tea_rhythm_score"       : id_kcjz.find("#teacher_kcjz_score").val(),     
                                "language_performance"       : id_yy.find("#teacher_yy").find("option:selected").text(),
                                "language_performance_score" : id_yy.find("#teacher_yy_score").val(),
                                "content_fam_degree"       : id_kbnr.find("#teacher_kbnr").find("option:selected").text(),
                                "content_fam_degree_score" : id_kbnr.find("#teacher_kbnr_score").val(),     
                                "answer_question_cre"       : id_tmjd.find("#teacher_tmjd").find("option:selected").text(),
                                "answer_question_cre_score" : id_tmjd.find("#teacher_tmjd_score").val(),     
                                "tea_attitude"       : id_jxtd.find("#teacher_jxtd").find("option:selected").text(),
                                "tea_attitude_score" : id_jxtd.find("#teacher_jxtd_score").val(),
                                "tea_method"       : id_jtff.find("#teacher_jtff").find("option:selected").text(),
                                "tea_method_score" : id_jtff.find("#teacher_jtff_score").val(),
                                "tea_concentration"       : id_jxzzd.find("#teacher_jxzzd").find("option:selected").text(),
                                "tea_concentration_score" : id_jxzzd.find("#teacher_jxzzd_score").val(),
                                "tea_accident"       : id_jxsg.find("#teacher_jxsg").find("option:selected").text(),
                                "tea_accident_score" : id_jxsg.find("#teacher_jxsg_score").val(),     
                                "tea_operation"                  : id_rjcz.find("#teacher_rjcz").find("option:selected").text(),
                                "tea_operation_score"            : id_rjcz.find("#teacher_rjcz_score").val(),     
                                "tea_environment"                : id_hj.find("#teacher_hj").find("option:selected").text(),
                                "tea_environment_score"          : id_hj.find("#teacher_hj_score").val(),     
                                "record_score"              : id_score.val(),
                                "class_abnormality"              : id_kcyc.find("#teacher_kcyc").find("option:selected").text(),
                                "class_abnormality_score"        : id_kcyc.find("#teacher_kcyc_score").val(), 
                                "record_info"                    : id_record.val(),
                                "record_monitor_class"           :id_jkqk.val(),
                                "record_rank"                    :id_rank.val(),
                                "record_lessonid_list"           :JSON.stringify(select_list),
                                "sshd_good"                          :JSON.stringify(sshd_good),                               
                            });
                        }
                    },function(){
                        id_score.attr("placeholder","满分100分");
                        id_record.attr("placeholder","字数不能超过150字");
                    });

                    //console.log(arr[0][1]);
                    arr[0][1].parent().parent().parent().parent().parent().parent().parent().find(".class_score").on("change",function(){
                        id_score.val(parseInt(0.6*(parseInt(id_bk_pp.find("#teacher_bk_pp_score").val())+parseInt(id_have_kj.find("#teacher_have_kj_score").val())+parseInt(id_kj_zl.find("#teacher_kj_zl_score").val())+parseInt(id_tea_pro.find("#teacher_tea_pro_score").val())+parseInt(id_kt_fw.find("#teacher_kt_fw_score").val())+parseInt(id_bs.find("#teacher_bs_score").val())+parseInt(id_kcjz.find("#teacher_kcjz_score").val())+parseInt(id_jtff.find("#teacher_jtff_score").val())+parseInt(id_zsd.find("#teacher_zsd_score").val())+parseInt(id_znd.find("#teacher_znd_score").val())+parseInt(id_kbnr.find("#teacher_kbnr_score").val())+parseInt(id_tmjd.find("#teacher_tmjd_score").val())+parseInt(id_yy.find("#teacher_yy_score").val()))+0.3*(parseInt(id_jxtd.find("#teacher_jxtd_score").val())+parseInt(id_jxzzd.find("#teacher_jxzzd_score").val())+parseInt(id_jxsg.find("#teacher_jxsg_score").val()))+0.1*(parseInt(id_rjcz.find("#teacher_rjcz_score").val())+parseInt(id_kcyc.find("#teacher_kcyc_score").val())+parseInt(id_hj.find("#teacher_hj_score").val()))));
                        if(id_score.val()>90 && id_score.val() <= 100){
                            id_rank.val("S");
                        }else if(id_score.val()>80 && id_score.val()<=90){
                            id_rank.val("A");
                        }else if(id_score.val()>70 && id_score.val()<=80){
                            id_rank.val("B");
                        }else{
                            id_rank.val("C");
                        }
                    });
                    arr[0][1].parent().parent().parent().parent().parent().parent().parent().parent().css("width",970);
                    arr[0][1].parent().parent().parent().parent().parent().parent().parent().parent().css("left",-200);


                }
            });
        }) ;

        
        
    });

    //反馈记录

    $(".opt-get-teacher-record").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var title = "反馈记录";
        var html_node = $("<div id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>时间</td><td>内容</td><td>评分</td><td>反馈人</td><tr></table></div>");
        
        $.do_ajax("/human_resource/get_teacher_record_list",{
            "teacherid" : teacherid,
            "type"      : 1
        },function(result){
            if(result.ret!=0){
                BootstrapDialog.alert(result.info);
                return ;
            }

            $.each(result.data,function(i,item){
                html_node.find("table").append("<tr><td>"+item['add_time_str']+"</td><td>"+item['record_info']+"</td><td>"+item['record_score']+"</td><td>"+item['acc']+"</td></tr>");

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

    $(".opt-set-teacher-record-new").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        $.do_ajax("/tongji_ss/get_week_test_lesson_list",{
            "teacherid" : teacherid
        },function(response){
            var data_list   = [];
            var select_list = [];
            $.each( response.data,function(){
                data_list.push([this["lessonid"], this["nick"],this["lesson_start_str"],this["subject_str"],this["grade_str"]]);
            });

            $(this).admin_select_dlg({
                header_list     : [ "lessonid","学生","时间","科目","年级"],
                data_list       : data_list,
                multi_selection : true,
                select_list     : select_list,
                onChange        : function( select_list,dlg) {
                    console.log("teacherid:"+teacherid);
                    dlg.close();
                    
                    var id_jysj =  $("<select class=\"class_score\" />");
                    var id_yybd =  $("<select class=\"class_score\" />");
                    var id_zyzs =  $("<select class=\"class_score\" />");
                    var id_jxjz =  $("<select class=\"class_score\" />");
                    var id_hdqk =  $("<select class=\"class_score\" />");
                    var id_bsqk =  $("<select class=\"class_score\" />");
                    var id_rjcz =  $("<select class=\"class_score\" />");
                    var id_skhj =  $("<select class=\"class_score\" />");
                    var id_khfk =  $("<select class=\"class_score\" />");
                    var id_lcgf =  $("<select class=\"class_score\" />"); 
                    var id_sshd=$("<label><input name=\"Fruit\" type=\"checkbox\" value=\"6\" />幽默风趣 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"7\" />生动活泼 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"8\" />循循善诱 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"9\" />细致耐心 </label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"10\" />考纲熟悉 </label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"11\" />善于互动</label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"12\" />没有口音</label> <label><input name=\"Fruit\" type=\"checkbox\" value=\"13\" />经验丰富</label>  <label><input name=\"Fruit\" type=\"checkbox\" value=\"14\" />功底扎实</label> ");


                    Enum_map.append_option_list("teacher_lecture_score",id_jysj,true,[0,1,2,3,4,5,6,7,8,9,10]);
                    Enum_map.append_option_list("teacher_lecture_score",id_yybd,true,[0,1,2,3,4,5,6,7,8,9,10]);
                    Enum_map.append_option_list("teacher_lecture_score",id_zyzs,true,[0,1,2,3,4,5,6,7,8,9,10]);
                    Enum_map.append_option_list("teacher_lecture_score",id_jxjz,true,[0,1,2,3,4,5,6,7,8,9,10]);
                    Enum_map.append_option_list("teacher_lecture_score",id_hdqk,true,[0,1,2,3,4,5,6,7,8,9,10]);
                    Enum_map.append_option_list("teacher_lecture_score",id_bsqk,true,[0,1,2,3,4,5,6,7,8,9,10]);
                    Enum_map.append_option_list("teacher_lecture_score",id_rjcz,true,[0,1,2,3,4,5,6,7,8,9,10]);
                    Enum_map.append_option_list("teacher_lecture_score",id_skhj,true,[0,1,2,3,4,5]);
                    Enum_map.append_option_list("teacher_lecture_score",id_khfk,true,[0,1,2,3,4,5,6,7,8,9,10]);
                    Enum_map.append_option_list("test_lesson_score",id_lcgf,true,[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]);
                    var id_score = $("<input readonly/>");
                    var id_no_tea_score = $("<input readonly/>");
                    var id_record = $("<textarea />");
                    var id_jkqk = $("<textarea />");

                    var arr=[
                        ["讲义设计情况评分", id_jysj],
                        ["语言表达能力评分", id_yybd],
                        ["专业知识技能评分", id_zyzs],
                        ["教学节奏把握评分", id_jxjz],
                        ["互动情况评分", id_hdqk],
                        ["板书情况评分", id_bsqk],
                        ["软件操作评分", id_rjcz],
                        ["授课环境评分", id_skhj],
                        ["课后反馈评分", id_khfk],
                        ["流程规范情况评分", id_lcgf],
                        ["总分",id_score],
                        ["非教学相关得分",id_no_tea_score],
                        ["监课情况",id_jkqk],
                        ["意见或建议",id_record],
                        ["老师标签",id_sshd]
                    ];
                    
                    $.show_key_value_table("试听评价", arr,{
                        label    : '确认',
                        cssClass : 'btn-warning',
                        action   : function(dialog) {
                            var record_info = id_record.val();
                            if(record_info==""){
                                BootstrapDialog.alert("请填写评价内容!");
                                return ;
                            }

                            if(record_info.length>150){
                                BootstrapDialog.alert("评价内容不能超过150字!");
                                return ;
                            }

                            var sshd_good=[];
                            id_sshd.find("input:checkbox[name='Fruit']:checked").each(function(i) {
                                sshd_good.push($(this).val());
                            });
                            if(sshd_good.length==0){
                                BootstrapDialog.alert("请选择老师标签");
                                return false;
                            }
                           
                            $.do_ajax("/human_resource/set_teacher_record_info_new",{
                                "teacherid"                        : teacherid,
                                "type"                             : 1,
                                "tea_process_design_score"         : id_jysj.val(),
                                "language_performance_score"       : id_yybd.val(),
                                "knw_point_score"                  : id_zyzs.val(),
                                "tea_rhythm_score"                 : id_jxjz.val(),
                                "tea_concentration_score"          : id_hdqk.val(),
                                "teacher_blackboard_writing_score" : id_bsqk.val(),
                                "tea_operation_score"              : id_rjcz.val(),
                                "tea_environment_score"            : id_skhj.val(),
                                "answer_question_cre_score"        : id_khfk.val(),
                                "class_abnormality_score"          : id_lcgf.val(),
                                "score"                            : id_score.val(),
                                "no_tea_related_score"             : id_no_tea_score.val(),
                                "record_info"                      : id_record.val(),
                                "record_monitor_class"             : id_jkqk.val(),
                                "record_lessonid_list"             : JSON.stringify(select_list),
                                "sshd_good"                        : JSON.stringify(sshd_good)
                            });
                        }
                    },function(){
                        id_score.attr("placeholder","满分100分");
                        id_record.attr("placeholder","字数不能超过150字");
                    });

                    //console.log(arr[0][1]);
                    arr[0][1].parent().parent().parent().parent().parent().parent().parent().find(".class_score").on("change",function(){
                        id_score.val(parseInt(id_jysj.val())+parseInt(id_yybd.val())+parseInt(id_zyzs.val())+parseInt(id_jxjz.val())+parseInt(id_hdqk.val())+parseInt(id_bsqk.val())+parseInt(id_rjcz.val())+parseInt(id_skhj.val())+parseInt(id_khfk.val())+parseInt(id_lcgf.val()));
                        id_no_tea_score.val(parseInt(id_hdqk.val())+parseInt(id_bsqk.val())+parseInt(id_rjcz.val())+parseInt(id_skhj.val())+parseInt(id_khfk.val())+parseInt(id_lcgf.val()));

                        
                    });
                    

                }
            });
        }) ;

        
        
    });


});



