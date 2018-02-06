/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/self_manage-test.d.ts" />

(function($, window, document,undefined) {
    //在插件中使用对象


    $.fn.admin_multiselect = function( options) {


        var Cadmin_multiselect = function( $ele,  opt) {

            var me=this;
            this.defaults = {
                multi_select_flag : true,
                select_value        : -1 ,
                title: "",
                on_change : null,
                data_list : [],
            };


          this.menu_item_select_css="select";
            this.$ele= $ele;
            this.options = $.extend({}, this.defaults, opt);
            this.menu_item_select_css="select";
            this.title= this.options.title;
            this.multi_select_flag= this.options.multi_select_flag;
            this.select_value= this.options.select_value;
            this.on_change= this.options.on_change;
            this.show_desc=function( ) {
                var desc_list=[];
                me.$ele.find('.admin-select-item' ).removeClass(me.menu_item_select_css);

                $.each ( me.select_value , function(i,v){
                    me.$ele.find('.admin-select-item-'+v ).addClass(me.menu_item_select_css);
                    if (v==-1)  {
                        desc_list.push( "[全部]" );
                    }else{
                        desc_list.push( me.options.data_list[v]);
                    }

                });
                me.$ele.find("input").val( desc_list.join(","));
            };

            var menu_item_str="";
            menu_item_str+='<li ><a class=" admin-select-item admin-select-item--1"   href="#" data-value="-1">全部</a></li>';
            $.each( this.options.data_list ,function(k,v)  {
                menu_item_str+='<li ><a class=" admin-select-item admin-select-item-'+k+'"   href="#" data-value="'+k+'">'+ v+'</a></li>';
            } );

            var multi_select_str="";
            console.log( this.title, "this.multi_select_flag :"+  this.multi_select_flag  );
            if ( this.multi_select_flag ){
                multi_select_str= '        <li class="  divider"  />'
                    +'        <li  style="text-align:center;" >'
                    +'            <button style="width: auto; color:white;  padding: 2px 10px 2px 10px;margin: 0px;  "  class="btn btn-flat btn-primary multi-select-post  "> 提交 </button>'
                    +'        </li>';

            }
            var html_obj=$(
                '<div class="input-group  admin-multi-select " >'
                +'    <span style="display:table-cell;">'+ this.title +'    </span>'
                +'    <input class="dropdown-toggle" readonly="readonly" data-toggle="dropdown" aria-expanded="false"/>'
                +'    <ul class="dropdown-menu  " role="menu">'
                +menu_item_str
                +multi_select_str
                +'    </ul>'
                +'</div>');
            this.$ele.html(html_obj);

            this.$ele.find( ".multi-select-post "  ).on("click",function() {
                if (me.on_change ){
                    me.on_change();
                }
            });

            this.$ele.on("click",".admin-select-item",function(){

                //[全部] 去掉选择
                me.$ele.find(".admin-select-item--1").removeClass(me.menu_item_select_css);

                if (!me.multi_select_flag ) {
                    me.$ele.find(".admin-select-item").removeClass(me.menu_item_select_css);
                }

                var value=$(this).data("value");

                $(this).toggleClass(me.menu_item_select_css);

                me.select_value=[];

                if ( value == -1 ) {
                    me.select_value=[-1];
                }else{
                    $.each( me.$ele.find(".admin-select-item.select") ,function() {
                        me.select_value.push($(this).data("value") );
                    });
                }

                me.show_desc();
                if (!me.multi_select_flag ||  value == -1 ) {
                    if (me.on_change ){
                        me.on_change();
                    }
                }
                return false;
            });
            this.show_desc();
        };

        Cadmin_multiselect.prototype = {
            get_select_value:function() {
                return this.select_value;
            },

            set_select_value : function (){
                this.select_value=[-1];
                this.show_desc();
            }

        } ;
        var  obj= new Cadmin_multiselect(this,  options);
        return obj;
    };


})(jQuery, window, document);


