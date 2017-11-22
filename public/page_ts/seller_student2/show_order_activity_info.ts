/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-show_order_activity_info.d.ts" />

$(function(){
    Enum_map.append_option_list("open_flag", $("#id_open_flag"));
    Enum_map.append_option_list("can_disable_flag", $("#id_can_disable_flag"));
    Enum_map.append_option_list("contract_type", $("#id_contract_type"));
    Enum_map.append_option_list("period_flag", $("#id_period_flag"));

    $("#id_open_flag").val(g_args.id_open_flag);
    $("#id_can_disable_flag").val(g_args.id_can_disable_flag);
    $("#id_contract_type").val(g_args.id_contract_type);
    $("#id_period_flag").val(g_args.id_period_flag);

    $('.opt-change').set_input_change_event(load_data);

    //添加活动
    $('#id_add_activity').on('click',function(){
        var id_title = $("<input/>");
        var arr=[
            ["活动标题", id_title ],
        ];
        $.show_key_value_table("添加活动", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {

                var title = id_title.val();
                var data = {
                    'title':title
                }
                if(!title){
                    BootstrapDialog.alert("活动标题必填");
                    return false;
                }
                $.ajax({
                    type     :"post",
                    url      :"/seller_student2/add_order_activity",
                    dataType :"json",
                    data     :data,
                    success : function(result){
                        BootstrapDialog.alert(result['info']);
                        window.location.reload();
                    }
                });
            }
        })

    })
    
    //删除活动
    $('.opt-del').on('click',function(){
        var opt_data = $(this).get_opt_data();

        var id = opt_data.id;
        var title = "你确定删除本活动,标题为" + opt_data.title + "？";
        var data = {
            'id':id
        };

        BootstrapDialog.confirm(title,function(val ){
            if (val) {
                $.do_ajax("/seller_student2/dele_order_activity",data);
            }
        });

    })

    //进入编辑页面
    $('.opt-set').on('click',function(){
        var opt_data=$(this).get_opt_data();
        window.open(
            '/seller_student2/get_order_activity?id='+ opt_data.id +"&return_url="+ encodeURIComponent(window.location.href)
        );
    });

    //修改活动
	  $('#id_update_activity').on('click',function(){

        var id_title = $("<input/>");
        var id_lesson_times_min = $("<input/>");
        var id_lesson_times_max = $("<input/>");

        var id_date_range_start=$("<input/>");
        var id_date_range_end=$("<input/>");
        var id_user_join_time_srart=$("<input/>");
        var id_user_join_time_end=$("<input/>");
        var id_last_test_lesson_srart=$("<input/>");
        var id_last_test_lesson_end=$("<input/>");

        //适配年级区间
        var id_grade_list = $(".grade_arr").clone();
        id_grade_list.removeClass('hide');
        id_grade_list.attr({"id":"grade_list"});

        var id_can_disable_flag = $(".can_disable_flag").clone();
        id_can_disable_flag.removeClass('hide');

        var id_open_flag = $(".open_flag").clone();
        id_open_flag.removeClass('hide');

        var id_order_activity_discount_type = $(".order_activity_discount_type").clone();
        id_order_activity_discount_type.removeClass('hide');

        var id_contract_type_list =$("<select/>");
        Enum_map.append_option_list("contract_type", id_contract_type_list);

        var id_period_flag_list =$("<select/>");
        Enum_map.append_option_list("period_flag", id_period_flag_list);

        var id_power_value = $("<input/>");
        var id_max_count = $("<input/>");
        var id_max_change_value = $("<input/>");
        var id_max_count_activity_type_list = $("<input/>");
        var id_discount_json = $("<textarea>");

        var timeItem = [id_date_range_start,id_date_range_end,id_user_join_time_srart,id_user_join_time_end,id_last_test_lesson_srart,id_last_test_lesson_end];
        
        bindTime(timeItem); 
 
        var arr=[
           
            ["活动标题", id_title ],
            ["活动日期开始时间*", id_date_range_start ],
            ["活动日期结束时间*", id_date_range_end ],

            ["用户加入开始时间", id_user_join_time_srart ],
            ["用户加入结束时间", id_user_join_time_end ],

            ["最近一次试听开始时间", id_last_test_lesson_srart ],
            ["最近一次试听结束时间", id_last_test_lesson_end ],
            ["参加活动最小课时*", id_lesson_times_min ],
            ["参加活动最大课时*", id_lesson_times_max ],
            ["合同类型*", id_contract_type_list ],
            ["分期类型*", id_period_flag_list ],
            ["年级适配*", id_grade_list ],
            ["优惠力度*", id_power_value ],
            ["最大合同数*", id_max_count ],
            ["最大修改累计值*", id_max_change_value ],
            ["总配额组合", id_max_count_activity_type_list ],
            ["是否手动开启活动*", id_can_disable_flag ],
            ["是否开启活动*", id_open_flag ],
            ["优惠类型*", id_order_activity_discount_type ],

            ["json字符串*", id_discount_json ],
        ];

        $.show_key_value_table("添加活动", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {

                var title = id_title.val();
                var date_range_start = id_date_range_start.val();
                var date_range_end = id_date_range_end.val();
                var user_join_time_srart = id_user_join_time_srart.val();
                var user_join_time_end = id_user_join_time_end.val();
                var last_test_lesson_srart = id_last_test_lesson_srart.val();
                var last_test_lesson_end = id_last_test_lesson_end.val();
                var lesson_times_min = id_lesson_times_min.val();
                var lesson_times_max = id_lesson_times_max.val();
                var contract_type_list = id_contract_type_list.val();
                var period_flag_list = id_period_flag_list.val();
                var grade_list = '(';
                id_grade_list.find("label").each(function(){
                    if($(this).find("input[type='checkbox']:checked").length > 0 ){
                        grade_list += $(this).find("input[type='checkbox']:checked").val() + ",";
                    }
                });
                grade_list = grade_list.substring(0,grade_list.length-1)+')';
                var power_value = id_power_value.val();
                var max_count = id_max_count.val();
                var max_change_value = id_max_change_value.val();

                var max_count_activity_type_list = id_max_count_activity_type_list.val();
                var can_disable_flag = id_can_disable_flag.val();
                var open_flag = id_open_flag.val();
                var order_activity_discount_type = id_order_activity_discount_type.val();
                var discount_json = id_discount_json.val();
                //输入检查
                var haveInput = [
                    [date_range_start,'活动日期开始时间必填'],
                    [date_range_end,'活动日期结束时间必填'],
                    [lesson_times_min,'参加活动最小课时必填'],
                    [lesson_times_max,'参加活动最大课时必填'],
                    [power_value,'优惠力度必填'],
                    [max_count,'最大合同数必填'],
                    [discount_json,'json字符串必填'],
                ];
                //时间检查
                var timeInput = [
                    [date_range_start,date_range_end,'活动日期'],
                    [user_join_time_srart,user_join_time_end,'用户加入'],
                    [last_test_lesson_srart,last_test_lesson_end,'最近一次试听']
                ];
                
                var data = {
                    'title':title,
                    'date_range_start':date_range_start,
                    'date_range_end':date_range_end,
                    'user_join_time_srart':user_join_time_srart,
                    'user_join_time_end':user_join_time_end,
                    'last_test_lesson_srart':last_test_lesson_srart,
                    'last_test_lesson_end':last_test_lesson_end,
                    'lesson_times_min':lesson_times_min,
                    'lesson_times_max':lesson_times_max,
                    'contract_type_list':contract_type_list,
                    'period_flag_list':period_flag_list,
                    'grade_list':grade_list,
                    'power_value':power_value,
                    'max_count':max_count,
                    'max_change_value':max_change_value,
                    'max_count_activity_type_list':max_count_activity_type_list,
                    'can_disable_flag':can_disable_flag,
                    'open_flag':open_flag,
                    'order_activity_discount_type':order_activity_discount_type,
                    'discount_json':discount_json,
                };
                
                console.log(data);

                var checkInfo = checkInput(haveInput);
                
                if( checkInfo['status'] != 200 ){
                    BootstrapDialog.alert(checkInfo['msg']);
                    return false;
                }

                if( lesson_times_min > lesson_times_max ){
                    BootstrapDialog.alert("最大课程必须大于最小课程");
                    return false;
                }

                if( grade_list == "()"){
                    BootstrapDialog.alert("");
                    return false;
                }

                var checkTimeInfo = checkTime(timeInput);
                
                if( checkTimeInfo['status'] != 200 ){
                    BootstrapDialog.alert(checkTimeInfo['msg']);
                    return false;
                }
                
                $.ajax({
                    type     :"post",
                    url      :"/seller_student2/add_order_activity",
                    dataType :"json",
                    data     :data,
                    success : function(result){
                        BootstrapDialog.alert(result['info']);
                        window.location.reload();
                    }
                });
            }
        });


    })
});

