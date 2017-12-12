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

    <section class="content">
        <div id="id_question_editor" >
            <div class="row">
                <div class="col-xs-6 col-md-6">
                    <div class="input-group ">
                        <div class="input-group-btn">
                            <button class="btn btn-primary" id="id_opt_type_e" title="">
                                <li class="fa   fa-exchange"></li>
                                <span>新增</span>
                            </button>
                            <select class=" form-control " id="id_reformat_flag" style="width: 60px; display: none;"></select>
                        </div>
                        <span class="input-group-addon" style="padding-left: 3px; padding-right: 6px;">年级</span>
                        <select class=" form-control " id="id_grade"></select>

                        <span class="input-group-addon" style="padding-left: 3px; padding-right: 6px;">科目</span>
                        <select class=" form-control " id="id_subject"></select>

                        <span class="input-group-addon" style="padding-left: 3px; padding-right: 6px;">类型</span>
                        <select class=" form-control " id="id_question_type"></select>

                        <span class="input-group-addon" style="padding-left: 3px; padding-right: 6px;">难度</span>
                        <select class=" form-control " id="id_difficult"></select>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="btn-toolbar" role="toolbar">
                        <div id="id_mathjax_add_pic_div" class="btn-group ">
                            <button type="button" class=" btn  btn-primary opt-title " style="height:28px">问题:</button>

                            <!-- <button type="button" class="btn btn-default  " id="id_mathjax_add_number_dollar_all" title="全部数字/字母自动加$" style="height:28px"><span>all$<span>x=1<span>$<span></span></span></span></span></button>
                                 <button type="button" class="btn btn-default  " id="id_mathjax_add_number_dollar" title="选中的加$ :(ctrl-`)" style="height:28px">多行各自加<span>$<span></span></span></button> -->

                            <button type="button" class="btn btn-default fa fa-picture-o" id="id_mathjax_add_pic" title="图片" style="z-index: 1;"></button>
                            <button type="button" class="btn btn-default " id="id_mathjax_add_under_line" title="插入下划线" style="height:28px">____</button>
                            <button type="button" class="btn btn-default " id="id_mathjax_add_kuo_hao" title="插入括号" style="height:28px">(&nbsp;&nbsp;&nbsp;&nbsp;)</button>            
                        </div>
                        <ul class="nav navbar-nav">
                            @include('question_new.mathjax')
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6 col-md-6">
                    <textarea type="text" value="" class="form-control math-opt-change-q " style="height:320px;font-size:18px;" id="id_mathjax_content" placeholder=""></textarea>
                    <textarea type="text" id="MathBuffer" style="display:none"></textarea>
                    
                    <div class="row opt-select-div">
                        <div class="col-md-6">
                            <div class="input-group ">
                                <span>A</span>
                                <input value="" class="math-opt-change-q" id="id_mathjax_q_A" placeholder="" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group ">
                                <span>B</span>
                                <input value="" class="math-opt-change-q" id="id_mathjax_q_B" placeholder="" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="row opt-select-div">
                        <div class="col-md-6">
                            <div class="input-group ">
                                <span>C</span>
                                <input value="" class="math-opt-change-q" id="id_mathjax_q_C" placeholder="" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group ">
                                <span>D</span>
                                <input value="" class="math-opt-change-q" id="id_mathjax_q_D" placeholder="" type="text">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-xs-6 col-md-6  ">
                    <div id="MathPreview" style="border: 1px solid; width: 100%; height: 400px; overflow: auto; font-size: 18px;">
                    </div>    
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    
@endsection
