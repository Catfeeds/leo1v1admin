var Cquestion_editor = {
    autoTextarea:function (elem, extra, maxHeight) {
        extra = extra || 0;
        var isFirefox = !!document.getBoxObjectFor || 'mozInnerScreenX' in window,
            isOpera = !!window.opera && !!window.opera.toString().indexOf('Opera'),
            addEvent = function (type, callback) {
                elem.addEventListener ?
                    elem.addEventListener(type, callback, false) :
                    elem.attachEvent('on' + type, callback);
            },
            getStyle = elem.currentStyle ? 
            function (name) {
                var val = elem.currentStyle[name];
                if (name === 'height' && val.search(/px/i) !== 1) {
                    var rect = elem.getBoundingClientRect();
                    return rect.bottom - rect.top -
                        parseFloat(getStyle('paddingTop')) -
                        parseFloat(getStyle('paddingBottom')) + 'px';       
                };
                return val;
            } : function (name) {
                return getComputedStyle(elem, null)[name];
            },
            minHeight = parseFloat(getStyle('height'));
        elem.style.resize = 'both';//如果不希望使用者可以自由的伸展textarea的高宽可以设置其他值

        var change = function () {
            var scrollTop, height,
                padding = 0,
                style = elem.style;

            if (elem._length === elem.value.length) return;
            elem._length = elem.value.length;

            if (!isFirefox && !isOpera) {
                padding = parseInt(getStyle('paddingTop')) + parseInt(getStyle('paddingBottom'));
            };
            scrollTop = document.body.scrollTop || document.documentElement.scrollTop;

            elem.style.height = minHeight + 'px';
            if (elem.scrollHeight > minHeight) {
                if (maxHeight && elem.scrollHeight > maxHeight) {
                    height = maxHeight - padding;
                    style.overflowY = 'auto';
                } else {
                    height = elem.scrollHeight - padding;
                    style.overflowY = 'hidden';
                };
                style.height = height + extra + 20 + 'px';
                scrollTop += parseInt(style.height) - elem.currHeight;
                document.body.scrollTop = scrollTop;
                document.documentElement.scrollTop = scrollTop;
                elem.currHeight = parseInt(style.height);
            };
        };

        addEvent('propertychange', change);
        addEvent('input', change);
        addEvent('focus', change);
        change();
    },

    reset_latex_str:function(val){
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
        console.log(str);
        return str;
    },

    //初始化公式显示模块　必须引入文件mathjax.js文件
    init_mathjax:function(mathId){
        //mathId 显示数学公式的内容id
        MathJax.Hub.Config({
            showProcessingMessages: false, //关闭js加载过程信息
            messageStyle: "none", //不显示信息
            extensions: ["tex2jax.js"],
            jax: ["input/TeX", "output/HTML-CSS"],
            tex2jax: {
                inlineMath:  [ ["$", "$"] ], //行内公式选择$
                displayMath: [ ["$$","$$"] ], //段内公式选择$$
                skipTags: ['script', 'noscript', 'style', 'textarea', 'pre','code','a'] //避开某些标签
            },
            "HTML-CSS": {
                availableFonts: ["STIX","TeX"], //可选字体
                showMathMenu: false //关闭右击菜单显示
            }
        });
        MathJax.Hub.Queue(["Typeset",MathJax.Hub,mathId]);
    },

    preview_update:function(id_question_type,id_mathjax_content,MathPreview,mathId) {
        //id_question_type 题型 id_mathjax_content题目输入框 MathBuffer 当前输入的字符串 MathPreview题目显示框 mathId数学公式显示区域
        //var question_type = id_question_type.val();
        var mathjax_content = id_mathjax_content.val();

        if( id_question_type == 1){
            mathjax_content += '<br/> A:' + $('#id_mathjax_q_A').val();
            //console.log($('#id_mathjax_q_A').val());
            mathjax_content += '<br/> B:' + $('#id_mathjax_q_B').val();
            mathjax_content += '<br/> C:' + $('#id_mathjax_q_C').val();
            mathjax_content += '<br/> D:' + $('#id_mathjax_q_D').val();
        }
        //console.log(mathjax_content);
        mathjax_content = mathjax_content.replace(/\n/g, '<br/>');
        mathjax_content = mathjax_content.replace(/[ ]/g, '&nbsp');
        mathjax_content = mathjax_content.replace(/(\!\[\]\()/g,"<img src='");
	      mathjax_content = mathjax_content.replace(/(\)\[\]\&)/ig,"'/>");

        MathPreview.html(mathjax_content);
        MathJax.Hub.Queue(["Typeset",MathJax.Hub,mathId]);
        //Cquestion_editor.render_mathJax(MathPreview,mathId);
    },
    render_mathJax:function(id_mathjax_content,MathPreview,mathId){
        MathJax.Hub.Queue(["Typeset",MathJax.Hub,mathId]);
    },
    custom_upload:function(btn_id, containerid, domain,id_question_type, id_mathjax_content,MathPreview,mathId){
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
					                BootstrapDialog.alert('上传图片前');
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
				                console.log('success');
			              },
			              'FileUploaded' : function(up, file, info) {
				                console.log('Things below are from FileUploaded');
                        console.log(info.response);
                        var imgName = JSON.parse(info.response).key;
                        var pic_str="\n![]("+  domain + imgName +")[]&\n";
                        id_mathjax_content.insertAtCaret(pic_str);
                        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);
			              },
			              'Error': function(up, err, errTip) {
				                console.log('Things below are from Error');
				                console.log(up);
				                console.log(err);
			              },
			              'Key': function(up, file) {
                        console.log("Key start");
                        var suffix = file.type.split('/').pop();
				                var key = "";
                        var time = (new Date()).valueOf();
                        var imgName = $.md5(file.name) +time+ "." + suffix;
                        console.log(imgName);
                        console.log("Key end");
				                return imgName;
			              }
		            }
	      });
    },

}
