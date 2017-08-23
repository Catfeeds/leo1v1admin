/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_user_wechat.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            phone: $("#id_phone").val(),
            id: g_args.id
        });
    }


    $('#id_phone').val(g_args.phone);


    $('.opt-change').set_input_change_event(load_data);
   $.ajax({
        type : "get",
        async:false,
        url : "http://wx-yxyx.leo1v1.com/wx_yxyx_api/get_user_info?_agent_id="+g_args.id ,
        dataType : "jsonp",//数据类型为jsonp
        success : function(data){
            alert(JSON.stringify(data ));
        },
        error:function(){
            alert('fail');
        }
    });
});