(function($, window, document,undefined) {

    var Cheader_query= function( $ele,  opt) {
        var me=this;
        this.defaults = {
            "list_type" : null,
            "html_power_list": {},
            "item_count" : 10,
            "show_all_item_limit_item_count":4,
        };

        this.options = $.extend({}, this.defaults, opt);

        me.need_show_field_index_list=[];
        $.each($ele.find(".select-menu-list .select"),function(){
            me.need_show_field_index_list.push( $(this).data("index"));
        });

        me.default_show_all_flag=false;
        var  query_item_count =window.localStorage.getItem( $.get_table_key("query_item_count" ) );
        if (query_item_count) {
            me.options.item_count= query_item_count;
        }
        if (me.options.item_count  < me.options.show_all_item_limit_item_count ) {
            me.default_show_all_flag=true;
        }



        me.select_all_flag= $ele.find(".query-meum-select-all").data("select_all_flag");



        this.query_item_list=[];

        this.menu_item_select_css="select";
        var flag_key = "query_flag_"+ window.location.pathname;
        var load_data_flag = parseInt( window.localStorage.getItem( flag_key ));
        var list_type_key = "query_list_type_"+ window.location.pathname;
        var list_type = parseInt( window.localStorage.getItem( list_type_key));
        if (!(list_type >=0) ) {
            list_type=2;
        }


        this.options.list_type = list_type;
        //超级紧凑
        this.min_flag=false;
        if ( this.options.list_type ==2 ){
            this.options.list_type =0;
            this.min_flag=true;
        }

        this.list_type=this.options.list_type;
        this.load_data_flag= load_data_flag;
        var query_but_str="";
        if (this.load_data_flag ) { // 不是立即
            query_but_str= '            <button type="button" style="margin-top: 5px; margin-right: 8pt;" class="btn btn-primary btn-flat  do-query ">查询</button>'
        }

        var base_html=
            '<div class="row  header-query-row " >'
            +'</div>  '

            +'<div class="row used-query-list " >'
            +'    <div class="col-xs-2 col-md-2 query-list-select-item "  >'
            +'        <div class="btn-group" style=" width: 120px; float:left;">'
            +'            <button type="button" class="btn btn-default query-meum-select-all">全部条件</button>'
            +'            <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown">'
            +'                <span class="caret"></span>'
            +'                <span class="sr-only">Toggle Dropdown</span>'
            +'            </button>'
            +'            <ul class="dropdown-menu select-menu-list " role="menu">'
            +'            </ul>'
            +'        </div>'
            +'</div>'
            +query_but_str
            +'</div>'
            +'<div class="row  query-list " >'
            +'</div>';
        this.$ele=$ele;
        this.$ele.html( base_html );
        this.$ele.addClass("header-query-info");
        if ( me.default_show_all_flag ) {
            this.$ele.find(".query-list-select-item") .hide();
        }

        var $menu_list       = this.$ele.find(".select-menu-list");
        var $query_list      = this.$ele.find(".query-list");
        var $used_query_list = this.$ele.find(".used-query-list");
        var select_query_all = this.$ele.find(".query-meum-select-all") ;


        var show_menu_btn_deal=function( $btn ) {
            var show_flag= ( $btn.data("show_flag") );
            var $query_list= me.$ele;//.find(".query-list");
            var index= ( $btn.data("index") );
            if (show_flag) {
                $btn.addClass( me.menu_item_select_css  );
                $query_list.find( ".query-item-line-" + index  ).show();
            }else{
                $btn.removeClass( me.menu_item_select_css  );
                $query_list.find( ".query-item-line-" + index  ).hide();
            }
        };

        this.$ele.find(".do-query").on( "click",function(){
            me.query(true);
        });

        select_query_all.on("click",function(){

            var select_all_flag=$(this).data("select_all_flag");
            if (select_all_flag==1)  {
                select_all_flag=0;
            }else{
                select_all_flag=1;
            }
            $(this).data("select_all_flag", select_all_flag);
            if (select_all_flag ) {
                $(this).text("已选条件" );
            }else{
                $(this).text("全部条件" );
            }

            var $menu_list = me.$ele.find(".select-menu-list");
            var item_list   = $menu_list.find( ".query-item");
            $.each(item_list,function(){
                var $btn = $(this);
                var show_flag=0;
                if (select_all_flag) {
                    show_flag= 1;
                }else{
                    var index= $btn.data("index");
                    if (  me.query_item_list[index].options.always_show_flag || me.query_item_list[index].get_show_flag()) {
                        show_flag=1;
                    }
                }
                $btn.data("show_flag",show_flag);
                show_menu_btn_deal($btn);
            });
        });

        $menu_list.on("click", ".query-item", function(){
            var show_flag= ( $(this).data("show_flag") );
            if (show_flag==1) {
                show_flag=0;
            }else{
                show_flag=1;
            }
            $(this).data("show_flag", show_flag ) ;
            show_menu_btn_deal( $(this));
            return false;
        });

        $used_query_list.on("click", ">a", function(){
            var index=$(this).data("index");
            $(this).hide();
            me.query_item_list[index].set_query_arg_clean() ;
            me.query();
            return false;
        });

        if (me.select_all_flag ) { //原来的情况
            select_query_all.click();
        }

    };

    //定义方法
    Cheader_query.prototype = {
        query:function( query_flag ) {

            if (this.load_data_flag==1   ) { //非立即查询
                if (!query_flag) { //不强制更新
                    return;
                }
            }
            var args={
            };

            if ( g_args["order_by_str"]) {
                args["order_by_str"] = g_args["order_by_str"];
            }

            $.each(this.query_item_list,function(){
                var item=this;
                args=$.extend(args, item.get_query_args() );
            });
            $.reload_self_page(args);
        },

        add :function( item ) {
            var me    = this;
            //check power
            var need_power= item.options.need_power;
            var check_power_flag=true;
            if (need_power) {
                if ($.isFunction(need_power ) ) {
                    check_power_flag= need_power( this.options.html_power_list );
                }else{
                    check_power_flag = this.options.html_power_list[need_power] ;
                }
            }

            if (!check_power_flag ) {
                return;
            }

            var index = this.query_item_list.length;
            this.query_item_list.push(item);
            window.localStorage.setItem( $.get_table_key("query_item_count"), index+1 );


            var $menu_list       = this.$ele.find(".select-menu-list");
            var $query_list      = this.$ele.find(".query-list");
            var $used_query_list = this.$ele.find(".used-query-list");
            var $header_query_row =  this.$ele.find(".header-query-row");


            var title=item.get_title() ;
            var query_info =item.get_query_info() ;
            var $query_item_list_obj =item.get_query_obj();
            var show_flag=item.get_show_flag();

            if (item.options.allway_show_flag ) {
                show_flag =1;
            }

            if ($.inArray( index, me.need_show_field_index_list  ) !== -1 ) {
                show_flag =1;
            }
            //
            if ( me.default_show_all_flag  ) {
                show_flag =1;
            }

            var as_header_query =  item.get_as_header_query?  item.get_as_header_query():false;
            var class_str="";
            if ( show_flag  ) {
                show_flag  =1;
                class_str=  this.menu_item_select_css ;
            }else{
                show_flag  =0;
            }
            if ( !as_header_query ) {
                var $menu_item=$('<li ><a class=" query-item '+class_str+'"   href="#" data-index="'+index+'" data-show_flag="'+show_flag+'"  >'+title+'</a></li>');
                $menu_list.append( $menu_item );
            }

            if (query_info &&  !$.check_in_phone() && !me.min_flag ) {

                var btn= $('<a href="#" class="btn  btn-info btn-flat used-query-item " data-index="'+index+'" >'+
                           query_info +'<i class="fa fa-times"></i></a>');
                $used_query_list.append( btn );

            }


            if (this.list_type==1 &&  !as_header_query ) {

                var $query_obj= $('<div class="col-xs-12 col-md-12"  >'
                                  +'<div   style=" width:120px; text-align: right; float: left; margin-top: 10px;  " >   <span class="query-title"  style=" font-size:18px">'+  title +':</span> </div>'
                                  +'</div>');
                $query_obj.append($query_item_list_obj);
                $query_obj.addClass("query-item-line-" + index );


                $query_list.append($query_obj);

            }else if ( me.list_type ==0 || as_header_query ){
                $query_obj = $query_item_list_obj;
                $query_obj.addClass("query-item-line-" + index );
                if (me.list_type ==0 )  {
                    if (me.min_flag ) {
                        $used_query_list.append($query_obj);
                    }else{
                        $query_list.append($query_obj);
                    }
                }else{
                    $header_query_row.append($query_obj);
                }
            }
          if ( $query_obj) {
            if (!show_flag ) {
              $query_obj.hide();
            }else{
              $query_obj.show();
            }
          }
        }
    };


    //在插件中使用对象
    $.fn.admin_header_query = function(options) {

        var  header_query = new Cheader_query(this,  options);

        return   header_query ;
    };

})(jQuery, window, document);

