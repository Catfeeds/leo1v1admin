@extends('layouts.app')
@section('content')
    <link href="/ztree/zTreeStyle.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="/ztree/jquery.ztree.all.min.js"></script>
    <script type="text/javascript" src="/ztree/jquery.ztree.core.js"></script>
    <script type="text/javascript" src="/ztree/jquery.ztree.excheck.min.js"></script>
    <script type="text/javascript" src="/ztree/jquery.ztree.exedit.min.js"></script>
    <script type="text/javascript" src="/ztree/jquery.ztree.exhide.min.js"></script>

    <script type="text/javascript" src="/page_js/lib/select_dlg.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript">
     var zExitKnow = <?php echo $exit_know?>;
     var zAllKnow = <?php echo $ret?>;
    </script>
    <style>
     .ztree *{ font-size:14px}
     .ztree li{ line-hedight:20px }
     .ztree li span.button.add { margin-left: 2px;margin-right: -1px;background-position: -144px 0;vertical-align: top;}
     .knowledge_background{ background:rgba(0, 0, 0, 0.4);position:absolute;top:0px;left:0px;width:100%;height:100%;z-index:999;display:none}
     .zTreeDemoBackground{position:absolute;top:20%;left:35%;background: white;padding:20px }
     #close_knowledge{ position:absolute;top:5px;right:5px;z-index:9999}
     .knowledge_exits span{ color: #258e25;font-weight: bold;margin-right:10px}

    </style>
    <input type="hidden" value="{{$exit_know}}" id="knowledge_old"/> 
    <section class="content">
        <div class="row">
            
            <div class="col-xs-6 col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">科目类型</span>
                    <select class="opt-change form-control" id="id_subject">
                    </select>

                    <span class="input-group-addon">教材版本</span>
                    <input type="hidden" value="{{$textbook_id}}" id="defaule_textbook_id" />
                    <select class="opt-change form-control" id="id_textbook">
                        @if($textbook)
                            @foreach($textbook as $item)
                                <option value="{{$item['textbook_id']}}">{{$item['name']}}</option>
                            @endforeach
                        @endif
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
                        <button style="margin-left:10px" id="add_textbook" type="button" class="btn btn-success">编辑教材</button>
                    </div>

                    <div class=" input-group-btn ">
                        <button style="margin-left:10px" id="question_list" type="button" class="btn btn-primary">题目列表</button>
                    </div>
                    
                    <!-- <div class=" input-group-btn ">
                         <button style="margin-left:10px" id="knowledge_pic" type="button" class="btn btn-info">知识点展现</button>
                         </div>
                    -->
                    <div class=" input-group-btn ">
                        <button style="margin-left:10px" id="get_all_knowledge" type="button" class="btn btn-success">所有知识点</button>
                    </div>

                </div>

            </div>

        </div>
        <hr/>
        <div class="row skin-blue">
            <div class="col-xs-6 col-md-6">
                <a href="javascript:;" id="show_exit_knowledge"> 显示全部知识点 </a>
                <ul id="exit_knowledge" class="ztree"></ul>
                
            </div>
        </div>

        <div class="knowledge_background">
            <div class="zTreeDemoBackground">
                <button type="button" class="btn btn-danger btn-circle" id="close_knowledge" onclick="close_know()"><i class="fa fa-times"></i></button>
                <a href="javascript:;" id="show_all_knowledge"> 显示全部知识点 </a>
                <ul id="all_knowledge" class="ztree"></ul>
                <button type="button" class="btn btn-primary" id="save_knowledge" answer_id="" onclick="save_know()" title="编辑完成">编辑完成</button>
            </div>
        </div>

    </section>

@endsection

