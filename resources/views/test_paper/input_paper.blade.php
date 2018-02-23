@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <!-- <script type="text/javascript" src="/js/qiniu/ui.js"></script> -->
    <script type="text/javascript" src="/js/qiniu/new_ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/jquery.contextify.js"></script>
    <script type="text/javascript" src="/js/area/distpicker.data.js"></script>
	  <script type="text/javascript" src="/js/area/distpicker.js"></script>
    <script type="text/javascript" src="/js/pdfobject.js"></script>
    <script>
    </script>
    <style>
     .hide{ display:none }
     .up_file,.down_file,.dele_file{ padding: 4px;margin-left: 6px;margin-bottom:5px }
     .paper_edit{ border:1px solid #999;padding:20px;width:800px }
     .fl{ float:left }
     .fr{ float:right}
     .clear_both{ clear:both }
     .paper_tab{ margin:0 auto }
     .edit_paper{ width:180px;height:50px;line-height:50px;text-align:center;font-size:18px;color:#055076;background:#c4ebf5;cursor:pointer}
     .edit_have{ color:white;background:#0995b8;}
     .edit_box{ width:720px;}
     .paper_info{ width:720px; }
     .paper_info_left,.paper_info_right{ width:359px }
     .paper_info_input{ margin:10px 0px 0px 10px }
     .paper_info_input font{ color:red }
     .paper_info_input span{ margin-right:10px;width:70px;display:inline-block }
     .paper_info_input select{ width:179px;height: 26px;background: white;}
     .paper_info_input .search_book{ height: 25px;line-height: 15px;margin-bottom: 4px;}
    </style>
    <section class="content">

        <div>
            
            <div class="row">
                <!-- <div class="row row-query-list"> -->
                <div class="col-xs-6 col-md-4" data-title="修改时间">
                    <div id="id_date_range"> </div>
                </div>

                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">评测ID</span>
                        <input class="form-control opt-change" id="id_paper"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="form-control opt-change" id="id_subject"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <select class="form-control opt-change" id="id_grade"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">上下册</span>
                        <select class="form-control opt-change" id="id_volume"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">教材版本</span>
                        <select class="form-control opt-change" id="id_book"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">录入状态</span>
                        <select class="form-control opt-change" id="id_input"> </select>
                    </div>
                </div>

                <div class="col-xs-2 col-md-2 ">
                    <button class="btn btn-primary opt-sub-tag">新建评测卷</button>
                </div>

                <div class="col-xs-2 col-md-2 ">
                    <button class="btn btn-primary opt-sub-tag">导入评测卷（excel）</button>
                </div>

            </div>
        </div>
        <hr/>
        <table class="common-table" id="menu_mark">
            <thead>
                <tr>
                    <th>评测ID</th>
                    <th>科目</th>
                    <th>年级</th>
                    <th>上下册</th>
                    <th>教材版本</th>
                    <th>操作人</th>
                    <th>修改时间</th>
                    <th>使用次数</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr class="right-menu" {!!  \App\Helper\Utils::gen_jquery_data($var )  !!} >
                        <td>{{@$var["paper_id"]}} </td>
                        <td>{{@$var["subject"]}} </td>
                        <td>{{@$var["grade"]}} </td>
                        <td>{{@$var["volume"]}} </td>
                        <td>{{@$var["book"]}} </td>
                        <td>{{@$var['operator']}}</td>
                        <td>{{@$var['modify_time']}}</td>
                        <td>{{@$var['use_number']}}</td>
                        <td style="max-width:150px">
                            <a class="opt-edit btn color-blue"  title="编辑">编辑</a>
                            <a class="opt-dele btn color-blue" title="删除">删除</a>          
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

    <div class="col-md-12 opt_process"  style="width:600px;position:fixed;right:0;top:200px;border-radius:5px;background:#eee;opacity:0.8;display:none;">
        <div class="hide" id="up_load"> </div>
        <table class="table table-striped table-hover text-left" >
            <thead>
                <tr>
                    <th class="col-md-4">文件名</th>
                    <th class="col-md-2">文件大小</th>
                    <th class="col-md-6">上传进度</th>
                </tr>
            </thead>
            <tbody id="fsUploadProgress">
            </tbody>
        </table>
    </div>

    <div class="paper_edit">
        <div class="paper_tab">
            <div class="edit_paper fl edit_none edit_have" onclick="edit_paper(this,event)">评测卷信息</div>
            <div class="edit_paper fl edit_none" onclick="edit_paper(this,event)">维度设置</div>
            <div class="edit_paper fl edit_none" onclick="edit_paper(this,event)">绑定题目</div>
            <div class="edit_paper fl edit_none" onclick="edit_paper(this,event)">维度结果与建议</div>
            <div class="clear_both"></div>
        </div>
        <div class="edit_box">
            <div class="paper_info">
                <div class="paper_info_left fl">
                    <div class="paper_info_input">
                        <span><font>*</font> 测评卷ID</span>
                        <input type="text" class="paper_id" />
                    </div>

                    <div class="paper_info_input">
                        <span><font>*</font> 测评名称</span>
                        <input type="text" class="paper_name" style="width:250px" />
                    </div>

                    <div class="paper_info_input">
                        <span><font>*</font>科目</span>
                        <select class="paper_subject" onchange="get_paper_book(this,event)"></select>
                    </div>

                    <div class="paper_info_input">
                        <span><font>*</font>上下册</span>
                        <select class="paper_volume"></select>
                    </div>

                </div>
                <div class="paper_info_right fl">
                    <div class="paper_info_input">
                        <span><font>*</font>题目数量</span>
                        <input type="text" class="paper_question" />
                    </div>

                    <div class="paper_info_input">
                        <span><font>*</font>年级</span>
                        <select class="paper_grade" onchange="get_paper_book(this,event)"></select>
                    </div>

                    <div class="paper_info_input">
                        <span><font>*</font>教材</span>
                        <select class="paper_book"></select>
                    </div>

                </div>
                <div class="clear_both"></div>
            </div>
            <div class="paper_answer"></div>
        </div>
        <div class="edit_box hide">
            22
        </div>
        <div class="edit_box hide">
            33
        </div>
        <div class="edit_box hide">
            44
        </div>


    </div>
@endsection
