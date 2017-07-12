// SWITCH-TO:   ../../template/supervisor/supervisor.html
$(function(){
    var connection = null;
    function log(msg) 
    {
        $('#log').append('<div></div>').append(document.createTextNode(msg));
    }
    
    function rawInput(data)
    {
        log('RECV: ' + data);
    }
    
    function rawOutput(data)
    {
        log('SENT: ' + data);
    }
    
    function get_item_data(svg){
        var item_data={};
        //item_data.pageid=Math.floor(svg.attr("y").baseVal.value/768)+1;
        item_data.pageid=Math.floor(svg.attr("y")/768)+1;
        var opt_item= svg.children(":first");
        item_data.opt_type=$(opt_item)[0].tagName;
        var opt_args={};
       
        var stroke_info = opt_item.attr("stroke");
        if( typeof stroke_info != "undefined" && stroke_info.indexOf("#") == -1){
            stroke_info = "#" + stroke_info;
        }

        switch( item_data.opt_type  ) {
        case "path":
            opt_args={
                fill    : "none",
                stroke : stroke_info,
                "stroke-width" : opt_item.attr("stroke-width"),
                "stroke-dasharray" : opt_item.attr("stroke-dasharray"),
                "d" : opt_item.attr("d")
            };
                
            break;
        case "image":
            opt_args={
                x         : opt_item.attr("x")
                ,y        : opt_item.attr("y")
                ,"width"  : opt_item.attr("width")
                ,"height" : opt_item.attr("height")
                ,"url"    : opt_item.text()
            };
            break;
        case "eraser":
            opt_args={
                fill    : "none"
                ,stroke : stroke_info 
                ,"stroke-width" : opt_item.attr("stroke-width")
                ,"stroke-dasharray" : opt_item.attr("stroke-dasharray")
                ,"d" : opt_item.attr("d")
                ,"stroke-color" : "FFFFFF" 
            };
            
            break;
        default:
            console.log( "ERROR:" +  item_data.opt_type );
            break;
        }
        
        item_data.opt_args=opt_args;
        return item_data;
    }
    
      
    function onConnect(status)
    {
        if (status == Strophe.Status.CONNECTING) {
            console.log('Strophe is connecting.');
        } else if (status == Strophe.Status.CONNFAIL) {
            console.log('Strophe failed to connect.');
	        $('#connect').get(0).value = 'connect';
        } else if (status == Strophe.Status.DISCONNECTING) {
            console.log('Strophe is disconnecting.');
        } else if (status == Strophe.Status.DISCONNECTED) {
            console.log('Strophe is disconnected.');
	        $('#connect').get(0).value = 'connect';
        } else if (status == Strophe.Status.CONNECTED) {
            console.log('Strophe is connected.');
			var conference = g_bridge_id + "@conference."+ g_server;
			connection.muc.join(conference,
                                "admin_user",
                                function(msg,room) {
                                    var txt= $(msg).text();
                                    if(txt[0] == "<"){
                                        var svg = $(txt);
                                        var item_data = get_item_data(svg);
                                        play_svg_item(item_data);
                                     }
                                        return true;
                                    },
                                    function(pres) {
                                            return true;
                                     });

        }
    }


    
    function connect ( server, bridge_id) {
         var jid = 'millions@'+server;
         var passwd = 'millions';
		 g_bridge_id = bridge_id;
		 g_server = server;
	     connection.connect(jid,
			                passwd,
			                onConnect);
         console.log('xmpp connected');
     }


    function get_lesson_contact(lessonid)
    {
        $.ajax({
			type     :"post",
			url      :"/supervisor/get_lesson_contact",
			dataType :"json",
			data     :{"lessonid":lessonid},
			success  : function(result){
                if(result['ret'] == 0){
                    $("#id_tea_nick").html('老师(id='+result['data']['teacherid']+'):'+result['data']['tea_nick']);
                    $("#id_tea_phone").html('电话:'+result['data']['tea_phone']);

                    $("#id_stu_nick").html('学生(id='+result['data']['userid']+'):'+result['data']['stu_nick']);
                    $("#id_stu_phone").html('电话:'+result['data']['par_phone']);
                }
			}
		});

    }
    
    $(".going_classes").on("click", function(){
        $(this).addClass('current');
        var lessonid = $(this).data('lessonid');
        get_lesson_contact(lessonid);
        var servers;
        var bridge_id =  $(this).data('room_id');
        var courseid = $(this).data('courseid');
        $.ajax({
			type     :"post",
			url      :"/supervisor/get_servers",
			dataType :"json",
			data     :{'courseid':courseid},
			success  : function(result){
                if(result['ret'] == 0){
                    servers = result['data'];
                    var BOSH_SERVICE = 'http://'+servers['ip']+':5280/http-bind';
                    connection = new Strophe.Connection(BOSH_SERVICE);
                    connection.rawInput = rawInput;
                    connection.rawOutput = rawOutput;
					connect( servers['ip'], bridge_id);
                    var url = "/supervisor/video?bridge_id="+bridge_id+"&bridge_pin=1234&user_id=supervisor&server="+servers['ip']+"&port="+servers['webrtc_port'];
                    $("#id_frame").attr("src", url);
                }else{
                    alert(result['info']);
                }
			}
		});
    });

    //监控list
	$('.arrow').click(function(){
		if($(this).hasClass('shou')){
			$(this).removeClass('shou').parent('.ban_list').animate({right:0},300);
		}else{
			$(this).addClass('shou').parent('.ban_list').animate({right:-300+'px'},300);
		}
	});

    function get_time(){
        var myDate = new Date();  
        var time = myDate.toLocaleTimeString();
        var end = time.lastIndexOf(":");  
        time  = time.substr(0, end);
        var mytime = "当前时间: "+time; 
        $("#id_time_now").html(mytime);
    }
    get_time();
    setInterval(get_time, 3000); 
});
