/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-complaint_department_deal.d.ts" />


$(function(){

    function load_data(){
        $.reload_self_page ( {
            date_type_config:	$('#id_date_type_config').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            is_complaint_state:	$('#id_is_complaint_state').val(),
            is_allot_flag:	$('#id_is_allot_flag').val(),
            // account_type:	$('#id_account_type').val(),

        });
    }


    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    Enum_map.append_option_list( "complaint_state", $("#id_is_complaint_state"));
    // Enum_map.append_option_list( "complaint_user_type", $("#id_account_type"));

    // $('#id_account_type').val(g_args.account_type);
    $('#id_is_complaint_state').val(g_args.is_complaint_state);
    $('#id_is_allot_flag').val(g_args.is_allot_flag);






    $(".opt-assign_remark").on("click",function(){

        var data            = $(this).get_opt_data();
        var complaint_id    = data.complaint_id;
        var html_node    = $.obj_copy_node("#id_assign_log");

        BootstrapDialog.show({
            title: "分配列表",
            message: html_node,
            closable: true
        });

        $.ajax({
            type: "post",
            url: "/ss_deal/get_assign_log",
            dataType: "json",
            data: {
                'complaint_id': complaint_id,
            },
            success: function (result) {
                if (result['ret'] == 0) {
                    var data = result['data'];

                    var html_str = "";
                    $.each(data, function (i, item) {
                        var cls = "success";

                        html_str += "<tr class=\"" + cls + "\" > <td>" + item.ass_date + "<td>" + item.assign_str + "<td>" + item.accept_str + "<td>" + item.assign_remarks+ "</tr>";
                    });

                    html_node.find(".data-body").html(html_str);

                }
            }
        });

    });


    $('.opt-deal').on("click",function(){
        var data               = $(this).get_opt_data();
        var id_deal_state      = $("<select>");
        var id_suggest_remark  = $("<textarea>");
        var id_deal_info       = $("<textarea>");
        var arr             = [
            ["处理方案",id_deal_info],
            ["投诉人建议",id_suggest_remark],
            ["处理状态",id_deal_state],
        ];
        var enable_video = 0;
        Enum_map.append_option_list("complaint_state",id_deal_state,true);
        $.show_key_value_table("投诉处理",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                if (id_deal_info.val() == '') {
                    alert('处理方案不能为空!');
                    load_data();
                    return ;
                }

                $.do_ajax("/ss_deal/deal_complaint",{
                    "deal_info"        : id_deal_info.val(),
                    "suggest_info"     : id_suggest_remark.val(),
                    "complaint_state"  : id_deal_state.val(),
                    "complaint_id"     : data.complaint_id,
                    "account_type"     : data.account_type,
                },function(result){
                    BootstrapDialog.alert(result.info);
                    dialog.close();
                    load_data();
                });
            }
        });

    });


    $('.opt-reject').on("click",function(){
        var data =  $(this).get_opt_data();
        if(!confirm('确定是要驳回分配吗?')){
            load_data();
            return;
        }
        $.do_ajax("/ss_deal/reject_complaint",{
            "complaint_id"    : data.complaint_id,
            "assign_adminid"  : data.assign_adminid,
            "accept_adminid"  : data.accept_adminid,
        },function(result){
            // BootstrapDialog.alert(result.info);
            alert(result.info);
            load_data();
        });
    });


    $('.opt-complaint-all').on("click",function(){
        var data = $(this).get_opt_data();
        $.show_key_value_table("投诉明细",[
            ["投诉类型" , data.complaint_type_str],
            ["投诉人身份" , data.account_type_str ],
            ["投诉时间" , data.complaint_date ],
            ["投诉人姓名/电话" , data.user_nick +'/'+ data.phone ],
            ["被投诉人" , data.complained_adminid_nick ],
            ["处理人" , data.deal_admin_nick ],
            ["投诉处理时间" , data.deal_date ],
            ["投诉内容" , data.complaint_info ],
            ["处理方案" , data.deal_info ],
            ["投诉人建议" , data.suggest_info ],
            ["处理状态" , data.complaint_state_str ],
        ]);
    });

    $('.opt-assign').on("click", function (g_adminid_right) {
        var $main_type_name = $("<select/>");
        var $main_group_name = $("<select/>");
        var $group_name = $("<select/>");
        var $account = $("<select/>");
        var $complaint_info = $("<textarea/>");
        var id_complaint_deparment = $("<select/>");
        var me = $(this);
        var key_list = me.val();

        var data = $(this).get_opt_data();

        id_complaint_deparment.html("<option value=\"" + -1 + "\">  [全部]</option><option value=\""+540+"\">市场-QC-施文斌</option><option value=\""+968+"\">市场-QC-李珉劼</option><option value=\""+1184+"\">市场-QC-郭冀江</option><option value=\""+1024+"\">市场-QC-王浩鸣</option><option value=\""+895+"\">老师反馈处理-老师薪资及反馈-苏佩云</option><option value=\""+1040+"\">老师反馈处理-老师管理运营-郭东</option><option value=\""+967+"\">老师反馈处理-老师管理运营-傅文莉</option><option value=\""+379+"\">教研-语文组-许琼文</option><option value=\""+868+"\">教研-语文组-黄灼文</option><option value=\""+849+"\">教研-语文组-张敏</option><option value=\""+913+"\">教研-语文组-潘艳亭</option><option value=\""+404+"\">教研-语文组-唐灵莉</option><option value=\""+310+"\">教研-数学组-彭标</option><option value=\""+480+"\">教研-数学组-徐格格</option><option value=\""+866+"\">教研-数学组-王海</option><option value=\""+890+"\">教研-数学组-夏劲松</option><option value=\""+892+"\">教研-数学组-梁立玉</option><option value=\""+329+"\">教研-英语组-许千千</option><option value=\""+372+"\">教研-英语组-赖国芬</option><option value=\""+923+"\">教研-英语组-王芳</option><option value=\""+770+"\">教研-物理组-展慧东</option><option value=\""+793+"\">教研-化学组-李红涛</option><option value=\""+1118+"\">产品-产品-孙瞿</option><option value=\""+895+"\">小科目组-苏佩云</option><option value=\""+1290+"\">教研-质监-艾欣</option>");


        var arr = [
            ["处理人",id_complaint_deparment],
            ["分配备注", $complaint_info],
        ];

        $.show_key_value_table("分配处理人", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function (dialog) {
                $.do_ajax("/ss_deal/do_complaint_assign_department",{
                    "accept_adminid"  : id_complaint_deparment.val(),
                    "ass_remark"      : $complaint_info.val(),
                    "complaint_id"    : data.complaint_id,
                });
            }
        },function(){
            if($main_type_name.val() == 'qc'){
                console.log("qc_xuanz");
            }
        });
    });


    $(".opt-telphone").on("click",function(){
        //
        var me=this;
        var opt_data= $(this).get_opt_data();
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

    });

    $('.opt-complaint-img').on("click",function(){
        var opt_data = $(this).get_opt_data();
        var img_str  = opt_data.complaint_img_url;
        var html_node = '';
        var img_arr = img_str.split(',');

        $.each(img_arr, function (i, item) {
            var cls = "success";

            html_node += "<tr class=\"" + cls + "\" > <td>" + i + "<td>" + item +"</tr>";
        });


        BootstrapDialog.show({
            title: "图片列表",
            message: html_node,
            closable: true
        });




    });




    $('.opt-change').set_input_change_event(load_data);


});
