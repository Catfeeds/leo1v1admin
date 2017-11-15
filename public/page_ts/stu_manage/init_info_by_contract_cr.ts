/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-init_info_by_contract_cr.d.ts" />

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

    $("#id_first_lesson_time").datetimepicker({
        lang:'ch',
        timepicker:true,
        format:'Y-m-d H:i'
    });


    Enum_map.append_option_list("relation_ship", $("#id_relation_ship"), true);
    Enum_map.append_option_list("boolean", $("#id_has_fapiao"), true);


    // console.log(init_data);

    if(init_data){
        $.each(init_data,function(i,item){
            if ( !$.isNumeric(i) ) {
                $("#id_"+i ).val($.trim(item));
            }
            if ( i=='reject_flag' && item=='1' ) { //助教组长 驳回cc
                $('.id_submit').hide();//驳回咨询

                if(i=='is_master'&& item!='1'){
                    $('.id_submit_succ').hide();
                    $('.id_reject_to_master').hide();
                }else if(i=='is_master'&& item=='1'){
                    $('.id_submit_succ').show();
                    $('.id_reject_to_ass').hide(); // 组员 驳回按钮
                }
            }else if(i=='reject_flag' && item=='0') { // 无驳回操作
                $('.id_submit_succ').hide();
                $('.id_reject_to_master').hide();
                $('.id_reject_to_ass').hide();
                $('.id_submit').hide();//驳回咨询

                if(i=='is_master'&& item!='1'){
                    $('.id_reject_to_master').show();
                    // $('.id_confirm').show();
                }else if(i=='is_master'&& item=='1'){
                    $('.id_reject_to_ass').show();
                    $('.id_submit').show();//驳回咨询
                }

            }else if(i=='reject_flag' && item=='2'){ // 助教组长 驳回助教
                $('.id_submit').hide();
                $('.id_reject_to_ass').hide();

                if(i=='is_master'&& item!='1'){
                    $('.id_submit_succ').hide();
                    $('.id_reject_to_master').show();
                }else if(i=='is_master'&& item=='1'){
                    $('.id_submit_succ').show();
                }
            }else if(i=='reject_flag' && item=='3'){ // 助教 驳回 助教组长
                $('.id_reject_to_ass').show();// 驳回助教
                $('.id_submit').show();//驳回咨询
                $('.id_reject_to_master').hide();

                if(i=='is_master'&& item!='1'){
                    $('.id_submit_succ').show();
                }else if(i=='is_master'&& item=='1'){
                    $('.id_submit_succ').hide();
                }
            }

            // if(i=='is_master'&& item!='1'){
            //     $('.id_submit').remove();
            //     $('.id_submit_succ').remove();
            // }
        });
    }

    $(".id_submit").on("click",function(){
        var url_arr = GetRequest();
        var orderid = url_arr['orderid'];
        var id      = $('#id_id').val();
        var id_reject_info       = $("<select/>");
        var id_reject_info_write = $("<textarea/>");

        id_reject_info.html('<option value=\" 空 \">[全部]</option><option value=\"未标明学生报课科目\">未标明学生报课科目</option> <option value=\"无试听反馈内容\">无试听反馈内容</option> <option value=\"未确认上课老师\">未确认上课老师</option> <option value=\"无首次上课时间\">无首次上课时间</option> <option value=\"无常规上课时间（如家长无法确定，给予学生可上课时间段）\">无常规上课时间（如家长无法确定，给予学生可上课时间段）</option>  <option value=\"未说明与老师的沟通情况（上课时间，内容和学生基本情况）\">未说明与老师的沟通情况（上课时间，内容和学生基本情况）</option>  <option value=\"要求安排时长90分钟的课程\">要求安排时长90分钟的课程</option> <option value=\"开课时间不确定\">开课时间不确定</option> <option value=\"无老师包装情况说明\">无老师包装情况说明</option> <option value=\"未听报未安排上课老师\">未听报未安排上课老师</option> <option value=\"开课前5分钟内提交交接单\">开课前5分钟内提交交接单</option> <option value=\"1\">各类情况不明，需详细填写</option>');

        var arr = [
            ["驳回原因",id_reject_info],
            ["驳回原因",id_reject_info_write],
        ];

        id_reject_info.on('change',function(){
            if(id_reject_info.val() ==1){
                id_reject_info_write.parent().parent().css('display','table-row');
                id_reject_info.parent().parent().css('display','none');
            }
        });

        $.show_key_value_table("驳回处理",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                if (id_reject_info.val() == '' && id_reject_info_write.val() == '' ) {
                    alert('驳回原因不能为空!');
                    load_data();
                    return ;
                }

                var reject_info ='';
                if(id_reject_info.val() !='' && id_reject_info.val() !=1){
                    reject_info = id_reject_info.val();
                }else{
                    reject_info = id_reject_info_write.val();
                }

                $.do_ajax("/user_deal/do_reject_flag_for_init_info",{
                    'is_reject_flag'  : 1,
                    'orderid'         : orderid,
                    'id'              : id,
                    'reject_info'     : reject_info,
                    'sid'             : g_args.sid
                },function(result){
                    BootstrapDialog.alert(result.info);
                    dialog.close();
                    if(result.ret == 0){
                        $('.id_submit').hide();
                        $('.id_submit_succ').show();
                    }
                });
            }
        },function(){
            id_reject_info_write.parent().parent().css('display','none');
        });

    });


    $(".id_reject_to_ass").on("click",function(){ // 组长驳回助教组员
        var url_arr = GetRequest();
        var orderid = url_arr['orderid'];
        var id      = $('#id_id').val();
        var id_reject_info       = $("<select/>");
        var id_reject_info_write = $("<textarea/>");

        id_reject_info.html('<option value=\" 空 \">[全部]</option><option value=\"未标明学生报课科目\">未标明学生报课科目</option> <option value=\"无试听反馈内容\">无试听反馈内容</option> <option value=\"未确认上课老师\">未确认上课老师</option> <option value=\"无首次上课时间\">无首次上课时间</option> <option value=\"无常规上课时间（如家长无法确定，给予学生可上课时间段）\">无常规上课时间（如家长无法确定，给予学生可上课时间段）</option>  <option value=\"未说明与老师的沟通情况（上课时间，内容和学生基本情况）\">未说明与老师的沟通情况（上课时间，内容和学生基本情况）</option>  <option value=\"要求安排时长90分钟的课程\">要求安排时长90分钟的课程</option> <option value=\"开课时间不确定\">开课时间不确定</option> <option value=\"无老师包装情况说明\">无老师包装情况说明</option> <option value=\"未听报未安排上课老师\">未听报未安排上课老师</option> <option value=\"开课前5分钟内提交交接单\">开课前5分钟内提交交接单</option> <option value=\"1\">各类情况不明，需详细填写</option>');

        var arr = [
            ["驳回原因",id_reject_info],
            ["驳回原因",id_reject_info_write],
        ];

        id_reject_info.on('change',function(){
            if(id_reject_info.val() ==1){
                id_reject_info_write.parent().parent().css('display','table-row');
                id_reject_info.parent().parent().css('display','none');
            }
        });

        $.show_key_value_table("驳回处理",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                if (id_reject_info.val() == '' && id_reject_info_write.val() == '' ) {
                    alert('驳回原因不能为空!');
                    load_data();
                    return ;
                }

                var reject_info ='';
                if(id_reject_info.val() !='' && id_reject_info.val() !=1){
                    reject_info = id_reject_info.val();
                }else{
                    reject_info = id_reject_info_write.val();
                }

                $.do_ajax("/user_deal/do_reject_flag_for_init_info",{
                    'is_reject_flag'  : 2, // 驳回助教
                    'orderid'         : orderid,
                    'id'              : id,
                    'reject_info'     : reject_info,
                    'sid'             : g_args.sid
                },function(result){
                    BootstrapDialog.alert(result.info);
                    dialog.close();
                    if(result.ret == 0){
                        $('.id_submit').hide();
                        $('.id_submit_succ').show();
                    }
                });
            }
        },function(){
            id_reject_info_write.parent().parent().css('display','none');
        });

    });




    $(".id_reject_to_ass").on("click",function(){ // 组长驳回助教组员
        var url_arr = GetRequest();
        var orderid = url_arr['orderid'];
        var id      = $('#id_id').val();
        var id_reject_info       = $("<select/>");
        var id_reject_info_write = $("<textarea/>");

        id_reject_info.html('<option value=\" 空 \">[全部]</option><option value=\"未标明学生报课科目\">未标明学生报课科目</option> <option value=\"无试听反馈内容\">无试听反馈内容</option> <option value=\"未确认上课老师\">未确认上课老师</option> <option value=\"无首次上课时间\">无首次上课时间</option> <option value=\"无常规上课时间（如家长无法确定，给予学生可上课时间段）\">无常规上课时间（如家长无法确定，给予学生可上课时间段）</option>  <option value=\"未说明与老师的沟通情况（上课时间，内容和学生基本情况）\">未说明与老师的沟通情况（上课时间，内容和学生基本情况）</option>  <option value=\"要求安排时长90分钟的课程\">要求安排时长90分钟的课程</option> <option value=\"开课时间不确定\">开课时间不确定</option> <option value=\"无老师包装情况说明\">无老师包装情况说明</option> <option value=\"未听报未安排上课老师\">未听报未安排上课老师</option> <option value=\"开课前5分钟内提交交接单\">开课前5分钟内提交交接单</option> <option value=\"1\">各类情况不明，需详细填写</option>');

        var arr = [
            ["驳回原因",id_reject_info],
            ["驳回原因",id_reject_info_write],
        ];

        id_reject_info.on('change',function(){
            if(id_reject_info.val() ==1){
                id_reject_info_write.parent().parent().css('display','table-row');
                id_reject_info.parent().parent().css('display','none');
            }
        });

        $.show_key_value_table("驳回处理",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                if (id_reject_info.val() == '' && id_reject_info_write.val() == '' ) {
                    alert('驳回原因不能为空!');
                    load_data();
                    return ;
                }

                var reject_info ='';
                if(id_reject_info.val() !='' && id_reject_info.val() !=1){
                    reject_info = id_reject_info.val();
                }else{
                    reject_info = id_reject_info_write.val();
                }

                $.do_ajax("/user_deal/do_reject_flag_for_init_info",{
                    'is_reject_flag'  : 2, // 驳回助教
                    'orderid'         : orderid,
                    'id'              : id,
                    'reject_info'     : reject_info,
                    'sid'             : g_args.sid
                },function(result){
                    BootstrapDialog.alert(result.info);
                    dialog.close();
                    if(result.ret == 0){
                        $('.id_submit').hide();
                        $('.id_submit_succ').show();
                    }
                });
            }
        },function(){
            id_reject_info_write.parent().parent().css('display','none');
        });

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
