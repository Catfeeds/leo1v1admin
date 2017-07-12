;(function($, window, document,undefined) {
    //定义构造函数
    var Cquestion_editor = function(ele, opt) {
        this.$element = ele;
        this.defaults = {
            'can_add_question_flag': true,
            "qiniu_upload_domain_url":"",
            "auto_reformat_flag" : true,
            "check_type" : "check",
            "onSave":null
        };

        var me=this;
        this.options     = $.extend({}, this.defaults, opt);
        this.html_node=$( 
            '<div>    <div class="row">'+
                ''+
                ''+
                '        <div class="col-xs-6 col-md-6">'+
                '            <div class="input-group ">'+
                ''+
                '                <div class="input-group-btn">'+
                '                    <button class="btn btn-warning " id="id_opt_type_e"  > <li class="fa   fa-exchange"  ></li><span>新增</span></button>'+
                '                <select class=" form-control " id="id_reformat_flag" style="width:60px" >'+
                '                <option value=1>自动格式化</option>'+
                '                <option value=0>不自动格式化</option>'+
                '                </select>'+

                '                </div>'+
                ''+
                ''+
                '                <span class="input-group-addon" style="padding-left: 3px; padding-right: 6px;" >年级</span>'+
                '                <select class=" form-control " id="id_grade_e" >'+
                '                </select>'+
                ''+
                '                <span class="input-group-addon"   style="padding-left: 3px; padding-right: 6px;" >科目</span>'+
                '                <select class=" form-control " id="id_subject_e" >'+
                '                </select>'+
                ''+
                ''+
                ''+
                ''+
                '                <span class="input-group-addon"  style="padding-left: 3px; padding-right: 6px;">类型</span>'+
                '                <select class=" form-control " id="id_question_type_e"  >'+
                '                </select>'+
                ''+
                '                <span class="input-group-addon"  style="padding-left: 3px; padding-right: 6px;">难度</span>'+
                '                <select class=" form-control " id="id_difficulty_e"  >'+
                '                </select>'+
                ''+
                ''+
                ''+
                '            </div>'+
                ''+
                '        </div>'+
                ''+
                ''+
                '        <div class="col-xs-6 col-md-2">'+
                '            <div class="input-group ">'+
                '                <div class=" input-group-btn " >'+
                '                    <button id="id_note_e" type="submit"  class="btn btn-primary "><i class="fa fa-plus"></i>知识点</button><span id="id_note_list_e" data-note_id_list="">  </span>'+
                '                </div>'+
                '            </div>'+
                '        </div>'+
                '        <div class="col-xs-6 col-md-4">'+
                '            <div class="input-group ">'+
                '                <div class="input-group-btn">'+

                '                    <button  style="display:none;" class="btn btn-warning " id="id_opt_question_type"> <li class="fa   fa-exchange"  ><span>答案</span> </li></button>'+
                '                </div>'+
                '                    <span class="input-group-addon opt-a-select" style="padding-left: 3px; padding-right: 5px;">答案</span>'+
                '                    <select  id="id_a_select_e" class="opt-a-select"  >'+
                '                        <option value="A" >A</option>'+
                '                        <option value="B" >B</option>'+
                '                        <option value="C" >C</option>'+
                '                        <option value="D" >D</option>'+
                '                    </select>'+
                '                <div class=" input-group-btn " style="text-align:center">'+
                '                    <button id="id_submit_question_e"  type="submit"  class="btn  btn-warning"  data-check_flag="1"  >提交</button>'+

                '                    <button id="id_submit_nopass_e_del" type="submit"  class="btn  btn-danger" data-check_flag="6" title="不通过,不入库, 扣100% "   >  <li class="fa  fa-exclamation-triangle "  /></button>'+
                '                    <button id="id_submit_nopass_e_10" type="submit"  class="btn  btn-warning" data-check_flag="5" title="不通过,并修复提交 扣100% "   >  <li class="fa  fa-exclamation-triangle "  />-10</button>'+
                '                    <button id="id_submit_nopass_e_5" type="submit"  class="btn  btn-warning" data-check_flag="4" title="不通过,并修复提交 扣50%"   >  <li class="fa  fa-exclamation-triangle "  />-5</button>'+
                '                    <button id="id_submit_nopass_e_1" type="submit"  class="btn  btn-warning" data-check_flag="3" title="不通过,并修复提交 扣10%"   >  <li class="fa  fa-exclamation-triangle "  />-1</button>'+
                '                    <button id="id_submit_pass_e" type="submit"  class="btn  btn-success" data-check_flag="2"  title="通过" > <li class="fa   fa-check"  /></button>'+
                '                </div>'+
                '            </div>'+
                '        </div>'+
                '    </div>'+
                '    <div style="margin-top:5px" id="id_edit_content_e" > </div>'+
                '    <div style="margin-top:5px" id="id_edit_answer_e" ></div>'+
                ' </div>'

        );



        if (this.options.auto_reformat_flag ) {
            this.html_node.find("#id_reformat_flag").val(   getCookie("reformat_flag")  );
        }else{
            this.html_node.find("#id_reformat_flag").val(0);
        }

        this.html_node.find("#id_reformat_flag").on("change",function(){
            setCookie( "reformat_flag", $(this).val());
        });

        me.get_qa_info =function(){
	        var $id_a_select_e= me.html_node.find("#id_a_select_e");
            var content_type=$id_a_select_e.data('type');
            var content=$.trim(me.mathjax_editor.val() );
            var question_type= me.html_node.find("#id_question_type_e"   ).val();
            var q="";
            var a="";
            if (content_type=="q"){
                q= content ;
                
               // for old: a=  $id_a_select_e.data("a"); 
               // a = 
                if (question_type ==1 ) { //select
                    var a_select_v_new= $id_a_select_e.val();
                    if (a_select_v_new.length != 1 ){
                        a_select_v_new="N";
                    }
                    var a_desc_new= $.trim(me.answer_mathjax_editor.val() );  
                    if (!a_desc_new){
                        a_desc_new="";
                    }
                    a=  a_select_v_new + ":"+ a_desc_new; 
                }else{
                    a= $.trim(me.answer_mathjax_editor.val() );  
                }

            }else{
                q=  $id_a_select_e.data("q");

                if (question_type ==1 ) {
                    var a_select_v= $id_a_select_e.val();
                    if (a_select_v.length != 1 ) {
                        a_select_v="N";
                    }
                    var a_desc= content; 
                    if (!a_desc){
                        a_desc="";
                    }
                    a=  a_select_v + ":"+ a_desc; 
                }else{
                    a=  content; 
                }

            }
            

            return {
                "questionid":me.html_node.find("#id_submit_question_e" ).data("id"),
                "q":q,
                "a":a,
                "question_type":question_type,
                "note_str": me.html_node.find("#id_note_list_e").text() ,
                "grade_str": me.html_node.find("#id_grade_e").select_get_text() ,
                "subject_str": me.html_node.find("#id_subject_e").select_get_text() ,
                "difficulty_str": me.html_node.find("#id_difficulty_e").select_get_text() ,
                "question_type_str": me.html_node.find("#id_question_type_e").select_get_text() 

            };

        };
        


        this.mathjax_editor= this.html_node.find("#id_edit_content_e").admin_mathjax_editor({
            "qiniu_upload_domain_url":this.options.qiniu_upload_domain_url,
            "title" : "问题:",
            "onUpdate":function( data) {
               var ret=me.get_qa_info();
                if (ret.questionid) {
		            $.ajax({
			            type     :"post",
			            url      :"/question/question_noti_update",
			            dataType :"json",
			            data     : ret,
			            success  : function(result){
                        }
		            });
                }
            }
        });

        this.answer_mathjax_editor= this.html_node.find("#id_edit_answer_e").admin_mathjax_editor({
            "qiniu_upload_domain_url":this.options.qiniu_upload_domain_url,
            "title" : "答案:",
            "onUpdate":function( data) {
               var ret=me.get_qa_info();
                if (ret.questionid) {
 		            $.ajax({
			            type     :"post",
			            url      :"/question/question_noti_update",
			            dataType :"json",
			            data     : ret,
			            success             : function(result){
                        }
		            });
                }
            }
        });

        this.answer_mathjax_editor.type("text");

        if ( me.options.can_add_question_flag ) {
            this.html_node.find("#id_submit_nopass_e_1").hide();
            this.html_node.find("#id_reformat_flag").hide();
            this.html_node.find("#id_submit_nopass_e_5").hide();
            this.html_node.find("#id_submit_nopass_e_10").hide();
            this.html_node.find("#id_submit_nopass_e_del").hide();
            this.html_node.find("#id_submit_pass_e").hide();
        }else{
            this.html_node.find("#id_submit_question_e").hide();
            this.html_node.find("#id_opt_type_e").hide();
        }

        Enum_map.append_option_list("question_grade", this.html_node.find("#id_grade_e"),true);
        Enum_map.append_option_list("subject", this.html_node.find("#id_subject_e"),true);
        Enum_map.append_option_list("question_type", this.html_node.find("#id_question_type_e"),true);
        Enum_map.append_option_list("question_difficulty", this.html_node.find("#id_difficulty_e"),true);


        this.$element.html(this.html_node);



        //
    };

    //定义方法
    Cquestion_editor.prototype = {
        initData:function(g_args){
           var me=this; 
	        //init  input data
	        me.html_node.find("#id_grade_e"         ).val(g_args.grade);
	        me.html_node.find("#id_subject_e"   ).val(g_args.subject);
	        me.html_node.find("#id_question_type_e" ).val(g_args.question_type);
	        me.html_node.find("#id_note_e"          ).val(g_args.note);
	        me.html_node.find("#id_difficulty_e"    ).val(g_args.difficulty);
            
            me.on_question_type_change(g_args.question_type );

        },
        bind: function() {
            var me=this;
            this.bind_note();

            var set_active_item_ex=function(id, value, text  ,css_class ,title) {
                var $id=$(id);
                $id.removeClass("btn-primary");
                $id.removeClass("btn-warning");
                $id.find("span").text(text);
                $id.addClass( css_class);
                $id.attr( "title",  title);
                $id.data("value", value);
            };

            var set_active_item_opt_type=function( value ){
                var opt_config={
                    "add":{ text : "新增", "css_class": "btn-primary" ,title:"" } ,
                    "update":{ text : "修改", "css_class": "btn-warning", title:"点击切换到'新增'"} 
                };
                var conf=opt_config[value];

                me.html_node.find("#id_submit_question_e").data("opt_type", value );
                set_active_item_ex("#id_opt_type_e", value, conf.text, conf.css_class,conf.title  );
            };
            var set_active_item_content=function( value, question_type ){
                var opt_config={
                    "q":{ text : "问题", "css_class": "btn-primary" ,title:"点击切换到'答案'" } ,
                    "a":{ text : "答案", "css_class": "btn-warning", title:"点击切换到'问题'"} 
                };
                if ( question_type == 1 ){
                    opt_config={
                        "q":{ text : "问题", "css_class": "btn-primary" ,title:"点击切换到'答案'" } ,
                        "a":{ text : "解析", "css_class": "btn-warning", title:"点击切换到'解析'"} 
                    };
                }
                var conf=opt_config[value];
                var $id_a_select_e=me.html_node.find("#id_a_select_e");
                var set_flag=false;
                if (value=="q" &&  $id_a_select_e.data("type") =="a" ){
                    $id_a_select_e.data("a", me.mathjax_editor.val());
                    me.mathjax_editor.val( $id_a_select_e.data("q") );
                    set_flag=true;
                }
                else if (value=="a" &&  $id_a_select_e.data("type") =="q" ){
                    $id_a_select_e.data("q", me.mathjax_editor.val());
                    me.mathjax_editor.val( $id_a_select_e.data("a") );
                    set_flag=true;
                }else{

                }


                $id_a_select_e.data("type",value );
                set_active_item_ex("#id_opt_question_type", value, conf.text, conf.css_class,conf.title  );
            };

            set_active_item_opt_type("add" );
            set_active_item_content("q",1 );


            this.on_question_type_change=function(value){

                if (value==1){
                    //me.mathjax_editor.type("select");
                    //me.html_node.find("#id_opt_question_type").hide();
                    me.html_node.find(".opt-a-select").show();
                    
                }else{
                    //me.mathjax_editor.type("text");
                    //me.html_node.find("#id_opt_question_type").show();
                    me.html_node.find(".opt-a-select").hide();
                }

	            var $id_a_select_e= me.html_node.find("#id_a_select_e");
                opt_qa_content("q",  $id_a_select_e.data("q"), $id_a_select_e.data("a")  );
            };

            var opt_qa_content= function(type, q, a  ){

                var  reformat_flag= false;
                if (!me.options.can_add_question_flag ) {
                    if ( me.options.auto_reformat_flag  ) {
                        reformat_flag= getCookie("reformat_flag") ==1 ;
                    }
                }

                var question_type= me.html_node.find("#id_question_type_e" ).val();
                if (question_type==1) {
                    if (type=="a"){
                        me.mathjax_editor.type("text");
                    }else{
                        me.mathjax_editor.type("select");
                    }
                }else{
                    me.mathjax_editor.type("text");
                }
                
                if (reformat_flag  ) {
                    if (!a){
                        a="";
                    }
                    a=me.mathjax_editor.reset_latex_str_2(a);
                }

                me.html_node.find("#id_a_select_e" ).data("q",q).data("a",a).data("type",type);

                if (type=="q"){
	                me.mathjax_editor.val(q, reformat_flag );
	                me.answer_mathjax_editor.val(a );
                }else{
	                me.mathjax_editor.val(a);
                }
                
                set_active_item_content(type,
                                        me.html_node.find("#id_question_type_e" ).val()
                                       );
            };

            
            me.html_node.find("#id_opt_question_type" ).on ("click",function(){
	            var $id_a_select_e= me.html_node.find("#id_a_select_e");
                var content_type=$id_a_select_e.data('type');
                var content=$.trim(me.mathjax_editor.val() );
                if (content_type=="q"){
                    $id_a_select_e.data("q", content) ;
                    opt_qa_content("a",  $id_a_select_e.data("q"), $id_a_select_e.data("a")  );
                }else{
                    $id_a_select_e.data("a", content) ;
                    opt_qa_content("q",  $id_a_select_e.data("q"), $id_a_select_e.data("a")  );
                }

            });
            

            me.html_node.find("#id_question_type_e").on("change", function(){
                me.on_question_type_change( me.html_node.find("#id_question_type_e").val());
            });

            me.set_to_add=function() {
	            //
                set_active_item_opt_type("add");
                opt_qa_content("q","","" );
	            me.html_node.find("#id_note_list_e"  ).data("note_id_list","" );
                me.show_note_name_list();
            };

            me.html_node.find("#id_opt_type_e").on("click",function(){
                var value=$(this).data("value");
                if (value=="update"){
                    me.set_to_add();
                }else{
                    alert("请从列表中选择题目进行修改") ;
                }

            });

            this.get_note_name_list=function(note_id_arr_str ){
                if (!note_id_arr_str ){
                    note_id_arr_str ="";
                }

                var note_id_arr= note_id_arr_str.split(",");
                var note_name_list_str= "";
                $.each(note_id_arr,function(i,item){
                    var note_id=$.trim(item);
                    if (note_id!=""){
                        note_name_list_str+= g_note_name_map[note_id]+"," ;
                    }
                });
                return note_name_list_str;
            };

            me.show_note_name_list = function(){

                var note_name_list_str = me.get_note_name_list( me.html_node.find("#id_note_list_e").data("note_id_list"));
                me.html_node.find("#id_note_list_e").text(note_name_list_str );
            };

            me.opt_question=function( check_flag,questionid) {
                
                if ( questionid ) {
                    if (me.html_node.find("#id_submit_question_e" ).data("id") != questionid )  {
                        alert("questionid="+questionid+"出错" +
                              me.html_node.find("#id_submit_question_e" ).data("id")
                             );
                        return;
                    }
                }

	            var $id_a_select_e= me.html_node.find("#id_a_select_e");
                var content_type=$id_a_select_e.data('type');
                var content=$.trim(me.mathjax_editor.val() );
                if (content_type=="q"){
                    $id_a_select_e.data("q", content) ;
                    $id_a_select_e.data("a",me.answer_mathjax_editor.val()  ) ;
                }else{
                    $id_a_select_e.data("a", content) ;
                }
                var q=  $id_a_select_e.data("q");

                if( q=="" ){
                    alert("题目不能为空");
                    return;
                }

                var question_type= me.html_node.find("#id_question_type_e").val();
                var a="";
                if (question_type ==1 ) {
                    var a_select_v= $id_a_select_e.val();
                    if (a_select_v.length != 1 ) {
                        alert( "答案不能为空") ;
                        return ;
                    }
                    var a_desc= $id_a_select_e.data("a");
                    if (!a_desc){
                        a_desc="";
                    }
                    a=  a_select_v + ":"+ a_desc; 
                }else{
                    a=  $id_a_select_e.data("a");
                }
                var note   = me.html_node.find("#id_note_list_e").data("note_id_list");
                var noteid = note.split(",");

                $.ajax({
			        type     :"post",
			        url      :"/question/add_or_update",
			        dataType :"json",
			        data     :{
                        "opt_type"      : me.html_node.find("#id_submit_question_e").data("opt_type"),
                        "id"            : me.html_node.find("#id_submit_question_e").data("id"),
                        "grade"         : me.html_node.find("#id_grade_e").val(),
                        "subject"       : me.html_node.find("#id_subject_e").val(),
                        "question_type" : question_type,
                        "note"          : note,
                        "noteid"        : noteid[1],
                        "difficulty"    : me.html_node.find("#id_difficulty_e").val(),
                        "q"             : q,
                        "a"             : a,
                        "check_flag"    : check_flag 
                    },
			        success  : function(result){
                        if (me.options.onSave){
                            me.options.onSave(me.html_node.find("#id_submit_question_e").data("opt_type"),result.id);
                        }
                    }
		        });
            };

            me.html_node.find("#id_submit_question_e , #id_submit_pass_e, #id_submit_nopass_e_1, #id_submit_nopass_e_5, #id_submit_nopass_e_10, #id_submit_nopass_e_del"  ).on("click",function(){

                var check_flag =$(this).data("check_flag");
                if (me.options.check_type=="check2" ) {
                    check_flag+=10;
                }


                if (!me.options.can_add_question_flag
                    && me.html_node.find("#id_submit_question_e" ).data("opt_type")=="add") {
                       alert("不能新增 ! 请选择题目修改后提交");
                       return;
                }
                //check
                if (check_flag >= 2) {
                    if (!confirm( $(this).attr("title")+',是吗?')){
                        return; 
                    }
                }
                me.opt_question( check_flag);
                //this.options.qiniu_upload_domain_url
            });

            this.edit_question=function( questionid){
	            //
		        $.ajax({
			        type     :"post",
			        url      :"/question/get_record",
			        dataType :"json",
			        data     :{
                        "questionid":questionid
                    },
			        success  : function(result){
                        var row=result.data;

                        me.html_node.find("#id_submit_question_e").data("id", questionid );
                        console.log("curid:"+questionid );

	                    me.html_node.find("#id_grade_e").val(row.grade);
	                    me.html_node.find("#id_subject_e").val(row.subject);
	                    me.html_node.find("#id_question_type_e").val(row.question_type);
                        //id_a_select_e

                        var a="";
                        if ( row.question_type==1 ){
                            me.mathjax_editor.type("select" );
                            var tmp_ret=select_a_split(row.a );
	                        me.html_node.find("#id_a_select_e" ).val(tmp_ret.a);
                            a=tmp_ret.desc;
                        }else{
                            a=row.a;
                            me.mathjax_editor.type("text" );
                        }
	                    me.html_node.find("#id_note_list_e"  ).data("note_id_list",row.note );
                        me.show_note_name_list();

	                    me.html_node.find("#id_difficulty_e").val(row.difficulty);
                        me.mathjax_editor.stop_preview_flag =true;
                        me.answer_mathjax_editor.stop_preview_flag =true;
                        opt_qa_content("q",row.q, a );
                        me.on_question_type_change( me.html_node.find("#id_question_type_e").val());
                        me.answer_mathjax_editor.stop_preview_flag =false;
                        me.mathjax_editor.stop_preview_flag =false;

                        //console.log("=====XXX:"+ me.answer_mathjax_editor.val() );
                        me.answer_mathjax_editor.preview_update();
                        me.mathjax_editor.preview_update(true );
                        set_active_item_opt_type("update");
                    }
		        });
            };
            me.on_question_type_change( me.html_node.find("#id_question_type_e").val());
        },


        bind_note: function() {
            var me=this;
            this.html_node.find("#id_note_e").on("click",function(){
                var show_note_dlg = function( result){
                    var col       = 4 ;
                    var main_list = result.data;
                    var all_count = main_list.length;
                    var per_count = Math.ceil(all_count/col);
                    var cur_page  = 0;

                    var note_html_node=$(
                            '            <div  id="id_sidebar_menu" >'+
                            '                <div class="row">'+
                            '                    <div class="col-xs-6 col-md-3">'+
                            '                        <section  class="sidebar" >'+
                            '                            <ul class="sidebar-menu">'+
                            '                            </ul>'+
                            '                        </section>'+
                            ''+
                            ''+
                            '                    </div>'+
                            '                    <div class="col-xs-6 col-md-3">'+
                            '                        <section  class="sidebar" >'+
                            '                            <ul class="sidebar-menu">'+
                            '                            </ul>'+
                            '                        </section>'+
                            ''+
                            ''+
                            '                    </div>'+
                            '                    <div class="col-xs-6 col-md-3">'+
                            '                        <section  class="sidebar" >'+
                            '                            <ul class="sidebar-menu">'+
                            '                            </ul>'+
                            '                        </section>'+
                            ''+
                            ''+
                            ''+
                            '                    </div>'+
                            '                    <div class="col-xs-6 col-md-3" >'+
                            '                        <section  class="sidebar" >'+
                            '                            <ul class="sidebar-menu">'+
                            '                            </ul>'+
                            '                        </section>'+
                            '                    </div>'+
                            '                </div>'+
                            ''+
                            '                <div class="row "  >'+
                            '                    <div class="col-xs-12 col-md-12 selected-list" style="text-align:center;" >'+
                            ''+
                            '                    </div>'+
                            '                </div>'+
                            '            </div>');
                    var col_list=note_html_node.find( ".sidebar-menu" );
                    var selected_list_node =note_html_node.find(".selected-list" );

                    $.each(main_list,function(i,item){
                        cur_page =  Math.floor(   i/  per_count );
                        
                        var main_name= item.name;
                        var main_node_html="<li class=\"treeview\">"
                            +"<a href=\"#\">"
                            +"<i class=\"fa fa-bar-chart-o\"></i>"
                            +"<span>  "+main_name +"</span>"
                            +"<i class=\"fa fa-angle-left pull-right\"></i>"
                            +"</a>"
                            +"<ul class=\"treeview-menu\">";
                        $.each( item.list ,function (i_note, item_note ){

                            main_node_html+="<li class=\"treeview\">"
                                +"<a href=\"#\">"
                                +" <i class=\"fa fa-angle-double-right\"></i></i>"
                                +"<span>  "+item_note.name+"</span>"
                                +"<i class=\"fa fa-angle-left pull-right\"></i>"
                                +"</a>"
                                +"<ul class=\"treeview-menu\">";

                            $.each(  item_note.list ,function (i_note_2, item_note_2 ){
                                main_node_html+= "<li><a style=\"cursor: pointer;\"  class=\"opt-note-item\" data-note_id=\""+item_note_2[0] +"\" ><i class=\"fa fa-angle-double-right \"></i><i class=\"fa fa-tag \"></i>"+item_note_2[1]+"</a></li>";
                            });
                            main_node_html +="</ul>" +"</li>";
                            
                        });

                        main_node_html +="</ul>" +"</li>";

                        $(col_list[cur_page]).append(main_node_html);
                        
                    });

                    var add_note=function( note_id,note_name ){

                        var btn=$("<button  data-note_id=\""+note_id+"\"   style=\"margin-left:10px;\" class=\"btn btn-warning note_"+note_id+"\">"+note_name+"<i class=\"fa   fa-times \"></i></button>");
                        btn.on("click", function(){
                            $(this).remove();
                        });
                        selected_list_node.append( btn);
                    };

                    var note_id_arr=$("#id_note_list_e").data("note_id_list").split(",");
                    $.each(note_id_arr,function(i,item){
                        var note_id=$.trim(item);
                        if (note_id!=""){
                            add_note ( note_id ,g_note_name_map[note_id] );
                        }
                    });

                    note_html_node.find( ".treeview > a").on("click",function(e) {
                        var btn=$(this);
                        var isActive =btn.data("isActive");
                        e.preventDefault();
                        if (isActive) {
                            btn.data("isActive",false);
                            btn.children(".fa-angle-down").first().removeClass("fa-angle-down").addClass("fa-angle-left");
                            //btn.parent("li").removeClass("active");
                            btn.parent("li").find(">ul ").hide();
                        } else {
                            //Slide down to open menu
                            isActive = true;
                            btn.data("isActive",true);
                            btn.children(".fa-angle-left").first().removeClass("fa-angle-left").addClass("fa-angle-down");
                            //btn.parent("li").addClass("active");
                            btn.parent("li").find(">ul ").show();
                        }
                    });


                    note_html_node.find( ".treeview    > ul  a").on("click",function(e) {
                        var note_id=$(this).data("note_id" );
                        var note_name= $(this).text();
                        if (note_id){
                            if(selected_list_node.find( ".note_"+note_id ).length ==0 ){
                                add_note ( note_id,note_name );
                            };
                        }
                    });
                    //add selected_list_node 


                    var dlg=BootstrapDialog.show({
                        title: '选择知识点',
                        message :  note_html_node,
                        closable: true, 
                        buttons: [{
                            label: '确认',
                            cssClass: 'btn-primary',
                            action: function(dialog) {
                                var note_id_list_str=",";
                                var note_name_list_str="";
                                $.each(   selected_list_node.find("button"), function(i,item){
                                    note_id_list_str+=$.trim($(item).data("note_id"))+",";
                                });
                                $("#id_note_list_e").data("note_id_list", note_id_list_str);

                                me.show_note_name_list();

                                dialog.close();
                            }
                        }, {
                            label: '取消',
                            cssClass: 'btn',
                            action: function(dialog) {
                                dialog.close();
                            }
                        }]
                    });
                    dlg.getModalDialog().css("width","1100px");
                    dlg.getModalDialog().css("margin-top","10px");
                    dlg.getModalDialog().find(".modal-header").hide();
                    dlg.getModalDialog().find(".modal-body").css("padding","8px");
                    dlg.getModalDialog().find(".modal-footer").css("padding","8px");
                    dlg.getModalDialog().find(".modal-footer").css("margin-top","0px");
                    dlg.getModalDialog().find(".btn").css("margin-right","30px");
                };
	            //
		        $.ajax({
			        type     : "post",
			        url      : "/question/get_note_list",
			        dataType : "json",
			        data     : {
                        "grade"   : $("#id_grade_e").val(),
                        "subject" : $("#id_subject_e").val()
                    },
			        success :  show_note_dlg

		        });

            });


        }

    };

    //在插件中使用对象
    $.fn.admin_question_editor  = function(options) {
        //创建的实体
        var item = new Cquestion_editor  (this, options);
        //调用其方法
        item.bind();

        return item;
    };

})(jQuery, window, document);
