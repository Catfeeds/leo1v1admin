//admin_set_select_field
(function($, window, document,undefined) {
    //定义构造函数
    var Cset_select_dlg= function($field_ele, $td_ele, opt) {
        this.defaults = {
            "header_list":["id","属性"] ,
            "onChange":null,
            "select_list":[],
            "multi_selection":false,
            "btn_list":[]
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


        //
    };


    //定义方法
    Cset_select_dlg.prototype = {

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
            var html_node     = $(me.select_html);

            var $tbody=html_node.find("#id_body");
            var $th_list=html_node.find("#id_th_list");
            $.each( me.options.header_list,function(i,item){
                $th_list.append( "<th>"+item+" </th>");
            });

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
                if (($.inArray(cur_id, me.options.select_list ) !==-1) || ($.inArray(parseInt(cur_id), me.options.select_list ) !==-1)  ) {
                    class_str="warning";
                }

                $tbody.append("<tr class=\""+class_str+"\" data-id=\""+cur_id+"\"  >" + td_list_str+ "</tr>");
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
                        if( me.options.multi_selection ) {
                            var select_item_list=$tbody.find("tr.warning");
                            var select_list=[];
                            select_item_list.each(function(){
                                select_list.push($(this).data("id") );
                            });
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
        }
    };

    //在插件中使用对象
    $.fn.admin_set_select_field = function(options) {
        //创建的实体
        var select_dlg  = new Cset_select_dlg(this, options);
        //调用其方法
        select_dlg.bind();
        select_dlg.show_select();
        return  select_dlg;
    };
})(jQuery, window, document);
