/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-get_hold_list.d.ts" />

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
      hold_flag:	$('#id_hold_flag').val(),
      phone_name:	$('#id_phone_name').val(),
      seller_student_status:	$('#id_seller_student_status').val(),
      subject:	$('#id_subject').val(),
      grade:	$('#id_grade').val(),
      page_count:	$('#id_page_count').val()
        });
    }

  Enum_map.append_option_list("boolean",$("#id_hold_flag"));
  Enum_map.append_option_list("seller_student_status",$("#id_seller_student_status"));
  Enum_map.append_option_list("subject",$("#id_subject"));
  Enum_map.append_option_list("grade",$("#id_grade"));

  $('#id_hold_flag').val(g_args.hold_flag);
  $('#id_phone_name').val(g_args.phone_name);
  $('#id_seller_student_status').val(g_args.seller_student_status);
  $('#id_subject').val(g_args.subject);
  $('#id_grade').val(g_args.grade);
  $('#id_page_count').val(g_args.page_count);

    $( "#id_phone_name" ).autocomplete({
        source: "/user_deal/get_item_list?list_flag=1&item_key="+show_name_key,
        minLength: 0,
        select: function( event, ui ) {
            $("#id_phone_name").val(ui.item.value);
            load_data();
        }
    });


  $('.opt-change').set_input_change_event(load_data);

    $("#id_select_all").on("click",function(){
        $(".opt-select-item").each(function(){
            var $item=$(this);
            var opt_data=$(this).get_row_opt_data();
            if ($item.iCheckValue() ) {
                $item.iCheck("uncheck");
            }else{
                if (!opt_data.set_not_hold_err_msg){
                    $item.iCheck("check");
                }
            }
        });
    });

    $(".opt-select-item").on("ifClicked", function (ent) {
        var me=this;
        var opt_data=$(this).get_row_opt_data();
        if(!$(this).iCheckValue() ) {
            if(opt_data.set_not_hold_err_msg) {
                alert("不可选择: "+ opt_data.set_not_hold_err_msg);
                setTimeout(function(){
                    $(me).iCheck("uncheck");
                } ,10);
            }
        } else  {

        }
        return false;
    }) ;

    $("#id_select_other").on("click",function(){
        $(".opt-select-item").each(function(){
            var opt_data=$(this).get_row_opt_data();
            var $item=$(this);
            if ($item.iCheckValue() ) {
                $item.iCheck("uncheck");
            }else{
                if (!opt_data.set_not_hold_err_msg){
                    $item.iCheck("check");
                }
            }
        } );
    });
    var set_hold_list=function (hold_flag)  {

        var select_userid_list=[];

        $(".opt-select-item").each(function(){
            var $item=$(this) ;
            if($item.iCheckValue()) {
                select_userid_list.push( $item.data("userid") ) ;
            }
        });
        if (select_userid_list.length>0) {
            var hold_flag_str=hold_flag?"保留":"不保留";
            BootstrapDialog.confirm("要批量设置选择的["+select_userid_list.length+"]个:" + hold_flag_str ,function(val ){
                if (val) {
                    $.do_ajax('/ss_deal/set_hold_list', {
                        'userid_list' : JSON.stringify(select_userid_list ),
                        "hold_flag" : hold_flag
                    });

                }

            });
        }else{
            alert("还没有选择") ;
        }


    };

    $("#id_set_hold").on("click",function(){
        set_hold_list(1);
    });

    $("#id_set_no_hold").on("click",function(){
        set_hold_list(0);
    });
    $("#id_set_all_hold").on("click",function(){
        $.do_ajax('/ss_deal/set_all_hold', {
        });

    });

    $("#id_set_no_hold_free").on("click",function(){
        $.do_ajax("/seller_student_new/test_lesson_order_fail_list_mul",{} ,function(ret){
            if(ret.ret){
                alert("回流前签单失败原因不能为'考虑中',请重新设置!");
                window.location.href = 'http://admin.yb1v1.com/seller_student_new/test_lesson_order_fail_list_seller?order_flag=0&userid='+ret.userid;
            }else{
                if (g_account != "jim"  ) {
                    if ($("#id_hold_cur_count").data("value")>50 ) {
                        BootstrapDialog.confirm("要放弃所有的不保留的例子,回流到公海?!",function(val ){
                            if (val) {
                                $.do_ajax('/ss_deal/set_no_hold_free', {
                                });
                            }
                        });
                    }else{
                        alert("当前保留的个数:"+  $("#id_hold_cur_count").data("value") +",没有超过50个,不能操作!,请保留更多的例子 ");
                    }
                }else{
                    $.do_ajax('/ss_deal/set_no_hold_free', {
                    });
                }
            }
        });
    });


    var init_noit_btn=function( id_name, title) {
        var btn=$('#'+id_name);
        btn.tooltip({
            "title":title,
            "html":true
        });
        var value =btn.data("value");
        btn.text(value);
        if (value >0 ) {
            btn.addClass("btn-warning");
        }
    };

    init_noit_btn("id_hold_define_count",    "可以保留的个数" );
    init_noit_btn("id_hold_cur_count",    "当前保留的个数" );




    //点击进入个人主页
    $('.opt-user').on('click', function () {
        var opt_data= $(this).get_opt_data();
        $.wopen('/stu_manage?sid=' + opt_data.userid);
    });

});
