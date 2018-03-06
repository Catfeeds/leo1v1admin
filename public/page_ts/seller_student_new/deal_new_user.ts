/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-deal_new_user.d.ts" />

$(function(){
    //试听未回访
    $.do_ajax( "/seller_student_new/check_lesson_end",{
    },function(resp){
        if(resp>0){
            // alert('有'+resp+'个试听成功用户未回访,不能获得新例子,请尽快完成回访');
            var start_time = g_args.start_time;
            var end_time = g_args.end_time;
            var url = "http://"+window.location.host+"/seller_student_new/seller_student_list_all?date_type=1&opt_date_type=0&start_time="+start_time+"&end_time="+end_time+"&no_lesson_call_flag=1&left_time_order=0&group_seller_student_status=-1&seller_groupid_ex=&seller_groupid_ex_new=&userid=-1&success_flag=-1&phone_name=&seller_student_status=-1&phone_location=&subject=-1&origin_assistant_role=-1&has_pad=-1&tq_called_flag=-1&global_tq_called_flag=-1&origin_assistantid=-1&origin_userid=-1&seller_require_change_flag=-1&tmk_student_status=-1&seller_resource_type=-1&favorite_flag=-1";
            // window.location.href = url;
        }
    });
    function load_data(){
        $.reload_self_page ( {
        });
    }

    // 待测试功能
    // 处理标记空号功能 [james]
    /*
    var test_arr = ['99','684','1173','1273','1408','1383','1384','1393','1394','1399','1404','1405','1406','1407','1408'];
    if($.inArray(g_adminid,test_arr)>=0){
        $('#id_tip_no_call').show();
        var hasCalledNum = g_args.hasCalledNum;
        if(g_args.cc_no_called_count_new>=3 && g_args.ccNoCalledNum>0){
            $('#id_tip_no_call').addClass('btn-warning').css('color','white').removeAttr('disabled');
        }else{
            $('#id_tip_no_call').attr('disabled','disabled').removeClass('btn-warning');
        }


        $('#id_tip_no_call').on('click',function(){
            $('.bs-example-modal-sm').modal('toggle');
            do_submit();
        });

        $('.submit_tag').on("click",function(){
            sign_func();
        });

        $('.invalid_type').on("change",function(){
            do_submit();
        });

        var do_submit = function(){
            var invalid_type = $('.invalid_type').val();
            if(invalid_type == 0){
                $('.submit_tag').attr('disabled','disabled');
            }else{
                $('.submit_tag').removeAttr('disabled');
            }
        }

        var sign_func = function(){
            var opt_data=$(this).get_opt_data();
            var invalid_type = $('.invalid_type').val();
            var checkText=$(".invalid_type").find("option:selected").text();

            $('.tip_text').text(checkText);
            $('.confirm-sm').modal('toggle');
            $('.confirm_tag').on("click",function(){
                $.do_ajax("/ajax_deal3/sign_phone",{
                    "adminid" : g_adminid,
                    "cc_confirm_type" : invalid_type,
                    "userid"  : g_args.userid,
                    "type"    : 1 // 1:CC标注 2:TMK 3:QC
                });

                window.location.reload();
            });
        }

        if(g_args.hasCalledNum == 0){
            $('#id_edit').attr('disabled','disabled');
        }else{
            $('#id_edit').removeAttr('disabled');
        }

    }
    */
    // 处理标记空号功能 [james-end]











    $("#id_sync_tq").on("click",function(){
        $.do_ajax("/ss_deal/sync_tq",{
            "phone" : g_args.phone,
        } );

    });

    $("#id_sync_ytx").on("click",function(){
        $.do_ajax("/ss_deal/sync_ytx",{
            "phone" : g_args.phone,
            "ytx_account" : 'liyou',
        } );

    });
    $("#id_call_phone").on("click",function(){

        var phone    = ""+ g_args.phone;
        phone=phone.split("-")[0];

        try{
            window.navigate(
                "app:1234567@"+phone+"");
        } catch(e){

        };
        $.do_ajax_t("/ss_deal/call_ytx_phone", {
            "phone": phone
        } );

    });


    $("#id_edit_new").on("click",function(){
        var opt_obj=this;

        $.do_ajax("/ss_deal/get_user_info",{
            "userid" : g_args.userid ,
            test_lesson_subject_id : g_args.test_lesson_subject_id,
        } ,function(ret){
            var data=ret.data;
            var html_node = $.dlg_need_html_by_id( "id_dlg_post_user_info");
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
                                    "phone":g_args.phone,
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
            var id_revisite_info     = html_node.find("#id_stu_revisite_info");
            var id_has_pad           = html_node.find("#id_stu_has_pad");
            var id_editionid         = html_node.find("#id_stu_editionid");
            var id_school            = html_node.find("#id_stu_school");
            var id_next_revisit_time = html_node.find("#id_next_revisit_time");
            var id_stu_request_test_lesson_time = html_node.find("#id_stu_request_test_lesson_time");
            var id_stu_request_test_lesson_demand= html_node.find("#id_stu_request_test_lesson_demand");
            var id_stu_score_info = html_node.find("#id_stu_score_info");
            var id_stu_character_info = html_node.find("#id_stu_character_info");
            var id_stu_test_lesson_level = html_node.find("#id_stu_test_lesson_level");
            var id_stu_test_ipad_flag = html_node.find("#id_stu_test_ipad_flag");
            var id_stu_request_test_lesson_time_info = html_node.find("#id_stu_request_test_lesson_time_info");
            var id_stu_request_lesson_time_info = html_node.find("#id_stu_request_lesson_time_info");
            id_stu_request_test_lesson_time_info.data("v" , data. stu_request_test_lesson_time_info  );
            id_stu_request_lesson_time_info.data("v" , data.stu_request_lesson_time_info);
            id_stu_request_lesson_time_info.on("click",function(){
                var v=$(this).data("v");
                if(!v) {
                    v="[]";
                }
                var data_list=JSON.parse(v);

                $(this).admin_select_dlg_edit({
                    onAdd:function( call_func ) {
                        var id_week= $("<select> "+
                                       "<option value=1>周1</option> "+
                                       "<option value=2>周2</option> "+
                                       "<option value=3>周3</option> "+
                                       "<option value=4>周4</option> "+
                                       "<option value=5>周5</option> "+
                                       "<option value=6>周6</option> "+
                                       "<option value=0>周日</option> "+
                                       "</select>");
                        var id_start_time=$("<input/>");
                        var id_end_time=$("<input/>");
                        id_start_time.datetimepicker({
                            datepicker:false,
                            timepicker:true,
                            format:'H:i',
                            step:30,
                            onChangeDateTime :function(){
                                var end_time= $.strtotime("1970-01-01 "+id_start_time.val() ) + 7200;
                                id_end_time.val(  $.DateFormat(end_time, "hh:mm"));
                            }
                        });
                        id_end_time.datetimepicker({
                            datepicker:false,
                            timepicker:true,
                            format:'H:i',
                            step:30
                        });
                        var arr=[
                            ["周", id_week],
                            ["开始时间", id_start_time],
                            ["结束时间", id_end_time],
                        ];
                        $.show_key_value_table("增加", arr, {
                            label: '确认',
                            cssClass: 'btn-warning',
                            action: function (dialog) {
                                call_func({
                                    "week" :  id_week.val() ,
                                    "start_time" : $.strtotime( "1970-01-01 "+ id_start_time.val()) ,
                                    "end_time" : $.strtotime ( "1970-01-01 "+ id_end_time.val())
                                });
                                dialog.close();
                            }
                        });





                        /*
                          var div=$("<div/>");
                          div.admin_select_date_time_range({

                          onSelect:function(start_time,end_time) {
                          call_func({
                          "start_time" : start_time ,
                          "end_time" : end_time
                          });
                          }
                          });
                          div.click();
                        */
                    },
                    sort_func : function(a,b){
                        var a_time=a["week"]*10000000+a["start_time"];
                        var b_time=b["week"]*10000000+b["start_time"];
                        if (a_time==b_time ) {
                            return 0;
                        }else{
                            if (a_time>b_time) return 1;
                            else return -1;
                        }
                    }, 'field_list' :[
                        {
                            title:"周",
                            render:function(val,item) {
                                return Enum_map.get_desc("week", item["week"]*1  );
                            }
                        },{

                            title:"时间段",
                            //width :50,
                            render:function(val,item) {
                                return  $.DateFormat(item.start_time, "hh:mm") +"~"+
                                    $.DateFormat(item.end_time, "hh:mm")  ;
                            }
                        }
                    ] ,
                    data_list: data_list,
                    onChange:function( data_list, dialog)  {
                        id_stu_request_lesson_time_info.data("v" , JSON.stringify(data_list));
                    }
                });
            }) ;

            id_stu_request_test_lesson_time_info.on("click",function(){
                var v=$(this).data("v");
                if(!v) {
                    v="[]";
                }
                var data_list=JSON.parse(v);

                $(this).admin_select_dlg_edit({
                    onAdd:function( call_func ) {
                        var div=$("<div/>");
                        div.admin_select_date_time_range({

                            onSelect:function(start_time,end_time) {
                                call_func({
                                    "start_time" : start_time ,
                                    "end_time" : end_time
                                });
                            }
                        });
                        div.click();
                    },
                    sort_func : function(a,b){
                        var a_time=a["start_time"];
                        var b_time=b["start_time"];
                        if (a_time==b_time ) {
                            return 0;
                        }else{
                            if (a_time>b_time) return 1;
                            else return -1;
                        }
                    }, 'field_list' :[
                        {
                            title:"时间段",
                            //width :50,
                            render:function(val,item) {
                                return  $.DateFormat(item.start_time, "yyyy-MM-dd hh:mm") +"~"+
                                    $.DateFormat(item.end_time, "hh:mm")  ;
                            }
                        }
                    ] ,
                    data_list: data_list,
                    onChange:function( data_list, dialog)  {
                        id_stu_request_test_lesson_time_info.data("v" , JSON.stringify(data_list));
                    }
                });


            }) ;

            html_node.find("#id_stu_reset_next_revisit_time").on("click",function(){
                id_next_revisit_time.val("");
            });
            Enum_map.append_option_list("grade", id_grade, true,[101,102,103,104,105,106,201,202,203,301,302,303]);
            Enum_map.append_option_list("pad_type", id_has_pad, true);
            Enum_map.append_option_list("subject", id_subject, true);
            Enum_map.append_option_list("boolean", id_stu_test_ipad_flag, true);
            Enum_map.append_option_list("test_lesson_level", id_stu_test_lesson_level, true);
            id_stu_request_test_lesson_time.datetimepicker( {
                lang:'ch',
                timepicker:true,
                format: "Y-m-d H:i",
                onChangeDateTime :function(){
                }
            });

            html_node.find("#id_stu_reset_stu_request_test_lesson_time").on("click",function(){
                id_stu_request_test_lesson_time.val("");
            });

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

            var vv=0;
            switch (vv ) {
            case 0:
            case  2:
                show_status_list=[ 2, 100,101,102,103 ];
                break;
            case 1:
                show_status_list=[  100, 101,102,103 ];
                break;
            case 101:
            case  102:
                show_status_list=[ 100, 101,102,103, 1  ];
                break;

            case 100:case 103:
                show_status_list=[  100, 101,102,103 ];
                break;

            case 110:case 120:
                show_status_list=[ 100, 101,102,103 ];
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

            show_status_list.push(status);

            Enum_map.append_option_list("seller_student_status", id_status ,true , show_status_list );
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
            id_revisite_info.val(data.revisite_info);
            id_has_pad.val(data.has_pad);
            id_school.val(data.school);
            id_editionid.val(data.editionid);
            id_next_revisit_time.val(data.next_revisit_time);


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
            id_stu_score_info.val(data.stu_score_info);
            id_stu_test_lesson_level.val(data.stu_test_lesson_level);
            id_stu_test_ipad_flag.val(data.stu_test_ipad_flag);
            id_stu_character_info.val(data.stu_character_info);

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

            var title= '用户信息['+g_args.phone+']';
            // if( g_args.account_seller_level >=100  && g_args.account_seller_level<400 ) { //S,A, B
            //     title= title+"-渠道:["+origin+"]";
            // }



            var dlg=BootstrapDialog.show({
                title: title,
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

                        $.do_ajax("/ss_deal/save_user_info",{
                            userid        : g_args.userid,
                            test_lesson_subject_id : g_args.test_lesson_subject_id,
                            phone: g_args.phone,
                            stu_nick      : id_stu_nick.val(),
                            par_nick      : id_par_nick.val(),
                            grade         : id_grade.val(),
                            gender        : id_gender.val(),
                            address       : id_address.val(),
                            subject       : id_subject.val(),
                            seller_student_status : id_status.val(),
                            seller_student_sub_status : id_seller_student_sub_status.val(),
                            user_desc     : id_user_desc.val(),
                            revisite_info : id_revisite_info.val(),
                            next_revisit_time : id_next_revisit_time.val(),
                            editionid : id_editionid.val(),
                            school: id_school.val(),
                            stu_request_test_lesson_time:id_stu_request_test_lesson_time.val(),
                            stu_request_test_lesson_demand:id_stu_request_test_lesson_demand.val(),
                            stu_score_info:id_stu_score_info.val(),
                            stu_test_lesson_level:id_stu_test_lesson_level.val(),
                            stu_test_ipad_flag:id_stu_test_ipad_flag.val(),
                            stu_character_info:id_stu_character_info.val(),
                            stu_request_test_lesson_time_info:id_stu_request_test_lesson_time_info.data("v"),
                            stu_request_lesson_time_info:id_stu_request_lesson_time_info.data("v"),
                            has_pad       : id_has_pad.val()
                        });

                    }
                }]
            });

            dlg.getModalDialog().css("width","98%");


            var close_btn=$('<div class="bootstrap-dialog-close-button" style="display: block;"><button class="close">×</button></div>');
            dlg.getModalDialog().find(".bootstrap-dialog-header").append( close_btn);
            close_btn.on("click",function(){
                dlg.close();
            } );

        });

    });


    $("#id_goto_new_list").on("click",function(){
        if ($.get_action_str()=="deal_new_user_tmk") {
            $.wopen("/seller_student_new/get_new_list_tmk",true);
        }else{
            $.wopen("/seller_student_new/get_new_list",true);
        }
    });
    if ($.get_action_str()=="deal_new_user") {
        $("#id_goto_new_list").hide();
        $("#id_get_new").show();
    }

    // james
    /*
    if($.inArray(g_adminid,test_arr)>=0){ // 测试环境
        $("#id_get_new").on("click",function(){
            var opt_data=$(this).get_opt_data();
            if(g_args.cc_no_called_count_new < 3 && g_args.ccNoCalledNum>0){
                alert("请先提交未拨通电话标注后才能继续抢新");
                return ;
            }

            $.do_ajax_t("/ajax_deal3/checkHasSign", {
                "userid"  : g_args.userid,
                "adminid" : g_adminid
            },function(ret){
                var is_sign = ret.is_sign;

                if(!is_sign && g_args.cc_no_called_count_new>=3 && g_args.ccNoCalledNum>0){
                    $('.bs-example-modal-sm').modal('toggle');
                    return;
                }else{
                    $.do_ajax("/seller_student_new/get_one_new_user",{},function(resp) {
                        if (resp.ret==0) {
                            var phone=resp.phone;

                            try{
                                window.navigate(
                                    "app:1234567@"+phone+"");
                            } catch(e){

                            };
                            $.do_ajax_t("/ss_deal/call_ytx_phone", {
                                "phone": phone
                            } );
                            $.reload();

                        }else{
                            alert(resp.info);
                            if(resp.userid){
                                var url = "http://admin.leo1v1.com/seller_student_new/no_lesson_call_end_time_list?adminid="+resp.adminid;
                                window.location.href = url;
                            }
                        }
                    });
                }

            } );
        });

    }else{ // 原有环境
*/
        $("#id_get_new").on("click",function(){
            $.do_ajax("/seller_student_new/get_one_new_user",{},function(resp) {
                if (resp.ret==0 ) {
                    var phone=resp.phone;

                    try{
                        window.navigate(
                            "app:1234567@"+phone+"");
                    } catch(e){

                    };
                    $.do_ajax_t("/ss_deal/call_ytx_phone", {
                        "phone": phone
                    } );
                    $.reload();

                }else{
                    alert(resp.info);
                    if(resp.userid){
                        var url = "http://admin.leo1v1.com/seller_student_new/no_lesson_call_end_time_list?adminid="+resp.adminid;
                        window.location.href = url;
                    }
                }
            });
        });
    // }







    if (!g_args.open_flag) {
        $("#id_get_new").hide();
        $("#id_edit").hide();
        $("#id_call_phone").hide();

        $("#id_sync_ytx").hide();
        $("#id_sync_tq").hide();
    }

     $("#id_edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var opt_obj=this;
        var click_type=1;

         //james
         // if(g_args.hasCalledNum ==0){
         //     return ;
         // }

        edit_user_info_new(opt_data,opt_obj,click_type);

    });

    var edit_user_info_new=function(opt_data,opt_obj,click_type){
        // var opt_data=$(this).get_opt_data();
        //var opt_obj=this;
        // alert(opt_data.test_lesson_subject_id);

        $.do_ajax("/ss_deal/get_user_info",{
            "userid" : g_args.userid ,
            test_lesson_subject_id : g_args.test_lesson_subject_id
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
                        // message : "您好，我是刚刚联系您的"+xing+"老师 ，如果您还需要申请我们的试听课，请添加一下我的微信："+data.wx_id+"。我们会尽快帮您安排，理优教育服务热线："+g_args.phone,
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
                                    "phone":g_args.phone,
                                    "name":xing,
                                    "wx_id":data.wx_id,
                                    "seller_phone":g_args.phone,
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




            id_stu_request_test_lesson_time.datetimepicker( {
                lang:'ch',
                timepicker:true,
                format: "Y-m-d H:i",
                onChangeDateTime :function(){
                }
            });

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

            //[james] 将show_status_list 中的1去除 即可 去除[无效资源]选项
            /*
            $.each(show_status_list,function(index,value){
                if(value == 1){
                    show_status_list.splice(index, 1);
                }
            });
            */

            Enum_map.append_option_list("seller_student_status", id_status ,true , show_status_list );
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
           // html_node.find("#id_knowledge_point_location").val(data.knowledge_point_location);
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



                        $.do_ajax("/ss_deal/save_user_info_new",{
                            new_demand_flag   : 1,
                            click_type        : click_type,
                            userid            : g_args.userid,
                            test_lesson_subject_id :g_args.test_lesson_subject_id,
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
                          //  knowledge_point_location: html_node.find("#id_knowledge_point_location").val(),
                            recent_results: html_node.find("#id_recent_results").val(),
                            advice_flag: html_node.find("#id_advice_flag").val(),
                            province: province,
                            city: city,
                            area: area,
                            region: region,
                            test_paper: html_node.find("#id_test_paper").val(),
                        });

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



    $("#id_get_this_new_user").on("click",function(){
        $.do_ajax("/seller_student_new/get_this_new_user",{
            "phone" : g_args.phone,
        },function(ret){
            if(ret == 1){
                alert('认领成功!')
                window.location.reload();
            }else if(ret == 2){
                alert('电话未拨通,请拨通后认领!')
            }else if(ret == 3){
                alert('已经认领过!')
            }
        });
    });
    if(g_args.seller_level == 700){
        $('section.content ').attr('style','display:none');
    }
});
