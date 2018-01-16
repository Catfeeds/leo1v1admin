(function($, window, document,undefined) {
    //定义构造函数
    var Cselect_user = function(ele, opt) {
        this.$element = ele;
        this.defaults = {
            'type': 'teacher',
            'value': 0 ,
            'show_select_flag':false,
            "auto_close": true,
            "onChange":null
        };
        var me = this;

        this.select_html= 
            '<div>            <div class="row">'+
            '                <div class="col-xs-6 col-md-3">'+
            '                    <div class="input-group ">'+
            '                        <span>性别</span>'+
            '                        <select id="id_gender" >'+
            '                            <option value="-1" > 不限 </option>  '+
            '                            <option  value="1" >男</option>  '+
            '                            <option  value="2" >女</option>  '+
            '                        </select>'+
            '                    </div>'+
            '                </div>'+
            '                <div class="col-xs-6 col-md-6">'+
            '                    <div class="input-group ">'+
            '                        <input   id="id_name_phone" placeholder="姓名/电话" />'+
            '                    </div>'+
            '                </div>'+
            '            </div>'+
            '          '+
            '            <hr style=" margin-top: 7px; margin-bottom: 7px;"/>'+
            '            <table   class="table table-bordered "   >'+
            '                <tr>   <th> 性别 <th>用户id <th>名字 <th> 电话 </tr>'+
            '                    <tbody id="id_body">'+
            '                    </tbody>'+
            '            </table>' +
            '           <div id="id_page_info"> '+
            '           </div> <div> ' ;

        //this.$element.attr("readonly","readonly");
        this.options     = $.extend({}, this.defaults, opt);
        //return val
        me.select_id=0;


        if( !this.options.show_select_flag  ) {
            this.$show_input = $(this.$element[0].outerHTML);
            //清除id
            this.$show_input.attr("id","");
            this.$show_input.css("cursor","inherit");
            this.$show_input.insertAfter(this.$element );

        }

        if( !this.options.show_select_flag  ) {
            this.$element.hide();
        }
        this.show_nick( this.$element.val() );
        //
    };

    //定义方法
    Cselect_user.prototype = {
        bind: function() {
            var me=this;
            //this.$element.hide();
            var type=this.options.type;
            

            if( !this.options.show_select_flag  ) {
                this.$show_input.on("click", function(){
                    me.show_select ();
                });
            }
        }
        ,show_nick:function(id){
            var me=this;
            if( !this.options.show_select_flag  ) {
                me.$show_input.val("");

                if (id == -1){
                    me.$show_input.val("[全部]");
                }else{
		            $.ajax({
			            type     : "post",
			            url      : "/user_manage/get_nick" ,
			            dataType : "json",
			            data : {
                            "type" : this.options.type 
                            ,"id"  : id 
                        },
			            success : function(result){
                            var nick = result.nick;
                            me.$show_input.val(nick);
                        }});
                }
            }
        }
        ,set_id:function(id,dlg){
            var me=this;
            me.$element.val(id);
            me.show_nick(id);
            if (me.options.onChange ){
                me.options.onChange(id,dlg);
            }
        }
        ,show_select:function(){
            var me=this;
            //事件
            var html_node     = $(me.select_html);
            me.$id_gender     = html_node.find("#id_gender");
            me.$id_name_phone = html_node.find("#id_name_phone");
            me.$id_page_info  = html_node.find("#id_page_info");
            me.$id_body       = html_node.find("#id_body");

            me.$id_gender.on("change",function(){
                me.reload_data( me.$id_gender.val() , me.$id_name_phone.val()  );
            });
	        me.$id_name_phone.on ("keypress", function( e){
		        if (e.keyCode==13){
                    me.reload_data( me.$id_gender.val() , me.$id_name_phone.val()  );
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
                        if (me.options.auto_close) {
			                dialog.close();
                        };
		            }
	            }]
            }); 
            dlg.getModalDialog().find( ".modal-footer").css({
                "margin-top":"0px"
            });
            //加载数据
            me.reload_data(-1,"",1);
        }
        ,reload_data:function(gender,nick_phone ,page_num,url){
            var me=this;
            var data=null;

            if (!url){
			    url     = "/user_manage/get_user_list";
                data={
                    "type"        : this.options.type 
                    ,"gender"     : gender
                    ,"nick_phone" : nick_phone 
                    ,"page_num"   : page_num
                };
            }

		    $.ajax({
			    type     : "post",
			    url      : url,
			    dataType : "json",
			    data     : data,
			    success  : function(result){
                    var ret_list      = result.data.list;
                    var ret_page_info = result.data.page_info;
                    var html_str      = "";
                    $.each( ret_list, function(i,item){
                        html_str+="<tr data-id="+item.id+"><td>"+item.gender
                            + "<td>" + item.id
                            + "<td>" + item.nick
                            + "<td>" + item.phone
                            + "</tr>";
                    });

                    me.select_id=0;
                    me.$id_body.html(html_str);

                    html_str=get_page_node(ret_page_info ,function(url ){
                        me.reload_data(-1,0,1,url);
                    });
                    me.$id_page_info.html(html_str);
                    me.$id_body.find("tr").on("click",function(){
                        if ( $(this).hasClass("warning") ){
                            me.$id_body.find("tr").removeClass("warning");
                            me.select_id=-1;
                        }else{
                            me.$id_body.find("tr").removeClass("warning");
                            $(this).addClass("warning");
                            me.select_id=$(this).data("id");
                        }
                    });
			    }
            });
        }
    };

    //在插件中使用对象
    $.fn.admin_select_user = function(options) {
        //创建的实体
        var select_user = new Cselect_user(this, options);
        //调用其方法
        select_user.bind();
        if(select_user.options.show_select_flag ){
            select_user.show_select();
        }
        return  select_user;
    };
})(jQuery, window, document);
