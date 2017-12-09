var Cquestion_editor = {
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

    preview_update:function(id_question_type,id_mathjax_content,MathBuffer,MathPreview,mathId) {
        //id_question_type 题型 id_mathjax_content题目输入框 MathBuffer 当前输入的字符串 MathPreview题目显示框 mathId数学公式显示区域
        var question_type = id_question_type.val();
        var mathjax_content = id_mathjax_content.val();
   
        mathjax_content = mathjax_content.replace(/\n/g, '<br/>');
        mathjax_content = mathjax_content.replace(/[ ]/g, '&nbsp');
        MathPreview.html(mathjax_content);
        //MathJax.Hub.Queue(["Typeset",MathJax.Hub,mathId]);
    },
    push_buffer:function(mathId){
        MathJax.Hub.Queue(["Typeset",MathJax.Hub,mathId]);
    }
}
