(function($, window, document,undefined) {
    //定义构造函数
    var Cselect_dlg= function(ele, opt) {
        this.$element = ele;
        var screen_height=window.screen.availHeight-350;
        //console.log(g_enum_map["subject"]["desc_map"]);
        this.defaults = {
            'data_list': [],
            'showSelect' : 1, //搜索框是input 还是textarea
            'select_search' : null, //搜索框中初始化搜索的内容
            'searchNo' : 1, //搜索tr中第几个td的值
            "enum_name" : null,  //不用ajax请求，直接枚举名称如subject
            "header_list":["id","属性"] ,
            "onChange":null,
            "select_list":[],
            "multi_selection":false,
            "btn_list":[],
            "div_style":null
        };
        var me=this;

        var search_html = "";
        if(this.defaults.showSelect == 1){
            search_html = '<div style="margin-bottom:10px"><span style="width:14%">选择搜索：</span><input type="text" style="padding:0px;width:86%;text-indent:5px" id="search_node" placeholder="搜索多个词用分号或者顿号隔开"></div>';          
        }else{
            search_html = '<div style="margin-bottom:10px"><span style="width:14%">选择搜索：</span><textarea style="padding:0px;width:86%;text-indent:5px" id="search_node" placeholder="搜索多个词用分号或者顿号隔开"></textarea></div>';
        }
        this.select_html= search_html + 
            '            <table   class="table table-bordered "   >'+
            '                <tr id="id_th_list"> </tr>'+
            '                    <tbody id="id_body">'+
            '                    </tbody>'+
            '            </table>' ;


        //this.$element.attr("readonly","readonly");
        this.options     = $.extend({}, this.defaults, opt);
        //return val
        me.select_id=0;
    };

    //定义方法
    Cselect_dlg.prototype = {

        bind: function() {
            var me=this;
            //this.$element.hide();
            var type=this.options.type;

        }
        ,set_id:function(id,dlg){
            var me=this;
            if (me.options.onChange ){
                me.options.onChange(id,dlg);
            }
        }
        ,show_select:function(){
            var me=this;
            //事件
            var html_node = $(me.select_html);

            var $tbody=html_node.find("#id_body");
            var $th_list=html_node.find("#id_th_list");
            $.each( me.options.header_list,function(i,item){
                $th_list.append( "<th>"+item+" </th>");
            });

            var new_select_list = [];
            if(me.options.data_list.length == 0 && me.options.enum_name){
                var enum_arr = g_enum_map[me.options.enum_name]["desc_map"];
                for(var x in enum_arr){
                    new_select_list.push([x,enum_arr[x]]);
                }
                me.options.data_list = new_select_list;
            }

            if(me.options.select_search){
                html_node.find("#search_node").val(me.options.select_search);
            }
            //console.log(me.options.data_list);
            $.each( me.options.data_list,function(i,row_item){
                var td_list_str="";
                var cur_id=-100000;
                $.each(  row_item,function(j,item){
                    if((cur_id==-100000) ) {
                        cur_id=item;
                    }
                    td_list_str+="<td>"+item+"</td>";
                });
                var class_str= "";
                if (($.inArray(cur_id,me.options.select_list)!==-1) || ($.inArray(parseInt(cur_id),me.options.select_list)!==-1)){
                    class_str="warning";
                }

                var $tr_html = "<tr class=\""+class_str+"\" data-id=\""+cur_id+"\"  >" + td_list_str+ "</tr>";
                if(me.options.select_search){
                    var int_key_arr = [];
                    var init_keywords = me.options.select_search;
                    var searchNo = me.defaults.searchNo;
                    if(init_keywords.indexOf(';') > 0){
                        int_key_arr = init_keywords.split(';');
                    };
                    if(init_keywords.indexOf('；') > 0){
                        int_key_arr = init_keywords.split('；');
                    };
                    if(init_keywords.indexOf('、') > 0){
                        int_key_arr = init_keywords.split('、');
                    };
                    var this_display = 0;
                    if(int_key_arr.length > 0){
                        for(var x in int_key_arr ){
                            if( row_item[searchNo].indexOf(int_key_arr[x]) >= 0){
                                this_display = 1;
                            }
                        }
                    }else{
                        if( row_item[searchNo].indexOf(init_keywords) >= 0){
                            this_display = 1;
                        }
                    }

                    if( this_display == 0){
                        var $tr_html = "<tr class=\""+class_str+"\" data-id=\""+cur_id+"\"  style= 'display:none'>" + td_list_str+ "</tr>";
                    }
                }

                $tbody.append($tr_html);
            });

            $tbody.find("tr").on("click",function(){
                if (!me.options.multi_selection ) {

                    if ( $(this).hasClass("warning") ){
                        $tbody.find("tr").removeClass("warning");
                        me.select_id=-1;
                    }else{
                        $tbody.find("tr").removeClass("warning");
                        $(this).addClass("warning");
                        me.select_id=$(this).data("id");
                    }
                }else{
                    if ( $(this).hasClass("warning") ){
                        $(this).removeClass("warning");
                    }else{
                        $(this).addClass("warning");
                    }
                }
            });
            var btn_list = me.options.btn_list;

            var $search = html_node.find("#search_node");

            var searchNo = me.defaults.searchNo;
            var old_keywords = "";
            $search.keyup(function(ret){
                var keywords = $(this).val();
                if( old_keywords != keywords){
                    //console.log(keywords);
                    
                    var key_arr = [];
                    if(keywords.indexOf(';') > 0){
                        key_arr = keywords.split(';');
                    };
                    if(keywords.indexOf('；') > 0){
                        key_arr = keywords.split('；');
                    };
                    if(keywords.indexOf('、') > 0){
                        key_arr = keywords.split('、');
                    };
                    if( key_arr.length == 0){
                        $tbody.find("tr").each(function(){
                            var words = $(this).find("td:eq('"+searchNo+"')").text();
                            if( words.indexOf(keywords) >= 0){
                                $(this).removeAttr("style");
                            }else{
                                $(this).attr({"style":"display:none"});
                            }
                        })
                    }else{
                        $tbody.find("tr").each(function(){
                            var words = $(this).find("td:eq('"+searchNo+"')").text();
                            var this_display = 0;
                            for(var x in key_arr ){
                                if( words.indexOf(key_arr[x]) >= 0){
                                    this_display = 1;
                                }
                            }
                            if( this_display == 1){
                                $(this).removeAttr("style");
                            }else{
                                $(this).attr({"style":"display:none"});
                            }
                        })
                    }
                    old_keywords = keywords;
                }
            });

            btn_list.push( {
                label: '返回',
                action: function(dialog) {
                  dialog.close();
                }
            });
            btn_list.push (
                {
                    label: '完成',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        console.log(111111);
                        if( me.options.multi_selection ) {
                            var select_item_list=$tbody.find("tr.warning");
                            var select_list=[];
                            select_item_list.each(function(){
                                select_list.push($(this).data("id") );
                            });
                            dialog.close();
                            if (me.options.onChange) {
                                me.options.onChange(select_list,dlg);
                            }

                        }else{

                            var select_id=me.select_id;
                            if (!(select_id>0)){
                                select_id=-1;
                                //BootstrapDialog.alert("还没选择");
                                //return;
                            }

                            me.set_id(select_id,dialog);

                        }
                    }
                }
            );


            var dlg=BootstrapDialog.show({
                title: '选择',
                message : html_node,
                closable: true,
                onhide: function(dialogRef){
                },
                buttons: btn_list
            });
            dlg.getModalDialog().find( ".modal-footer").css({
                "margin-top":"0px"
            });
            var div_style = me.options.div_style ;
            //console.log(div_style);
            if(div_style != null || div_style != undefined ){
                dlg.getModalDialog().find( ".bootstrap-dialog-message").css(div_style);
            }
        }
    };

    //在插件中使用对象
    $.fn.admin_select_dlg_second = function(options) {
        //创建的实体
        var select_dlg  = new Cselect_dlg(this, options);
        //调用其方法
        select_dlg.bind();
        select_dlg.show_select();
        return  select_dlg;
    };
})(jQuery, window, document);

