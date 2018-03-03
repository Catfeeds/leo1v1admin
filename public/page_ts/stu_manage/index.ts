/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-index.d.ts" />
/// <reference path="./stu.d.ts" />

$(function(){
    Enum_map.td_show_desc("subject", $(".td-subject"));

    $('#id_add_user_parent').on('click', function(){
        var studentid = $(this).parent().data('studentid');
        var $phone=$("<input/>");
        var $parent_name=$("<input/>");
        var $parent_type=$("<select><option value=\"1\">父亲</option><option value=\"2\">母亲</option><option value=\"3\">爷爷</option><option value=\"4\">奶奶</option><option value=\"5\">外公</option><option value=\"6\">外婆</option><option value=\"7\">其他</option></select>");

        $.do_ajax("/stu_manage/get_stu_parent",{
            'studentid':studentid
        },function(result){
            if(result.phone!=0){
                $phone.val(result.phone);
                $parent_type.val(result.parent_type);
                $parent_name.val(result.parent_name);
            }

            var arr=[
                ["家长电话",$phone],
                ["家长名字",$parent_name],
                ["家长关系",$parent_type],
            ];

            $.show_key_value_table("绑定家长", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.ajax({
                        url: '/stu_manage/set_stu_parent',
                        type: 'POST',
                        dataType: 'json',
                        data : {
                            'studentid' : studentid,
                            'sid' : studentid,
                            'phone'     : $phone.val(),
                            'parent_name'     : $parent_name.val(),
                            'parent_type'     : $parent_type.val()
                  },
                        success: function(data) {
                            if(data.ret==0){
                                window.location.reload();
                            }else{
                                BootstrapDialog.alert(data.info);
                            }
                        }
                    });
                }
            });
        });
    });

  $(' .opt-select-teacher').on('click',function(){
        var courseid =  $(this).parent().data("courseid");

        $.admin_select_user($(this),"teacher",function(id){
            $.do_ajax( '/stu_manage/change_manage_id',
                       {
                   'courseid' : courseid,
                   'opt_type' : "teacher",
                           'userid'   : g_sid,
                   'opt_id'   : id
                 });
        } );
    });


  $('.opt-select-assistant  ').on('click',function(){
        var courseid =  $(this).parent().data("courseid");
        $(this).admin_select_user({
            "show_select_flag":true,
            "type":"assistant",
            "onChange":function(val){
                var id = val;
                $.do_ajax( '/stu_manage/change_manage_id',
                    {
                'courseid': courseid,
                'opt_type': "assistant",
                        'userid'  : g_sid,
                'opt_id': id
              },function(){

                    });
            }
        });
  });



    $("#id_set_user").on("click",function(){
        var html_node = $.dlg_need_html_by_id("id_dlg_set_user" );
        var opt_data=$("#id_stu_info").data();
        html_node.find("#id_name").val(opt_data.nick );
        html_node.find("#id_realname").val(opt_data.realname);
        html_node.find("#id_gender").val(opt_data.gender);
      //  html_node.find("#id_parent_name").val(opt_data.parent_name);
        html_node.find("#id_birth").val(opt_data.birth );
      //  html_node.find("#id_parent_type").val(opt_data.parent_type);
        html_node.find("#id_address").val(opt_data.address);
        html_node.find("#id_school").val(opt_data.school);
        html_node.find("#id_stu_email").val(opt_data.stu_email);

        Enum_map.append_option_list( "region_version", html_node.find("#id_textbook"), true );

        html_node.find("#id_textbook").val(opt_data.editionid );
       // html_node.find("#id_region").val(opt_data.region);

        html_node.find("#id_birth").datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Ymd'
        });

        var old_province = opt_data.region;
        if(old_province == ''){
            old_province="选择省（市）";
        }

        var old_city = opt_data.city;
        if(old_city == ''){
            old_city="选择市（区）";
        }
        var old_area = opt_data.area;
        if(old_area == ''){
            old_city="选择区（县）";
        }

        var province = html_node.find("#province");
        var city = html_node.find("#city");
        var area = html_node.find("#area");
        var preProvince = "<option value=\"\">"+old_province+"</option>";  
        var preCity = "<option value=\"\">"+old_city+"</option>";  
        var preArea = "<option value=\"\">"+old_area+"</option>";  

        //初始化
        province.html(preProvince);  
        city.html(preCity);  
        area.html(preArea);
        
        //文档加载完毕:即从province_city_select_Info.xml获取数据,成功之后采用  
        //func_suc_getXmlProvice进行 省的 解析  
        $.ajax({  
            type : "GET",  
            url : "/province_city_select_Info.xml",  
            success : func_suc_getXmlProvice  
        });  
        
        //省 下拉选择发生变化触发的事件  
        province.change(function() {  
            //province.val()  : 返回是每个省对应的下标,序号从0开始  
            if (province.val() != "") {  
                if(opt_data.region != html_node.find("#province").find("option:selected").text()){
                    var preCity = "<option value=\"\">选择市（区）</option>";  
                    var preArea = "<option value=\"\">选择区（县）</option>";   
                }
                city.html(preCity);  
                area.html(preArea);  
                
                //根据下拉得到的省对于的下标序号,动态从从province_city_select_Info.xml获取数据,成功之后采用  
                //func_suc_getXmlProvice进行省对应的市的解析  
                $.ajax({  
                    type : "GET",  
                    url : "/province_city_select_Info.xml",  
                    success : func_suc_getXmlCity  
                });  
                
            }  
        });  
        
        //市 下拉选择发生变化触发的事件  
        city.change(function() {  
            if(opt_data.city != html_node.find("#city").find("option:selected").text()){  
                var preArea = "<option value=\"\">选择区（县）</option>";   
            } 

            area.html(preArea);  
            $.ajax({  
                type : "GET",  
                url : "/province_city_select_Info.xml",  
                
                //根据下拉得到的省、市对于的下标序号,动态从从province_city_select_Info.xml获取数据,成功之后采用  
                //func_suc_getXmlArea进行省对应的市对于的区的解析  
                success : func_suc_getXmlArea  
            });  
        });  
        
        //区 下拉选择发生变化触发的事件  
        area.change(function() {  
            var value = province.find("option:selected").text()  
                + city.find("option:selected").text()  
                + area.find("option:selected").text();  
            html_node.find("#id_address").val(value);  
            $("#txtProCity").val(value);  
        });  
        
        //解析获取xml格式文件中的prov标签,得到所有的省,并逐个进行遍历 放进下拉框中  
        function func_suc_getXmlProvice(xml) {  
            //jquery的查找功能  
            var sheng = $(xml).find("prov");  
            
            //jquery的遍历与查询匹配 eq功能,并将其放到下拉框中  
            sheng.each(function(i) {  
                province.append("<option value=" + i + ">"  
                                + sheng.eq(i).attr("text") + "</option>");  
            });  
        }  
        
        function func_suc_getXmlCity(xml) {  
            var xml_sheng = $(xml).find("prov");  
            var pro_num = parseInt(province.val());  
            var xml_shi = xml_sheng.eq(pro_num).find("city");  
            xml_shi.each(function(j) {  
                city.append("<option  value=" + j + ">"  
                            + xml_shi.eq(j).attr("text") + "</option>");  
            });  
        }  
        
        function func_suc_getXmlArea(xml) {  
            var xml_sheng = $(xml).find("prov");  
            var pro_num = parseInt(province.val());  
            var xml_shi = xml_sheng.eq(pro_num).find("city");  
            var city_num = parseInt(city.val());  
            var xml_xianqu = xml_shi.eq(city_num).find("county");  
            xml_xianqu.each(function(k) {  
                area.append("<option  value=" + k + ">"  
                            + xml_xianqu.eq(k).attr("text") + "</option>");  
            });  
        } 

        var subject_textbook = html_node.find("#id_set_subject_textbook"); 
        subject_textbook.on("click",function(){
            var title = "科目教材详情";
            var html_node = $("<div id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>科目</td><td>教材</td><td>操作</td></tr></table></div>");                     

            $.do_ajax("/ajax_deal2/get_subject_textbook_list",{
                "userid" : g_sid
            },function(result){
                if(result.ret!=0){
                    BootstrapDialog.alert(result.info);
                    return ;
                }

                $.each(result.data,function(i,item){
                    html_node.find("table").append("<tr><td>"+item['subject_str']+"</td><td>"+item['editionid_str']+"</td><td><a href=\"javascript:;\" class=\"update_textbook\"  data-userid=\""+g_sid+"\" data-subject=\""+item['subject']+"\">修改教材版本</a>&nbsp&nbsp&nbsp&nbsp<a href=\"javascript:;\" class=\"delete_stu_subject\" data-userid=\""+g_sid+"\" data-subject=\""+item['subject']+"\">删除科目</a></td></tr>");
                                      
                });
                html_node.find("table").find(".update_textbook").each(function(){
                    $(this).on("click",function(){
                        var userid = $(this).data("userid");

                        var subject = $(this).data("subject");
                        var id_textbook_new     = $("<select/>");
                        Enum_map.append_option_list("region_version", id_textbook_new, true );
                        var arr=[
                            ["教材",id_textbook_new],
                        ];
                        $.show_key_value_table("修改", arr ,{
                            label    : '确认',
                            cssClass : 'btn-warning',
                            action   : function(dialog) {
                                $.do_ajax( '/ajax_deal2/update_user_subject_textbook', {
                                    "userid"             :g_sid,
                                    "subject" :          subject,
                                    "editionid"     : id_textbook_new.val(),
                                });
                            }
                        });

                        
                    });
                    
                });

                html_node.find("table").find(".delete_stu_subject").each(function(){
                    $(this).on("click",function(){
                        var userid = $(this).data("userid");

                        var subject = $(this).data("subject");
                        BootstrapDialog.confirm("确定要删除？", function(val){
                            if (val) {
                                $.do_ajax( '/ajax_deal2/delete_user_subject_textbook', {
                                    "userid"             :g_sid,
                                    "subject" :          subject,
                                });

                            } 
                        });
 

                        
                    });
                    
                });


                

                var dlg=BootstrapDialog.show({
                    title:title, 
                    message :  html_node   ,
                    closable: true, 
                    buttons:[{
                        label: '增加科目',
                        cssClass: 'btn-warning',
                        action: function(dialog) {
                           // alert(1111);
                            var id_subject_new = $("<select/>");
                            var id_textbook_new     = $("<select/>");
                            Enum_map.append_option_list("subject", id_subject_new, true );
                            Enum_map.append_option_list("region_version", id_textbook_new, true );
                            var arr=[
                                ["科目",id_subject_new],
                                ["教材",id_textbook_new],
                            ];
                            $.show_key_value_table("增加", arr ,{
                                label    : '确认',
                                cssClass : 'btn-warning',
                                action   : function(dialog) {
                                    $.do_ajax( '/ajax_deal2/set_user_subject_textbook', {
                                        "userid"             :g_sid,
                                        "subject" : id_subject_new.val(),
                                        "editionid"     : id_textbook_new.val(),
                                    });
                                }
                            });


                        }
 
                    },{
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

        var id_editionid= html_node.find("#id_editionid"); 
        var id_subject= html_node.find("#id_subject");
        id_subject.on("change",function(){
            $.do_ajax("/ajax_deal2/get_editionid",{
                "userid" : g_sid,
                "subject": id_subject.val()
            },function(result){
                id_editionid.val(result.editionid); 
            });
 
        });

        

        BootstrapDialog.show({
            title: '修改用户数据',
            message :html_node ,
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
                   
                    var stu_nick     = html_node.find("#id_name").val();
                   // var parent_name  = html_node.find("#id_parent_name").val();
                  //  var parent_phone = html_node.find("#id_parent_phone").val();
                    var stu_phone    = html_node.find("#id_stu_phone").val();
                    var address      = html_node.find("#id_address").val();
                    var school       = html_node.find("#id_school").val();
                   // var region       = html_node.find("#id_region").val();
                  //  var parent_type  = html_node.find("#id_parent_type").val();
                    var editionid    = html_node.find("#id_textbook").val();
                    var textbook     = html_node.find("#id_textbook").find("option:selected").text();
                    var sexy         = html_node.find("#id_gender").val();
                    var birth        = html_node.find("#id_birth").val();
                    var stu_email        = html_node.find("#id_stu_email").val();
                    var region = html_node.find("#province").find("option:selected").text();
                    var province = html_node.find("#province").val();
                    var city = html_node.find("#city").find("option:selected").text();
                    var area = html_node.find("#area").find("option:selected").text();
                   // alert(province);
                    if(province==""){
                        region="";
                        city="";
                        area="";
                    }
                    //alert(region);
                   // return;
                    $.ajax({
                        url: '/ajax_deal2/change_stu_info',
                        type: 'POST',
                        data : {
                            'studentid'   : g_sid,
                            'stu_nick'    : stu_nick,
                           // 'parent_name' : parent_name,
                            'address'     : address,
                            'school'      : school,
                            //'parent_type' : parent_type,
                            'textbook'    : textbook,
                            'editionid'   : editionid ,
                            "sexy"        : sexy,
                            "region"      : region,
                            "realname"    : html_node.find("#id_realname").val(),
                            'birth'       : birth,
                            'stu_email'   : stu_email,
                            'province'    : province,
                            'city'        : city,
                            'area'        : area
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data['ret'] != 0) {
                                alert(data['info']);
                            }else{
                                window.location.reload();
                            }

                        }
                    });
                }
            }]
        });
    });




    $(".opt-lesson-require").on("click", function(){
        var orderid = $(this).parent().data('orderid');
        $.ajax({
            url: '/stu_manage/get_arrange_require',
            type: 'POST',
            data: {
        'orderid':orderid
      },
            dataType: 'json',
            success: function(data) {
                if (data['ret'] == 0) {

                    BootstrapDialog.show({
                        title: '排课要求',
                        message : $("<textarea id=\"id_note\" class=\"form-control\" style=\"height:150px\" />").val( data['requirement']),
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
                            var note = $.trim($("#id_note").val() );
                                $.ajax({
                                    url: '/stu_manage/set_arrange_require',
                                    type: 'POST',
                                    data: {
                                'orderid':orderid,
                                        'requirement':note
                              },
                                    dataType: 'json',
                                    success: function(data) {
                                        if (data['ret'] == 0) {
                                            window.location.reload();
                                        }
                                    }
                                });
                            }
                        }]
                    });

                }
            }
        });
    });



    $(".opt-lesson-bell").on("click", function(){
        var orderid = $(this).parent().data('orderid');
        BootstrapDialog.show({
            title: '课前音频',
            message : "确认插入课前视频?!",
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
                    $.ajax({
                        url: '/stu_manage/set_course_begin_audio',
                        type: 'POST',
                        data: {
                    'orderid':orderid
                  },
                        dataType: 'json',
                        success: function(data) {
                            if (data['ret'] == 0) {
                                window.location.reload();
                            }else{
                                alert(data['info']);
                                window.location.reload();
                            }
                        }
                    });
                }
            }]
        });


    });

    $(".opt-set-server").on("click", function(){

        var courseid=$(this).get_opt_data("courseid");
        $.ajax({
            url: '/stu_manage/get_course_server',
            type: 'POST',
            data: {
        'courseid':courseid
      },
            dataType: 'json',
            success: function(data) {
                if(data['ret'] == 0){
                    var html_node=$.dlg_need_html_by_id( "id_dlg_set_server") ;
                    html_node.find("#id_region").val(data['info'][0]);
                    html_node.find("#id_server").val(data['info'][1]);
                    BootstrapDialog.show({
                        title: '选择服务器',
                        message : html_node,
                        buttons: [{
                            label: '返回',
                            action: function(dialog) {
                                dialog.close();
                            }
                        }, {
                            label: '确认',
                            cssClass: 'btn-warning',
                            action: function(dialog) {
                                var region = html_node.find("#id_region").val();
                                var server = html_node.find("#id_server").val();
                                if(region == -1 || server == -1){
                                    alert("请选择地区以及服务器!");
                                    return;
                                }
                                $.ajax({
                                    url: '/stu_manage/set_course_server',
                                    type: 'POST',
                                    data: {
                                'courseid':courseid,
                                        'region'  :region,
                                        'id'  :server
                              },
                                    dataType: 'json',
                                    success: function(data) {
                                        if (data['ret'] == 0) {
                                            window.location.reload();
                                        }else{
                                            alert(data['info']);
                                        }
                                    }
                                });

                            }
                        }]
                    });


                }
            }
        });
    });


    $(".opt-set-course-name").on("click",function(){

        var courseid=$(this).get_opt_data("courseid");
      //
        $.show_input( "设置课程名称" , ""  , function(val){
            $.do_ajax('/course_manage/set_course_name', {
                'courseid': courseid,
                'course_name': val
            });
        });
    });


    $("#id_set_seller_adminid").on("click",function(){
        $(this).admin_select_user({
            "show_select_flag":true,
            "type":"admin",
            "onChange":function(val){
                var id = val;
                $.do_ajax( '/user_deal/student_set_seller',
                    {
                        'studentid'  : g_sid,
                'seller_adminid': id
              });
            }
        });
    }) ;

    $("#id_set_assistantid").on("click",function(){
        $(this).admin_select_user({
            "show_select_flag":true,
            "type":"assistant",
            "onChange":function(val){
                var id = val;
                $.do_ajax( '/stu_manage/set_assistantid',{
                    'sid'         : g_sid,
                    'assistantid' : id
              });
            }
        });


    }) ;


    $(".opt-set-status").on("click",function(){
        var orderid = $(this).get_opt_data("orderid");
        var contract_status = $(this).get_opt_data("contract_status");
        if ( contract_status ==1 || contract_status ==2  ) {

            /*
         1 => "执行中",
         2 => "已结束",
             */
            var $contract_status=$("<select/>");

            Enum_map.append_option_list("contract_status",$contract_status,true,[1,2] );
            var arr=[
                ["合同id" , orderid] ,
                ["状态" , $contract_status] ,
            ];
            $contract_status.val(contract_status);

            $.show_key_value_table("课程状态", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.do_ajax( "/user_deal/course_set_status", {
                        "contract_status" : $contract_status.val(),
                        'orderid': orderid
                    },function(data){
                        if (data.ret !=0 ) {
                            alert(data.info);
                        }else{
                            alert("成功");
                            window.location.reload();
                        }
                    }) ;
                }
            });


        }else{
            alert("不是正式合同");
        }


    });

    var set_upload_complete_for_init_info_pdf_url = function(up, info, file, lesson_info) {
        var res = $.parseJSON(info);
        if (res.key) {
            $.do_ajax ('/user_manage_new/stu_set_init_info_pdf_url',
                     {
                           'init_info_pdf_url' : res.key,
                           'userid':g_sid
                       });

        }else{
            alert("上传失败");
        }
    };

    $.custom_upload_file( 'id_set_init_info_pdf_url',
                          false , set_upload_complete_for_init_info_pdf_url, null,
                          ["pdf"] );

    $("#id_show_init_info_pdf").on("click",function(){
        var opt_data=$("#id_stu_info").data();
        var init_info_pdf_url=opt_data.init_info_pdf_url;
        $.custom_show_pdf(init_info_pdf_url);
    });


    $("#id_tmp_passwd").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var id_tmp_passwd=$("<input/>");
        id_tmp_passwd.val("123456");

        var arr=[
            ["账号", g_phone],
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
                data     :{
                        "userid" : g_sid,
                        "phone"  : g_phone,
                        "passwd" : id_tmp_passwd.val(),
                        "role"   : 1
                    },
                    success : function(result){
                        BootstrapDialog.alert(result['info']);
                        window.location.reload();
              }
                });
            }
        });
    });

    $("#id_set_grade").on("click",function(){
        var opt_data    = $("#id_stu_info").data();
        var $grade      = $("<select/>");
        var $start_time = $("<input/>");

        Enum_map.append_option_list("grade", $grade, true);
        $grade.val(opt_data.grade);

        var arr=[
            ["年级",$grade],
            ["----","如果不设置课程重置开始时间，则不重置课程年级"],
            ["课程重置开始时间",$start_time]
        ];

        $.show_key_value_table("重置年级", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/set_stu_grade", {
                    userid     : g_args.sid,
                    grade      : $grade.val(),
                    start_time : $start_time.val(),
                });
            }
        },function(){
            $start_time.datetimepicker({
                datepicker : true,
                timepicker : true,
                format     : 'Y-m-d H:i',
                step       : 30,
            });
        });
    });

    $("#id_set_stu_account").on("click",function(){
        var opt_data   = $("#id_stu_info").data();
        var $stu_phone = $("<input/>");
        $stu_phone.val(opt_data.phone);
        var arr = [
            ["手机号",$stu_phone]
        ];

        $.show_key_value_table("修改账号", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
              $.do_ajax("/user_deal/set_stu_account", {
                    userid    : g_args.sid,
                    old_phone : opt_data.phone,
                    stu_phone : $stu_phone.val(),
                });
            }
        });

    });

    $("#id_add_mypraise").on("click",function(){
        var id_praise_num = $("<input/>");
        var id_reason     = $("<textarea />");
        var arr           = [
            ['获赞数量',id_praise_num],
            ['获赞原因',id_reason]
        ];

        $.show_key_value_table("添加赞记录",arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var praise_num = id_praise_num.val();
                var reason     = id_reason.val();
                if( praise_num == '' || reason == ''){
                    BootstrapDialog.alert("请填写全部信息!");
                    return ;
                }

                if( g_uid==0 ){
                    BootstrapDialog.alert("用户信息出错!");
                    return ;
                }

                if(isNaN(praise_num)){
                    BootstrapDialog.alert("赞的数量必须为数字!");
                    return;
                }

                $.do_ajax("/user_manage/add_praise", {
                    "userid"     : g_uid,
                    "praise_num" : praise_num,
                    "reason"     : reason
                },function(result){
                    if(result.ret!=0){
                        BootstrapDialog.alert(result.info);
                    }else{
                        BootstrapDialog.alert(result.info);
                        sleep(1000);
                        dialog.close();
                    }
                });
            }
        });
    });

   
});
