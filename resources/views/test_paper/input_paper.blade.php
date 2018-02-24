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
     .paper_edit{ border:1px solid #999;padding:20px;width:1000px }
     .fl{ float:left }
     .fr{ float:right}
     .clear_both{ clear:both }
     .paper_tab{ margin:0 auto }
     .edit_paper{ width:238px;height:50px;line-height:50px;text-align:center;font-size:18px;color:#055076;background:#c4ebf5;cursor:pointer}
     .edit_have{ color:white;background:#0995b8;}
     .edit_box{ width:960px;}
     .paper_info{ width:960px; }
     .paper_info_left,.paper_info_right{ width:479px }
     .paper_info_input{ margin:10px 0px 0px 10px }
     .paper_info_input font{ color:red }
     .paper_info_input span{ margin-right:10px;width:70px;display:inline-block }
     .paper_info_input select{ width:200px;height: 32px;background: white;}
     .paper_info_input .search_book{ height: 25px;line-height: 15px;margin-bottom: 4px;}
     .paper_answer,.suggestion_info,.suggest_result{ width:960px; margin-top:10px}
     .paper_answer table{ width:960px;}
     .paper_answer table tr th, .paper_answer table tr td { border:1px solid #4b5d6a;padding:10px 5px }
     .paper_answer table tr.edit_answer td{ padding:0px }
     .add_answer{ text-align:center;color:#0995b8;cursor:pointer }
     .paper_answer table tr td input{ width:100%;height:100%;border:0px;height:30px;text-indent: 5px;}
     .edit_answer a{ cursor:pointer;margin-left:5px}
     .answer_save{ text-align:center;margin-top:10px }
     .answer_save .answer_save_all{ padding: 5px 20px; font-size: 18px;}
     .paper_dimension,.dimension_box,.dimension_bind{ width:960px; margin-top:10px}
     .paper_dimension table,.dimension_box table,.dimension_bind table,.suggestion_info table{ width:960px;}
     .paper_dimension table tr th, .paper_dimension table tr td { border:1px solid #4b5d6a;padding:10px 5px }
     .paper_dimension table tr td input{ width:100%;border:0px;text-indent: 5px;}
     .paper_dimension table tr.edit_dimension td.edit_dimension_name{ padding:0px }
     .dimension-dele{ cursor:pointer }
     .check_dimension{ font-size:14px;margin-top:10px }
     .check_dimension span{ margin-right:10px;font-size:16px }
     .check_dimension .dimension_item{ width:200px;height: 32px;background: white; }
     .dimension_box table tr th, .dimension_box table tr td,.dimension_bind table tr th, .dimension_bind table tr td,.suggestion_info table tr th, .suggestion_info table tr td,.suggest_result table tr th, .suggest_result table tr td { border:1px solid #4b5d6a;padding:10px 5px }
     .dimension_bind input[type=checkbox]{ width :18px;height :18px;}
     .dimension_box .dimension_var a{ cursor:pointer}
     .suggest_result{ padding : 10px 15px;background:rgba(200, 196, 196, 0.15)}
     .suggest_result table{ width :900px }
     .suggest_result span{ font-size:14px;font-weight:bold;margin:8px 0px}
     .suggest_result span font{ color:red}
     .suggest_result table{margin-top:10px;background:#faf8f8 }
     .suggest_result .suggest_score{ margin:10px 0px}
     .suggest_result .suggest_supply{ position:relative;}
     .suggest_result .suggest_supply span{ position:absolute;width:100px;top:0px}
     .suggest_result .suggest_supply textarea{ margin:10px 0px 0px 100px;width:750px;height:160px}
     .suggest_result .suggest_score input{ width:50px}
     .suggest_save{ margin-top:10px;text-align:center}
     .suggest_save button{ margin:0 auto}
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
                    <button class="btn btn-primary add_new_paper">新建评测卷</button>
                </div>

                <div class="col-xs-2 col-md-2 ">
                    <button class="btn btn-primary import_paper">导入评测卷（excel）</button>
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
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["volume_str"]}} </td>
                        <td>{{@$var["book_str"]}} </td>
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

    <div class="paper_edit hide">
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
            <div class="paper_answer">
                <table>
                    <thead>
                        <tr>
                            <th width="10%">题目序号</th>
                            <th width="40%">题目描述/题目</th>
                            <th width="20%">标准答案</th>
                            <th width="10%">分值</th>
                            <th width="20%">操作</th>
                        </tr>
                    </thead>                 
                    <tbody>
                        <tr class="edit_answer hide">
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td>
                                <a class="answer-insert" onclick="answer_insert(this,event)" title="插入">插入</a>
                                <a class="answer-dele" onclick="answer_dele(this,event)" title="删除">删除</a>
                                <a class="answer-up" onclick="answer_up(this,event)" title="上移">上移</a>      
                                <a class="answer-dowm" onclick="answer_down(this,event)" title="下移">下移</a>      
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="add_answer" onclick="add_answer(this,event)">
                                <i class="fa fa-plus"></i>增加题目
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="answer_save">
                <button class="btn btn-primary answer_save_all" onclick="save_answer(this,event)">保存</button>
            </div>
        </div>
        <div class="edit_box hide">
            <div class="paper_dimension">
                <table>
                    <thead>
                        <tr>
                            <th width="10%">编号</th>
                            <th width="50%">维度名称</th>
                            <th width="20%">题目数</th>
                            <th width="20%">操作</th>
                        </tr>
                    </thead>                 
                    <tbody>
                        <tr class="edit_dimension hide">
                            <td></td>
                            <td class="edit_dimension_name"><input type="text"></td>
                            <td></td>
                            <td>
                                <a class="dimension-dele" onclick="dimension_dele(this,event)" title="删除">删除</a>                     
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="add_answer" onclick="add_dimension(this,event)">
                                <i class="fa fa-plus"></i>增加维度
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="answer_save">
                <button class="btn btn-primary answer_save_all" onclick="save_dimension(this,event)">保存维度</button>
            </div>

        </div>
        <div class="edit_box hide">
            <div class="check_dimension">
                <span>选择维度</span>
                <select class="dimension_item" onchange="get_dimension(this.options[this.options.selectedIndex].value,event)">
                    <option value="-1">全部</option>
                </select>
            </div>
            <div class="dimension_box">
                <table>
                    <thead>
                        <tr>
                            <th>维度</th>
                            <th>已绑定的题目</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="dimension_var hide">
                            <td></td>
                            <td></td>
                            <td>
                                <a onclick="dimension_bind(this,event)" title="绑定">绑定</a>       
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="dimension_bind hide">
                <table>
                    <thead>
                        <tr>
                            <th>维度</th>
                            <th>已绑定的题目</th>
                            <th>绑定的维度</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="dimension_answer hide">
                            <td><input type="checkbox" class="have_bind" onclick="click_dimension(event)"></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <div class="answer_save">
                    <button class="btn btn-primary answer_save_all" onclick="save_bind(this,event)">保存绑定</button>
                </div>

            </div>

        </div>
        <div class="edit_box hide">
            <div class="suggestion_info">
                <table>
                    <thead>
                        <tr>
                            <th width="20%">编号</th>
                            <th width="50%">维度名称</th>
                            <th width="30%">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="suggest_item hide">
                            <td></td>
                            <td></td>
                            <td>
                                <a onclick="suggest_set(this,event)" title="设置维度结果">设置维度结果</a>       
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>

            <div class="suggest_result hide">
                <span class="suggest_dimension">维度：<font>代数知识</font></span>
                <table>
                    <thead>
                        <tr>
                            <th width="20%">得分范围</th>
                            <th width="50%">评测结果与建议</th>
                            <th width="30%">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="suggest_item">
                            <td></td>
                            <td></td>
                            <td>
                                <a onclick="suggest_edit(this,event)" title="修改">修改</a>
                                <a onclick="suggest_dele(this,event)" title="删除">删除</a> 
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="suggest_score">
                    <span>得分范围：</span>
                    <span><input type="text" class="score_min"></span>
                    <span>-</span>
                    <span><input type="text" class="score_max"></span>
                    <span class="score_total">总分：<font>8</font></span>
                </div>
                <div class="suggest_supply">
                    <span>结果与建议：</span>
                    <textarea></textarea>
                </div>
                <div class="suggest_save">
                    <button class="btn btn-info answer_save_all" onclick="save_suggest(this,event)">新增结果与建议</button>
                </div>
            </div>

        </div>


    </div>
@endsection
