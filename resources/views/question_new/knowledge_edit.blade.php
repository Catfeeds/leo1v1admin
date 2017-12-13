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
    <input type="hidden" value="{{$editData}}" id="editData" />
    <input type="hidden" value="{{@$ret['father_id']}}" id="father_id" />
    <input type="hidden" value="{{@$ret['level']}}" id="level" />
    <input type="hidden" value="{{@$ret['father_other']}}" id="father_other" />
    <section class="content">
        <div id="id_question_editor" >
            <div class="row">
                <div class="col-xs-6 col-md-6">
                    <div class="input-group "><h3>{{@$ret['describe']}}</h3></div>
                    <div class="input-group ">
                        
                        <span class="input-group-addon" style="padding-left: 3px; padding-right: 6px;">科目</span>
                        <select class=" form-control " id="id_subject"></select>

                        <!-- <span class="input-group-addon" style="padding-left: 3px; padding-right: 6px;">类型</span>
                             <select class=" form-control " id="id_question_type"></select>
                        -->

                        <span class="input-group-addon" style="padding-left: 3px; padding-right: 6px;">是否开启</span>
                        <select class=" form-control " id="id_open_flag"></select>

                        @if(@$ret['father_id'] == -1)
                            <div class=" input-group-btn " style="margin-left:10px">
                                <button id="add_other_father" type="submit" class="btn btn-primary">
                                    <i class="fa fa-plus"></i>添加其它父知识点
                                </button>
                            </div>
                        @endif

                        <div class=" input-group-btn " style="margin-left:10px">
                            <button id="save_know" type="submit" class="btn  btn-warning">
                                <i class="fa fa-plus"></i>保存知识点
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="btn-toolbar" role="toolbar">
                        <div id="id_mathjax_add_pic_div_0" class="btn-group ">
                            <button type="button" class=" btn  btn-primary opt-title " style="height:28px">知识点:</button>
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
                    <textarea type="text" value="" class="form-control math-opt-change-q id_mathjax_content" style="height:140px;font-size:18px;"  id="id_mathjax_content_0" placeholder=""></textarea>
                    
                </div>

                <div class="col-xs-6 col-md-6  ">
                    <div class="MathPreview" id="MathPreview_0" style="border: 1px solid; width: 100%; height: 140px; overflow: auto; font-size: 18px;">
                    </div>    
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="btn-toolbar" role="toolbar">
                        <div id="id_mathjax_add_pic_div_1" class="btn-group ">
                            <button type="button" class=" btn  btn-primary opt-title " style="height:28px">知识点详情解读:</button>
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
                    <textarea type="text" value="" class="form-control math-opt-change-q id_mathjax_content" style="height:140px;font-size:18px;" id="id_mathjax_content_1" placeholder=""></textarea>
                    
                </div>

                <div class="col-xs-6 col-md-6  ">
                    <div class="MathPreview" id="MathPreview_1" style="border: 1px solid; width: 100%; height: 140px; overflow: auto; font-size: 18px;">
                    </div>    
                </div>
            </div>

        </div>
    </section>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    
@endsection
