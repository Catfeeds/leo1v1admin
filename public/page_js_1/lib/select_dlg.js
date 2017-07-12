(function($, window, document,undefined) {
    //定义构造函数
    var Cselect_dlg= function(ele, opt) {
        this.$element = ele;
        this.defaults = {
            'data_list': [],
            "header_list":["id","属性"] ,
            "onChange":null
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
            var html_node     = $(me.select_html);

            var $tbody=html_node.find("#id_body");
            var $th_list=html_node.find("#id_th_list");
            $.each( me.options.header_list,function(i,item){
                $th_list.append( "<th>"+item+" </th>");
            });

            $.each( me.options.data_list,function(i,row_item){
                var td_list_str="";
                var cur_id=-1;
                $.each(  row_item,function(j,item){
                    if((cur_id==-1) ) {
                        cur_id=item; 
                    }
                    td_list_str+="<td>"+item+"</td>";
                });

                $tbody.append("<tr data-id=\""+cur_id+"\"  >" + td_list_str+ "</tr>");
            });

            $tbody.find("tr").on("click",function(){
                if ( $(this).hasClass("warning") ){
                    $tbody.find("tr").removeClass("warning");
                    me.select_id=-1;
                }else{
                    $tbody.find("tr").removeClass("warning");
                    $(this).addClass("warning");
                    me.select_id=$(this).data("id");
                }
            });


            var dlg=BootstrapDialog.show({
                title: '选择',
                message : html_node,
                closable: true, 
                onhide: function(dialogRef){
                },

	            buttons: [{
		            label: '返回',
		            action: function(dialog) {
			            dialog.close();
		            }
	            }, {
		            label: '完成',
		            cssClass: 'btn-warning',
		            action: function(dialog) {
                        var select_id=me.select_id;
                        if (!(select_id>0)){
                            select_id=-1;
                            //BootstrapDialog.alert("还没选择");
                            //return;
                        }
                        me.set_id(select_id,dialog);
		            }
	            }]
            }); 
            dlg.getModalDialog().find( ".modal-footer").css({
                "margin-top":"0px"
            });
        }
    };

    //在插件中使用对象
    $.fn.admin_select_dlg = function(options) {
        //创建的实体
        var select_dlg  = new Cselect_dlg(this, options);
        //调用其方法
        select_dlg.bind();
        select_dlg.show_select();
        return  select_dlg;
    };
})(jQuery, window, document);
