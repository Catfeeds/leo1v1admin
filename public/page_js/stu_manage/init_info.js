/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-init_info.d.ts" />

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

    $.each(init_data,function(i,item){
        if ( !$.isNumeric(i) ) {
            $("#id_"+i ).val($.trim(item));
        }
    });

    $("#id_submit").on("click",function(){
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
            $.do_ajax( "/user_deal/set_stu_init_info", {
                "userid" : g_args.sid,
                data : JSON.stringify(arr )
            });
        }


    });
});
