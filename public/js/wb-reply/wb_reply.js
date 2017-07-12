
$(function () {

    //audiojs 时间回调, 每秒3-4次
    notify_cur_playpostion=function (cur_play_time){
        Cwhiteboard.play_next( cur_play_time );
    };
    
    var Cwhiteboard = {
        "get_page" :function ( pageid,show_pageid){
            var me=this;
            var div_id= "drawing_"+ pageid ;
            var page_info=me.draw_page_list[pageid];
            if (!page_info){
                var tmp_div=$(  "<div  class=\"page_item\"  id=\""+div_id+ "\"/>");
                if ( show_pageid &&  pageid != show_pageid ) {
                    tmp_div.hide();
                }
                
                $("#drawing_list").append( tmp_div );
                me.draw_page_list[ pageid]={
                    pageid    :  pageid
                    ,opt_list :  [] //svg_obj_list
                    ,draw : SVG(div_id).size(me.width, me.height )
                };

                page_info=me.draw_page_list[pageid];

                page_info.draw.attr("viewBox", "0,0,1024,768" );
                //page_info.draw.

                var text = page_info.draw.text(""+pageid+"/"+me.max_pageid );
                //1024', '768
                text.attr({
                    x:969, 
                    y:732
                });
            }

            return page_info;

        }
        ,"play_one_svg":function(item_data,show_pageid){
            
            var me=this;

            if(item_data.svg_id){
               $("#"+ item_data.svg_id) .show();
               return;
            }

            var page_info = me.get_page(item_data.pageid,show_pageid);

            var draw = page_info.draw;

            var opt_args=item_data.opt_args;
            var id="";
            switch( item_data.opt_type  ) {
            case "path":
                var path = draw.path( opt_args.d );
                path.fill( opt_args.fill   ).stroke({ width: opt_args["stroke-width"] , dasharray:opt_args["stroke-dasharray"] }).attr({
                    "stroke":  opt_args.stroke
                });
                id=path.id();
                
                break;

            case "image":
                var image=draw.image(opt_args.url,opt_args.width,opt_args.height  );
                image.attr({ x:  opt_args.x , y:  opt_args.y });
                id=image.id();
                break;

            case "eraser":
                var eraser = draw.path( opt_args.d );
                eraser.fill( opt_args.fill   ).stroke({ width: opt_args["stroke-width"] , dasharray:opt_args["stroke-dasharray"], color:opt_args["stroke-color"] }).attr({
                    "stroke":  opt_args.stroke
                });

                id=eraser.id();
                
                break;

            default:
                console.log( "ERROR:" +  item_data.opt_type );
                break;
            }
            item_data.svg_id=id;
            
        }

        ,"init_to_play": function(){
            var me = this;

            me.draw_page_list=[];
            me.play_index=0;
            me.play_pageid=-1;
            me.play_svg=null ;
            me.get_page(1);
            me.show_page(1);
            
        }
        , "play_next_front":function(  cur_play_time ){
            var me=this;
            var front_flag=false;
            var show_pageid=-1;
            var opt_list=[];
            while ( me.play_index< me.svg_data_list.length  ){
                var item_data=me.svg_data_list[me.play_index];
                if (item_data.timestamp <= cur_play_time ){ //时间到了已经
                    show_pageid=item_data.pageid;
                    opt_list.push(item_data);
                    me.play_index++;
                    front_flag=true;
                }else{
                    break;
                }
            }
            if(show_pageid!=-1){
                me.show_page(show_pageid);
                $.each(opt_list,function(i,item_data){
                    me.play_one_svg(item_data ,show_pageid);
                });
            }
            return front_flag;
        }

        , "play_next_back" :function (cur_play_time) {

            //后退处理

            var me=this;
            var a_show_page_id=-1;
            var opt_list=[];
            while ( me.play_index>0 ){
                var item_data=me.svg_data_list[me.play_index-1];
                if (item_data.timestamp > cur_play_time ){ 
                    opt_list.push(item_data);
                    a_show_page_id= item_data.pageid;
                    me.play_index--;
                }else{
                    break;
                }
            }

            if(a_show_page_id!=-1){
                me.show_page(a_show_page_id);
                $.each(opt_list,function(i,item_data){
                    $("#"+ item_data.svg_id) .hide();
                });
            }
        }
        
        ,"play_next" :  function( cur_play_time){
            var me=this;
            //前进处理
            var front_flag=me.play_next_front(cur_play_time);
            if (!front_flag){
                me.play_next_back(cur_play_time);
            }
        }

        ,"show_page":function( pageid ) {
            console.log("PAGEID:"+pageid);
            var me=this;
            if ( me.play_pageid != pageid ){
                var div_id= "drawing_"+ pageid ;
                $("#drawing_list div ").hide();
                $("#"+div_id ).show();
                me.play_pageid=pageid;
            }
        }
            
        ,"loadData":function(  w, h, lession_start_time , xml_file, mp3_file ){
            var me=this;
            me.svg_data_list=[];
            me.height=h;
            me.width=w;
            me.max_pageid=0;
            me.start_time=lession_start_time ;
            $.get(xml_file,function(xml){   
	            var svg_list =$(xml).find("svg") ;

                svg_list.each(function(){
                    var item_data={};
                    var item=$(this);
                    item_data.pageid=Math.floor(item.attr("y")/768)+1;
                    if (me.max_pageid <  item_data.pageid ){
                        me.max_pageid =  item_data.pageid ;
                    }

                    item_data.timestamp=item.attr("timestamp")-me.start_time;
                    var opt_item= item.children(":first");
                    item_data.opt_type=$(opt_item)[0].tagName;

                    var opt_args={};
                    switch( item_data.opt_type  ) {

                    case "path":
                        //<path fill="none" stroke="0bceff" stroke-width="4" d="M458.0 235.5Q458.0 235.5 457.0 237.5M457.0 237.5Q456.0 239.5 456.0 242.5M456.0 242.5Q456.0 245.5 455.5 253.2M455.5 253.2Q455.0 261.0 453.0 273.5M453.0 273.5Q451.0 286.0 447.8 301.0M447.8 301.0Q444.5 316.0 441.2 333.0M441.2 333.0Q438.0 350.0 435.8 367.8M435.8 367.8Q433.5 385.5 433.2 402.5"></path>
                        opt_args={
                            fill    : opt_item.attr("fill")
                            ,stroke : "#"+opt_item.attr("stroke")
                            ,"stroke-width" : opt_item.attr("stroke-width")
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
                    
                    me.svg_data_list.push( item_data );
                });
                
                me.init_to_play();

                //加载mp3
                audiojs.events.ready(function() {
                    var as = audiojs.createAll();
                    //reset width
                    //
                    $(".audiojs").css("width","100%" );
                    $("#id_wb").css("width", w.toString()+"px" );
                    $(".scrubber").css("width", (w-174).toString()+"px" );
                    //
                    as[0].load( mp3_file  );

                });
	        });
        }
    };
});