(function($, window, document,undefined) {

    var Cenum_select= function(opt) {

        var me =this;
        this.defaults = {
            "join_header" : null,
            "enum_type"   : null,
            "option_map" : {},
            "field_name"  :null,
            "multi_select_flag"  :  true,
            "title"  :  "是否标志",
            "select_value" : "-1",
            "th_input_id"  : null,
            "only_show_in_th_input" :false , //是否只显示 表头
            "show_id_list" : null,
            "width"   :300,
            "btn_id_config": [],
            "select_css" :  "danger",
            "title_length" : 7,
            "show_title_flag": false,
        };


        this.options    = $.extend({}, this.defaults, opt);
        this.title      = this.options.title;
        me.header_query = this.options.join_header;
        me.list_type =  me.header_query .list_type;


        this.enum_type = this.options.enum_type;
        this.field_name= this.options.field_name?this.options.field_name:this.enum_type ;

        if ( !$.isArray(  this.options.select_value) )  { // 1,3,33,3
            var tmp_list=[];
            $.each( (""+this.options.select_value).split(","), function(){
                tmp_list.push(parseInt(this ));
            });
            this.select_value  =tmp_list;
        }else{
            this.select_value  =this.options.select_value;
        }


        var desc_map={};
        if ( this.enum_type) {
            desc_map=g_enum_map[this.enum_type]["desc_map"];
        }else{
            desc_map=this.options.option_map;
        }

        if (me.list_type==1) { //
            var select_str="";

            $.each(desc_map, function(k,v){
                var add_flag=false;
                if ($.isArray( me.options.id_list)) {
                    if($.inArray( parseInt(k), me.options.id_list ) != -1 ){
                        add_flag=true;
                    }
                }else{
                        add_flag=true;
                }
                if(add_flag) {
                    select_str+="<button  class=\" item-"+k +" btn btn-flat btn-default select-list-item \" data-value=\""+k+"\">"+v+"</button>";
                }
            });


            var $ele=$("<div style=\" display: table-cell; \"  >" + select_str+ "</div>");
            this.$ele= $ele;

            $.each(this.select_value,function( i, v)  {
                var $item=me.$ele.find(".item-"+ v );
                $item.addClass("btn-warning" );
                $item.removeClass("btn-default" );
            });
            this.$ele.on("click","button",function(){
                var $item=$(this);
                $item.toggleClass("btn-warning");
                $item.toggleClass("btn-default");
                var opt_list=[];
                $.each (me.$ele.find("button"),function( ){
                    var $btn=$(this);
                    if ($btn.hasClass( "btn-warning" )) {
                        opt_list.push($btn.data("value"));
                    }
                }) ;
                me.select_value=opt_list;
                if (me.header_query ) {
                    me.header_query.query();
                }

            });
        }else if ( me.list_type==0 ) { //紧凑模式
            var $html_obj= $( '<div class="col-xs-6 col-md-2"> </div>' );
            var data_list={};
            $.each(desc_map, function(k,v){
                if ($.isArray( me.options.id_list)) {
                    if($.inArray( parseInt(k), me.options.id_list ) != -1 ){
                        data_list[k]=v;
                    }
                }else{
                    data_list[k]=v;
                }
            });

            var select_obj= $html_obj.admin_multiselect({
                title : me.title,
                select_value : me.select_value,
                data_list    : data_list,
                multi_select_flag  :  me.options.multi_select_flag,
                on_change : function ( ) {
                    me.header_query.query();
                }
            });

            this.$ele = $html_obj ;
            this.select_obj= select_obj;

        }
        //加入到列表
        this.header_query.add(this);
    };

    //定义方法
    Cenum_select.prototype = {
        get_title :function() {
            return this.title ;
        },

        set_query_arg_clean(){
            this.select_value=[-1];
            if (this.list_type==0) {
                this.select_obj.set_select_value([-1]);
            }
        },

        get_show_flag:function() {
            if (this.select_value[0]==-1) {
                return false;
            }else{
                return true;
            }
        },

        get_query_args:function () {

            if ( this.list_type==0 ) {
                this.select_value = this.select_obj.get_select_value();
            }

            var ret={};
            var field_name= this.field_name;
            ret[field_name ]=this.select_value.join(",");
            return  ret;
        },
        get_query_info:function() {
            var me= this;
            if (this.select_value[0]==-1) {
                return null;
            }else{
                var list=[];
                $.each( this.select_value, function(i, value){
                    var desc="";
                    if (me.enum_type ) {
                        desc= Enum_map.get_desc( me.enum_type , value);
                    }else{
                        desc= me.options.option_map[value] ;
                    }
                    list.push(desc);
                });
                return list.join(",");
            }
        },

        get_query_obj:function( ) {
            return this.$ele;
        }

    };


    //在插件中使用对象
    $.admin_enum_select = function(options) {

        var   enum_select= new Cenum_select(  options);

        return   enum_select;
    };

})(jQuery, window, document);

