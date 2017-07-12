(function($, window, document,undefined) {
    //定义构造函数
    var Cselect_dlg_ajax= function(ele, opt) {
        this.$element = ele;
        this.element_is_input=$(ele).is("input");
        if( this.element_is_input ) {
            //原来的不显示，显示display
            this.$show_input = $(this.$element[0].outerHTML);
            //清除id
            this.$show_input.attr("id","");
            this.$show_input.css("cursor","inherit");
            this.$show_input.insertAfter(this.$element);
            this.$element.hide();
        }

        this.defaults = {
            "opt_type" : "select", // or "list"
            "url"      : "/user_manage/get_user_list",
            //其他参数
            "args_ex" : {
                //type  :  "student"
            },

            select_primary_field : "id",
            select_display       : "nick",
            select_no_select_value  :  -1  , // 没有选择是，设置的值 
            select_no_select_title  :  "[全部]"  , // "未设置"

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
            "onLoadData"       : null
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
                        $input.on("change",function(){
                            me.reload_data(1);
                        });
                    }else{ //input
                        $input= $('<input/>');
	                    $input.on ("keypress", function( e){
		                    if (e.keyCode==13){
                                me.reload_data(1);
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
            //处理th
            $.each( me.options.field_list,function(i,field_item){
                th_str+= "<th>"+ field_item.title + "</th>";
                
            });
            
            $body.append('<table   class="table table-bordered "   >'+
                         '                <tr>   '+ th_str +' </tr>'+
                         '                    <tbody id="id_body">'+
                         '                    </tbody>'+
                         '            </table>' +
                         '           <div id="id_page_info"> '+
                         '           </div>  ' );
            me.$body=$body;

            
            

        },
        show_display:function( ) {
            var me =this;
            if (!me.element_is_input) {
                return ;
            }

            var val=me.$element.val() ;
            if (val==me.options.select_no_select_value ) {
                me.$show_input.val(me.options.select_no_select_title );
            } else if (val  && me.element_is_input ) {
                var data = {};
                data[ me.options.select_primary_field ] = val; 
                data=$.extend({},me.options.args_ex,data);
                
                var key=me.$element.val();
		        $.ajax({
			        type     : "post",
			        url      :  me.options.url,
			        dataType : "json",
			        data     : data,
			        success  : function(result){
                        var ret_list = result.data.list;
                        var row_data = ret_list[0];
                        if (row_data) {
                            me.$show_input.val( row_data[me.options.select_display]);
                        }else{
                            me.$show_input.val("[没找到]");
                        }
			        }
                });
            }else{
                me.$show_input.val("未设置");
            }
        },
        bind: function() {
            var me=this;
            if( this.element_is_input) {
                this.$show_input.on("click", function(){
                    me.show_select ();
                });
            }

        }
        ,show_select:function(){
            //
            var me=this;
            me.init_body();
            var btns=  [{
		            label: '返回',
		            action: function(dialog) {
			            dialog.close();
		            }
	        }];

            if (me.options.opt_type=="select") {
                btns.push ({
		            label: '完成',
		            cssClass: 'btn-warning',
		            action: function(dialog) {
                        var $tbody=me.$body.find("#id_body");
                        var row_data_str= $tbody.find ("tr.warning" ).data("row_data");
                        if (row_data_str) {
                            var row_data=JSON.parse(row_data_str);
                            var value=row_data[me.options.select_primary_field];

                            me.$element.val(value);
                            if (me.element_is_input ) {
                                me.$show_input.val( row_data[me.options.select_display]);
                            }
                            
                            if (me.options.onChange ){
                                me.options.onChange( value ,row_data,dialog);
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

            var dlg=BootstrapDialog.show({
                title: '选择',
                message : me.$body,
                closable: true, 
                onhide: function(dialogRef){
                },
	            buttons: btns
            }); 
            dlg.getModalDialog().find( ".modal-footer").css({
                "margin-top":"0px"
            });
            me.dlg=dlg;
            //加载数据
            me.reload_data(1);
        },reload_data:function(page_num,url){
            var me=this;
            var data=null;

            if (!url){
			    url     = me.options.url  ;
                data= {};
                var $args=me.$body.find(".filter-arg");
                $.each($args,function (i,input_item){
                    var $item=$(input_item);
                    data[$item.data("arg_name") ] =  $item.val();
                });
                data=$.extend({},me.options.args_ex,data);
            }

		    $.ajax({
			    type     : "post",
			    url      : url,
			    dataType : "json",
			    data     : data,
			    success  : function(result){
                    var ret_list      = result.data.list;
                    var ret_page_info = result.data.page_info;
                    var $tbody=me.$body.find("#id_body");
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
                        });
                        $tbody.append( $tr_item);
                    });

                    //console.log("888");

                    var html_str=get_page_node(ret_page_info ,function(url ){
                        me.reload_data(null,url);
                    });
                    me.$body.find("#id_page_info").html(html_str);

                    $tbody.find("tr").on("click",function(){
                        if ( $(this).hasClass("warning") ){
                            $tbody.find("tr").removeClass("warning");
                        }else{
                            $tbody.find("tr").removeClass("warning");
                            $(this).addClass("warning");
                        }
                    });
                    if (me.options. onLoadData ) {
                        me.options.onLoadData( me.dlg ,result );
                    }
			    }
            });
        }
    };

    //在插件中使用对象
    $.fn.admin_select_dlg_ajax = function(options) {
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
