/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-get_new_list.d.ts" />

$(function(){
    var deal_new_user_url="/seller_student_new/deal_new_user";
    if ($.get_action_str()=="get_new_list_tmk"){
        deal_new_user_url="/seller_student_new/deal_new_user_tmk";
    }



    function load_data(){
        $.reload_self_page ( {
            grade:	$('#id_grade').val(),
            has_pad:	$('#id_has_pad').val(),
			      t_flag:	$('#id_t_flag').val(),
            phone:	$('#id_phone').val(),
            subject:	$('#id_subject').val()
        });
    }

    Enum_map.append_option_list("grade",$("#id_grade"));
    Enum_map.append_option_list("pad_type",$("#id_has_pad"));
    Enum_map.append_option_list("subject",$("#id_subject"));

    $('#id_grade').val(g_args.grade);
    $('#id_has_pad').val(g_args.has_pad);
    $('#id_subject').val(g_args.subject);
	  $('#id_t_flag').val(g_args.t_flag);

    $('#id_phone').val(g_args.phone);

    $(".opt-set-self").on("click",function(){
        var opt_data=$(this).get_opt_data();

        $.do_ajax("/ss_deal/set_no_called_to_self",{
            "test_lesson_subject_id" : opt_data.test_lesson_subject_id,
            "new_flag" :1
        });


        /*
          $.do_ajax( "/ss_deal/test_subject_free_list_add",{
          "userid" : opt_data.userid
          },function(resp){
          if ( resp.ret==0 ) {
          $.do_ajax("/ss_deal/set_no_called_to_self",{
          "test_lesson_subject_id" : opt_data.test_lesson_subject_id,
          "new_flag" :1
          });

          }else{
          alert(resp.info);
          }
          });
        */
    });

    $('.opt-change').set_input_change_event(load_data);
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


    init_noit_btn("id_max_count",    "今天总共名额" );
    init_noit_btn("id_left_count",    "今天剩余名额" );

    /*
      <td>生成时间 </td>
      <td>类型</td>
      <td>销售 </td>
      <td>可抢</td>
      <td>已抢</td>
      <td>剩余</td>
      <td>生效开始时间</td>
      <td>生效结束时间</td>
      <td>说明</td>
      <td>{{$var["add_time"]}} </td>
      <td>{{$var["seller_new_count_type_str"]}} </td>
      <td>{{$var["admin_nick"]}} </td>
      <td>{{$var["count"]}} </td>
      <td>{{$var["get_count"]*1}} </td>
      <td>{{$var["count"]-$var["get_count"]}} </td>
      <td>{{$var["start_time"]}} </td>
      <td>{{$var["end_time"]}} </td>
      <td>{{$var["value_ex_str"]}} </td>

    */


    $("#id_count_detail" ).on("click",function(){
        $(this).admin_select_dlg_ajax({
            "opt_type" :  "list", // or "list"
            "url"          : "/ss_deal/get_seller_new_count_list_js",
            //其他参数
            "args_ex" : {
            },
            //字段列表
            'field_list' :[
                {
                    title:"生成时间",
                    render:function(val,item) {return item.add_time;}
                },{
                    title:"类型",
                    render:function(val,item) {return item.seller_new_count_type_str;}
                },{
                    title:"额度",
                    render:function(val,item) {return item.count;}
                },{
                    title:"已抢",
                    render:function(val,item) {return item.get_count*1;}
                },{
                    title:"剩余",
                    render:function(val,item) {return item.count-item.get_count;}
                },{
                    title:"有效开始时间",
                    render:function(val,item) {return item.start_time;}
                },{
                    title:"有效结束时间",
                    render:function(val,item) {return item.end_time;}
                },{
                    title:"说明",
                    render:function(val,item) {return item.value_ex_str;}
                }
            ] ,
            filter_list: [],

            "auto_close"       : true,
            //选择
            "onChange"         : null,
            //加载数据后，其它的设置
            "onLoadData"       : null,

        });

    });




    $("#id_reload").on("click",function(){
        window.location.reload();
    });


    /*
     */


    $(".opt-telphone").on("click",function(){
        //
        var opt_data= $(this).get_opt_data();
        $.do_ajax( "/ss_deal/test_subject_free_list_add",{
            "userid" : opt_data.userid
        },function(resp){
            if (resp.ret==0) {
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
                $.wopen( deal_new_user_url,true);

            }else{
                if (resp.need_deal_cur_user_flag ){
                    alert(resp.info);
                    $.wopen( deal_new_user_url, true);
                }else{
                    alert(resp.info);
                }
            }
        } ) ;


    });

    $("#id_cur_user").on("click",function(){
        $.wopen( deal_new_user_url,true);
    });
    if (g_args.seller_level >=400 ) { //C ,D ,E ...
        $(".td-add-time").hide();
    }
    if ($.get_action_str() != "get_new_list_tmk" ) {
        $("#id_t_flag").parent().parent().hide();
    }

});