(function($, window, document,undefined) {

    var Cinput = function(opt) {

        var me =this;
        this.defaults = {
            "join_header" : null,
            "field_name"  :null,
            "title"  :  "",
            "placeholder" : "回车查询",
            "length_css" : "col-xs-12 col-md-3",
            "select_value" : "",
            "show_title_flag":false,
            "as_header_query" : false ,
        };


        var me=this;
        this.options    = $.extend({}, this.defaults, opt);
        this.title      = this.options.title;
        me.header_query = this.options.join_header;
        me.list_type =  me.header_query .list_type;

        this.field_name= this.options.field_name ;
        var btn_str="";
        var title_str="";

        if (me.options.show_title_flag) {

            title_str= '<span>'+me.options.title+'</span>';
        }else{
            btn_str= '<div class="input-group-btn">'
                +'<button type="button" class="btn btn-warning btn-flat"><i class="fa fa-search"></i>'
                +'</button></div>';
        }

        this.$ele=  $(
            '<div class="'+me.options.length_css +'">'
                +'<div class="input-group ">'
                + title_str
                +'<input class="form-control" placeholder="'+me.options.placeholder  +'" type="text">'
                + btn_str
                +'</div>'
                +'</div>'
        );
        if ( this.list_type ==1 && !this.options.as_header_query ){
            this.$ele.css( {
                "padding-left": "0px"
            });
        }


        this.$input=this.$ele.find("input");
        this.$input.val(this.options.select_value);

        this.$input.on("keypress",function(e){
            if(e.which==13) {
                me.header_query.query();
            }
        } );

        this.$ele.find("button").on("click",function(){
            me.header_query.query();
        });

        //加入到列表
        this.header_query.add(this);
    };

    //定义方法
    Cinput.prototype = {
        get_title :function() {
            return this.title ;
        },
        //是否作为头部查询
        get_as_header_query:function() {
            return this.options.as_header_query;
        },

        set_query_arg_clean(){
            this.select_value="";
        },

        get_show_flag:function() {
            if ( this.list_type==0  ){
                return  !(this.options.select_value=="" || this.options.select_value==-1) ;
            }else{
                return  true;
            }
        },

        get_query_args:function () {

            var ret={};
            var field_name= this.field_name;
            ret[field_name ]=this.$input.val() ;
            return ret;

        },
        get_query_info:function() {
            var me= this;
            if (this.list_type==0 ) {
                var value= this.$input.val();
                return value=="" ?null: value;
            }else{
                return null;
            }
        },

        get_query_obj:function( ) {
            return this.$ele;
        }

    };


    //在插件中使用对象
    $.admin_query_input = function(options) {
       return new Cinput (  options);
    };

})(jQuery, window, document);



