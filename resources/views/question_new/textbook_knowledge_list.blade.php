@extends('layouts.app')
@section('content')
    <link href="/ztree/zTreeStyle.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="/ztree/jquery.ztree.all.min.js"></script>
    <script type="text/javascript" src="/ztree/jquery.ztree.core.js"></script>
    <script type="text/javascript" src="/ztree/jquery.ztree.excheck.min.js"></script>
    <script type="text/javascript" src="/ztree/jquery.ztree.exedit.min.js"></script>
    <script type="text/javascript" src="/ztree/jquery.ztree.exhide.min.js"></script>

    <script type="text/javascript" src="/js/MathJax/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
    <script type="text/javascript" src="/page_js/enum_map.js"></script>
    <script type="text/javascript" src="/page_js/question_edit_new.js"></script>

    <script type="text/javascript" src="/page_js/lib/select_dlg.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript">
     var zNodes = <?php echo $exit_know?>;
     var zAllKnow = <?php echo $ret?>;
    </script>
    <style>
     .ztree *{ font-size:14px}
     .ztree li{ line-hedight:20px }
     .ztree li span.button.add { margin-left: 2px;margin-right: -1px;background-position: -144px 0;vertical-align: top;}
     .knowledge_background{ width:100%;height:100%;position:absolute;top:0px;left:0px;background: rgba(0, 0, 0, 0.4);z-index: 999;display:none}
     .knowledge_background .knowledge_eject{ width:50%;position:fixed;left:25%;top:20%;background: white;padding: 10px;border-radius: 10px; }
     #close_knowledge{ position:absolute;top:10px;right:10px;z-index:9999}
    </style>
    
    <section class="content">
        <div class="row">
            
            <div class="col-xs-6 col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">科目类型</span>
                    <select class="opt-change form-control" id="id_subject">
                    </select>

                    <span class="input-group-addon">教材版本</span>
                    <select class="opt-change form-control" id="id_textbook">
                    </select>

                    <span class="input-group-addon">年级</span>
                    <select class="opt-change form-control" id="id_grade">
                    </select>

                </div>
            </div>

            <div class="col-xs-4 col-md-4">
                <div class="input-group">
                    <div class=" input-group-btn ">
                        <button type="submit" onclick="add_knowledge()"  class="btn  btn-warning">编辑知识点</button>
                    </div>
              
                    <div class=" input-group-btn ">
                        <button style="margin-left:10px" id="add_textbook" type="button" class="btn btn-success">添加教材</button>
                    </div>

                    <div class=" input-group-btn ">
                        <button style="margin-left:10px" id="question_list" type="button" class="btn btn-primary">题目列表</button>
                    </div>
               
                    <div class=" input-group-btn ">
                        <button style="margin-left:10px" id="knowledge_pic" type="button" class="btn btn-info">知识点展现</button>
                    </div>
                    <div class=" input-group-btn ">
                        <button style="margin-left:10px" id="all_knowledge" type="button" class="btn btn-success">所有知识点</button>
                    </div>

                </div>

            </div>

        </div>
        <hr/>
        <div class="row skin-blue">
            <div class="col-xs-5 col-md-5">

                <a href="javascript:;" id="show_all_knowledge"> 显示全部知识点 </a>

                <div class="zTreeDemoBackground" id="mathview">
                    <ul id="treeDemo" class="ztree"></ul>
                </div>
            </div>

            <div class="col-xs-5 col-md-5">
                
            </div>
        </div>
    </section>

    <div class="knowledge_background">
        <div class="knowledge_eject">
            <button type="button" class="btn btn-danger btn-circle" id="close_knowledge" onclick="close_know()"><i class="fa fa-times"></i></button>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="btn-toolbar" role="toolbar">
                        <div id="id_mathjax_add_pic_div_0" class="btn-group ">
                            <button type="button" class=" btn  btn-primary opt-title " style="height:28px">知识点:</button>
                            
                            <button type="button" class="btn btn-default add_under_line" id="id_mathjax_add_under_line_0" title="插入下划线" style="height:28px">____</button>
                            <button type="button" class="btn btn-default add_kuo_hao" id="id_mathjax_add_kuo_hao_0" title="插入括号" style="height:28px">(&nbsp;&nbsp;&nbsp;&nbsp;)</button>
                            <button type="button" class=" btn  btn-warning answer-save" style="height:30px;margin-right:10px" title="点击保存知识点" onclick="save_know()">保存知识点</button>
                        </div>
                        <ul class="nav navbar-nav" id="navbar_0">
                            @include('question_new.mathjax')
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <textarea type="text" value="" class="form-control math-opt-change-q id_mathjax_content" style="height:140px;font-size:18px;margin-bottom:10px"  id="id_mathjax_content_0" placeholder=""></textarea>
                    <div class="MathPreview" id="MathPreview_0" style="border: 1px solid #bbb1b1; width: 100%; height: 140px; overflow: auto; font-size: 18px;">
                    </div>    

                </div>

            </div>

        </div>
    </div>
@endsection

