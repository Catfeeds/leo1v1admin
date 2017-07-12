;(function($, window, document,undefined) {
    //定义构造函数
    var Cselect_course= function(ele, opt) {
        this.$element = ele;
        this.defaults = {
            'value': 0 ,
            "onChange":null
        };
        var me=this;

        this.select_html= 
            '<div>            <div class="row">'+
            '                <div class=" col-md-6">'+
            '                    <div class="input-group ">'+
            '                        <span>老师</span>'+
            '                        <input   id="id_teacherid" placeholder="" />'+
            '                    </div>'+
            '                </div>'+
            '            </div>'+
            '          '+
            '            <hr style=" margin-top: 7px; margin-bottom: 7px;"/>'+
            '            <table   class="table table-bordered "   >'+
            '                <tr>   <th> 课程id <th>名称 <th> 老师  <th> 科目 <th>课次  </tr>'+
            '                    <tbody id="id_body">'+
            '                    </tbody>'+
            '            </table>' +
            '           <div id="id_page_info"> '+
            '           </div> <div> ' ;

        this.$element.attr("readonly","readonly");
        this.options     = $.extend({}, this.defaults, opt);
        this.$show_input = $(this.$element[0].outerHTML);

        me.select_id=0;

        //清除id
        this.$show_input.attr("id","");
        this.$show_input.insertAfter(this.$element );
        this.$element.hide();
        this.show_data( this.$element.val() );

        //
    };

    //定义方法
    Cselect_course.prototype = {
        bind: function() {
            var me=this;
            //this.$element.hide();
            var course_type=this.options.course_type;
            this.$show_input.on("click",function(){
                //事件
                var html_node     = $(me.select_html);
                me.$id_teacherid= html_node.find("#id_teacherid");
                me.$id_page_info  = html_node.find("#id_page_info");
                me.$id_body       = html_node.find("#id_body");

	            me.$id_teacherid.on ("keypress", function( e){
		            if (e.keyCode==13){
                        me.reload_data(  me.$id_teacherid.val()  );
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
                            }
                            
                            me.set_id(select_id);
			                dialog.close();
		                }
	                }]

                }); 
                dlg.getModalDialog().find( ".modal-footer").css({
                    "margin-top":"0px"
                });
                //加载数据
                me.reload_data(-1,1);
            });

        }
        ,show_data:function(id){
            var me=this;
            me.$show_input.val("");
            if (id == -1){
                me.$show_input.val("[全部]");
            }else{
		        $.ajax({
			        type : "post",
			        url      : "/course_manage/get_course_info" ,
			        dataType : "json",
			        data : {
                        "courseid"  : id 
                    },
			        success : function(result){
                        var data = result.data.course_name;
                        me.$show_input.val(data );
                    }});
            }

        }
        ,set_id:function(id){
            var me=this;
            me.$element.val(id);

            me.show_data(id);
            if (me.options.onChange ){
                me.options.onChange();
            }

        }

        ,reload_data:function(teacherid,page_num,url){
            var me=this;
            var data=null;

            if (!url){
			    url     = "/course_manage/get_filter_list";
                data = {
                    "course_type" : this.options.course_type 
                    ,"teacherid"  : teacherid 
                    ,"page_num"   : page_num
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
                        html_str+="<tr data-id="+item.courseid+"><td>"+item.courseid
                            + "<td>" + item.course_name
                            + "<td>" + item.nick
                            + "<td>" + item.subject
                            + "<td>" + item.lesson_total
                            + "</tr>";
                    });

                    me.select_id=0;
                    me.$id_body.html(html_str);

                    html_str=get_page_node(ret_page_info,function(url){
                        me.reload_data(-1,1,url);
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
    $.fn.admin_select_course = function(options) {
        //创建的实体
        var select_course  = new Cselect_course(this, options);
        //调用其方法
        
        
        return select_course.bind();
    };

})(jQuery, window, document);
