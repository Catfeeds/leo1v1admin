;(function($, window, document,undefined) {
    //定义构造数
    var Cmathjax_editor= function(ele, opt) {

        var me=this;
        this.$element = ele;
        this.defaults = {
            "qiniu_upload_domain_url":"",
            "type":"select" ,
            "subject":2,
            "height":400,
            "onUpdate":null
        };

        mathjax_init_once();

        this.options = $.extend({}, this.defaults, opt);
        
        this.stop_preview_flag=false;

        this.reset_latex_str_2=function(val){
           
            return me.reset_latex_str(me.reset_latex_str(val));
            //return me.reset_latex_str(val);
        };

        this.reset_latex_str=function(val){
            val= val
                .replace(/﹣/g, "-")
                .replace(/×/g, "\\times ")
                .replace(/÷/g, "\\div ")
                .replace(/∙/g, "\\cdot ")
                .replace(/•/g, "\\cdot ")
                .replace(/△/g, "\\triangle ")
                .replace(/⊙/g, "\\odot ")
                .replace(/°/g, "\\degree ")
                .replace(/∠/g, "\\angle ")
                .replace(/∵/g, "\\because ")
                .replace(/π/g, "\\pi ")
                .replace(/∴/g, "\\therefore ")
                .replace(/±/g, "\\pm ")
                .replace(/（/g, "(")
                .replace(/）/g, ")")
                .replace(/\n\n/g, "\n")
                .replace(/菁优网版权所有/g, "")
            ;

            //对图片特别处理 
            var arr=val.split( /(!\[\]\([^)]*\)|\(    \)|_______|\n\n|{\\rm{[^}]*}}|\s*\([ \t]*[0-9][0-9]?[ \t]*\))/ );
            var str="";
            $.each(arr, function(){
                var sub_str=this;

                if ( sub_str.substring(0,5) == "{\\rm{") { //space
                    var space_str=sub_str.replace(/.*{([^{}]*)}.*/, "$1");
                    if (space_str==sub_str){ //fail
                        
                    }else{
                        sub_str = space_str.replace(/ /g, "\\ " );
                    }
                } else if (sub_str.match(/^\s*\(\s*[0-9][0-9]?\s*\)$/) ){ //标号
                    //不处理

                } else if (sub_str.substring(0,4)!="![]\(" ){
                    sub_str= sub_str
                        .replace(/[ \t]*\\\][ \t]*/g, " ")
                        .replace(/[ \t]*\\\[[ \t]*/g, " ")
                        .replace(/[ \t]*\$[ \t]*/g, " ")
                        .replace(/([&\s{}\\\^_\|A-Za-z0-9.<>\t+=×*()\[\]-]*[A-Za-z0-9][&\s{}\\\^_\|A-Za-z0-9.<>\t+=×*()\[\]-]*)/g, function($0,$1,$2,$3,$4,$5){
                            
                            return $1.replace(/^(\s*)((\s|.)*?)(\s*)$/,function($00,$11,$22,$33,$44){
                                return $11+"$"+$22+"$"+$44;
                            });
                        }

                                );
                        //.replace(/([&\s{}\\\^_\|A-Za-z0-9.<>\t+-=×*()\[\],]*[A-Za-z0-9][&\s{}\\\^_\|A-Za-z0-9.<>\t+-=×*()\[\],]*)/g, "$$$1$$");
                }
                str+=sub_str;
            });
            return str;
        };


        this.html_node=$(
            '        <div class="row">'+
                '            <div class=" col-md-12  " >'+
                '     <div class="btn-toolbar" role="toolbar">  '+
                '            <div class="btn-group " id="id_mathjax_add_pic_div"> <button type="button" class=" btn  btn-primary opt-title " style="height:28px" > </button>  '+
                '                <button type="button" class="btn btn-default  " id="id_mathjax_add_number_dollar_all"  title="全部数字/字母自动加$" style="height:28px" ><span>all$<span>x=1<span>$<span></button>  '+
                '                <button type="button" class="btn btn-default  " id="id_mathjax_add_number_dollar"  title="选中的加$ :(ctrl-`)" style="height:28px" >多行各自加<span>$<span></button>  '+
                '                <button type="button" class="btn btn-default fa fa-picture-o" id="id_mathjax_add_pic"  title="图片" ></button>  '+
                '                <button type="button" class="btn btn-default " id="id_mathjax_add_under_line" title="插入下划线"   style="height:28px" >____</button>  '+
                '                <button type="button" class="btn btn-default " id="id_mathjax_add_kuo_hao" title="插入括号"   style="height:28px" >(&nbsp;&nbsp;&nbsp;&nbsp;)</button>  '+
                '            </div>  '+


                ' <ul class="nav navbar-nav">'+
                '         <li class="dropdown">'+
                '            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-top: 0px; padding-bottom: 0px;" >'+
                '               符号1'+
                '               <b class="caret"></b>'+
                '            </a>'+
                '            <ul class="dropdown-menu" id="id_mathjax_math_group_1" style="width: 800px;">'+
                '            </ul>'+
                '         </li>'+
                '         <li class="dropdown">'+
                '            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-top: 0px; padding-bottom: 0px;" >'+
                '               符号2'+
                '               <b class="caret"></b>'+
                '            </a>'+
                '            <ul class="dropdown-menu" id="id_mathjax_math_group_2" style="width: 800px;">'+

                '            </ul>'+
                '         </li>'+

                '         <li class="dropdown">'+
                '            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-top: 0px; padding-bottom: 0px;" >'+
                '               自定义'+
                '               <b class="caret"></b>'+
                '            </a>'+
                '            <ul class="dropdown-menu" style="width: 800px;">'+
                '<li>                <button type="button" class="btn btn-warning  fa fa-edit " id="id_mathjax_user_define_edit"  title="自定义编辑"  >编辑</button>  </li> '+
                '<li id="id_mathjax_math_group_user" > </li> '+

                '            </ul>'+
                '         </li>'+

                '      </ul>'+
                '              '+
                '        </div>  '+

                '            </div >'+
                '</div >'+
                '        <div class="row" style="margin-top:0px ;padding-top:0px">'+
                '            <div class="col-xs-12 col-md-6  " >'+

                '                <textarea  type="text" value=""   class="form-control math-opt-change-q " style="height:'+(this.options.height-80)+'px;font-size:18px;"     id="id_mathjax_content"   placeholder=""   ></textarea>'+

                '                <div class="row opt-select-div">'+
                '                    <div class="col-md-6">'+
                '                        <div class="input-group ">'+
                '                            <span >A</span>'+
                '                            <input type="text" value="" class="math-opt-change-q"  id="id_mathjax_q_A"  placeholder="" />'+
                '                        </div>'+
                '                    </div>'+
                '                    <div class="col-md-6">'+
                '                        <div class="input-group ">'+
                '                            <span >B</span>'+
                '                            <input type="text" value="" class="math-opt-change-q"   id="id_mathjax_q_B"  placeholder="" />'+
                '                        </div>'+
                '                    </div>'+
                '                </div>'+

                '                <div class="row opt-select-div">'+
                '                    <div class="col-md-6">'+
                '                        <div class="input-group ">'+
                '                            <span >C</span>'+
                '                            <input type="text" value=""   class="math-opt-change-q"  id="id_mathjax_q_C"  placeholder="" />'+
                '                        </div>'+
                '                    </div>'+
                '                    <div class="col-md-6">'+
                '                        <div class="input-group ">'+
                '                            <span >D</span>'+
                '                            <input type="text" value=""   class="math-opt-change-q"  id="id_mathjax_q_D"  placeholder="" />'+
                '                        </div>'+
                '                    </div>'+
                '                </div>'+


                '            </div>'+
                '            <div class="col-xs-12 col-md-6  " >'+
                '                <div id="MathPreview" style="border:1px solid;width:100%; height:'+this.options.height+'px; overflow:auto;font-size:18px;"></div>'+
                ''+
                '                <div id="MathBuffer" style="border:1px solid;  width:100%;  visibility:hidden; position:absolute; top:0; left: 0; height:'+this.options.height+'px ;overflow:auto; font-size:18px;"></div>'+
                '            </div>'+
                ''+
                '        </div>');
        this.html_node.find( ".opt-title" ).text(this.options.title );

        this.id_mathjax_content=this.html_node.find("#id_mathjax_content");
        this.id_mathjax_q_A=this.html_node.find("#id_mathjax_q_A");
        this.id_mathjax_q_B=this.html_node.find("#id_mathjax_q_B");
        this.id_mathjax_q_C=this.html_node.find("#id_mathjax_q_C");
        this.id_mathjax_q_D=this.html_node.find("#id_mathjax_q_D");

        //自动加$
        this.html_node.find("#id_mathjax_q_A, #id_mathjax_q_B, #id_mathjax_q_C, #id_mathjax_q_D, #id_mathjax_content").on("blur", function(){
            var val=$(this).val();

            if ( !val.match(/\$/) ){
                $(this).val(  me.reset_latex_str_2(val) );
            }

            me.preview_update();
        });
        


        me.last_opt_input=this.id_mathjax_content;

        this.html_node.find(".math-opt-change-q")
            .on("input",function(){
                me.preview_update();
            })
            .on("keyup",function(e){
                if (e.keyCode==192 && e.ctrlKey==true){
                    me.append_dollar();
                }
            })

            .on("focus",function(){
                me.last_opt_input=($(this));
            });
        /*
    "\\tiny"=>0.5,
    "\\scriptsize"=>0.7,
    "\\footnotesize"=>0.8,
    "\\small"=>0.9,
    "\\normalsize"=>1,
    "\\large"=>1.2,
    "\\Large"=>1.44,
    "\\LARGE"=>1.728,
    "\\huge"=>2.074,
    "\\Huge"=>2.488, 
         */


        this.math_group_1_conf=[
            [
                ["\\large ",  "\\large large 1.2" ],
                ["\\Large ",  "\\Large Large 1.44" ],
                ["\\LARGE ",  "\\LARGE LARGE 1.728" ],
                ["\\huge " , "\\huge huge 2.074" ],
                ["\\Huge ",  "\\Huge Huge 2.488" ]
              ],

            [ "\\cdot", "\\times", "\\div","{x}_{1}", "{x}^{2}", "x_{a}^{b}", "\\frac{1}{2}", "\\sum_{a}^{b}", "\\sqrt{x}", "\\sqrt[3]{x}", "\\frac{4ac-b^2}{4a}", "S=\\pi r^2", 
              "\\left\\{\\begin{matrix}\n x=y+1 \n \\\\ \n y=2x-1 \n \\end{matrix}\\right.\n ",
              "a{x}^{2}+bx+c=0", "(a\\neq 0)" , "\\frac{1}{x^2}"  , "\\frac{1}{x}"
              ," \\begin{align*}\n y &= (x+1 )(x-1) \\\\\n &=x^2-x+x-1 \\\\ \n &= x^2-1 \n \\end{align*}\n"],
            [ "\\therefore", "\\because", "\\triangle", "\\angle","\\measuredangle","\\sphericalangle","\\varnothing","\\infty","\\mho","\\forall", "\\odot", "\\perp",  "\\pi", "\\because  \\triangle ABC \\approx   \\triangle ABD "
            ],

            ["\\leq","\\geq","\\doteq","\\leqslant","\\geqslant","\\equiv","\\nless","\\ngtr","\\neq","\\nleqslant","\\ngeqslant","\\not\\equiv","\\prec","\\succ","\\preceq","\\succeq","\\sim","\\ll","\\gg","\\approx","\\vdash","\\dashv","\\simeq","\\smile","\\frown","\\cong","\\models","\\perp","\\asymp","\\parallel","\\propto","\\bowtie","\\Join"],

            ["\\widehat{abc}","\\widetilde{abc}","\\overline{abc}","\\underline{abc}","\\overbrace{abc}","\\overrightarrow{abc}","\\overleftarrow{abc}"],

            ["\\sqcap","\\sqcup","\\ast","\\wedge","\\vee","\\barwedge","\\veebar","\\setminus","\\triangleleft","\\triangleright","\\star","\\dotplus","\\lozenge","\\blacklozenge","\\bigstar","\\triangle","\\triangledown","\\square","\\pm","\\mp"],
        ];

        this.math_group_2_conf=[
            [
                //
                "\\;\\;\\; ①",
                "\\;\\;\\; ②",
                "\\;\\;\\; ③",
                "\\;\\;\\; ④",
                "\\;\\;\\; ⑤",
                "\\;\\;\\; ⑥",
                "\\;\\;\\; ⑦",
                "\\;\\;\\; ⑧",
                "\\;\\;\\; ⑨",
                "\\;\\;\\; ⑩",
            ],

            ["\\dot{a}","\\ddot{a}","{a}'","{a}''","\\hat{a}","\\check{a}","\\grave{a}","\\acute{a}","\\tilde{a}","\\breve{a}","\\bar{a}","\\vec{a}","\\not{a}","a^{\\circ}" ,"37^{\\circ}"],
            ["\\in","\\ni","\\nsubseteq","\\nsupseteqq","\\notin","\\nsubseteq","\\nsupseteq","\\subseteq","\\supseteq","\\subset","\\supset","\\subseteqq","\\supseteqq","\\sqsubset","\\sqsupset","\\sqsubseteq","\\sqsupseteq"],
            ["\\alpha","\\beta","\\gamma","\\delta","\\epsilon","\\varepsilon","\\zeta","\\eta","\\theta","\\vartheta","\\iota","\\kappa","\\lambda","\\mu","\\nu","\\xi","\\pi","\\varpi","\\rho","\\varrho","\\sigma","\\varsigma","\\tau","\\upsilon","\\phi","\\varphi","\\chi","\\psi","\\omega"]
        ];


        //$('.dropdown-toggle').dropdown();

        var $id_mathjax_math_group_1=this.html_node.find("#id_mathjax_math_group_1");
        $.each(this.math_group_1_conf,function(i,btn_list ){
            var $li=$("<li/>") ;
            $.each(btn_list,function(i,item){
                var cmd_str="" ;
                var cmd_desc_str="";
                if ($.isArray (item) ) {
                    cmd_str =item[0];
                    cmd_desc_str=item[1];
                }else{
                    cmd_str= "$"+item+"$";
                    cmd_desc_str=cmd_str;
                }
                var $btn= $('<button type="button" class="btn btn-default " >$'+cmd_desc_str+'$</button>  ') ;
                $btn.on("click",function(){
                    me.insert(cmd_str);
                });
                $li.append($btn);
            });
            $id_mathjax_math_group_1.append($li);
        });

        var $id_mathjax_math_group_2=this.html_node.find("#id_mathjax_math_group_2");

        $.each(this.math_group_2_conf,function(i,btn_list ){
            var $li=$("<li/>") ;
            var cmd_str_add_dollar=true;
            if (i==0) { //不需要$$
                cmd_str_add_dollar= false;
            }

            $.each(btn_list,function(i,item){

                var cmd_str= "$"+item+"$";
                var $btn= $('<button type="button" class="btn btn-default " >$'+cmd_str+'$</button>  ') ;
                $btn.on("click",function(){
                    
                    if ( cmd_str_add_dollar ) {
                        me.insert(cmd_str);
                    }else{
                        me.insert(item);
                    }
                });
                $li.append($btn);
            });
            $id_mathjax_math_group_2.append($li);
        });



        this.$element.html(this.html_node);



        this.Preview = {
            delay: 300,        // delay after keystroke before updating

            preview: null,     // filled in by Init below
            buffer: null,      // filled in by Init below

            timeout: null,     // store setTimout id
            mjRunning: false,  // true when MathJax is processing
            oldText: null,     // used to check if an update is needed

            //
            //  Get the preview and buffer DIV's
            //
            Init: function () {
                this.preview = me.html_node.find ("#MathPreview").get(0);
                this.buffer = me.html_node.find ("#MathBuffer").get(0);
            },

            //
            //  Switch the buffer and preview, and display the right one.
            //  (We use visibility:hidden rather than display:none since
            //  the results of running MathJax are more accurate that way.)
            //
            SwapBuffers: function () {
                var buffer = this.preview, preview = this.buffer;
                this.buffer = buffer; this.preview = preview;
                buffer.style.visibility = "hidden"; buffer.style.position = "absolute";
                preview.style.position = ""; preview.style.visibility = "";
            },

            //
            //  This gets called when a key is pressed in the textarea.
            //  We check if there is already a pending update and clear it if so.
            //  Then set up an update to occur after a small delay (so if more keys
            //    are pressed, the update won't occur until after there has been 
            //    a pause in the typing).
            //  The callback function is set up below, after the Preview object is set up.
            //
            Update: function () {
                if (this.timeout) {clearTimeout(this.timeout);}
                this.timeout = setTimeout(this.callback,this.delay);
            },

            //
            //  Creates the preview and runs MathJax on it.
            //  If MathJax is already trying to render the code, return
            //  If the text hasn't changed, return
            //  Otherwise, indicate that MathJax is running, and start the
            //    typesetting.  After it is done, call PreviewDone.
            //  
            CreatePreview: function () {
                if (this.mjRunning){
                    this.timeout = setTimeout(this.callback,this.delay);
                    return;
                };

                me.Preview.timeout = null;
                var text = me.val();
                

                //console.log("=====2222222:"+ me.val() );

                if (text === this.oldText) return;
                this.oldText = text;
                this.buffer.innerHTML =  mathjax_show_str(this.oldText );
                this.mjRunning = true;
                MathJax.Hub.Queue(
                    ["Typeset",MathJax.Hub,this.buffer],
                    ["PreviewDone",this]
                );
            },

            //
            //  Indicate that MathJax is no longer running,
            //  and swap the buffers to show the results.
            //
            PreviewDone: function () {
                this.mjRunning = false;
                this.SwapBuffers();
            }

        };

        this.Preview.callback = MathJax.Callback(["CreatePreview",this.Preview]);
        this.Preview.callback.autoReset = true;  // make sure it can run more than once
        
        this.Preview.Init();

        
    };


    //定义方法
    Cmathjax_editor.prototype = {

        bind:function(){
            //qiniu
            var me=this;

            var custom_upload = function(btn_id, containerid, domain, compelete_func){
                var uploader = Qiniu.uploader({
		            runtimes: 'html5, flash, html4',
		            browse_button: btn_id , //choose files id
		            uptoken_url: '/upload/pub_token',
		            domain: domain,
		            container: containerid,
		            drop_element: containerid,
		            max_file_size: '30mb',
		            dragdrop: true,
		            flash_swf_url: '/js/qiniu/plupload/Moxie.swf',
		            chunk_size: '4mb',
		            unique_names: false,
		            save_key: false,
		            auto_start: true,
		            init: {
			            'FilesAdded': function(up, files) {
                            /*
				             plupload.each(files, function(file) {
                             var progress = new FileProgress(file, 'process_info');
                             console.log('waiting...');
                             });
                             */
			            },
			            'BeforeUpload': function(up, file) {
                            /*
				             console.log('before uplaod the file');
				             if (!check_type(file.type)) {
					         BootstrapDialog.alert('请上传PDF文件');
					         return;
                             }
                             */

			            },
			            'UploadProgress': function(up,file) {
                            /*
				             var progress = new FileProgress(file, 'process_info');
                             progress.setProgress(file.percent + "%", up.total.bytesPerSec, btn_id);
				             console.log('upload progress');
                             */
			            },
			            'UploadComplete': function() {
                            // $("#"+btn_id).siblings('div').remove();
				            console.log('success');
			            },
			            'FileUploaded' : function(up, file, info) {
				            console.log('Things below are from FileUploaded');
                            compelete_func(domain, info);
                            // var res = $.parseJSON(info);
                            // $(".bootstrap-dialog-body .gift_url").val(domain + res.key);
                            // $(".bootstrap-dialog-body .preview_gift_pic").attr("href", domain + res.key);
                            // set the key
			            },
			            'Error': function(up, err, errTip) {
				            console.log('Things below are from Error');
				            console.log(up);
				            console.log(err);
				            console.log(errTip);
			            },
			            'Key': function(up, file) {
                            console.log("Key start");
                            console.log(file);
                            var suffix = file.type.split('/').pop();
                            console.log(suffix);
                            console.log("Key end");
				            var key = "";
				            //generate the key
                            var time = (new Date()).valueOf();
				            return $.md5(file.name) +time+ "." + suffix;
			            }
		            }
	            });

            };

            custom_upload(me.html_node.find('#id_mathjax_add_pic')[0],
                          me.html_node.find('#id_mathjax_add_pic_div')[0],
                          this.options .qiniu_upload_domain_url, set_upload_data_end   );

            function set_upload_data_end(domain, info)
            {
                var res = $.parseJSON(info.response);
                var pic_str="\n![]("+  domain+res.key+")\n";
                me.insert(pic_str );
            }

            //===================
            var btns_config=[
                [ "id_mathjax_add_under_line" ,"_______"],
                [ "id_mathjax_add_kuo_hao" ,"(    )"]
            ];

            $.each(btns_config,function(i,item){
                me.html_node.find("#"+item[0]).on("click",function(){
                    me.insert(item[1]);
                });
            });
            //
            

            me.html_node.find("#id_mathjax_add_number_dollar_all").on("click",function(){
                
                $.each($(".math-opt-change-q" ) ,function() {
                    $(this).val(  me.reset_latex_str_2($(this).val()) );
                });
                me.preview_update();
            });

            me.html_node.find("#id_mathjax_user_define_edit").on("click",function(){
                var dlg_node= $(

                    "<table   class=\"table table-bordered table-striped\"   >"  +
                       "<thead> <td>id  <td> 片段   <a id=\"id_add_snippet\" href=\"javascript:;\" class=\"btn btn-warning fa fa-plus\">新增</a> <td width=200> 操作 </thead>" +
                        "<tbody id=\"id_math_edit_body\"></tbody>"+
                        "</table>"
                    
                );
                var $id_math_edit_body= dlg_node.find("#id_math_edit_body");
                var opt_func=function(opt_type ,id,subject, sub_id_1,sub_id_2, content  ){
		            $.ajax({
			            type     :"post",
			            url      :"/question/user_snippet_opt",
			            dataType :"json",
			            data     :{
                            opt_type: opt_type,
                            id: id  ,
                            subject:  subject,
                            sub_id_1:  sub_id_1,
                            sub_id_2:  sub_id_2,
                            content: content
                        }
			            ,success  : function(result){
                            me.reset_user_snippets_with_data(result.data );

                            var html_str="" ;
                            $.each(result.data,function(){
                                html_str += "<tr><td>"+this.id+" <td class=\"opt-math-content\" >"+this.content+"<td class =\"td-opt\" data-id=\""+this.id+"\" data-sub_id=\""+this.sub_id+"\"   > <a  class=\" btn icon icon-arrow-up opt-math-up \"> <a  class=\" btn icon icon-arrow-down opt-math-down \"> </a> <a  class=\"btn fa  fa-trash-o opt-math-del \"> </a> " +  "</tr>" ;
                            });
                            $id_math_edit_body.html(html_str);

                            MathJax.Hub.Queue(
                                ["Typeset",MathJax.Hub, $id_math_edit_body[0] ]
                            );
                            
                            // 
                            $id_math_edit_body.find(".opt-math-del" ).on("click",function(){
	                            //
                                var id=$(this).parent().data("id");

                                BootstrapDialog.show({
                                    title: '删除片段',
                                    message :"删除片段 id="+ id + "?" ,
                                    closable: true, 
                                    buttons: [{
                                        label: '取消',
                                        cssClass: 'btn',
                                        action: function(dialog) {

                                            dialog.close();
                                        }
                                    }, {
                                        label: '提交',
                                        cssClass: 'btn btn-warning',
                                        action: function(dialog) {
                                            opt_func("del",id ,me.subject() );
                                            dialog.close();
                                        }
                                    }]
                                });
	                            
                            });


                            $id_math_edit_body.find(".opt-math-up" ).on("click",function(){
                                var $tr= $(this).parent().parent();
                                var $other=$tr.prev();
                                if ($other.length==0){
                                    alert("已经是第一个了");
                                }else{
                                    opt_func("switch_sub_id",0,me.subject(),
                                             $tr.find(".td-opt" ).data("sub_id"),
                                             $other.find(".td-opt" ).data("sub_id"));
                                }
                                   //$this.find(".td-opt")
                            });

                            $id_math_edit_body.find(".opt-math-down" ).on("click",function(){
                                var $tr= $(this).parent().parent();
                                var $other=$tr.next();
                                if ($other.length==0){
                                    alert("已经是最后一个了");
                                }else{
                                    opt_func("switch_sub_id",0,me.subject(),
                                             $tr.find(".td-opt" ).data("sub_id"),
                                             $other.find(".td-opt" ).data("sub_id"));
                                }
                            });

                        }
                    });
                };

                opt_func("get",0,me.subject() );

                dlg_node.find( "#id_add_snippet" ) .on("click",function(){
	                //
                    var subject = me.subject();
                    var dlg_node=$("<textarea></textarea>");
                    BootstrapDialog.show({
                        title: '新增',
                        message : dlg_node ,
                        closable: true, 
                        buttons: [{
                            label: '取消',
                            cssClass: 'btn',
                            action: function(dialog) {

                                dialog.close();
                            }
                        }, {
                            label: '提交',
                            cssClass: 'btn btn-warning',
                            action: function(dialog) {
                                opt_func("add",0,me.subject(),"","", dlg_node.val() );
                                dialog.close();

                            }
                        }]
                    });

	                
                });


                BootstrapDialog.show({
                    title: '自定义片段',
                    message : dlg_node ,
                    closable: true, 
                    buttons: [
                        {
                            label: '取消',
                            cssClass: 'btn',
                            action: function(dialog) {
                                dialog.close();
                            }
                        }]
                });

                
            });

            me.reset_user_snippets();

            me.html_node.find("#id_mathjax_add_number_dollar").on("click",function(){
                me.append_dollar();
            });

        },

        insert:function(text ) {
            var me=this;
            me.last_opt_input.insertAtCaret(text);
            me.preview_update();
            //me.html_node.find("#id_mathjax_content").insertAtCaret(text);
        },

        preview_update:function ( no_noti_flag ) {
            var me=this;
            
            if ( !me.stop_preview_flag ) {
                me.Preview.Update();
                if (me.options.onUpdate &&  !no_noti_flag ){
                    me.options.onUpdate(me.val() ) ;
                }
            }

        },
        append_dollar:function() {
            var me=this;
            me.last_opt_input.append_dollar();
            me.preview_update();
        },

        type: function(data){
            if (data!= undefined  ){
                if (data != this.options.type ){
                    this.options.type=data;
                    if ( this.type_is_select() ){
                        this.html_node.find(".opt-select-div").show();
                        this.id_mathjax_content.height( this.id_mathjax_content.height()-80 );

                    }else{
                        this.html_node.find(".opt-select-div").hide();
                        this.id_mathjax_content.height( this.id_mathjax_content.height()+80 );
                    }

                    this.preview_update();
                };
                return this;
            }else{
                return this.options.type;
            }

        },
        
        

        type_is_select: function(){
            return this.type()=="select";
        },


        val: function( data , reformat_flag ) {
            var ret;
			var me =this;
            if (data!= undefined ) {
                ret=me.id_mathjax_content.val("");
                me.id_mathjax_q_A.val("");
                me.id_mathjax_q_B.val("");
                me.id_mathjax_q_C.val("");
                me.id_mathjax_q_D.val("");

                var find_flag=false;
                var do_reformat=function(val) {
                    if (reformat_flag) {
                        return me.reset_latex_str_2(val);
                    }else{
                        return val;
                    }
                };

                if  (me.type_is_select()){
                    data.replace(/^([\s\S]*)\n\n\n\$A.\$([\s\S]*)\n\n\$B\.\$([\s\S]*)\n\n\$C\.\$([\s\S]*)\n\n\$D\.\$([\s\S]*)$/ ,
                                 function($0,$1,$2,$3,$4,$5){
                                     me.id_mathjax_content.val(do_reformat($1));
                                     me.id_mathjax_q_A.val(do_reformat($2));
                                     me.id_mathjax_q_B.val(do_reformat($3));
                                     me.id_mathjax_q_C.val(do_reformat($4));
                                     me.id_mathjax_q_D.val(do_reformat($5));
                                     find_flag=true;
                                 }
                                );
                    if (find_flag==false){
                        me.id_mathjax_content.val( do_reformat( data));
                    }
                }else{
                    me.id_mathjax_content.val(do_reformat(data));
                }
                this.preview_update();


            }else{

                ret= this.id_mathjax_content.val();
                if ( $.trim(ret)==""){
                    return ret;
                }

                if(this.type_is_select()){
                    //处理 A. B. C. D.
                    // $A.$  =>$A. $
                    var deal_content=function(str){
                        return str.replace(/\$([A-D])\.\$/g,"$$$1. $$" );
                    };
                    ret=deal_content(ret);
                    ret+= "\n\n\n$A.$ "+deal_content(this.id_mathjax_q_A.val())
                        +"\n\n$B.$ "+deal_content(this.id_mathjax_q_B.val())
                        +"\n\n$C.$ "+deal_content(this.id_mathjax_q_C.val())
                        +"\n\n$D.$ "+deal_content(this.id_mathjax_q_D.val())  ;
                }
            }
            return ret;
        },
        subject:function (val){
            if (val==undefined){
                return this.options.subject;
            }else{
                this.options .subject=val;
                return this;
            }
            
        },
        reset_user_snippets_with_data:function(data){
            var me=this;
            var $id_mathjax_math_group_user=me.html_node.find("#id_mathjax_math_group_user");
            $id_mathjax_math_group_user.html("");
            var $li=$("<li/>") ;
            $.each(data,function(i,item){
                var cmd_str= item.content;
                var $btn= $('<button type="button" class="btn btn-default " >'+cmd_str+'</button>  ') ;
                $btn.on("click",function(){
                    me.insert(cmd_str);
                });
                $li.append($btn);
            });
            $id_mathjax_math_group_user.append($li);
            MathJax.Hub.Queue(
                ["Typeset",MathJax.Hub, $id_mathjax_math_group_user[0] ]
            );

        },


        reset_user_snippets:function( ){
            var me=this;
		    $.ajax({
			    type     :"post",
			    url      :"/question/user_snippet_opt",
			    dataType :"json",
			    data     :{
                    opt_type: "get",
                    id:  "",
                    subject:  me.subject() ,
                    sub_id_1:  "",
                    sub_id_2:  "",
                    content:"" 
                }
			    ,success  : function(result){
                    me.reset_user_snippets_with_data(result.data);
                }
            });

        }


    };

    //在插件中使用对象
    $.fn.admin_mathjax_editor = function(options) {
        //创建的实体
        var item = new Cmathjax_editor(this, options);
        item.bind();
        //调用其方法
        return item;
        //return item.bind();
    };

})(jQuery, window, document);