(function($, window, document,undefined) {

    var Cdate_select = function(opt) {

        var me =this;
        this.defaults = {
            'date_type' :  null,
            'opt_date_type' : null ,
            'start_time'    :  null,
            'end_time'      : null ,
            date_type_config : null ,

            "join_header" : null,
            "field_name"  :null,
            "title"  :  "",
            "length_css" : "col-xs-12 col-md-5",
            "as_header_query" : false ,
        };


        var me=this;
        this.options    = $.extend({}, this.defaults, opt);
        this.title      = this.options.title;
        me.header_query = this.options.join_header;
        me.list_type =  me.header_query .list_type;


        this.$ele=  $(
            '<div class="'+me.options.length_css +'">'
                +'</div>');
        this.field_name= this.options.field_name ;

        this.$ele.select_date_range({
            'date_type' : me.options.date_type,
            'opt_date_type' : me.options.opt_date_type,
            'start_time'    : me.options.start_time,
            'end_time'      : me.options.end_time,
            date_type_config : me.options.date_type_config,
            onQuery :function() {
                me.header_query.query();
            }
        });

        if ( this.list_type ==1 && !this.options.as_header_query ){
            this.$ele.css( {
                "padding-left": "0px"
            });
        }


        //加入到列表
        this.header_query.add(this);

    };

    //定义方法
    Cdate_select.prototype = {
        get_title :function() {
            return this.title ;
        },
        //是否作为头部查询
        get_as_header_query:function() {
            return this.options.as_header_query;
        },

        set_query_arg_clean(){
            return false;
        },

        get_show_flag:function() {
            return  true;
        },

        get_query_args:function () {
            return {
                date_type_config:	$('#id_date_type_config').val(),
                date_type:	$('#id_date_type').val(),
                opt_date_type:	$('#id_opt_date_type').val(),
                start_time:	$('#id_start_time').val(),
                end_time:	$('#id_end_time').val()
            };
        },

        get_query_info:function( async_set_funtion ) {
            return "";
        },

        get_query_obj:function( ) {
            return this.$ele;
        }

    };


    //在插件中使用对象
    $.admin_date_select = function(options) {
       return new Cdate_select (  options);
    };

})(jQuery, window, document);



