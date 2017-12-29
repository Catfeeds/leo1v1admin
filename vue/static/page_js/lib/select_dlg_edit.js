(function($, window, document,undefined) {
    //定义构造函数
    var Cselect_dlg_edit= function(ele, opt) {
        this.$element = ele;
        this.defaults = {
            'data_list': [],
            'field_list' :[
                {
                    title:"id",
                    width :50,
                    field_name:"id"
                },{
                    title:"昵称",
                    //width :50,
                    field_name:"nick",
                    render:function(val,item) {
                        return item.nick;
                    }
                }
            ] ,
            onAdd:function() {
                alert("add");
            }
            ,sort_func :null
        };
        var me=this;

        this.select_html=

            '            <table   class="table table-bordered "   >'+
            '                <tr id="id_th_list"> </tr>'+
            '                    <tbody id="id_body">'+
            '                    </tbody>'+
            '            </table>' ;


        //this.$element.attr("readonly","readonly");
        this.options     = $.extend({}, this.defaults, opt);
        //return val
        me.select_id=0;
        this.data_list=this.options.data_list;

        //
    };


    //定义方法
    Cselect_dlg_edit.prototype = {

        bind: function() {
            var me=this;
            //this.$element.hide();
            var type=this.options.type;

        }
        ,set_id:function(id,dlg){
            var me=this;
        }
        ,reset_body:function() {
            var me = this;
            var $tbody=me.$tbody;
            $tbody.html("");
            if (me.options.sort_func) {
                me.data_list.sort( me.options.sort_func );
            }

            $.each(me.data_list , function(i,item){
                var $tr_item=$("<tr />");

                $.each( me.options.field_list,function (f_i,f_item){
                    var $td_item=$("<td/>");
                    if ( f_item.render) {
                        $td_item.append( f_item.render( item[ f_item.field_name] ,item ));
                    }else{
                        $td_item.append( item[ f_item.field_name] );
                    }
                    $tr_item.append($td_item );
                });
                var $del_td=$("<td><a class=\"fa fa-times btn\"> </a>  </td>");
                $del_td.find("a").on("click",function(){

                    var text="";
                    $.each( me.options.field_list,function (f_i,f_item){
                        if ( f_item.render) {
                            text+= f_item.title + ":"+ f_item.render( item[ f_item.field_name] ,item );
                        }else{
                            text+= f_item.title + ":"+  item[ f_item.field_name] +"<br/>" ;
                        }
                    });

                    BootstrapDialog.confirm("要删除吗?<br/>"+text,function(val){
                        if (val) {
                            me.data_list.splice(i, 1);
                            me.reset_body();
                        }
                    });
                });

                $tr_item.append( $del_td );
                $tbody.append( $tr_item);
            });
        }

        ,show_select:function(){
            var me=this;
            //事件
            var html_node     = $(me.select_html);

            me.$tbody=html_node.find("#id_body");
            var $th_list=html_node.find("#id_th_list");

            $.each( me.options.field_list ,function(i,field_item){
                var width_css="";
                if (field_item.width  ) {
                    width_css="width:"+field_item.width+"px;";
                }
                $th_list.append(  "<th style=\""+width_css+"\">"+ field_item.title + "</th>");
            });

            $th_list.append(  "<th style=\"width:70px\">操作</th>");
            me.reset_body();

            var dlg=BootstrapDialog.show({
                title: '列表',
                message : html_node,
                closable: true,
                onhide: function(dialogRef){
                },

              buttons: [
                    {
                    label: '增加',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                            me.options.onAdd( function(item ){
                                me.data_list.push(item);
                                me.reset_body();
                            });
                    }
                }, {
                    label: '返回',
                    action: function(dialog) {

                            BootstrapDialog.confirm("要关闭?",function(val){
                                if (val) {
                              dialog.close();
                                }
                            });
                    }
                  }, {
                    label: '完成',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                            if (me.options.onChange ){
                                me.options.onChange(me.data_list ,dlg);
                            }
                            dialog.close();

                            /*
                             var select_id=me.select_id;
                             if (!(select_id>0)){
                             select_id=-1;
                             //BootstrapDialog.alert("还没选择");
                             //return;
                             }
                             me.set_id(select_id,dialog);
                             */
                    }
                  }]
            });
            dlg.getModalDialog().find( ".modal-footer").css({
                "margin-top":"0px"
            });
        }
    };

    //在插件中使用对象
    $.fn.admin_select_dlg_edit = function(options) {
        //创建的实体
        var select_dlg_edit  = new Cselect_dlg_edit(this, options);
        //调用其方法
        select_dlg_edit.bind();
        select_dlg_edit.show_select();
        return  select_dlg_edit;
    };
})(jQuery, window, document);
