/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-teacher_reward_rule_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      reward_count_type:	$('#id_reward_count_type').val(),
			      rule_type :	$('#id_rule_type').val()
        });
    }

    Enum_map.append_option_list("reward_count_type",$("#id_reward_count_type"));
    if(g_args.reward_count_type==1){
        Enum_map.append_option_list("rule_type_1",$("#id_rule_type"));
    }else if(g_args.reward_count_type==2){
        Enum_map.append_option_list("rule_type_2",$("#id_rule_type"));
    }else {
        Enum_map.append_option_list("rule_type_2",$("#id_rule_type"),false,[]);
    }
    $('#id_reward_count_type').val(g_args.reward_count_type);
	  $('#id_rule_type').val(g_args.rule_type);

	  $('.opt-change').set_input_change_event(load_data);

    $("#id_add_reward_type").on("click",function(){
        var id_reward_count_type = $("<select>");
        var id_rule_type         = $("<select>");
        var id_num               = $("<input>");
        var id_money             = $("<input>");

        var arr = [
            ["奖励类型",id_reward_count_type],
            ["规则类型",id_rule_type],
            ["累积数量",id_num],
            ["奖励金额",id_money],
        ];

        Enum_map.append_option_list("reward_count_type",id_reward_count_type);
        Enum_map.append_option_list("rule_type_2",id_rule_type,false,[]);

        $.show_key_value_table("添加奖励规则",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/user_manage_new/add_reward_rule_type",{
                    "type"              : "add",
                    "reward_count_type" : id_reward_count_type.val(),
                    "rule_type"         : id_rule_type.val(),
                    "num"               : id_num.val(),
                    "money"             : id_money.val(),
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                });
            }
        },function(){
            id_reward_count_type.on("change",function(){
                var reward_count_type = $(this).val();
                id_rule_type.empty();
	              if(reward_count_type==1){
                    Enum_map.append_option_list("rule_type_1",id_rule_type);
                }else if(reward_count_type==2){
                    Enum_map.append_option_list("rule_type_2",id_rule_type);
                }else{
                    Enum_map.append_option_list("rule_type_2",id_rule_type,false,[]);
                }
            });
        });
    });

    $(".opt-update").on("click",function(){
	      var data = $(this).get_opt_data();
        var id_num = $("<input>");
        var id_money= $("<input>");

        var arr = [
            ["累积数量",id_num],
            ["奖励金额",id_money],
        ];
        id_num.val(data.num);
        id_money.val(data.money);

        $.show_key_value_table("编辑奖励信息规则",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/user_manage_new/add_reward_rule_type",{
                    "type"              : "update",
                    "reward_count_type" : data.reward_count_type,
                    "rule_type"         : data.rule_type,
                    "num"               : id_num.val(),
                    "money"             : id_money.val(),
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                });

            }
        });

    });



});