(function($, window, document,undefined) {

    var Cadmin_ajax_select_user = function(opt) {

        var me =this;
        this.defaults = {
            "join_header" : null,
            "field_name"  :null,
            "title"  :  "",
            "length_css" : "col-xs-6 col-md-2",
            "as_header_query" : false ,

            "user_type"    : "student",
            "select_value" :null,
            "th_input_id"  : null,
            "can_select_all_flag"     : true
        };

        this.options = $.extend({}, this.defaults, opt);
        this.menu_item_select_css="select";
        this.title= this.options.title;
        this.select_value= this.options.select_value;
        me.header_query = this.options.join_header;

        me.list_type =  me.header_query .list_type;
        this.field_name= this.options.field_name;

        this.options.onChange=function() {
            me.header_query.query();
        };

        var title_str="";
        if (this.list_type==0) {
            title_str= '<span style="display:table-cell;">'+ this.title +'    </span>';
        }


        this.$ele=  $(
            '<div class="'+me.options.length_css +'">'
                +'<div class="input-group ">'
                + title_str
                +'<input class="form-control"  type="text">'
                +'</div>'
                +'</div>'
        );

        if ( this.list_type ==1 && !this.options.as_header_query ){
            this.$ele.css( {
                "padding-left": "0px"
            });
        }


        this.$input=this.$ele.find("input");
        this.$input.val(this.options.select_value);

        this.$input.on("keypress",function(e){
            if(e.which==13) {
                me.header_query.query();
            }
        } );
      this.options["onChange"]=function() {
        me.header_query.query();
      };
      this.$input.admin_select_user_new( this.options );

      //加入到列表
      this.header_query.add(this);

    };

    //定义方法
    Cadmin_ajax_select_user.prototype = {
        get_title :function() {
            return this.title ;
        },
        //是否作为头部查询
        get_as_header_query:function() {
            return this.options.as_header_query;
        },

        set_query_arg_clean(){
            return   ;
        },

        get_show_flag:function() {
            return this.$input.val() != -1;
        },

        get_query_args:function () {
            var val=this.$input.val() ;
            var field_name= this.field_name;
            var ret={};
            ret[field_name ]= val;
            return  ret;
        },

        get_query_info:function(){
            return null;
        },

        get_query_obj:function( ) {
            return this.$ele;
        }

    };


    //在插件中使用对象
    $.admin_ajax_select_user  = function(options) {
       return new  Cadmin_ajax_select_user (  options);
    };

})(jQuery, window, document);
(function($, window, document,undefined) {

    var Ccommon = function(opt) {

        var me =this;
        this.defaults = {
            "join_header" : null,
            "length_css" : "col-xs-12 col-md-2",
          jquery_body :  "" ,
          "title" : "命令列表",
          "as_header_query" : false ,
        };

        this.options    = $.extend({}, this.defaults, opt);
        this.title      = this.options.title;
        me.header_query = this.options.join_header;
        me.list_type =  me.header_query .list_type;

        this.field_name= this.options.field_name ;

        this.$ele=  $(
            '<div class="'+me.options.length_css +'">'
                +'</div>'
        );

        if ( this.list_type ==1 && !this.options.as_header_query ){
            this.$ele.css( {
                "padding-left": "0px"
            });
        }
      this.$ele.html( this.options.jquery_body );


        //加入到列表
        this.header_query.add(this);
    };

    //定义方法
    Ccommon.prototype = {
        get_title :function() {
          return this.title ;
        },
        //是否作为头部查询
        get_as_header_query:function() {
            return this.options.as_header_query;
        },

        set_query_arg_clean(){
        },

        get_show_flag:function() {
          return  true;
        },

        get_query_args:function () {
          return null;
        },
        get_query_info:function() {
          return null;
        },

        get_query_obj:function( ) {
            return this.$ele;
        }

    };


    //在插件中使用对象
    $.admin_query_common = function(options) {
       return new Ccommon (  options);
    };

})(jQuery, window, document);



