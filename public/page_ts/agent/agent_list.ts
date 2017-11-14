/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_list.d.ts" />

function load_data(){
    $.reload_self_page ( {
        order_by_str  :    g_args.order_by_str,
        date_type     :    $('#id_date_type').val(),
        opt_date_type :    $('#id_opt_date_type').val(),
        start_time    :    $('#id_start_time').val(),
        end_time      :    $('#id_end_time').val(),

        order_flag:	$('#id_order_flag').val(),
        test_lesson_flag:	$('#id_test_lesson_flag').val(),
        agent_level:	$('#id_agent_level').val(),
        userid:	$('#id_userid').val(),
        phone:	$('#id_phone').val(),
        p_phone:	$('#id_p_phone').val(),
        agent_type:$('#id_agent_type').val(),
			  l1_child_count:	$('#id_l1_child_count').val()
    })
};


$(function(){
    Enum_map.append_option_list("agent_type", $("#id_agent_type"));
    Enum_map.append_option_list("boolean",$("#id_test_lesson_flag"));
    Enum_map.append_option_list("boolean",$("#id_order_flag"));

    $('#id_date_range').select_date_range({
        'date_type'     : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    $("#id_agent_type").val(g_args.agent_type);
    $('#id_userid').val(g_args.userid);
    $('#id_p_phone').val(g_args.p_phone);
    $('#id_phone').val(g_args.phone);
    $('#id_order_flag').val(g_args.order_flag);

	  $('#id_l1_child_count').val(g_args.l1_child_count);

    $('#id_test_lesson_flag').val(g_args.test_lesson_flag);
    $('#id_agent_level').val(g_args.agent_level);
    $.enum_multi_select( $('#id_agent_level'), 'agent_level', function(){load_data();} )

    $("#id_p_phone").on("change",function(){
        load_data();
    });

    $("#id_add").on("click",function(){
        var $parentid  = $("<input/>");
        var $userid    = $("<input/>");
        var $phone     = $("<input/>");
        var $type     = $("<select><option value='0'>注册</option><option value='1'>我要报名</option><option value='2'>我要推荐</option><select/>");
        var $bankcard     = $("<input/>");
        var $idcard     = $("<input/>");
        var $bank_address     = $("<input/>");
        var $bank_account     = $("<input/>");
        var $bank_phone     = $("<input/>");
        var $bank_province     = $("<input/>");
        var $bank_city     = $("<input/>");
        var $bank_type     = $("<input/>");
        var $zfb_name     = $("<input/>");
        var $zfb_account     = $("<input/>");

        var arr=[
            ["上级id",  $parentid],
            ["userid",  $userid],
            ["手机",  $phone],
            ["类型",  $type],
            ["银行卡号",  $bankcard],
            ["身份证号码",  $idcard],
            ["开户行和支行",  $bank_address],
            ["持卡人姓名",  $bank_account],
            ["银行预留手机号",  $bank_phone],
            ["银行卡开户省",  $bank_province],
            ["银行卡开户市",  $bank_city],
            ["银行卡类型",  $bank_type],
            ["支付宝姓名",  $zfb_name],
            ["支付宝账户",  $zfb_account],
        ];
        $.show_key_value_table("新增优学优享账号", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal/agent_add",{
                    "parentid"      : $parentid.val(),
                    "phone"         : $phone.val(),
                    "userid"        : $userid.val(),
                    "type"          : $type.val(),
                    "bankcard"      : $bankcard.val(),
                    "idcard"        : $idcard.val(),
                    "bank_address"  : $bank_address.val(),
                    "bank_account"  : $bank_account.val(),
                    "bank_phone"    : $bank_phone.val(),
                    "bank_province" : $bank_province.val(),
                    "bank_city"     : $bank_city.val(),
                    "bank_type"     : $bank_type.val(),
                    "zfb_name"     : $zfb_name.val(),
                    "zfb_account"     : $zfb_account.val(),
                })
            }
        })
    });


    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var $parentid      = $("<input/>");
        var $phone         = $("<input/>");
        var $type     = $("<select><option value='0'>注册</option><option value='1'>我要报名</option><option value='2'>我要推荐</option><select/>");
        // var $bankcard      = $("<input/>");
        // var $idcard        = $("<input/>");
        // var $bank_address  = $("<input/>");
        // var $bank_account  = $("<input/>");
        // var $bank_phone    = $("<input/>");
        // var $bank_province = $("<input/>");
        // var $bank_city     = $("<input/>");
        // var $bank_type     = $("<input/>");
        // var $zfb_name     = $("<input/>");
        // var $zfb_account     = $("<input/>");

        $parentid.val(opt_data.parentid);
        $phone.val(opt_data.phone );
        $type.val(opt_data.type);
        // $bankcard.val(opt_data.bankcard);
        // $idcard.val(opt_data.idcard);
        // $bank_address.val(opt_data.bank_address);
        // $bank_account.val(opt_data.bank_account);
        // $bank_phone.val(opt_data.bank_phone);
        // $bank_province.val(opt_data.bank_province);
        // $bank_city.val(opt_data.bank_city);
        // $bank_type.val(opt_data.bank_type);
        // $zfb_name.val(opt_data.zfb_name);
        // $zfb_account.val(opt_data.zfb_account);
        var arr=[
            ["上级id",  $parentid],
            ["电话",  $phone],
            ["类型",  $type],
            // ["银行卡号",  $bankcard],
            // ["身份证号码",  $idcard],
            // ["开户行和支行",  $bank_address],
            // ["持卡人姓名",  $bank_account],
            // ["银行预留手机号",  $bank_phone],
            // ["银行卡开户省",  $bank_province],
            // ["银行卡开户市",  $bank_city],
            // ["银行卡类型",  $bank_type],
            // ["支付宝姓名",  $zfb_name],
            // ["支付宝账户",  $zfb_account],
        ];

        $.show_key_value_table("修改代理信息", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal/agent_edit",{
                    "id"       : opt_data.id,
                    "parentid" : $parentid.val(),
                    "phone"    : $phone.val() ,
                    "type"     : $type.val() ,
                    // "bankcard"      : $bankcard.val(),
                    // "idcard"        : $idcard.val(),
                    // "bank_address"  : $bank_address.val(),
                    // "bank_account"  : $bank_account.val(),
                    // "bank_phone"    : $bank_phone.val(),
                    // "bank_province" : $bank_province.val(),
                    // "bank_city"     : $bank_city.val(),
                    // "bank_type"     : $bank_type.val(),
                    // "zfb_name"     : $zfb_name.val(),
                    // "zfb_account"     : $zfb_account.val(),
                })
            }
        })
    });

    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm(
            "要删除手机为:" + opt_data.phone + "的代理吗？",
            function(val) {
                if (val) {
                    $.do_ajax("/ajax_deal/agent_del", {
                        "id": opt_data.id
                    })
                }
            })
    });

    $('.opt-change').set_input_change_event(load_data);

    $(".opt-wechat-desc").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/agent/agent_user_wechat?id="+ opt_data.id  );
    });

    //@desn:获取微信数据新版
    $(".opt-wechat-new-desc").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/agent/user_center_info?id="+ opt_data.id  );
    });


    $(".opt-user-link").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/agent/agent_user_link?id="+ opt_data.id  );

    });
    //@desn:学员明细
    $(".student_info").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/agent/agent_child_info?id="+ opt_data.id+"&type=1"  );

    });
    //@desn:会员明细
    $(".member_info").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/agent/agent_child_info?id="+ opt_data.id+"&type=2"  );

    });
    //@desn:会员+学员明细
    $(".member_student_info").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/agent/agent_child_info?id="+ opt_data.id+"&type=3" );

    });



    $(".opt-reset-info").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.do_ajax("/ajax_deal2/agent_reset_info", {
            id: opt_data.id
        });
    });

    //电话列表
    $(".opt-telphone").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen('/tq/get_list?phone=' + opt_data.phone);

    });


    //@desn:查看体现来源明细
    $(".opt-return-back-list").on("click",function(){
        //优学优享会员、学员用标识
        var opt_data=$(this).get_opt_data();
        
        var userid=$(this).parent().data("userid");
        var phone=$(this).parent().data("phone");
        $.ajax({
            type     : "post",
            url      : "/agent/get_agent_income_log/",
            dataType : "json",
            size     : BootstrapDialog.SIZE_WIDE,
            data     : {"userid":userid,phone:phone},
            success  : function(result){
                var html_str=$("<div id=\"div_table\"><table class = \"table table-bordered table-striped\"  > <tr><th> 时间  <th> 回访类型 <th>回访路径 <th> 负责人 <th>对象 <th>内容 <th>详情 </tr> </table></div>");
                $.each( result.revisit_list ,function(i,item){
                    //console.log(item);
                    //return;

                    var revisit_person = "";
                    if(item.revisit_person  ) {
                        revisit_person = item.revisit_person;
                    }
                    var userid     = item["userid"];
                    var revisit_time  = item["revisit_time"];
                    if(userid){
                        var html                                  = "<tr><td>"+item.revisit_time +"</td><td>"+
                            item.revisit_type+"</td><td>"+item.revisit_path+"<td>"+ 
                            item.sys_operator +"</td><td>"+item.revisit_person+"</td><td>"+
                            item.operator_note+"</td><td><a class = \"opt_detail\" data-userid=\""+userid+"\" data-revisit_time=\""+revisit_time+"\">详情</a></td></tr>";
                    }else{
                        var html                                  = "<tr><td>"+item.revisit_time +"</td><td>"+
                            item.revisit_type+"</td><td>"+item.revisit_path+"<td>"+ 
                            item.sys_operator +"</td><td>"+item.revisit_person+"</td><td>"+
                            item.operator_note+"</td><td></td></tr>";
                    }
                    html_str.find("table").append(html);
                });

                var dlg = BootstrapDialog.show({
                    title    : '回访记录',
                    message  : html_str ,
                    closable : true,
                    buttons  : [{
                        label: '查看全部',
                        cssClass : 'btn-warning',
                        action   : function(dialog) {

                        }
                    },{
                        label  : '返回',
                        action : function(dialog) {
                            dialog.close();
                        }
                    }]
                });
            },

        });
    });
});
