// SWITCH-TO:   ../../template/monitor/api_func.html
// /monitor/api_func

$(function(){

    //其它参数变化, 服务器地址,语音通道,时间更改,
    var ws = $.websocket("ws://" +window.location.hostname+":9501/", {
        events: {
            "noti_api_func": function(e) {
                var data= e.data;
                var url=data.url;
                var td_data="";
                if (url[0]=="h") {
                    td_data='<a href="'+url+'" target="_blank"> '+url+'</a>';
                }else{
                    td_data=url;
                }
                
                $("#table_body").prepend( "<tr> <td> "+data.logtime+"<td>"+data.userid+"<td>"+td_data+" </tr>" );
            }
        }
        ,open :function(){
            ws.send( "monitor_api_function" );
        }
    });


});











