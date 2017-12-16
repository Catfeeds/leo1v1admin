@extends("layouts.app")

@section('content')
    <script type="text/javascript" src="/js/MathJax/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
    <script type="text/javascript" src="/page_js/enum_map.js"></script>
    <script type="text/javascript" src="/page_js/question_edit_new.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/jquery.admin.js"></script>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

    <link href="/ztree/zTreeStyle.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="/ztree/jquery.ztree.all.min.js"></script>
    <script type="text/javascript" src="/ztree/jquery.ztree.core.js"></script>
    <script type="text/javascript" src="/ztree/jquery.ztree.excheck.min.js"></script>
    <script type="text/javascript" src="/ztree/jquery.ztree.exedit.min.js"></script>
    <script type="text/javascript" src="/ztree/jquery.ztree.exhide.min.js"></script>
    <script type="text/javascript">
     var zNodes = <?php echo $knowledge?>;
    </script>

    <style>
     .row{ margin-left:-10px}
     .knowledge_all{ background:rgba(0, 0, 0, 0.4);position:absolute;top:0px;left:0px;width:100%;height:100%;z-index:999;display:none}
     .zTreeDemoBackground{position:absolute;top:20%;left:35%;background: white;padding:20px }
     #close_knowledge{ position:absolute;top:5px;right:5px;z-index:9999}
     .knowledge_exits span{ color: #258e25;font-weight: bold;margin-right:10px}
    </style>

    <div class="knowledge_all">
        <div class="zTreeDemoBackground">
            <button type="button" class="btn btn-danger btn-circle" id="close_knowledge" onclick="close_know()"><i class="fa fa-times"></i></button>
            <a href="javascript:;" id="show_all_knowledge"> 显示全部知识点 </a>
            <ul id="treeDemo" class="ztree"></ul>
            <button type="button" class="btn btn-primary" id="save_knowledge" answer_id="" onclick="save_know()" title="编辑完成">编辑完成</button>
        </div>
    </div>


    <section class="content">
        <input type="hidden" value="{{@$question['question_id']}}" id="question_id" />
        <div id="id_question_editor" >
            <div class="row">
                <div class="col-xs-6 col-md-6">
                    <div class="input-group "><h3>题目:{{@$question['title']}} 总分:{{@$question['score']}}</h3></div>
                    <div class="input-group ">
                     
                    </div>
                </div>
            </div>
            @foreach($ret as $key => $item)
                <div class="answer_step">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="btn-toolbar" role="toolbar">
                                <div id="id_mathjax_add_pic_div_{{$key+1}}" class="btn-group ">
                                    <input type="hidden" class="editType" value="2">
                                    <input type="hidden" class="answer_id" value="{{$item['answer_id']}}">
                                    <input type="hidden" class="step" value="{{$item['step']}}">
                                    <button type="button" class=" btn  btn-primary opt-title " style="height:30px;" >步骤类型:</button>
                                    <select class="btn answer_type" style="height:30px;padding:0px;width:100px" id="answer_type_{{$key+1}}"></select>
                                    <input type="hidden" class="answer_type_value" value="{{$item['answer_type']}}">

                                    <button type="button" class=" btn  btn-primary answer-step" style="height:30px;margin-right:10px" title="默认添加最后一步，可以点击选择在两步之间添加本步骤">{{$item['step_str']}}</button>


                                    <button type="button" class=" btn  btn-primary opt-title " style="height:30px;" >分值:</button>
                                    <input class="btn answer_score" style="height:30px;margin-right:10px;padding:0px;text-align: left;
                                                  text-indent: 4px;width: 100px;" value="{{$item['score']}}" >

                                   
                                    <button class="btn btn-primary add_question_knowledge" style="height:30px;margin-right:10px" title="编辑知识点" onclick="open_know({{$key+1}})">
                                        编辑知识点
                                    </button>
                                    

                                    <button type="button" class=" btn  btn-warning answer-save" style="height:30px;margin-right:10px" title="点击保存本步骤">保存步骤</button>

                                    <button type="button" class=" btn  btn-danger answer-dele" style="height:30px;margin-right:10px" title="点击保存本步骤">删除步骤</button>
                                    
                                    <button type="button" class="btn btn-default fa fa-picture-o add_pic" id="id_mathjax_add_pic_{{$key+1}}" title="图片" style="z-index: 1;"></button>
                                    <button type="button" class="btn btn-default add_under_line" id="id_mathjax_add_under_line_{{$key+1}}" title="插入下划线" style="height:28px">____</button>
                                    <button type="button" class="btn btn-default add_kuo_hao" id="id_mathjax_add_kuo_hao_{{$key+1}}" title="插入括号" style="height:28px">(&nbsp;&nbsp;&nbsp;&nbsp;)</button>            
                                </div>
                                <ul class="nav navbar-nav" id="navbar_{{$key+1}}">
                                    @include('question_new.mathjax')
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <input type="hidden" value="{{$item['know_str']}}" id="knowledge_old_{{$key+1}}" class="knowledge_old" />
                            <div id="knowledge_exits_{{$key+1}}" class="knowledge_exits">
                                @if(!empty($item['know_str']))
                                    @foreach(json_decode($item['know_str'],true) as $var)
                                        <span knowledge_id="{{$var['knowledge_id']}}">{{$var['title']}}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                            <textarea type="text" class="form-control math-opt-change-q id_mathjax_content" style="height:auto;font-size:18px;"  id="id_mathjax_content_{{$key+1}}" placeholder="">{{$item['detail']}}</textarea>
                            
                        </div>

                        <div class="col-xs-6 col-md-6  ">
                            <div class="MathPreview" id="MathPreview_{{$key+1}}" style="background:white; width: 100%; height:auto; overflow: auto; font-size: 18px;padding:6px 12px">
                            </div>    
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="answer_step">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="btn-toolbar" role="toolbar">
                            <div id="id_mathjax_add_pic_div_0" class="btn-group ">
                                <input type="hidden" class="editType" value="1">
                                <input type="hidden" class="answer_id" value="">
                                <input type="hidden" class="step" value="{{$next_step}}">
                                <button type="button" class=" btn  btn-primary opt-title " style="height:30px;" >步骤类型:</button>
                                <select class="btn answer_type" style="height:30px;padding:0px;width:100px" id="answer_type_0"></select>

                                <button type="button" class=" btn  btn-primary answer-step" style="height:30px;margin-right:10px" title="默认添加最后一步，可以点击选择在两步之间添加本步骤">最新步骤</button>


                                <button type="button" class=" btn  btn-primary opt-title " style="height:30px;" >分值:</button>
                                <input class="btn answer_score" style="height:30px;margin-right:10px;padding:0px;text-align: left;
                                              text-indent: 4px;width: 100px;" >

                                <button class="btn btn-primary add_question_knowledge" style="height:30px;margin-right:10px" title="编辑知识点" onclick="open_know({{0}})">

                                <button type="button" class=" btn  btn-warning answer-save" style="height:30px;margin-right:10px" title="点击保存本步骤">保存步骤</button>

                                <button type="button" class="btn btn-default fa fa-picture-o add_pic" id="id_mathjax_add_pic_0" title="图片" style="z-index: 1;"></button>
                                <button type="button" class="btn btn-default add_under_line" id="id_mathjax_add_under_line_0" title="插入下划线" style="height:28px">____</button>
                                <button type="button" class="btn btn-default add_kuo_hao" id="id_mathjax_add_kuo_hao_0" title="插入括号" style="height:28px">(&nbsp;&nbsp;&nbsp;&nbsp;)</button>            
                            </div>
                            <ul class="nav navbar-nav" id="navbar_0">
                                @include('question_new.mathjax')
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <input type="hidden" value="" id="knowledge_old_0" class="knowledge_old" />
                        <div id="knowledge_exits_0" class="knowledge_exits"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6 col-md-6">
                        <textarea type="text" value="" class="form-control math-opt-change-q id_mathjax_content" style="height:240px;font-size:18px;" id="id_mathjax_content_0" placeholder=""></textarea>
                        
                    </div>

                    <div class="col-xs-6 col-md-6  ">
                        <div class="MathPreview" id="MathPreview_0" style="border: 1px solid; width: 100%; min-height: 240px; overflow: auto; font-size: 18px;">
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    
@endsection
