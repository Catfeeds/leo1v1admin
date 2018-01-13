(function($, window, document,undefined) {
    //定义构造函数
    var Cselect_dlg_ajax = function(ele, opt) {
        this.$element = ele;
        this.element_is_input = $(ele).is("input");
        //当用户关掉弹出层重新勾选时，保留选择过的数据
        this.haveChecked = new Array();
        //判断页面初始化
        this.select_init = 1;
        //console.log(ele);
        if( this.element_is_input ) {
            //原来的不显示，显示display
            this.$show_input = $(this.$element[0].outerHTML);
            //清除id
            this.$show_input.attr("id","");
            this.$show_input.css("cursor","inherit");
            this.$show_input.insertAfter(this.$element);
            //console.log( this.$show_input);
            this.$element.hide();

        }

        this.defaults = {
            "opt_type" : "select", // or "list"
            "url"      : "/user_manage/get_user_list",
            "lru_flag" : false,
            "th_input_id" :null,
            "only_show_in_th_input" :false , //是否只显示 表头
            lru_item_desc: null,
            //其他参数
            "args_ex" : {
                //type  :  "student"
            },

            width : 1000,
            select_primary_field : "id",
            select_display       : "nick",
            select_no_select_value  :  -1  , // 没有选择是，设置的值
            select_no_select_title  :  "[全部]"  , // "未设置"
            select_have_id_arr : new Array(),          
            //字段列表
            'field_list' :[
                {
                    title:"id",
                    width :50,
                    field_name:"id"
                },{
                    title:"性别",
                    //width :50,
                    field_name:"gender",
                    render:function(val,item) {
                        return val;
                    }

                },{
                    title:"昵称",
                    //width :50,
                    field_name:"nick",
                    render:function(val,item) {
                        return item.nick;
                    }
                },{
                    title:"电话",
                    field_name:"phone"
                }
            ] ,
            //查询列表
            filter_list:[
                [
                    {
                        size_class: "col-md-4" ,
                        title :"性别",
                        type  : "select" ,
                        'arg_name' :  "gender"  ,
                        select_option_list: [ {
                            value : -1 ,
                            text :  "全部"
                        },{
                            value :  1 ,
                            text :  "男"
                        },{
                            value :  2 ,
                            text :  "女"
                        }]
                    },{
                        size_class: "col-md-8" ,
                        title :"姓名/电话",
                        'arg_name' :  "nick_phone"  ,
                        type  : "input"
                    }
                ]
            ],
            "auto_close"       : true,
            //选择
            "onChange"         : null,
            //加载数据后，其它的设置
            "onLoadData"       : null,
            "select_btn_config" : null
        };

        var me=this;
        this.options = $.extend({}, this.defaults, opt);
    };


    //定义方法
    Cselect_dlg_ajax.prototype = {
        init_body:function() {
            var me=this;
            var $body=$("<div ></div>");

            $.each(me.options.filter_list,function (i,row_item){
                var $filter_row= $('<div class="row"></div>');
                $.each(row_item,function(j,item){
                    var input_str="";
                    var $input=null;

                    if (item.type=="select" ) {
                        input_str='<select  >';
                        $.each( item.select_option_list,function(o_i,opt_item ){
                            input_str+='<option value="'+ opt_item.value +'" >'+opt_item.text+' </option>  ';
                        });
                        input_str+= '</select>';

                        $input=$(input_str );
                        $input.val(item.value );
                        $input.on("change",function(){
                            me.reload_data(1,null,'search');
                        });
                    }else{ //input
                        $input= $('<input/>');
                        $input.on ("keypress", function( e){
                            if (e.keyCode==13){
                                me.reload_data(1,null,'search');
                            }
                        });
                    }

                    $input.data("arg_name" ,  item.arg_name );
                    $input.addClass("filter-arg");

                    var $filter_item=$('<div class="'+item.size_class +'">'+
                                       '                    <div class="input-group ">'+
                                       '                        <span>'+item.title+' </span>'+
                                       '                    </div>'+
                                       '                </div>');
                    $filter_item.find(".input-group").append($input);
                    $filter_row.append($filter_item );

                });

                $body.append( $filter_row);
            });
            $body.append( '<hr style=" margin-top: 7px; margin-bottom: 7px;"/>' );

            var th_str="";
            var lru_th_str = "";
            $.each( me.options.field_list,function(i,field_item){
                var width_css="";
                if (field_item.width  ) {
                    width_css="width:"+field_item.width+"px;";
                }
                th_str+= "<th style=\""+width_css+"\">"+ field_item.title + "</th>";
                lru_th_str += "<th style=\""+width_css+"\">"+ field_item.title + "</th>";
            });

            $body.append('<table   class="table table-bordered "   >'+
                         '                <tr>   '+ th_str +' </tr>'+
                         '                    <tbody id="id_body">'+
                         '                    </tbody>'+
                         '            </table>' +
                         '           <div id="id_page_info"> '+
                         '           </div>  ' );
            me.$body=$body;

            me.$lru_body=$('<div class="row">'+
                           '<h4>已经选择条目</h4>'+
                           '<table   class="table table-bordered "   >'+
                           '                <thead>' + lru_th_str + '</thead>'+
                           '                    <tbody id="id_lru_body">'+
                           '                    </tbody>'+
                           '            </table>' + 
                           '</div>'
                          );
        
        },
        show_display:function( ) {
            var me =this;
            if (!me.element_is_input) {
                return ;
            }

            var val=me.$element.val() ;
            var get_value_desc_flag=false;
            $(me.options.select_btn_config).each(function(i,item){
                if ( item.value== val) {
                    get_value_desc_flag=true;
                    me.$show_input.val(item.label );
                }
            });


            if (get_value_desc_flag ) {
                //do null
            } else if (val==me.options.select_no_select_value ) {
                me.$show_input.val(me.options.select_no_select_title );
            } else if (val  && me.element_is_input ) {

                var data = {lru_flag: me.options.lru_flag?1:0 };
                data[ me.options.select_primary_field ] = val;
                data=$.extend({},me.options.args_ex,data);
                var data_type="json";
                if ( me.options.url.substr(0, 7)=="http://" ) {
                    data_type="jsonp";
                }

                var key=me.$element.val();
                $.do_ajax(me.options.url, data, function(result){
                        var ret_list = result.data.list;
                        var row_data = ret_list[0];
                        if (row_data) {
                            me.$show_input.val( row_data[me.options.select_display]);
                        }else{
                            me.$show_input.val("[没找到]");
                        }
                        if ( me.options.th_input_id ) {
                            var $th_input=$( '#'+ me.options.th_input_id ).find("span");
                            if (row_data) {
                                $th_input.text( row_data[me.options.select_display]);
                            }else{
                                $th_input.text("[没找到]");
                            }
                        }
                    });
            }else{
                me.$show_input.val("未设置");
            }
        },bind: function() {
            var me=this;
            if( this.element_is_input) {
                this.$show_input.on("click", function(){
                    me.show_select ();
                });

                if(this.options.th_input_id  ) {
                    console.log(this.options.th_input_id );
                    var $th_input=$( '#'+ this.options.th_input_id );
                    $th_input.data("title", $.trim($th_input.find("span").text()) );
                    $th_input=$th_input.find("span");

                    $th_input.css({
                        cursor: "pointer",
                        color: "#3c8dbc",
                    });
                    $th_input.on("click", function(){
                        me.show_select ();
                    });
                    if ($th_input.is(":visible")){ //选择框放到 td
                        if (me.options.only_show_in_th_input ) {
                            me.$element.parent().parent().hide();
                            me.$element.parent().parent().data( "always_hide", 1);
                        }
                    }

                }

            }
        },show_select:function(){
            var me=this;
            me.init_body();
            var btns=  [
            ];
       
            if ( me.options.select_btn_config) {
                //console.log(me.options.select_btn_config);
                var add_count=0;
                $(me.options.select_btn_config).each(function(i,item){
                    btns.push ({
                        cssClass : 'btn-primary',
                        label    : item.label,
                        action   : function(dialog) {
                            var value=item.value;
                            me.$element.val(value);
                            if (me.element_is_input ) {
                                me.$show_input.val(item.label )  ;
                            }

                            if (me.options.onChange ){
                                me.options.onChange( value ,{},dialog);
                            }
                        }
                    });
                    add_count++;
                });

                if (add_count) {
                    btns.push({
                        label: '----',
                        action: function(dialog) {
                        }
                    });
                }
            }

            btns.push({
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            });


            if (me.options.opt_type=="select") {
                //console.log(me.options);
                btns.push ({
                    label: '选择',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                     
                        var $lru_tbody=me.$lru_body.find("#id_lru_body");
                        var row_data_arr = new Array();
                        $.each($lru_tbody.find("tr"),function(i,item){                   
                            row_data_arr.push($(this).data('row_data'));
                        })

                        me.haveChecked = row_data_arr;

                        if (row_data_arr) {
                            var value = '';
                            var show = '';
                            for(var x in row_data_arr){
                                var row_data=JSON.parse(row_data_arr[x]);
                                show += ( row_data[me.options.select_display] || row_data["name"] ) + ',';
                                value += ( row_data[me.options.select_primary_field] || row_data["id"] ) + ',';                              
                            }
                            show = show.substring(0, show.length-1);
                            value = value.substring(0, value.length-1);
    
                            me.$element.val(value);
                            if (me.element_is_input ) {
                                me.$show_input.val(show);
                            }

                            if (me.options.onChange ){
                                me.options.onChange( value ,row_data_arr,dialog);
                            }
                       
                        }else{
                            if (me.element_is_input ) {
                                me.$element.val(me.options.select_no_select_value);
                                me.$show_input.val(me.options.select_no_select_title );
                            }
                            if (me.options.onChange ){
                                me.options.onChange( me.options.select_no_select_value , undefined,dialog);
                            }
                        }

                        if (me.options.auto_close) {
                            dialog.close();
                        };

                    }
                }) ;
            }

            var $dlg_form=$('<div class="row">'+
                            '    <div class="col-xs-12 col-md-6 ajax_list">'+
                            '    </div>'+
                            '    <div class="col-xs-12 col-md-6 lru_list " >'+
                            '    </div>'+
                            '</div>');
            if (me.options.opt_type=="list" ) {
                $dlg_form=$('<div class="row">'+
                            '    <div class="col-xs-12 col-md-12 ajax_list">'+
                            '    </div>'+
                            '</div>');
            }

            $dlg_form.find( ".ajax_list"  ).append(me.$body);
            $dlg_form.find( ".lru_list"  ).append(me.$lru_body);

            var dlg=BootstrapDialog.show({
                title: '选择',
                message : $dlg_form,
                closable: true,
                onhide: function(dialogRef){
                },
                buttons: btns
            });
            dlg.getModalDialog().find( ".modal-footer").css({
                "margin-top":"0px"
            });
            if (me.options.width){
                $.dlg_set_width( dlg, me.options.width) ;
            }

            if ( !$.check_in_phone()  && me.options.lru_flag  ) {
                $.dlg_set_width(dlg,1000);
            }

            me.dlg=dlg;
            //加载数据
            me.reload_data(1);
        },reload_data:function(page_num,url,type){
            var me=this;
            var $lru_tbody=me.$lru_body.find("#id_lru_body");
            var $tbody=me.$body.find("#id_body");

            //点击lru_body的tr移除
            var remove_opt = function(){
                $(this).remove();
                var cancelChecked = $(this).html();
                $.each($tbody.find("tr"),function(i,item){                   
                    if($(this).html() == cancelChecked){
                        $(this).removeClass("warning");
                    }
                })
            }

            //点击body中的tr
            var select_opt = function(){
                if ( $(this).hasClass("warning") ){
                    $(this).removeClass("warning");
                    var checked = $(this).html();
                    $.each($lru_tbody.find("tr"),function(i,item){                   
                        if($(this).html() == checked){
                            $(this).remove();
                        }
                    })
                }else{
                    $(this).addClass("warning");
                    var $copy = $(this).clone(true);
                    $lru_tbody.append($copy).addClass("warning");
                    $copy.on("click", remove_opt );
                }
            };
           

            if( me.select_init == 1 && me.options.select_have_id_arr.length>0 ){
                var data = {lru_flag: me.options.lru_flag?1:0,id_arr:me.options.select_have_id_arr };
            }else{
                var data = {lru_flag: me.options.lru_flag?1:0 };
            }

            if (!url){
                url     = me.options.url  ;
                var $args=me.$body.find(".filter-arg");
                $.each($args,function (i,input_item){
                    var $item=$(input_item);
                    data[$item.data("arg_name") ] =  $item.val();
                });
                data=$.extend({},me.options.args_ex,data);
            }

            if( page_num == 1 && type != 'search'){
                //当页面初始化的时候将已经选择过的条目重新放入id_lru_body中
                if(me.haveChecked){
                    //console.log(me.haveChecked);                   
                    $.each( me.haveChecked,function (i,item){
                        var $tr_item=$("<tr />");
                        $tr_item.data( "row_data",item);
                        item = JSON.parse(item);
                        $.each( me.options.field_list,function (f_i,f_item){
                            var $td_item=$("<td/>");
                            var filed = '';
                            if ( f_item.render) {
                                $td_item.append( f_item.render( item[ f_item.field_name] ,item ));
                            }else{
                                $td_item.append( item[ f_item.field_name] );
                            }

                            $tr_item.append($td_item );
                        })
                        $tr_item.addClass('warning');
                        $tr_item.on("click", remove_opt );
                        $lru_tbody.append( $tr_item);
                    });
                    
                }
            }
           
            $.do_ajax(url, data, function(result){
                me.select_init = 0;
                if (result.ret!=0) {
                    alert(result.info);
                    return;
                }
                var ret_list      = result.data.list;
                var ret_page_info = result.data.page_info;

                $tbody.html("");

                $.each( ret_list, function(i,item){
                    var $tr_item=$("<tr />");
                    $tr_item.data( "row_data", JSON.stringify( item  ));

                    $.each( me.options.field_list,function (f_i,f_item){
                        var $td_item=$("<td/>");
                        if ( f_item.render) {
                            $td_item.append( f_item.render( item[ f_item.field_name] ,item ));
                        }else{
                            $td_item.append( item[ f_item.field_name] );
                        }
                       
                        $tr_item.append($td_item );

                        $.each($lru_tbody.find("tr"),function(i,item){                   
                            if($(this).html() == $tr_item.html()){
                                $tr_item.addClass("warning");
                            }
                        })

                    });
                    $tbody.append( $tr_item);
                });

                var html_str = get_page_node(ret_page_info ,function(url ){
                    me.reload_data(null,url,'page');
                });

                me.$body.find("#id_page_info").html(html_str);

                $tbody.find("tr").on("click", select_opt );

                if (me.options. onLoadData ) {
                    me.options.onLoadData( me.dlg ,result );
                }

            });
        }
    };

    //在插件中使用对象
    $.fn.admin_select_dlg_ajax_more = function(options) {
        //创建的实体
        var select_dlg_ajax = new Cselect_dlg_ajax(this, options);
        //调用其方法
        select_dlg_ajax.bind();
        if(! select_dlg_ajax.element_is_input ){
            select_dlg_ajax.show_select();
        }else{
            select_dlg_ajax.show_display();
        }
        return  select_dlg_ajax;
    };
})(jQuery, window, document);