(function($, window, document,undefined) {

    var Cadmin_ajax_select_ajax = function(opt) {

        var me =this;
        this.defaults = {
            "join_header" : null,
            "field_name"  :null,
            "title"  :  "",
            "length_css" : "col-xs-6 col-md-2",
            "as_header_query" : false ,

            "select_value" :null,
            "can_select_all_flag"     : true,


            "opt_type" :  "select", // or "list"
            "url"          : "/user_deal/get_xmpp_server_list_js",
            select_primary_field   : "server_name",
            select_display         : "server_name",
            select_no_select_value : "",
            //select_no_select_title : "[全部]",
            select_no_select_title : "xmpp服务器",
            "th_input_id"  : null,

            //其他参数
            "args_ex" : {
            },
            //字段列表
            'field_list' :[
                {
                title:"ip",
                render:function(val,item) {return item.ip;}
            },{
                title:"权重",
                render:function(val,item) {return item.weights ;}
            },{
                title:"名称",
                render:function(val,item) {return item.server_name;}
            },{

                title:"说明",
                render:function(val,item) {return item.server_desc;}
            }
            ] ,
            filter_list: [],

            "auto_close"       : true,
            //选择
            "onChange"         : function(v) {
                $("id_xmpp_server_name").val(v);
                load_data();
            },
            //加载数据后，其它的设置
            "onLoadData"       : null,

        };

        this.options = $.extend({}, this.defaults, opt);
        this.menu_item_select_css="select";
        this.title= this.options.title;
        this.select_value= this.options.select_value;
        me.header_query = this.options.join_header;

        me.list_type =  me.header_query .list_type;
        this.field_name= this.options.field_name;

        this.options.onChange=function() {
            me.header_query.query();
        };

        var title_str="";
        if (this.list_type==0) {
            title_str= '<span style="display:table-cell;">'+ this.title +'    </span>';
        }


        this.$ele=  $(
            '<div class="'+me.options.length_css +'">'
                +'<div class="input-group ">'
                + title_str
                +'<input class="form-control"  type="text">'
                +'</div>'
                +'</div>'
        );

        if ( this.list_type ==1 && !this.options.as_header_query ){
            this.$ele.css( {
                "padding-left": "0px"
            });
        }


        this.$input=this.$ele.find("input");
        this.$input.val(this.options.select_value);

        this.$input.on("keypress",function(e){
            if(e.which==13) {
                me.header_query.query();
            }
        } );
      this.options["onChange"]=function() {
        me.header_query.query();
      };
      this.$input.admin_select_dlg_ajax( this.options );

      //加入到列表
      this.header_query.add(this);

    };

    //定义方法
    Cadmin_ajax_select_ajax.prototype = {
        get_title :function() {
            return this.title ;
        },
        //是否作为头部查询
        get_as_header_query:function() {
            return this.options.as_header_query;
        },

        set_query_arg_clean(){
            return   ;
        },

        get_show_flag:function() {
            return this.$input.val() != -1;
        },

        get_query_args:function () {
            var val=this.$input.val() ;
            var field_name= this.field_name;
            var ret={};
            ret[field_name ]= val;
            return  ret;
        },

        get_query_info:function(){
            return null;
        },

        get_query_obj:function( ) {
            return this.$ele;
        }

    };


    //在插件中使用对象
    $.admin_ajax_select_dlg_ajax  = function(options) {
       return new  Cadmin_ajax_select_ajax (  options);
    };

})(jQuery, window, document);