//必须检查输入
function  checkInput(haveInput)
{
    var result = new Array();
    result['status'] = 200;
    for(var x in haveInput){
        if(haveInput[x][0] == ""){
            result['status'] = 500;
            result['msg'] = haveInput[x][1];
            return result;
        }
    }

    return result;
}
//时间输入检查
function checkTime(timeInput){
    var result = new Array();
    result['status'] = 200;
    for(var x in timeInput){
        var timeStart = timeInput[x][0];
        var timeEnd = timeInput[x][1];
        if(timeStart != "" && timeEnd != "" && timeStart > timeEnd){
            result['status'] = 500;
            result['msg'] = timeInput[x][2] + "开始时间必须小于结束时间";
            return result;
        }

        if( ( timeStart != '' && timeEnd == '') || ( timeStart == '' && timeEnd != '') ){
            result['status'] = 500;
            result['msg'] = timeInput[x][2] + "开始时间和结束时间或者不填或者填写完整";
            return result;

        }
    }

    return result;

}

function load_data(){

    var data = {
        id_open_flag   : $("#id_open_flag").val(),
        id_can_disable_flag    : $("#id_can_disable_flag").val(),
        id_contract_type       : $("#id_contract_type").val(),
        id_period_flag   : $("#id_period_flag").val(),
    };

    //$.do_ajax("/seller_student2/show_order_activity_info",data,function(){});

    $.reload_self_page(data);
}

//循环绑定时间变量
function bindTime(itemArr){
    for(var x in itemArr){
        itemArr[x].datetimepicker({
            datepicker:true,
            timepicker:true,
            format:'Y-m-d H',
            step:30,
        });
    }
}
