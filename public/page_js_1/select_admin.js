(function($, window, document,undefined) {
    //定义构造函数
    var Cselect_user= function(ele, opt) {
        this.$element = ele;
        this.defaults = {
            'show_select_flag':false,
            "onChange":null
        };
        var me=this;

        this.select_html= 
            '<div><div class="row">'+
            '                <div class="col-xs-6 col-md-6">'+
            '                    <div class="input-group ">'+
            '                        <input   id="id_name_phone" placeholder="姓名/电话" />'+
            '                    </div>'+
            '                </div>'+
            '            </div>'+
            '          '+
            '            <hr style=" margin-top: 7px; margin-bottom: 7px;"/>'+
            '            <table class="table table-bordered "   >'+
            '                <tr>   <th> 用户ID <th> 用户名 <th> 名字 <th> 电话 </tr>'+
            '                    <tbody id="id_body">'+
            '                    </tbody>'+
            '            </table>' +
            '           <div id="id_page_info"> '+
            '           </div> </div> ' ;

        this.$element.attr("readonly","readonly");
        this.options = $.extend({}, this.defaults, opt);
        //return val
        me.select_id=0;

        if( !this.options.show_select_flag  ) {
            this.$show_input = $(this.$element[0].outerHTML);
            //清除id
            this.$show_input.attr("id","");
            this.$show_input.insertAfter(this.$element );
        }

        if( !this.options.show_select_flag  ) {
            this.$element.hide();
        }

    };

    //定义方法
    Cselect_user.prototype = {
        bind: function() {
            var me=this;
            //this.$element.hide();
            var type=this.options.type;
            if( !this.options.show_select_flag  ) {
                this.$show_input.on("click", function(){
                    me.show_select();
                });
            }
        }
        ,add_user_grp:function(acc){
            var me = this;
            var account = acc;
            var groupid = $(".danger.auth_grp").data("groupid");
            $.ajax({
                url: '/authority/add_manager_to_grp',
                type: 'POST',
                data: {'groupid':groupid,'account':account},
                dataType: 'json',
                success: function(result) {
                    window.location.href= window.location.pathname +"?groupid="+groupid;
		        }
            }); 
        }
        ,show_select:function(){
            var me=this;
            //事件
            var html_node     = $(me.select_html);
            me.$id_name_phone = html_node.find("#id_name_phone");
            me.$id_page_info  = html_node.find("#id_page_info");
            me.$id_body       = html_node.find("#id_body");
 	        me.$id_name_phone.on ("keypress", function(e){
		        if (e.keyCode==13){
                    me.reload_data(me.$id_name_phone.val());
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
                        var account   = me.account;
                        me.add_user_grp(account);
                        dialog.close();
		            }
	            }]
            }); 
            //加载数据
            me.reload_data("",1);
        }
        ,reload_data:function(nick_phone,page_num,url){
            var me=this;
            var data=null;
            var manage_str = "";
            $("#id_grp_member tr").each(function(){
                manage_str += $(this).children(".user_account").text() + ",";
            });
            manage_str = manage_str.substr(0, manage_str.length-1);

            if (!url){
			    url = "/authority/get_admin_list";
                data={
                    "manage_str" : manage_str, 
                    "nick_phone" : nick_phone, 
                    "page_num"   : page_num
                };
            }
		    $.ajax({
			    type     :"post",
			    url      :url,
			    dataType :"json",
			    data     : data,
			    success  : function(result){
                    var ret_list      = result.data.list;
                    var ret_page_info = result.data.page_info;
                    var html_str="";
                    $.each( ret_list, function(i,item){
                        html_str+="<tr data-account="+item.account+" data-id="+item.uid+">"
                            + "<td>" + item.uid
                            + "<td>" + item.account
                            + "<td>" + item.name
                            + "<td>" + item.phone
                            + "</tr>";
                    });
                    me.select_id=0;
                    me.$id_body.html(html_str);
                    html_str=get_page_node(ret_page_info ,function(url ){
                        me.reload_data(0,1,url);
                    });
                    me.$id_page_info.html(html_str);
                    me.$id_body.find("tr").on("click",function(){
                        if ( $(this).hasClass("warning") ){
                            me.$id_body.find("tr").removeClass("warning");
                            me.select_id = -1;
                        }else{
                            me.$id_body.find("tr").removeClass("warning");
                            $(this).addClass("warning");
                            me.select_id = $(this).data("id");
                            me.account=$(this).data("account");
                        }
                    });
			    }
            });
        }
    };

    //在插件中使用对象
    $.fn.admin_select_admin = function(options) {
        //创建的实体
        var select_user  = new Cselect_user(this, options);
        //调用其方法
        select_user.bind();
        if(select_user.options.show_select_flag ){
            select_user.show_select();
        }
        return  select_user;
    };
})(jQuery, window, document);
