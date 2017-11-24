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

    //返回
    $('#id_return').on('click',function(){
        var return_url = GetQueryString("return_url");
        window.location.href = return_url;
    })

    //关闭
    $('#id_close').on('click',function(){
        window.close(); 
    })

    //编辑活动1
    $('#opt_edit_01').on('click',function(){
        var opt_data = $(this).parents('#id_tea_info').get_self_opt_data(); 
        var id_title = $("<input style='width:400px'/>");
        var id_id = $('<input onkeypress="keyPressCheck(this)" onkeyup="keyUpCheck(this)" />');
        var id_date_range_start = $("<input/>");
        var id_date_range_end = $("<input/>");
        var id_lesson_times_min = $("<input/>");
        var id_lesson_times_max = $("<input/>");

        id_title.val(opt_data.title);
        id_id.val(opt_data.id);
        id_date_range_start.val(opt_data.date_range_start);
        id_date_range_end.val(opt_data.date_range_end);
        id_lesson_times_min.val(opt_data.lesson_times_min);
        id_lesson_times_max.val(opt_data.lesson_times_max);

        var timeItem = [id_date_range_start,id_date_range_end];
        
        bindTime(timeItem); 

        var arr=[
            ["活动标题", id_title ],
            ["活动ID", id_id ],
            ["活动日期开始时间*", id_date_range_start ],
            ["活动日期结束时间*", id_date_range_end ],
            ["参加活动最小课时*", id_lesson_times_min ],
            ["参加活动最大课时*", id_lesson_times_max ],
        ];

        $.show_key_value_table("编辑活动", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var title = id_title.val();
                var date_range_start = id_date_range_start.val();
                var date_range_end = id_date_range_end.val();
                var lesson_times_min = id_lesson_times_min.val();
                var lesson_times_max = id_lesson_times_max.val();

                if(!title){
                    BootstrapDialog.alert("活动标题必填");
                    return false;
                }

                //时间检查
                var timeInput = [
                    [date_range_start,date_range_end,'活动日期'],
                ];

                var checkTimeInfo = checkTime(timeInput);
                
                if( checkTimeInfo['status'] != 200 ){
                    BootstrapDialog.alert(checkTimeInfo['msg']);
                    return false;
                }

                var data = {
                    'id': opt_data.id,
                    'id_after': id_id.val(),
                    'title':title,
                    'date_range_start':date_range_start,
                    'date_range_end':date_range_end,
                    'lesson_times_min':lesson_times_min,
                    'lesson_times_max':lesson_times_max
                }

                $.ajax({
                    type     :"post",
                    url      :"/seller_student2/update_order_activity_01",
                    dataType :"json",
                    data     :data,
                    success : function(result){
                        console.log(result);
                        BootstrapDialog.alert(result.info);
                        var return_url = GetQueryString("return_url");
                        if(result.status == 200){
                            window.location = '/seller_student2/get_order_activity?id='+result.data+'&return_url='+return_url;
                        }
                    }
                });
            }
        })

    })

    //编辑活动2
    $('#opt_edit_02').on('click',function(){
        var opt_data = $(this).parents('#id_tea_info').get_self_opt_data();
        
        var id_grade_list =$("<input id='grade_list' />");
        id_grade_list.val(opt_data.grade_list);


        var id_contract_type_list =$("<input id='contract_type_list'/> ");
        id_contract_type_list.val(opt_data.contract_type_list);

        var id_period_flag_list =$("<input id='period_flag_list'/> ");
        id_period_flag_list.val(opt_data.period_flag_list);
        
        var arr=[
            ["适配年级", id_grade_list ],
            ["分期试用*", id_period_flag_list ],
            ["合同类型*", id_contract_type_list ],
        ];

        $.show_key_value_table("编辑活动", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {

                var data = {
                    'id': opt_data.id,
                    'period_flag_list':id_period_flag_list.val(),
                    'contract_type_list':id_contract_type_list.val(),
                    'grade_list':id_grade_list.val(),
                }
                
                $.ajax({
                    type     :"post",
                    url      :"/seller_student2/update_order_activity_02",
                    dataType :"json",
                    data     :data,
                    success : function(result){
                        BootstrapDialog.alert(result['info']);
                        window.location.reload();
                    }
                });
            }
        },function(){
            $.enum_multi_select_new( $('#contract_type_list'), 'contract_type', function(){});
            $.enum_multi_select_new( $('#period_flag_list'), 'period_flag', function(){});
            $.enum_multi_select_new( $('#grade_list'), 'grade', function(){});
            
        } ,false,900)
        
    })


    //编辑活动3
    $('#opt_edit_03').on('click',function(){
        var opt_data = $(this).parents('#id_tea_info').get_self_opt_data();
        
        var id_power_value = $("<input/>");
        var id_max_count = $("<input/>");
        var id_max_change_value = $("<input/>");

        id_power_value.val(opt_data.power_value);
        id_max_count.val(opt_data.max_count);
        id_max_change_value.val(opt_data.max_change_value);

        var arr=[
            ["优惠力度(power_value)", id_power_value ],
            ["最大合同数", id_max_count ],
            ["最大修改金额累计值", id_max_change_value ],
        ];

        $.show_key_value_table("编辑活动", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                
                var data = {
                    'id': opt_data.id,
                    'power_value':id_power_value.val(),
                    'max_count':id_max_count.val(),
                    'max_change_value':id_max_change_value.val(),
                }
              
                $.ajax({
                    type     :"post",
                    url      :"/seller_student2/update_order_activity_03",
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
 
    //编辑活动4
    $('#opt_edit_04').on('click',function(){

        var opt_data = $(this).parents('#id_tea_info').get_self_opt_data();

        var activity_type_list = $("._activity_type_list").clone();
        activity_type_list.removeClass('hide');
        
        var activity_exits = opt_data.max_count_activity_type_list.toString();
        if( activity_exits != ''){
            var activity_arr = activity_exits.split(',');
            activity_type_list.find("div").each(function(){
                var activity = $(this).find("input[type='checkbox']").val();
                if($.inArray(activity, activity_arr) != -1){
                    $(this).find("input[type='checkbox']").prop("checked", "checked");
                }
            });

        }

        var arr=[
            ["总配额组合", activity_type_list ],
        ];

        $.show_key_value_table("编辑活动", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var activity_list = '';
                activity_type_list.find("div").each(function(){
                    if($(this).find("input[type='checkbox']:checked").length > 0 ){
                        activity_list += $(this).find("input[type='checkbox']:checked").val() + ",";
                    }
                });

                activity_list = activity_list.substring(0,activity_list.length-1);
 
                var data = {
                    'id': opt_data.id,
                    'max_count_activity_type_list':activity_list,
                }
               
                $.ajax({
                    type     :"post",
                    url      :"/seller_student2/update_order_activity_04",
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

    //编辑活动5
    $('#opt_edit_05').on('click',function(){
        var opt_data = $(this).parents('#id_tea_info').get_self_opt_data();

        var id_can_disable_flag =$("<select/>");
        Enum_map.append_option_list("can_disable_flag", id_can_disable_flag);

        var id_open_flag =$("<select/>");
        Enum_map.append_option_list("open_flag", id_open_flag);
        
        id_can_disable_flag.val(opt_data.can_disable_flag);
        id_open_flag.val(opt_data.open_flag);

        var arr=[
            ["是否开启活动", id_open_flag ],
            ["是否手动开启活动", id_can_disable_flag ],
        ];

        $.show_key_value_table("编辑活动", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
               
                var data = {
                    'id': opt_data.id,
                    'can_disable_flag':id_can_disable_flag.val(),
                    'open_flag':id_open_flag.val(),
                }
              
                $.ajax({
                    type     :"post",
                    url      :"/seller_student2/update_order_activity_05",
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

    //编辑活动6
    $('#opt_edit_06').on('click',function(){

        var opt_data = $(this).parents('#id_tea_info').get_self_opt_data(); 
        
        var id_user_join_time_start = $("<input/>");
        var id_user_join_time_end = $("<input/>");
        var id_last_test_lesson_start = $("<input/>");
        var id_last_test_lesson_end = $("<input/>");

        id_user_join_time_start.val(opt_data.user_join_time_start);
        id_user_join_time_end.val(opt_data.user_join_time_end);
        id_last_test_lesson_start.val(opt_data.last_test_lesson_start);
        id_last_test_lesson_end.val(opt_data.last_test_lesson_end);

        var timeItem = [id_user_join_time_start,id_user_join_time_end,id_last_test_lesson_start,id_last_test_lesson_end];
        
        bindTime(timeItem); 

        var arr=[
            ["用户加入开始时间", id_user_join_time_start ],
            ["用户加入结束时间", id_user_join_time_end ],

            ["最近一次试听开始时间", id_last_test_lesson_start ],
            ["最近一次试听结束时间", id_last_test_lesson_end ],

        ];

        $.show_key_value_table("编辑活动", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var user_join_time_start = id_user_join_time_start.val();
                var user_join_time_end = id_user_join_time_end.val();
                var last_test_lesson_start = id_last_test_lesson_start.val();
                var last_test_lesson_end = id_last_test_lesson_end.val();

                //时间检查
                var timeInput = [
                    [user_join_time_start,user_join_time_end,'用户加入'],
                    [last_test_lesson_start,last_test_lesson_end,'最近一次试听']
                ];

                var checkTimeInfo = checkTime(timeInput);
                
                if( checkTimeInfo['status'] != 200 ){
                    BootstrapDialog.alert(checkTimeInfo['msg']);
                    return false;
                }


                var data = {
                    'id': opt_data.id,
                    'user_join_time_start':user_join_time_start,
                    'user_join_time_end':user_join_time_end,
                    'last_test_lesson_start':last_test_lesson_start,
                    'last_test_lesson_end':last_test_lesson_end,
                }
               
                $.ajax({
                    type     :"post",
                    url      :"/seller_student2/update_order_activity_06",
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
  
    //编辑活动7
    $('#opt_edit_07').on('click',function(){
        var opt_data = $(this).parents('#id_tea_info').get_self_opt_data();

        var id_discount_type =$("<select onchange='changeActivity(this)'/>");
        Enum_map.append_option_list("order_activity_discount_type", id_discount_type);
        id_discount_type.val(opt_data.order_activity_discount_type);
        var id_discount_json = $("<div class='discount_activity' style='width:420px'/>");
        var edit_json = opt_data.discount_json;
        var discount_type = opt_data.order_activity_discount_type;

        //展示编辑页面
        editJson(id_discount_json,edit_json,discount_type,1);
        
        var arr=[
            ["优惠类型", id_discount_type ],
            ["优惠方案",id_discount_json  ],
        ];

        $.show_key_value_table("编辑活动", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var checkFull = 1;
                var discount_json = {};
                $('.discount_activity').find('.lesson_activity').each(function(){
                    var condition = $(this).find('.show_activity:eq(0)').val();
                    var discount = $(this).find('.show_activity:eq(1)').val();
                    if( condition == '' || discount == ''){
                        checkFull = 0;
                    }
                    
                    discount_json[condition] = discount;
                   
                });
                
                if( checkFull == 0 ){
                    BootstrapDialog.alert("请输入完整，再点击提交");
                    return false;
                }
                discount_json = JSON.stringify(discount_json);
                
                var data = {
                    'id': opt_data.id,
                    'order_activity_discount_type':id_discount_type.val(),
                    'discount_json':discount_json,
                }
                
                $.ajax({
                    type     :"post",
                    url      :"/seller_student2/update_order_activity_07",
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

});
//根据不同的优惠活动选择不同的选项
function changeActivity(obj){
    //1 按课次数打折 2 按年级打折 3 按课次数送课 4 按金额立减
    var act = $(obj).val();
    var activity = showActivity(act);
    var opt_data = $('#id_tea_info').get_self_opt_data();

    var edit_json = opt_data.discount_json;
    var discount_type = opt_data.order_activity_discount_type;
    var id_discount_json = $('.discount_activity');
    id_discount_json.html('');
    if( act == discount_type ){
        //编辑
        editJson(id_discount_json,edit_json,discount_type,act);
    }else{
        //新增
        id_discount_json.html(activity);
    }
}

function showActivity(config){
 
    var activity = '';
    var config = parseInt(config);
    switch(config){
    case 1:
        activity = $(".lesson_times_off_perent_list:hidden").clone();
        break;
    case 2:
        activity = $(".grade_off_perent_list:hidden").clone();
        break;
    case 3:
        activity = $(".lesson_times_present_lesson_count:hidden").clone();
        break;
    case 4:
        activity = $(".price_off_money_list:hidden").clone();
        break;
    default:
        activity = $(".lesson_times_off_perent_list:hidden").clone();
        break;
    }

    var index = $('.discount_activity .lesson_activity').length;

    activity.find('button').attr({
        'onclick':"remove_activity("+index+")"
    })

    activity.addClass('activity_'+index);

    activity.removeClass('hide');

    return activity;
}

//编辑活动
function editJson(id_discount_json,edit_json,discount_type,act){
    
    if( edit_json != ''){
        var index = 0;
        for(var x in edit_json){
            var divShow = showActivity(discount_type);
            id_discount_json.append(divShow);
            var className = id_discount_json.find('.lesson_activity:eq('+index+')').attr('class').replace(/[0-9]/ig,"")+index;
            id_discount_json.find('.lesson_activity:eq('+index+')').attr({'class':className});
            id_discount_json.find('.activity_'+index+' .show_activity:eq(0)').val(x);
            id_discount_json.find('.activity_'+index+' .show_activity:eq(1)').val(edit_json[x]);
            id_discount_json.find('.activity_'+index+' button').attr({
                'onclick':"remove_activity("+index+")"
            });

            index++;
        }
    }

    var activity = showActivity(act);
    id_discount_json.append(activity);
    
}

//回车添加新的输入框
function addActivity(event,act){
   
    if (event.keyCode == '13'){

        var nextActivity = 1;
        $('.discount_activity').find('.lesson_activity').each(function(){
            var condition = $(this).find('input:eq(0)').val();
            var gift = $(this).find('input:eq(1)').val();
            if( condition == '' || gift == ''){
                nextActivity = 0;
            }
        });
        if( nextActivity == 1){
            var activity = showActivity(act);
            $('.discount_activity').append(activity);
            $('.discount_activity .lesson_activity:last .show_activity:eq(0)').focus();
        }else{
            BootstrapDialog.alert("请输入完整，再点击回车");
            return false;
        }
    }
}
//将下个输入框激活
function nextInput(event){
    if (event.keyCode == '13'){
        $('.discount_activity .show_activity:empty').focus();
    }
}
function remove_activity(index){
    $('.discount_activity .activity_'+index).remove();
};
//获取链接参数
function GetQueryString(name)
{
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null) return unescape(r[2]); return null;
}

//必须检查输入
function  checkInput(haveInput)
{
    for(var x in haveInput){
        if(haveInput[x] == ""){
            haveInput[x] = -2;
        }
    }
    return haveInput;
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

//循环绑定时间变量
function bindTime(itemArr){
    for(var x in itemArr){
        itemArr[x].datetimepicker({
            datepicker:true,
            timepicker:false,
            format:'Y-m-d',
            step:30,
        });
    }
}

function keyPressCheck(ob) {
    if (!ob.value.match(/^[\+\-]?\d*?\.?\d*?$/)) ob.value = ob.t_value; else ob.t_value = ob.value; if (ob.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/)) ob.o_value = ob.value;
}
function keyUpCheck(ob) {
    if (!ob.value.match(/^[\+\-]?\d*?\.?\d*?$/)) ob.value = ob.t_value; else ob.t_value = ob.value; if (ob.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/)) ob.o_value = ob.value;
}
