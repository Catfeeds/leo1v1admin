/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-init_info_by_contract.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
      sid: g_args.sid
        });
    }
    var init_data=g_data_ex_list;
    Enum_map.append_option_list("gender", $("#id_gender"), true);
    Enum_map.append_option_list("grade", $("#id_grade"), true);
    $("#id_birth").datetimepicker({
    lang:'ch',
    timepicker:false,
    format:'Ymd'
  });
    $("#id_call_time").datetimepicker({
    lang:'ch',
    timepicker:true,
    format:'Y-m-d H:i'
  });

    Enum_map.append_option_list("relation_ship", $("#id_relation_ship"), true);
    Enum_map.append_option_list("boolean", $("#id_has_fapiao"), true);

    if(init_data){
        $.each(init_data,function(i,item){
            if ( !$.isNumeric(i) ) {
                $("#id_"+i ).val($.trim(item));
            }

        });


        $.each(init_data,function(i,item){
            if(i == 'is_show_submit' && item == 1){
                $("#id_submit").hide();
                $("#id_submit_succ").hide();
            }
        });



    }

    $("#id_submit").on("click",function(){
        var url_arr = GetRequest();

        var input= $(".form-control");
        var arr={
        };
        input.each(function(){
            var $item=$(this);
            var id=$item.attr("id");
            if (id.substr(0,3 )=="id_") {
                var name=id.substr(3 );
                arr[name]=$.trim($item.val());
            }
        });
        var check_field_list=["subject_info","order_info"
                              ,"teacher", "teacher_info" ,"test_lesson_info"
                              ,"lesson_plan","except_lesson_count","parent_other_require"
                             ];

        var check_flag=true;
        var check_err_field="";
        $(check_field_list).each(function(){
            var name=this;
            if (!arr[name]) {
                check_flag=false;

                check_err_field=name;
                return false;
            }
        });
        if (!check_flag) {
            var field_title= $("#id_"+check_err_field ).parent().find(".field-name").text();
            alert( field_title+"不能为空");

            return ;
        }else{
            arr['orderid'] = url_arr['orderid'];
            $.do_ajax( "/user_deal/set_stu_cc_to_cr_info", {
                "userid" : g_args.sid,
                "ispost" : 0,
                data : JSON.stringify(arr )
            });
        }


    });



    $("#id_submit_succ").on("click",function(){
        var url_arr = GetRequest();
        var input= $(".form-control");
        var arr={
        };
        input.each(function(){
            var $item=$(this);
            var id=$item.attr("id");
            if (id.substr(0,3 )=="id_") {
                var name=id.substr(3 );
                arr[name]=$.trim($item.val());
            }
        });
        var check_field_list=["subject_info","order_info"
                              ,"teacher", "teacher_info" ,"test_lesson_info"
                              ,"lesson_plan","except_lesson_count","parent_other_require"
                             ];

        var check_flag=true;
        var check_err_field="";
        $(check_field_list).each(function(){
            var name=this;
            if (!arr[name]) {
                check_flag=false;

                check_err_field=name;
                return false;
            }
        });
        if (!check_flag) {
            var field_title= $("#id_"+check_err_field ).parent().find(".field-name").text();
            alert( field_title+"不能为空");

            return ;
        }else{
            arr['orderid'] = url_arr['orderid'];
            $.do_ajax( "/user_deal/set_stu_cc_to_cr_info", {
                'data'    : JSON.stringify(arr ),
                'ispost'  : 1

            });
        }


    });

    var GetRequest = function() {
        var url = location.search; //获取url中"?"符后的字串
        var theRequest = new Object();
        if (url.indexOf("?") != -1) {
            var str = url.substr(1);
            var strs = str.split("&");
            for(var i = 0; i < strs.length; i ++) {
                theRequest[strs[i].split("=")[0]]= unescape(strs[i].split("=")[1]);
            }
        }
        return theRequest;
    }



});
