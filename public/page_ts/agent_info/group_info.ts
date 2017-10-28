/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent_info-get_agent_group_list.d.ts" />
$(function(){

    //@desn:跳转到团队明细
    $(".opt-members").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/agent_info/members_info?group_id="+ opt_data.group_id  );
    });
    
});