(function($, window, document,undefined) {

    var Cgroup = function(opt) {

        var me =this;
        this.defaults = {
            "join_header" : null,
            "field_name"  :null,
            "title"  :  "",
            "length_css" : "col-xs-6 col-md-2",
            "as_header_query" : false ,
            "select_value" :null,
        };

        this.options = $.extend({}, this.defaults, opt);
        this.menu_item_select_css="select";
        this.title= this.options.title;
        this.select_value= this.options.select_value;
        me.header_query = this.options.join_header;

        me.list_type =  me.header_query .list_type;
        this.field_name= this.options.field_name;

        this.options.onChange=function() {
            me.header_query.query();
        };

        var title_str="";
        if (this.list_type==0) {
            title_str= '<span style="display:table-cell;">'+ this.title +'    </span>';
        }


        this.$ele=  $(
            '<div class="'+me.options.length_css +'">'
                +'<div class="input-group ">'
                + title_str
                +'<input class="form-control"  type="text">'
                +'</div>'
                +'</div>'
        );

        if ( this.list_type ==1 && !this.options.as_header_query ){
            this.$ele.css( {
                "padding-left": "0px"
            });
        }


        this.$input=this.$ele.find("input");
        this.$input.val(this.options.select_value);

        this.$input.on("keypress",function(e){
            if(e.which==13) {
                me.header_query.query();
            }
        } );
        this.$input.init_seller_groupid_ex(null, function(){
            me.header_query.query();
        });

      //加入到列表
      this.header_query.add(this);

    };

    //定义方法
    Cgroup.prototype = {
        get_title :function() {
            return this.title ;
        },
        //是否作为头部查询
        get_as_header_query:function() {
            return this.options.as_header_query;
        },

        set_query_arg_clean(){
            return   ;
        },

        get_show_flag:function() {
            return this.$input.val() != -1;
        },

        get_query_args:function () {
            var val=this.$input.val() ;
            var field_name= this.field_name;
            var ret={};
            ret[field_name ]= val;
            return  ret;
        },

        get_query_info:function(){
            return null;
        },

        get_query_obj:function( ) {
            return this.$ele;
        }

    };


    //在插件中使用对象
    $.admin_query_admin_group = function(options) {
       return new  Cgroup(  options);
    };

})(jQuery, window, document);




(function($, window, document,undefined) {

    var Corigin = function(opt) {

        var me =this;
        this.defaults = {
            "join_header" : null,
            "field_name"  :null,
            "title"  :  "",
            "length_css" : "col-xs-6 col-md-2",
            "as_header_query" : false ,
            "select_value" :null,
        };

        this.options = $.extend({}, this.defaults, opt);
        this.menu_item_select_css="select";
        this.title= this.options.title;
        this.select_value= this.options.select_value;
        me.header_query = this.options.join_header;

        me.list_type =  me.header_query .list_type;
        this.field_name= this.options.field_name;

        this.options.onChange=function() {
            me.header_query.query();
        };

        var title_str="";
        if (this.list_type==0) {
            title_str= '<span style="display:table-cell;">'+ this.title +'    </span>';
        }


        this.$ele=  $(
            '<div class="'+me.options.length_css +'">'
                +'<div class="input-group ">'
                + title_str
                +'<input class="form-control"  type="text">'
                +'</div>'
                +'</div>'
        );

        if ( this.list_type ==1 && !this.options.as_header_query ){
            this.$ele.css( {
                "padding-left": "0px"
            });
        }


        this.$input=this.$ele.find("input");
        this.$input.val(this.options.select_value);

        this.$input.on("keypress",function(e){
            if(e.which==13) {
                me.header_query.query();
            }
        } );

        this.$input.init_origin_ex(function(){
            me.header_query.query();
        });

      //加入到列表
      this.header_query.add(this);

    };

    //定义方法
    Corigin.prototype = {
        get_title :function() {
            return this.title ;
        },
        //是否作为头部查询
        get_as_header_query:function() {
            return this.options.as_header_query;
        },

        set_query_arg_clean(){
            return   ;
        },

        get_show_flag:function() {
            return this.$input.val() != -1;
        },

        get_query_args:function () {
            var val=this.$input.val() ;
            var field_name= this.field_name;
            var ret={};
            ret[field_name ]= val;
            return  ret;
        },

        get_query_info:function(){
            return null;
        },

        get_query_obj:function( ) {
            return this.$ele;
        }

    };


    //在插件中使用对象
    $.admin_query_origin = function(options) {
       return new  Corigin(  options);
    };

})(jQuery, window, document);
