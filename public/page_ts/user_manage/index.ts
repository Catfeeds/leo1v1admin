/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-index.d.ts" />

$(function(){
    var show_name_key="stu_info_name_"+g_adminid;

    $( "#id_user_name" ).autocomplete({
      source: "/user_deal/get_item_list?list_flag=1&item_key="+show_name_key,
      minLength: 0,
      select: function( event, ui ) {
          $("#id_user_name").val(ui.item.value);
          load_data();
      }
    });


    $(".opt-set-tmp-passwd").on("click", function(){
        var opt_data      = $(this).get_opt_data();
        var id_tmp_passwd = $("<input/>");
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
              data     :{
                        "phone"  : opt_data.phone,
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

    $("#id_test").on("click",function(){
        $("#id_test").admin_select_dlg_edit({
            onAdd:function( call_func ) {
                var id_start_time=$("<input/>");
                  id_start_time.datetimepicker({
                    datepicker:true,
                    timepicker:true,
                    format:'Y-m-d H:i',
                    step:30,
                  });
                var arr=[
                    ["时间", id_start_time ]
                ];

                $.show_key_value_table("增加",arr, {
                    label    : '确认',
                    cssClass : 'btn-warning',
                    action   : function(dialog) {
                        call_func({
                            "time" : $.strtotime( id_start_time.val() )
                        }) ;
                        dialog.close();
                    }
                });



            },
            sort_func : function(a,b){
                    var a_time=a["time"];
                    var b_time=b["time"];
                    if (a_time==b_time ) {
                        return 0;
                    }else{
                        if (a_time>b_time) return 1;
                        else return -1;
                    }
            }, 'field_list' :[
                {
                    title:"时间",
                    //width :50,
                    field_name:"time",
                    render:function(val,item) {
                        return  $.DateFormat(item.time, "yyyy-MM-dd hh:mm");
                    }
                    /*
                },{
                    title:"昵称",
                    //width :50,
                    field_name:"nick",
                    render:function(val,item) {
                        return item.nick;
                    }
                    */
                }
            ] ,
            data_list:[
                {"time":113133} ,
                {"time":113} ,
                {"time":113413413} ,
                {"time":11341333}
            ],
            onChange:function( data_list, dialog)  {
                alert(JSON.stringify(data_list));
            }
        });
    });

    Enum_map.append_option_list("grade", $("#id_grade"));
    Enum_map.append_option_list("test_user", $("#id_test_user"));
    Enum_map.append_option_list("stu_origin", $("#id_originid"));
    Enum_map.td_show_desc("grade", $(".td-grade"));
    Enum_map.td_show_desc("grade", $(".td-grade-up"));
    Enum_map.td_show_desc("relation_ship", $(".td-parent-type"));
    Enum_map.append_option_list("student_type", $("#id_student_type"));

  $("#id_grade").val(g_args.grade);
  $("#id_test_user").val(g_args.test_user);
  $("#id_originid").val(g_args.originid);
  $("#id_user_name").val(g_args.user_name);
  $("#id_phone").val(g_args.phone);
  $("#id_seller_adminid").val(g_args.seller_adminid);
  $("#id_order_type").val(g_args.order_type);
  $("#id_student_type").val(g_args.student_type);

    $("#id_assistantid").val(g_args.assistantid);

    $.admin_select_user($("#id_assistantid"), "assistant", load_data);
    $.admin_select_user($("#id_seller_adminid"), "admin", load_data);

  $('.opt-change').set_input_change_event(load_data);


  //点击进入个人主页
  $('.opt-user').on('click',function(){
        var opt_data=$(this).get_opt_data();
        window.open(
            '/stu_manage?sid='+ opt_data.userid +"&return_url="+ encodeURIComponent(window.location.href)
        );
  });


  function load_data(){
        if ($.trim($("#id_user_name").val()) != g_args.user_name ) {
            $.do_ajax("/user_deal/set_item_list_add",{
                "item_key" :show_name_key,
                "item_name":  $.trim($("#id_user_name").val())
            },function(){});
        }

        $.reload_self_page({
            test_user   : $("#id_test_user").val(),
            originid    : $("#id_originid").val(),
            grade       : $("#id_grade").val(),
            user_name   : $("#id_user_name").val(),
            phone       : $("#id_phone").val(),
            assistantid : $("#id_assistantid").val(),
            order_type : $("#id_order_type").val(),
            seller_adminid : $("#id_seller_adminid").val(),
            student_type : $("#id_student_type").val()
        });
  }

    //设置是否为测试用户
    Enum_map.append_option_list("test_user",$("#id_set_channel"),true);

    $(".opt-test-user").on("click", function(){
        var opt_data=$(this).get_opt_data();
        var $is_test_user= $("<select/>");
        Enum_map.append_option_list("boolean", $is_test_user,true );
        $is_test_user.val(opt_data.is_test_user);
        var arr=[
            ["测试用户" , $is_test_user]
        ];

        $.show_key_value_table("测试用户", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax( "/user_manage/set_test_user", {

                    "userid":opt_data.userid ,
                    "type":$is_test_user.val()
                } );
            }
        });

    });



    $(".opt-set-spree").on("click", function(){
        var opt_data=$(this).get_opt_data();
    var studentid  =opt_data.userid;

        var id_spree = $("<input />");
        var arr = [
            [ "设置大礼包",id_spree]
        ];

        $.show_key_value_table("设置大礼包", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/user_manage/set_spree", {
                    'studentid' : studentid,
                    'spree'     : id_spree.val()
                });

            }
        });



    });





    $(".opt-left-time").on("click", function(){
        var opt_data=$(this).get_opt_data();
        $.do_ajax( "/user_deal/reset_lesson_count",{
                'studentid' : opt_data.userid
        } );
    });

    $(".opt-stu-origin").on("click", function(){
        var opt_data=$(this).get_opt_data();
        var id_origin_userid = $("<input/>");
        var userid           = opt_data.userid;

        var arr = [
            [ "学生", opt_data.nick] ,
            [ "电话", opt_data.phone] ,
            [ "转介绍人", id_origin_userid] ,
        ];
        id_origin_userid.val(opt_data.origin_userid );



        $.show_key_value_table("设置转介绍", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax('/user_deal/stu_set_origin_userid', {
                    'origin_userid' :id_origin_userid.val(),
                    'userid'        : userid
          });
            }
        },function(){
            $.admin_select_user(id_origin_userid, "student", function(){},true );
        });




  });

    // 设置学生临时密码
    $(".opt-modify").on("click", function(){
        var html_node =$("<div></div>").html($.dlg_get_html_by_class('dlg_set_dynamic_passwd'));

        html_node.find(".stu_phone").text($(this).parents("td").siblings(".user_phone").text());
        html_node.find(".stu_nick").text($(this).parents("td").siblings(".user_nick").text());
        html_node.find(".dynamic_passwd").val("123456");

        BootstrapDialog.show({
            title: '设置登陆密码',
            message : html_node,
            closable: true,
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action: function(dialog) {
                        var phone = html_node.find(".stu_phone").text();
                        var passwd = html_node.find(".dynamic_passwd").val();

                    $.ajax({
                      type     :"post",
                      url      :"/user_manage/set_dynamic_passwd",
                      dataType :"json",
                      data     :{"phone":phone, "passwd": passwd, "role": 1 },
                      success  : function(result){
                                BootstrapDialog.alert(result['info']);
                      }
                        });
                        dialog.close();
                    }
                },
                {
                    label: '取消',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
        });
    });

    $('.opt-test-room').on('click', function(){

        var phone = $(this).parents("td").siblings(".user_phone").text();

    $.ajax({
      type     :"post",
      url      :"/user_manage/get_test_room",
      dataType :"json",
      data     :{"phone":phone },
      success  : function(result){
                if (result['ret'] != 0) {
                    BootstrapDialog.alert(result['info']);
                } else {
                    var msg = '';
                    if (result['test_room'] == '') {
                        msg = '是否设置试音室';
                    } else {
                        msg = '是否取消试音室'+result['test_room'];
                    }
                    BootstrapDialog.show({
                        title: '设置试音室',
                        message : msg,
                        closable: true,
                        buttons: [
                            {
                                label: '确认',
                                cssClass: 'btn-primary',
                                action: function(dialog) {

                                $.ajax({
                                  type     :"post",
                                  url      :"/user_manage/set_test_room",
                                  dataType :"json",
                                  data     :{"phone":phone },
                                  success  : function(result){
                                            BootstrapDialog.alert(result['info']);
                                  }
                                    });
                                    dialog.close();
                                }
                            },
                            {
                                label: '取消',
                                cssClass: 'btn',
                                action: function(dialog) {
                                    dialog.close();
                                }
                            }]
                    });
                }
      }
        });
    });

    var init_show_name_list_flag=false;

    $('.opt-lesson').on('click',function(){
        var opt_data=$(this).get_opt_data();
        window.open(
            '/stu_manage/course_list?sid='+ opt_data.userid +"&return_url="+ encodeURIComponent(window.location.href)
        );
    });

    $(".show_phone").on("click",function(){
        var phone = $(this).data("phone");
        BootstrapDialog.alert(phone);
    });

    download_hide();

});
