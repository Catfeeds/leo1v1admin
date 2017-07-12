var BOSH_SERVICE = 'http://115.28.190.105:5280/http-bind';
var connection = null;

function log(msg) 
{
    $('#log').append('<div></div>').append(document.createTextNode(msg));
}

function rawInput(data)
{
    //log('RECV: ' + data);
}

function rawOutput(data)
{
    //log('SENT: ' + data);
}

function onConnect(status)
{
    if (status == Strophe.Status.CONNECTING) {
	    log('Strophe is connecting.');
    } else if (status == Strophe.Status.CONNFAIL) {
	    log('Strophe failed to connect.');
	    $('#connect').get(0).value = 'connect';
    } else if (status == Strophe.Status.DISCONNECTING) {
	    log('Strophe is disconnecting.');
    } else if (status == Strophe.Status.DISCONNECTED) {
	    log('Strophe is disconnected.');
	    $('#connect').get(0).value = 'connect';
    } else if (status == Strophe.Status.CONNECTED) {
	    log('Strophe is connected.');
       
    }
}

$(document).ready(function () {
    connection = new Strophe.Connection(BOSH_SERVICE);
    connection.rawInput = rawInput;
    connection.rawOutput = rawOutput;

    $('#connect').bind('click', function () {
	    var button = $('#connect').get(0);
	    if (button.value == 'connect') {
	        button.value = 'disconnect';

	        connection.connect($('#jid').get(0).value,
			                   $('#pass').get(0).value,
			                   onConnect);
	    } else {
	        button.value = 'connect';
	        connection.disconnect();
	    }
    });

    $('#join_room').bind('click', function () {

        connection.muc.join("l_106y4y0@conference.115.28.190.105",
                            "testnick",
                            function(msg,room) {
                                //$('#muc_item').append($(msg).text());
                                var txt= $(msg).text();
                                if(txt[0] == "<"){
                                    var svg = $(txt);
                                    var item_data = get_item_data(svg);
                                    play_svg_item(item_data);
                                }
                                return true;
                            },
                            function(pres) {
                                log("=====ROMMPRES:"+$(pres).text());
                                return true;
                            });
    });

    function get_item_data(svg){
        var item_data={};
        item_data.pageid=Math.floor(svg.attr("y").baseVal.value/768)+1;
        var opt_item= svg.children(":first");
        item_data.opt_type=$(opt_item)[0].tagName;
        var opt_args={};
        switch( item_data.opt_type  ) {
            
        case "path":
            //<path fill="none" stroke="0bceff" stroke-width="4" d="M458.0 235.5Q458.0 235.5 457.0 237.5M457.0 237.5Q456.0 239.5 456.0 242.5M456.0 242.5Q456.0 245.5 455.5 253.2M455.5 253.2Q455.0 261.0 453.0 273.5M453.0 273.5Q451.0 286.0 447.8 301.0M447.8 301.0Q444.5 316.0 441.2 333.0M441.2 333.0Q438.0 350.0 435.8 367.8M435.8 367.8Q433.5 385.5 433.2 402.5"></path>
            opt_args={
                fill    : opt_item.attr("fill")
                ,stroke : "#"+opt_item.attr("stroke")
                ,"stroke-width" : opt_item.attr("stroke-width")
                ,"stroke-dasharray" : opt_item.attr("stroke-dasharray")
                ,"d" : opt_item.attr("d")
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
            
        default:
            console.log( "ERROR:" +  item_data.opt_type );
            break;
        }
        
        item_data.opt_args=opt_args;
        return item_data;
    }

});
