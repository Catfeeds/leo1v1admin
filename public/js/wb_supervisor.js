
$(function () {
    //audiojs 时间回调, 每秒3-4次
    play_svg_item=function(item_data){
        Cwhiteboard.play(item_data);
    };
    
    get_new_whiteboard=function (obj_drawing_list,w ,h ){

        var ret={
            "obj_drawing_list": obj_drawing_list,
            "get_page" :function(pageid){
                var me=this;
                var div_id= "drawing_"+ pageid ;
                var page_info=me.draw_page_list[pageid];
                if (!page_info){
                    var tmp_div=$(  "<div  class=\"page_item\"  id=\""+div_id+ "\"/>");
                    
                    me.obj_drawing_list.append( tmp_div );

                    me.draw_page_list[pageid]={
                        pageid    :  pageid
                        ,opt_list :  [] //svg_obj_list
                        ,draw : SVG(tmp_div[0]).size(me.width, me.height )
                    };

                    page_info=me.draw_page_list[pageid];

                    page_info.draw.attr("viewBox", "0,0,1024,768" );
                    //page_info.draw.

                    if(pageid > me.max_pageid){
                        me.max_pageid = pageid;
                    }

                    var text = page_info.draw.text(""+pageid);
                    //1024', '768
                    text.attr({
                        x:969, 
                        y:732
                    });
                }

                return page_info;

            }
            ,"play_one_svg":function(item_data){
                
                var me=this;

                if(item_data.svg_id){
                    $("#"+ item_data.svg_id) .show();
                    return;
                }

                var page_info = me.get_page(item_data.pageid);


                var draw = page_info.draw;


                var opt_args=item_data.opt_args;
                var id="";
                var path=null;
                switch( item_data.opt_type  ) {
                case "path":
                    path = draw.path( opt_args.d );
                    path.fill( opt_args.fill   ).stroke({ width: opt_args["stroke-width"] , dasharray:opt_args["stroke-dasharray"] , "linecap": "round"  }).attr({
                        "stroke":  opt_args.stroke
                    });
                    id=path.id();
                    
                    break;

                case "image":
                    var image=draw.image(opt_args.url,opt_args.width,opt_args.height  );
                    image.attr({ x:  opt_args.x , y:  opt_args.y });
                    id=image.id();
                    console.log("opt_args.url:"+ opt_args.url );
                    break;
                case "eraser":
                    path = draw.path( opt_args.d );
                    path.fill( opt_args.fill   ).stroke({ width: opt_args["stroke-width"] , dasharray:opt_args["stroke-dasharray"], color:opt_args["stroke-color"] }).attr({
                        "stroke":  opt_args.stroke
                    });
                    id=path.id();
                    
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
                //me.play_index=0;
                me.play_pageid=-1;
                me.play_svg=null ;
                me.get_page(1);
                me.show_page(1);
                
            }
            ,"play" :  function(item_data){
                var me=this;
                me.show_page(item_data.pageid);
                me.play_one_svg(item_data);
            }

            ,"show_page":function( pageid ){
                //console.log("PAGEID:"+pageid);
                var me=this;
                if ( me.play_pageid != pageid ){
                    var div_id= "drawing_"+ pageid ;
                    me.obj_drawing_list.find("div" ).hide();
                    me.obj_drawing_list.find("#"+div_id ).show();
                    me.play_pageid=pageid;
                }
                if(pageid > me.max_pageid){
                    me.max_pageid = pageid;
                }
            }
            
            ,"loadData":function( w, h){
                var me=this;
                me.svg_data_list=[];
                me.height=h;
                me.width=w;
                me.max_pageid=0;
                me.init_to_play();
            }
        };
        ret.loadData(w,h);
        return ret;
    };
    
    //加载数据
    var w=1024;
    var h=768;

  Cwhiteboard=get_new_whiteboard( $("#drawing_list"), w,h  );
    
});

