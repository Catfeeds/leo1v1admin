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
     #knowledge_exits span{ color: #258e25;font-weight: bold;margin-right:10px}
    </style>
    <input type="hidden" value="{{$editData}}" id="editData" />

    <div class="knowledge_all">
        <div class="zTreeDemoBackground">
            <button type="button" class="btn btn-danger btn-circle" id="close_knowledge" onclick="close_know()"><i class="fa fa-times"></i></button>
            <a href="javascript:;" id="show_all_knowledge"> 显示全部知识点 </a>
            <ul id="treeDemo" class="ztree"></ul>
            <button type="button" class="btn btn-primary" id="save_knowledge" onclick="save_know()" title="编辑完成">编辑完成</button>
        </div>

    </div>

    <section class="content">
        <div id="id_question_editor" >
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="input-group "><h3>{{@$ret['describe']}}</h3></div>
                    <div class="input-group ">
                        
                        <!-- <span class="input-group-addon" style="padding-left: 3px; padding-right: 6px;">类型</span>
                             <select class=" form-control " id="id_question_type"></select>
                        -->
                        <span class="input-group-addon" style="padding-left: 3px; padding-right: 6px;">难度</span>
                        <select class=" form-control " id="question_difficult"></select>

                        <span class="input-group-addon" style="padding-left: 3px; padding-right: 6px;">是否开启</span>
                        <select class=" form-control " id="id_open_flag"></select>

                        <span class="input-group-addon" style="padding-left: 3px; padding-right: 6px;">科目类型</span>
                        <select class=" form-control " id="id_subject"></select>

                        <span class="input-group-addon" style="padding-left: 3px; padding-right: 6px;">题目类型</span>
                        <select class=" form-control " id="question_type">
                            @if($question_type)
                                @foreach( $question_type as $item)
                                    <option value="{{$item['id']}}">{{$item['name']}}</option>
                                @endforeach
                            @endif
                        </select>

                        <span class="input-group-addon" style="padding-left: 3px; padding-right: 6px;">题目来源类型</span>
                        <select class=" form-control " id="id_question_resource_type"></select>

                        <span class="input-group-addon" style="padding-left: 3px; padding-right: 6px;">题目来源</span>
                        <input class=" form-control " id="id_question_resource_name" type="text" >

                        <span class="input-group-addon" style="padding-left: 3px; padding-right: 6px;">题目分值</span>
                        <input class=" form-control " id="id_score" type="text">

                        <div class=" input-group-btn ">
                            <button id="add_question_knowledge" type="submit" class="btn btn-primary" onclick="open_know()">
                                编辑知识点
                            </button>
                        </div>
                      
                        <div class=" input-group-btn ">
                            <button id="save_know" type="submit" class="btn  btn-warning">
                                <i class="fa fa-plus"></i>保存问题
                            </button>
                        </div>

                        @if(!empty($question_id))
                            <input type="hidden" value="{{$question_id}}" id="question_id" />
                            <div class=" input-group-btn ">
                                <button id="eidt_answer" type="submit" class="btn  btn-info">
                                    <i class="fa fa-plus"></i>编辑答案
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <input type="hidden" value="{{json_encode($know_arr)}}" id="knowledge_old"/>
                    <div id="knowledge_exits">
                        @if(!empty($know_arr))
                            @foreach($know_arr as $item)
                                <span knowledge_id="{{$item['knowledge_id']}}">{{$item['title']}}</span>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="btn-toolbar" role="toolbar">
                        <div id="id_mathjax_add_pic_div_0" class="btn-group ">
                            <button type="button" class=" btn  btn-primary opt-title " style="height:28px">题目标题:</button>
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
                <div class="col-xs-6 col-md-6">
                    <textarea type="text" value="" class="form-control math-opt-change-q id_mathjax_content" style="height:60px;font-size:18px;"  id="id_mathjax_content_0" placeholder=""></textarea>
                    
                </div>

                <div class="col-xs-6 col-md-6  ">
                    <div class="MathPreview" id="MathPreview_0" style="border: 1px solid; width: 100%; height: 60px; overflow: auto; font-size: 18px;">
                    </div>    
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="btn-toolbar" role="toolbar">
                        <div id="id_mathjax_add_pic_div_1" class="btn-group ">
                            <button type="button" class=" btn  btn-primary opt-title " style="height:28px">题目详情:</button>
                            <button type="button" class="btn btn-default fa fa-picture-o add_pic" id="id_mathjax_add_pic_1" title="图片" style="z-index: 1;"></button>
                            <button type="button" class="btn btn-default add_under_line" id="id_mathjax_add_under_line_1" title="插入下划线" style="height:28px">____</button>
                            <button type="button" class="btn btn-default add_kuo_hao" id="id_mathjax_add_kuo_hao_1" title="插入括号" style="height:28px">(&nbsp;&nbsp;&nbsp;&nbsp;)</button>            
                        </div>
                        <ul class="nav navbar-nav" id="navbar_1">
                            @include('question_new.mathjax')
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6 col-md-6">
                    <textarea type="text" value="" class="form-control math-opt-change-q id_mathjax_content" style="height:300px;font-size:18px;" id="id_mathjax_content_1" placeholder=""></textarea>
                    <div class="row opt-select-div">
                        <div class="col-md-6">
                            <div class="input-group ">
                                <span>A</span>
                                <input type="text" value="{{@$question_option['A']['option_text']}}" option_id="{{@$question_option['A']['id']}}" class="math-opt-change-q" id="id_mathjax_q_A" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group ">
                                <span>B</span>
                                <input type="text" value="{{@$question_option['B']['option_text']}}" option_id="{{@$question_option['B']['id']}}" class="math-opt-change-q" id="id_mathjax_q_B" placeholder="">
                            </div>
                        </div>
                    </div>

                    <div class="row opt-select-div">
                        <div class="col-md-6">
                            <div class="input-group ">
                                <span>C</span>
                                <input type="text" value="{{@$question_option['C']['option_text']}}" option_id="{{@$question_option['C']['id']}}" class="math-opt-change-q" id="id_mathjax_q_C" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group ">
                                <span>D</span>
                                <input type="text" value="{{@$question_option['D']['option_text']}}" option_id="{{@$question_option['D']['id']}}" class="math-opt-change-q" id="id_mathjax_q_D" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-6 col-md-6  ">
                    <div class="MathPreview" id="MathPreview_1" style="border: 1px solid; width: 100%; min-height: 300px; overflow: auto; font-size: 18px;">
                    </div>    
                </div>
            </div>

        </div>
    </section>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    
@endsection
