/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-deal_new_user.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
        });
    }

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


    $("#id_edit").on("click",function(){
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
                    // var url = "http://admin.yb1v1.com/seller_student_new/seller_student_list_all?success_flag=1&userid="+resp.userid;
                    var url = "http://admin.yb1v1.com/seller_student_new/no_lesson_call_end_time_list?adminid="+resp.adminid;
                    window.location.href = url;
                }
            }
        });

    });
    if (!g_args.open_flag) {
        $("#id_get_new").hide();
        $("#id_edit").hide();
        $("#id_call_phone").hide();

        $("#id_sync_ytx").hide();
        $("#id_sync_tq").hide();
    }


    $("#id_get_this_new_user").on("click",function(){
        $.do_ajax("/seller_student_new/get_this_new_user",{
            "phone" : g_args.phone,
        },function(ret){
            if(ret == 1){
                alert('认领成功!')
                window.location.reload();
            }
        });
    });

});
