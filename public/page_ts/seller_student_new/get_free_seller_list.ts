/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-get_free_seller_list.d.ts" />

$(function(){

    var show_name_key="stu_info_name_"+g_adminid;
    function load_data(){
        if ($.trim($("#id_phone_name").val()) != g_args.phone_name ) {
            $.do_ajax("/user_deal/set_item_list_add",{
                "item_key" :show_name_key,
                "item_name":  $.trim($("#id_phone_name").val())
            },function(){});
        }


        $.reload_self_page ( {
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            grade:	$('#id_grade').val(),
            phone_name:	$('#id_phone_name').val(),
            phone_location: $('#id_phone_location').val(),
            has_pad:	$('#id_has_pad').val(),
            subject:	$('#id_subject').val(),
            test_lesson_count_flag : $('#id_test_lesson_count_flag').val(),
            test_lesson_order_fail_flag:    $('#id_test_lesson_order_fail_flag').val(),
            return_publish_count : $('#id_return_publish_count').val(),
            cc_no_called_count   : $('#cc_no_called_count').val(),
            cc_called_count      : $('#cc_called_count').val(),
            call_admin_count     : $('#call_admin_count').val(), 
        });
    }

    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    Enum_map.append_option_list("grade",$("#id_grade"));
    Enum_map.append_option_list("pad_type",$("#id_has_pad"));
    Enum_map.append_option_list("subject",$("#id_subject"));
    Enum_map.append_option_list("test_lesson_count_flag",$("#id_test_lesson_count_flag"));
    Enum_map.append_option_list("test_lesson_order_fail_flag",$("#id_test_lesson_order_fail_flag"));
    $('#id_grade').val(g_args.grade);
    $('#id_has_pad').val(g_args.has_pad);
    $('#id_phone_name').val(g_args.phone_name);
    $('#id_phone_location').val(g_args.phone_location);
    $('#id_subject').val(g_args.subject);
    $('#id_test_lesson_count_flag').val(g_args.test_lesson_count_flag),
    $('#id_test_lesson_order_fail_flag').val(g_args.test_lesson_order_fail_flag);
    $('#id_return_publish_count').val(g_args.return_publish_count);
    $('#id_cc_called_count').val(g_args.cc_called_count);
    $('#id_cc_no_called_count').val(g_args.cc_no_called_count);
    $('#id_call_admin_count').val(g_args.call_admin_count);
    $( "#id_phone_name" ).autocomplete({
        source: "/user_deal/get_item_list?list_flag=1&item_key="+show_name_key,
        minLength: 0,
        select: function( event, ui ) {
            $("#id_phone_name").val(ui.item.value);
            load_data();
        }
    });


    $(".opt-set-self").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.do_ajax("/ss_deal/set_no_called_to_self",{
            "test_lesson_subject_id" : opt_data.test_lesson_subject_id,
            "free_flag" :1
        });

    });


    $(".opt-telphone").on("click",function(){
        //
        var opt_data= $(this).get_opt_data();
        var phone    = ""+ opt_data.phone;
        //opt_data.userid

        phone=phone.split("-")[0];

        //copyToClipboard(opt_data.phone);

        try{
            window.navigate(
                "app:1234567@"+phone+"");
        } catch(e){

        };
        $.do_ajax_t("/ss_deal/call_ytx_phone", {
            "phone": opt_data.phone
        } );
        /*

        //同步...
        var lesson_info = JSON.stringify({
        cmd: "noti_phone",
        phone: phone
        });
        $.ajax({
        type: "get",
        url: "http://admin.leo1v1.com:9501/pc_phone_noti_user_lesson_info",
        dataType: "text",
        data: {
        'username': g_account,
        "lesson_info": lesson_info
        }
        });
        */
        //
        $(this).parent().find(".opt-edit").click();
    });

    $(".opt-telphone_new").on("click",function(){
        //
        var me=this;
        var opt_data= $(this).get_opt_data();
        var phone    = ""+ opt_data.phone;
        phone=phone.split("-")[0];

        try{
            window.navigate(
                "app:1234567@"+phone+"");
        } catch(e){

        };
        $.do_ajax_t("/ss_deal/call_ytx_phone", {
            "phone": opt_data.phone
        } );

        var time_a = opt_data.last_revisit_time;
        var timea = Date.parse(new Date(time_a));
        var timeb = Date.parse(new Date());
        timeb =  timeb - 3600000;
        if(timea>timeb){
            alert("请一个小时后再拨打");
        }
        $(me).parent().find(".opt-edit-new_new").click();
    });

    $(".opt-edit-new_new").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var opt_obj=this;
        var click_type=2;
        edit_user_info_new(opt_data,opt_obj,click_type);
    });
    var edit_user_info_new=function(opt_data,opt_obj,click_type){
       // var opt_data=$(this).get_opt_data();
        //var opt_obj=this;
       // alert(opt_data.test_lesson_subject_id);
        $.do_ajax("/ss_deal/get_user_info",{
            "userid" : opt_data.userid ,
            "test_lesson_subject_id" : opt_data.test_lesson_subject_id ,
        } ,function(ret){
            var data=ret.data;
            var html_node = $.dlg_need_html_by_id( "id_dlg_post_user_info_new");
            var show_noti_info_flag=false;
            var $note_info=html_node.find(".note-info");
            var note_msg="";
            if (data.test_lesson_count >0 ) {
                show_noti_info_flag=true;
                note_msg="已有试听课:"+data.test_lesson_count +"次" ;
            }

            if (!show_noti_info_flag) {
                $note_info.hide();
            }else{
                $note_info.find("span").html( note_msg);
            }

            if( data.status !=0 ) {
                html_node.find("#id_stu_rev_info").removeClass("btn-primary");
                html_node.find("#id_stu_rev_info").addClass("btn-warning");
            }else{
                html_node.find("#id_stu_rev_info").addClass("btn-primary");
                html_node.find("#id_stu_rev_info").removeClass("btn-warning");
            }
            html_node.find("#id_send_sms").on("click",function(){
                $.do_ajax("/user_deal/get_admin_wx_info",{},function(resp){
                    var data=resp.data;
                    var xing=$.trim(data.name).substr(0,1);
                    var dlg=BootstrapDialog.show({
                        title: "发送信息内容:",
                        message : "您好，我是刚刚联系您的"+xing+"老师 ，如果您还需要申请我们的试听课，请添加一下我的微信："+data.wx_id+"。我们会尽快帮您安排，理优教育服务热线："+data.phone,
                        closable: true,
                        buttons: [{
                            label: '返回',
                            action: function(dialog) {
                                dialog.close();
                            }
                        },{
                            label: '发送',
                            cssClass: 'btn-warning',
                            action: function(dialog) {
                                $.do_ajax("/user_deal/send_seller_sms_msg", {
                                    "phone":opt_data.phone,
                                    "name":xing,
                                    "wx_id":data.wx_id,
                                    "seller_phone":data.phone,
                                },function( resp){
                                    alert("发送成功");
                                } );
                            }
                        }]
                    });


                    /*
                      BootstrapDialog.show();
                    */

                });
            });

            html_node.find("#id_stu_rev_info") .on("click",function(){
                $(opt_obj).parent().find(".opt-return-back-list").click();
            });
            var id_stu_nick          = html_node.find("#id_stu_nick");
            var id_par_nick          = html_node.find("#id_par_nick");
            var id_grade             = html_node.find("#id_stu_grade");
            var id_gender            = html_node.find("#id_stu_gender");
            var id_address           = html_node.find("#id_stu_addr");
            var id_subject           = html_node.find("#id_stu_subject");
            var id_status            = html_node.find("#id_stu_status");
            var id_seller_student_sub_status = html_node.find("#id_seller_student_sub_status");
            var id_user_desc         = html_node.find("#id_stu_user_desc");
           // var id_revisite_info     = html_node.find("#id_stu_revisite_info");
            var id_has_pad           = html_node.find("#id_stu_has_pad");
            var id_editionid         = html_node.find("#id_stu_editionid");
            var id_school            = html_node.find("#id_stu_school");
            var id_intention_level            = html_node.find("#id_intention_level");
            var id_next_revisit_time = html_node.find("#id_next_revisit_time");
            var id_stu_request_test_lesson_time = html_node.find("#id_stu_request_test_lesson_time");
            var id_stu_request_test_lesson_demand= html_node.find("#id_stu_request_test_lesson_demand");
          //  var id_stu_score_info = html_node.find("#id_stu_score_info");
           // var id_stu_character_info = html_node.find("#id_stu_character_info");
           // var id_stu_test_lesson_level = html_node.find("#id_stu_test_lesson_level");
            var id_stu_test_ipad_flag = html_node.find("#id_stu_test_ipad_flag");
           // var id_stu_request_test_lesson_time_info = html_node.find("#id_stu_request_test_lesson_time_info");
           // var id_stu_request_lesson_time_info = html_node.find("#id_stu_request_lesson_time_info");
            var id_advice_flag = html_node.find("#id_advice_flag");
            var id_academic_goal = html_node.find("#id_academic_goal");
            var id_test_stress = html_node.find("#id_test_stress");
            var id_entrance_school_type = html_node.find("#id_entrance_school_type");
            var id_extra_improvement = html_node.find("#id_extra_improvement");
            var id_habit_remodel = html_node.find("#id_habit_remodel");
            var id_interest_cultivation = html_node.find("#id_interest_cultivation");
            var id_study_habit = html_node.find("#id_study_habit");
            var id_interests_hobbies = html_node.find("#id_interests_hobbies");
            var id_character_type = html_node.find("#id_character_type");
            var id_need_teacher_style = html_node.find("#id_need_teacher_style");
            var id_intention_level = html_node.find("#id_intention_level");
            var id_test_paper = html_node.find("#id_test_paper");
            var id_demand_urgency = html_node.find("#id_demand_urgency");
            var id_quotation_reaction = html_node.find("#id_quotation_reaction");
            var id_revisit_info_new = html_node.find("#id_revisit_info_new");
            if(click_type==1){
                //id_revisit_info_new.hide();
            }

            html_node.find(".upload_test_paper").attr("id","id_upload_test_paper");

            html_node.find("#id_stu_reset_next_revisit_time").on("click",function(){
                id_next_revisit_time.val("");
            });
            Enum_map.append_option_list("grade", id_grade, true,[101,102,103,104,105,106,201,202,203,301,302,303]);
            Enum_map.append_option_list("pad_type", id_has_pad, true);
            Enum_map.append_option_list("subject", id_subject, true);
            Enum_map.append_option_list("boolean", id_stu_test_ipad_flag, true);
            Enum_map.append_option_list("boolean", id_advice_flag, true);
          //  Enum_map.append_option_list("test_lesson_level", id_stu_test_lesson_level, true);
            Enum_map.append_option_list("academic_goal", id_academic_goal, true);
            Enum_map.append_option_list("test_stress", id_test_stress, true);
            Enum_map.append_option_list("habit_remodel", id_habit_remodel, true);
            Enum_map.append_option_list("extra_improvement", id_extra_improvement, true);
            Enum_map.append_option_list("entrance_school_type", id_entrance_school_type, true);
            Enum_map.append_option_list("interest_cultivation", id_interest_cultivation, true);
            Enum_map.append_option_list("intention_level", id_intention_level, true);
            Enum_map.append_option_list("demand_urgency", id_demand_urgency, true);
            Enum_map.append_option_list("quotation_reaction", id_quotation_reaction, true);




           /* id_stu_request_test_lesson_time.datetimepicker( {
                lang:'ch',
                timepicker:true,
                format: "Y-m-d H:i",
                onChangeDateTime :function(){
                }
            });*/

            id_stu_request_test_lesson_time.datetimepicker({
                lang             : 'ch',
                timepicker       : true,
                format:'Y-m-d H:i',
                step             : 30,
                onGenerate       : function(){
                    check_disable_time();
                }

            });
            //检测该时间该人是否排课
            var check_disable_time = function() {

                var cur_time = id_stu_request_test_lesson_time.val();
                var cur_day = new Date(cur_time).getTime() / 1000;

                $.do_ajax("/seller_student_new/get_stu_request_test_lesson_time_by_adminid",{
                    "cur_day" : cur_day
                },function(res){
                    var ret = res.list;
                    $(ret).each(function(i){
                        var dis_time = ret[i];
                        console.log(dis_time)
                        $('.xdsoft_time').each(function(){
                            var add_attr = function(obj){
                                $(obj).css('border','1px solid red');
                                $(obj).css('background-color','#ccc');
                                $(obj).on('click',function(){
                                    BootstrapDialog.alert('你已经在该时间段内排过一节课!');
                                    return false;
                                });
                            };

                            if ( $(this).text() == dis_time ) {
                                var that = $(this);
                                var prev_that = $(this).prev();
                                var next_that = $(this).next();
                                add_attr(prev_that);
                                add_attr(that);
                                add_attr(next_that);
                            }

                        });
                    });
                });

            };


            html_node.find("#id_stu_reset_stu_request_test_lesson_time").on("click",function(){
                id_stu_request_test_lesson_time.val("");
            });

            id_study_habit.data("v",data.study_habit);
            id_study_habit.on("click",function(){
                // var study_habit= data.study_habit;
                var study_habit  = id_study_habit.data("v");
                $.do_ajax("/ss_deal2/get_stu_study_habit_list",{
                    "study_habit" : study_habit
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["study_habit"]  ]);

                        if (this["has_study_habit"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","学习习惯" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_study_habit_name",{
                                "study_habit" : JSON.stringify(select_list)
                            },function(res){
                                id_study_habit.val(res.data);
                                id_study_habit.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });

                });

            });

            id_interests_hobbies.data("v",data.interests_and_hobbies);
            id_interests_hobbies.on("click",function(){
                // var interests_hobbies= data.interests_hobbies;
                var interests_hobbies  = id_interests_hobbies.data("v");
                $.do_ajax("/ss_deal2/get_stu_interests_hobbies_list",{
                    "interests_hobbies" : interests_hobbies
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["interests_hobbies"]  ]);

                        if (this["has_interests_hobbies"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","兴趣爱好" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_interests_hobbies_name",{
                                "interests_hobbies" : JSON.stringify(select_list)
                            },function(res){
                                id_interests_hobbies.val(res.data);
                                id_interests_hobbies.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });

                });

            });

            id_character_type.data("v",data.character_type);
            id_character_type.on("click",function(){
                // var character_type= data.character_type;
                var character_type  = id_character_type.data("v");
                $.do_ajax("/ss_deal2/get_stu_character_type_list",{
                    "character_type" : character_type
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["character_type"]  ]);

                        if (this["has_character_type"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","性格特点" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_character_type_name",{
                                "character_type" : JSON.stringify(select_list)
                            },function(res){
                                id_character_type.val(res.data);
                                id_character_type.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });

                });

            });

            id_need_teacher_style.data("v",data.need_teacher_style);
            id_need_teacher_style.on("click",function(){
                // var need_teacher_style= data.need_teacher_style;
                var need_teacher_style  = id_need_teacher_style.data("v");
                $.do_ajax("/ss_deal2/get_stu_need_teacher_style_list",{
                    "need_teacher_style" : need_teacher_style
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["need_teacher_style"]  ]);

                        if (this["has_need_teacher_style"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","老师要求" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_need_teacher_style_name",{
                                "need_teacher_style" : JSON.stringify(select_list)
                            },function(res){
                                id_need_teacher_style.val(res.data);
                                id_need_teacher_style.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });

                });

            });



            var old_province = data.region;
            if(old_province == ''){
                old_province="选择省（市）";
            }

            var old_city = data.city;
            if(old_city == ''){
                old_city="选择市（区）";
            }
            var old_area = data.area;
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
                    if(data.region != html_node.find("#province").find("option:selected").text()){
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
                if(data.city != html_node.find("#city").find("option:selected").text()){
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
                id_address.val(value);
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


            /*
              array(0,"","未回访" ),
              array(1,"","无效资源" ),
              array(2,"","未接通" ),
              array(3,"","有效-意向A档" ),
              array(4,"","有效-意向B档" ),
              array(5,"","有效-意向C档" ),
              array(6,"","已试听-待跟进" ),
              array(7,"","已试听-未签A档" ),
              array(20,"","已试听-未签B档" ),
              array(21,"","已试听-未签C档" ),
              array(8,"","已试听-已签" ),
              array(9,"test_lesson_report","试听-预约" ),
              array(10,"test_lesson_set_lesson","试听-已排课" ),
              array(11,"","试听-时间待定" ), //,有预约意向，但时间没有确定
              array(12,"","试听-时间确定" ), //
              array(13,"","试听-无法排课" ),
              array(14,"","试听-驳回" ),
              array(15,"","试听-课程取消" ),

            */


            var now=(new Date()).getTime()/1000;

            var status=data.status*1;
            var show_status_list=[];

            var cur_page= g_args.cur_page;

            show_status_list=[];

            /*
              return $this->seller_student_list_ex(0,"0,2");
              return $this->seller_student_list_ex(103,"100,103");
              return $this->seller_student_list_ex(101,"101,102");
              return $this->seller_student_list_ex(110,"110,120");
              return $this->seller_student_list_ex(200);
              return $this->seller_student_list_ex(1);
              return $this->seller_student_list_ex(210);
              return $this->seller_student_list_ex(220);
              return $this->seller_student_list_ex(290);
              return $this->seller_student_list_ex(301, "300,301,302,420");
            */

            if(opt_data.stu_type==1){
                switch ( opt_data.seller_student_status) {
                case 0:
                case  2:
                    show_status_list=[ 1,2, 100,101,102,103 ];
                    break;
                case 1:
                    show_status_list=[  100, 101,102,103 ];
                    break;
                case  60:
                    show_status_list=[ 1,2,61, 100,101,102,103 ];
                    break;
                case 61:
                    show_status_list=[1,2,60,  100, 101,102,103 ];
                    break;

                case 101:
                case  102:
                    show_status_list=[ 100, 101,102,103, 1  ];
                    break;

                case 100:case 103:
                    show_status_list=[ 1, 100, 101,102,103 ];
                    break;

                case 110:case 120:
                    show_status_list=[ 1,100, 101,102,103 ];
                    break;

                case 200:
                    show_status_list=[ ];
                    break;

                case 210:
                    show_status_list=[ 220, 290  ];
                    break;
                case 220: //待开课
                    show_status_list=[ 290,300,301,302 ];
                    break;

                case 290: //待跟进
                    show_status_list=[ 300,301,302 ];
                    break;

                case 300:case 301:case 302:   //未签
                    show_status_list=[ 300,301,302,420  ];
                    break;
                case 400:case 410:case 420: //
                    show_status_list=[  60,61 ];
                    break;
                }

            }else{
                switch ( opt_data.seller_student_status) {
                case 0:
                case  2:
                    show_status_list=[ 1,2, 100,101,102,103 ];
                    break;
                case 1:
                    show_status_list=[  100, 101,102,103 ];
                    break;
                case 101:
                case  102:
                    show_status_list=[ 100, 101,102,103, 1  ];
                    break;

                case 100:case 103:
                    show_status_list=[ 1, 100, 101,102,103 ];
                    break;

                case 110:case 120:
                    show_status_list=[ 1,100, 101,102,103 ];
                    break;

                case 200:
                    show_status_list=[ ];
                    break;

                case 210:
                    show_status_list=[ 220, 290  ];
                    break;
                case 220: //待开课
                    show_status_list=[ 290,300,301,302 ];
                    break;

                case 290: //待跟进
                    show_status_list=[ 300,301,302 ];
                    break;

                case 300:case 301:case 302:case 420 :   //未签
                    show_status_list=[ 300,301,302,420  ];
                    break;
                case 400:case 410:case 420: //
                    show_status_list=[   ];
                    break;
                }

            }

            show_status_list.push(status);
            Enum_map.append_option_list("seller_student_status", id_status ,true );
            Enum_map.append_option_list("gender", id_gender, true);
            Enum_map.append_option_list("region_version", id_editionid, true);

            id_stu_nick.val(data.stu_nick);
            id_par_nick.val(data.par_nick);
            id_grade.val(data.grade);
            id_gender.val(data.gender);
            id_address.val(data.address);
            id_subject.val(data.subject);
            id_status.val(data.status);
            id_user_desc.val(data.user_desc);
           // id_revisite_info.val(data.revisite_info);
            id_has_pad.val(data.has_pad);
            id_school.val(data.school);
            id_editionid.val(data.editionid);
            id_next_revisit_time.val(data.next_revisit_time);
            html_node.find("#id_class_rank").val(data.class_rank);
            html_node.find("#id_grade_rank").val(data.grade_rank);
            html_node.find("#id_academic_goal").val(data.academic_goal);
            html_node.find("#id_test_stress").val(data.test_stress);
            html_node.find("#id_entrance_school_type").val(data.entrance_school_type);
            html_node.find("#id_interest_cultivation").val(data.interest_cultivation);
            html_node.find("#id_extra_improvement").val(data.extra_improvement);
            html_node.find("#id_habit_remodel").val(data.habit_remodel);
            html_node.find("#id_study_habit").val(data.study_habit);
            html_node.find("#id_interests_hobbies").val(data.interests_and_hobbies);
            html_node.find("#id_character_type").val(data.character_type);
            html_node.find("#id_need_teacher_style").val(data.need_teacher_style);
            html_node.find("#id_intention_level").val(data.intention_level);
            html_node.find("#id_demand_urgency").val(data.demand_urgency);
            html_node.find("#id_quotation_reaction").val(data.quotation_reaction);
            html_node.find("#id_recent_results").val(data.recent_results);
            html_node.find("#id_advice_flag").val(data.advice_flag);
            html_node.find("#id_test_paper").val(data.stu_test_paper);
            if(!data.knowledge_point_location ){
                html_node.find("#id_knowledge_point_location").val(data.stu_request_test_lesson_demand);
            }else{
                html_node.find("#id_knowledge_point_location").val(data.knowledge_point_location);
            }



            var reset_seller_student_status_options=function()  {
                var opt_list=[0];
                var desc_map=g_enum_map["seller_student_sub_status"]["desc_map"];
                var seller_student_status=  parseInt( id_status.val());
                $.each(desc_map, function(k,v){
                    if(k>0 ) {
                        if (  Math.floor(k/1000) == seller_student_status ){
                            opt_list.push(parseInt(k));
                        }
                    }
                });
                id_seller_student_sub_status.html("");
                Enum_map.append_option_list("seller_student_sub_status", id_seller_student_sub_status,true, opt_list );
            };

            reset_seller_student_status_options();
            id_seller_student_sub_status.val(data.seller_student_sub_status);
            id_status.on("change",function(){
                reset_seller_student_status_options();
            });


            id_stu_request_test_lesson_time.val(data.stu_request_test_lesson_time);
            id_stu_request_test_lesson_demand.val(data.stu_request_test_lesson_demand );
           // id_stu_score_info.val(data.stu_score_info);
           // id_stu_test_lesson_level.val(data.stu_test_lesson_level);
            id_stu_test_ipad_flag.val(data.stu_test_ipad_flag);
           // id_stu_character_info.val(data.stu_character_info);

            id_next_revisit_time.datetimepicker( {
                lang:'ch',
                timepicker:true,
                format: "Y-m-d H:i",
                onChangeDateTime :function(){
                }
            });
            var origin=data.origin;
            if (  /bm_/.test(origin) ||
                  /bw_/.test(origin) ||
                  /baidu/.test(origin)
               ) {
                //origin="百度:"+ origin;
                origin="百度";
            }

            var title= '用户信息['+opt_data.phone+':'+opt_data.phone_location+']';
            // if( g_args.account_seller_level >=100  && g_args.account_seller_level<400 ) { //S,A, B
            //     title= title+"-渠道:["+origin+"]";
            // }

            if(click_type == 1){
                if(html_node.find("#id_stu_editionid").val() == 0){
                    html_node.find("#id_stu_editionid").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_stu_request_test_lesson_time").val() == 0){
                    html_node.find("#id_stu_request_test_lesson_time").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_stu_subject").val() <= 0){
                    html_node.find("#id_stu_subject").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_stu_request_test_lesson_time").val() == '无'){
                    html_node.find("#id_stu_request_test_lesson_time").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }else{
                    var require_time= $.strtotime(html_node.find("#id_stu_request_test_lesson_time").val());
                    var need_start_time=0;
                    var now=(new Date()).getTime()/1000;
                    var min_date_time="";
                    var nowDayOfWeek = (new Date()).getDay();
                    if ( (new Date()).getHours() <18 ) {
                        min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 08:00:00"  );
                    }else{
                        if( nowDayOfWeek==5 ||  nowDayOfWeek==6){
                            min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 16:00:00"  );
                        }else{
                            min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 14:00:00"  );
                        }
                    }
                    need_start_time=$.strtotime(min_date_time );
                    if (require_time < need_start_time ) {
                        html_node.find("#id_stu_request_test_lesson_time").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                    }
                }
                if(html_node.find("#id_stu_nick").val() == ''){
                    html_node.find("#id_stu_nick").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_stu_grade").val() <= 0){
                    html_node.find("#id_stu_grade").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_stu_gender").val() == 0){
                    html_node.find("#id_stu_gender").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(data.region == ''){
                    html_node.find("#province").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(data.city == ''){
                    html_node.find("#city").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(data.area == ''){
                    html_node.find("#area").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_class_rank").val() == ''){
                    html_node.find("#id_class_rank").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_grade_rank").val() == ''){
                    html_node.find("#id_grade_rank").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_academic_goal").val() <= 0){
                    html_node.find("#id_academic_goal").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_test_stress").val() <= 0){
                    html_node.find("#id_test_stress").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_entrance_school_type").val() <= 0){
                    html_node.find("#id_entrance_school_type").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_entrance_school_type").val() <= 0){
                    html_node.find("#id_entrance_school_type").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_study_habit").val() == ''){
                    html_node.find("#id_study_habit").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_character_type").val() == ''){
                    html_node.find("#id_character_type").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_need_teacher_style").val() == ''){
                    html_node.find("#id_need_teacher_style").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_intention_level").val() <= 0){
                    html_node.find("#id_intention_level").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                // if(html_node.find("#id_demand_urgency").val() <= 0){
                //     html_node.find("#id_demand_urgency").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                // }
                // if(html_node.find("#id_quotation_reaction").val() <= 0){
                //     html_node.find("#id_quotation_reaction").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                // }
                if(html_node.find("#id_stu_request_test_lesson_demand").val() == ''){
                    html_node.find("#id_stu_request_test_lesson_demand").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
                if(html_node.find("#id_recent_results").val() == ''){
                    html_node.find("#id_recent_results").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                }
            }

            



            var dlg=BootstrapDialog.show({
                title:  title,
                size: "size-wide",
                message : html_node,
                closable: false,
                buttons: [{
                    label: '返回',
                    action: function(dialog) {
                        dialog.close();
                    }
                },{
                    label: '提交',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        if (  id_seller_student_sub_status.find("option").length>1  && id_seller_student_sub_status.val()=="0" ) {
                            alert("请选择回访状态的子分类");
                            return;
                        }

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
                        if(html_node.find("#city").val()==""){
                             city="";
                        }
                        if(html_node.find("#area").val()==""){
                            area="";
                        }
                        if(id_status.val() !=  '2'){//
                            if(html_node.find("#id_stu_editionid").val() == 0){
                                html_node.find("#id_stu_editionid").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(html_node.find("#id_stu_request_test_lesson_time").val() == 0){
                                html_node.find("#id_stu_request_test_lesson_time").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(html_node.find("#id_stu_subject").val() <= 0){
                                html_node.find("#id_stu_subject").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(html_node.find("#id_stu_request_test_lesson_time").val() == '无'){
                                html_node.find("#id_stu_request_test_lesson_time").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }else{
                                var require_time= $.strtotime(html_node.find("#id_stu_request_test_lesson_time").val());
                                var need_start_time=0;
                                var now=(new Date()).getTime()/1000;
                                var min_date_time="";
                                var nowDayOfWeek = (new Date()).getDay();
                                if ( (new Date()).getHours() <18 ) {
                                    min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 08:00:00"  );
                                }else{
                                    if( nowDayOfWeek==5 ||  nowDayOfWeek==6){
                                        min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 16:00:00"  );
                                    }else{
                                        min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 14:00:00"  );
                                    }
                                }
                                need_start_time=$.strtotime(min_date_time );
                                if (require_time < need_start_time ) {
                                    html_node.find("#id_stu_request_test_lesson_time").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                }
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(html_node.find("#id_stu_nick").val() == ''){
                                html_node.find("#id_stu_nick").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(html_node.find("#id_stu_grade").val() <= 0){
                                html_node.find("#id_stu_grade").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(html_node.find("#id_stu_gender").val() == 0){
                                html_node.find("#id_stu_gender").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(data.region == ''){
                                html_node.find("#province").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(data.city == ''){
                                html_node.find("#city").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(data.area == ''){
                                html_node.find("#area").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(html_node.find("#id_class_rank").val() == ''){
                                html_node.find("#id_class_rank").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(html_node.find("#id_grade_rank").val() == ''){
                                html_node.find("#id_grade_rank").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(html_node.find("#id_academic_goal").val() <= 0){
                                html_node.find("#id_academic_goal").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(html_node.find("#id_test_stress").val() <= 0){
                                html_node.find("#id_test_stress").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(html_node.find("#id_entrance_school_type").val() <= 0){
                                html_node.find("#id_entrance_school_type").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(html_node.find("#id_entrance_school_type").val() <= 0){
                                html_node.find("#id_entrance_school_type").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(html_node.find("#id_study_habit").val() == ''){
                                html_node.find("#id_study_habit").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(html_node.find("#id_character_type").val() == ''){
                                html_node.find("#id_character_type").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(html_node.find("#id_need_teacher_style").val() == ''){
                                html_node.find("#id_need_teacher_style").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(html_node.find("#id_intention_level").val() <= 0){
                                html_node.find("#id_intention_level").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            // if(html_node.find("#id_demand_urgency").val() <= 0){
                            //     html_node.find("#id_demand_urgency").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            // }
                            // if(html_node.find("#id_quotation_reaction").val() <= 0){
                            //     html_node.find("#id_quotation_reaction").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                            // }
                            if(html_node.find("#id_stu_request_test_lesson_demand").val() == ''){
                                html_node.find("#id_stu_request_test_lesson_demand").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }
                            if(html_node.find("#id_recent_results").val() == ''){
                                html_node.find("#id_recent_results").parent().attr('style','border-style:solid;border-width:2px;border-color:#FF0000');
                                alert("请把标红的补充完整");
                                return;
                            }

                            $.do_ajax("/ss_deal/save_user_info_new_new",{
                                new_demand_flag   : 1,
                                click_type        : click_type,
                                userid            : opt_data.userid,
                                test_lesson_subject_id : opt_data.test_lesson_subject_id,
                                phone: opt_data.phone,
                                stu_nick      : id_stu_nick.val(),
                                par_nick      : id_par_nick.val(),
                                grade         : id_grade.val(),
                                gender        : id_gender.val(),
                                address       : id_address.val(),
                                subject       : id_subject.val(),
                                seller_student_status : id_status.val(),
                                seller_student_sub_status : id_seller_student_sub_status.val(),
                                user_desc     : id_user_desc.val(),
                               // revisite_info : id_revisite_info.val(),
                                next_revisit_time : id_next_revisit_time.val(),
                                editionid : id_editionid.val(),
                                school: id_school.val(),
                                stu_request_test_lesson_time:id_stu_request_test_lesson_time.val(),
                                stu_request_test_lesson_demand:id_stu_request_test_lesson_demand.val(),
                               // stu_score_info:id_stu_score_info.val(),
                               // stu_test_lesson_level:id_stu_test_lesson_level.val(),
                                stu_test_ipad_flag:id_stu_test_ipad_flag.val(),
                              //  stu_character_info:id_stu_character_info.val(),
                              //  stu_request_test_lesson_time_info:id_stu_request_test_lesson_time_info.data("v"),
                              //  stu_request_lesson_time_info:id_stu_request_lesson_time_info.data("v"),
                                has_pad       : id_has_pad.val(),
                                intention_level       : id_intention_level.val(),
                                class_rank: html_node.find("#id_class_rank").val(),
                                grade_rank: html_node.find("#id_grade_rank").val(),
                                academic_goal: html_node.find("#id_academic_goal").val(),
                                test_stress: html_node.find("#id_test_stress").val(),
                                entrance_school_type: html_node.find("#id_entrance_school_type").val(),
                                interest_cultivation: html_node.find("#id_interest_cultivation").val(),
                                extra_improvement : html_node.find("#id_extra_improvement").val(),
                                habit_remodel: html_node.find("#id_habit_remodel").val(),
                                study_habit : html_node.find("#id_study_habit").val(),
                                interests_and_hobbies: html_node.find("#id_interests_hobbies").val(),
                                character_type: html_node.find("#id_character_type").val(),
                                need_teacher_style: html_node.find("#id_need_teacher_style").val(),
                                demand_urgency: html_node.find("#id_demand_urgency").val(),
                                quotation_reaction: html_node.find("#id_quotation_reaction").val(),
                               // knowledge_point_location: html_node.find("#id_knowledge_point_location").val(),
                                recent_results: html_node.find("#id_recent_results").val(),
                                advice_flag: html_node.find("#id_advice_flag").val(),
                                province: province,
                                city: city,
                                area: area,
                                region: region,
                                test_paper: html_node.find("#id_test_paper").val(),
                            });
                        }else{
                            if(html_node.find("#id_stu_user_desc").val() == ''){
                                alert("请填写备注");
                                return;
                            }
                            if(html_node.find("#id_stu_status").val() <= 0){
                                alert("请选择回访状态");
                                return;
                            }
                            $.do_ajax("/ss_deal/save_user_info_new_new",{
                                new_demand_flag   : 1,
                                click_type        : click_type,
                                userid            : opt_data.userid,
                                test_lesson_subject_id : opt_data.test_lesson_subject_id,
                                phone: opt_data.phone,
                                stu_nick      : id_stu_nick.val(),
                                par_nick      : id_par_nick.val(),
                                grade         : id_grade.val(),
                                gender        : id_gender.val(),
                                address       : id_address.val(),
                                subject       : id_subject.val(),
                                seller_student_status : id_status.val(),
                                seller_student_sub_status : id_seller_student_sub_status.val(),
                                user_desc     : id_user_desc.val(),
                               // revisite_info : id_revisite_info.val(),
                                next_revisit_time : id_next_revisit_time.val(),
                                editionid : id_editionid.val(),
                                school: id_school.val(),
                                stu_request_test_lesson_time:id_stu_request_test_lesson_time.val(),
                                stu_request_test_lesson_demand:id_stu_request_test_lesson_demand.val(),
                               // stu_score_info:id_stu_score_info.val(),
                               // stu_test_lesson_level:id_stu_test_lesson_level.val(),
                                stu_test_ipad_flag:id_stu_test_ipad_flag.val(),
                              //  stu_character_info:id_stu_character_info.val(),
                              //  stu_request_test_lesson_time_info:id_stu_request_test_lesson_time_info.data("v"),
                              //  stu_request_lesson_time_info:id_stu_request_lesson_time_info.data("v"),
                                has_pad       : id_has_pad.val(),
                                intention_level       : id_intention_level.val(),
                                class_rank: html_node.find("#id_class_rank").val(),
                                grade_rank: html_node.find("#id_grade_rank").val(),
                                academic_goal: html_node.find("#id_academic_goal").val(),
                                test_stress: html_node.find("#id_test_stress").val(),
                                entrance_school_type: html_node.find("#id_entrance_school_type").val(),
                                interest_cultivation: html_node.find("#id_interest_cultivation").val(),
                                extra_improvement : html_node.find("#id_extra_improvement").val(),
                                habit_remodel: html_node.find("#id_habit_remodel").val(),
                                study_habit : html_node.find("#id_study_habit").val(),
                                interests_and_hobbies: html_node.find("#id_interests_hobbies").val(),
                                character_type: html_node.find("#id_character_type").val(),
                                need_teacher_style: html_node.find("#id_need_teacher_style").val(),
                                demand_urgency: html_node.find("#id_demand_urgency").val(),
                                quotation_reaction: html_node.find("#id_quotation_reaction").val(),
                               // knowledge_point_location: html_node.find("#id_knowledge_point_location").val(),
                                recent_results: html_node.find("#id_recent_results").val(),
                                advice_flag: html_node.find("#id_advice_flag").val(),
                                province: province,
                                city: city,
                                area: area,
                                region: region,
                                test_paper: html_node.find("#id_test_paper").val(),
                            });
                        }
                        


                       


                    }
                }]
            });


            dlg.getModalDialog().css("width","78%");


            var close_btn=$('<div class="bootstrap-dialog-close-button" style="display: block;"><button class="close">×</button></div>');
            dlg.getModalDialog().find(".bootstrap-dialog-header").append( close_btn);
            close_btn.on("click",function(){
                dlg.close();
            } );
            var th = setTimeout(function(){
                $.custom_upload_file('id_upload_test_paper', false,function (up, info, file) {
                    var res = $.parseJSON(info);
                    console.log(res);
                    id_test_paper.val(res.key);

                }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);
                clearTimeout(th);
            }, 1000);
        });
    };
    

    $('.opt-change').set_input_change_event(load_data);
});
