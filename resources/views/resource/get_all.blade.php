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
     var tag_one = '{{@$tag_info['tag_one']['menu']}}';
     var tag_two = '{{@$tag_info['tag_two']['menu']}}';
     var tag_three = '{{@$tag_info['tag_three']['menu']}}';
     var tag_four = '{{@$tag_info['tag_four']['menu']}}';
     var tag_five = '{{@$tag_info['tag_five']['menu']}}';

     var tag_one_name = '{{@$tag_info['tag_one']['name']}}';
     var tag_two_name = '{{@$tag_info['tag_two']['name']}}';
     var tag_three_name = '{{@$tag_info['tag_three']['name']}}';
     var tag_four_name = '{{@$tag_info['tag_four']['name']}}';
     var tag_five_name = '{{@$tag_info['tag_five']['name']}}';

     var my_subject = {{@$subject}};
     var my_grade = {{@$grade}};
     var book = {{@$book}};
     var is_teacher = {{@$is_teacher}};
    </script>
    <style>
     .up_file,.down_file,.dele_file{ padding: 4px;margin-left: 6px;margin-bottom:5px };
     .hide{ display:none}
     .comment{ width:900px }
     .comment .comment_item{ width:440px;float:left; margin-bottom: 20px; }
     .comment .comment_half{ float:left; margin-bottom: 20px; margin-right:10px; }
     .comment .comment_item .comment_info span{ margin-right:10px }
     .comment .comment_eject tr td{ padding:7px 10px; text-align: center; border: 1px solid #42474a; }

     .error{ width:800px}
     .error .error_info .error_title{ font-size: 17px;font-weight: bold;}
     .error .error_info .error_status_choose{ float:right;margin-right:10px }
     .error .error_info .error_choose{ width: 120px;margin-left: 10px;display: inline-block; }
     .error .error_upload_info{ text-align: right;margin: 10px;font-size: 14px;color: #828181;}
     .error .error_content{ padding: 10px 5px;background: #fbfbfb;}
     .error .error_detail tr th,.error .error_detail tr td{border: 1px solid #aab2b7;padding:7px 10px;  }
     .error .error_detail .look_err_pic{ background: #d2cfcf;padding: 0px 20px;color: #3290a7;margin-right: 10px;}
     .error_type_1,.error_type_2{font-size: 16px;font-weight: bold;color:#029dc3;margin-right: 10px;}
     .error_author{ margin: 0px 20px;color: #6f6a6a;}
     .error_time{color: #6f6a6a; }
     .error .color-blue{ padding:0px 10px}
     .error .error_status{ color:red }
     .error .error_status_pass{ color:#04a704 }

    </style>
    <section class="content">

        <div>
            <!-- <div class="row  row-query-list" >
                 <div class="col-xs-12 col-md-5"  data-title="时间段">
                 <div  id="id_date_range" >
                 </div>
                 </div>
                 </div> -->
            <div class="row">
                <!-- <div class="row row-query-list"> -->
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">分类</span>
                        <select class="form-control opt-change" id="id_use_type"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">资源类型</span>
                        <select class="form-control opt-change" id="id_resource_type"> </select>
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

                <div class="col-xs-6 col-md-2 {{$tag_info['tag_one']['hide']}}">
                    <div class="input-group ">
                        <span class="input-group-addon">{{$tag_info['tag_one']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_one"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2 {{$tag_info['tag_two']['hide']}} ">
                    <div class="input-group ">
                        <span class="input-group-addon">{{$tag_info['tag_two']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_two"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2 {{$tag_info['tag_three']['hide']}} ">
                    <div class="input-group ">
                        <span class="input-group-addon">{{$tag_info['tag_three']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_three"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-3 {{@$tag_info['tag_four']['hide']}} ">
                    <div class="input-group ">
                        <span class="input-group-addon">{{@$tag_info['tag_four']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_four"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2 {{@$tag_info['tag_five']['hide']}} ">
                    <div class="input-group ">
                        <span class="input-group-addon">{{@$tag_info['tag_five']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_five"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否有评价</span>
                        <select class="form-control opt-change" id="id_has_comment"> </select>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-xs-2 col-md-1 ">
                    <button class="btn btn-warning opt-add">上传</button>
                </div>
                <div class="col-xs-2 col-md-1 ">
                    <button class="btn btn-warning opt-del">删除</button>
                </div>
                <div class="col-xs-2 col-md-2 ">
                    <button class="btn btn-primary opt-sub-tag">添加学科化标签</button>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <input class="opt-change form-control" id="id_file_title" placeholder="输入文件名称搜索" />
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table class="common-table" id="menu_mark">
            <thead>
                <tr>
                    <td style="width:10px">
                        <a href="javascript:;" id="id_select_all" title="全选">全</a>
                        <a href="javascript:;" id="id_select_other" title="反选">反</a>
                    </td>
                    <td>文件名</td>
                    <td>修改日期</td>
                    <td>操作人</td>
                    <td>文件格式</td>
                    <td>文件信息</td>
                    <td>文件大小</td>
                    <td>科目</td>
                    <td>年级</td>
                    @if($resource_type <= 6)
                        <td>教材</td>
                    @endif

                    @if( in_array($resource_type,[1,2,9]))
                        <td>春暑秋寒</td>
                    @endif

                    @if( $resource_type == 1 || $resource_type == 3 )
                        <td>学科化标签</td>
                        <td>难度类型</td>
                    @endif

                    @if($resource_type < 7 && $resource_type > 3)
                        <td>上下册</td>
                    @endif

                    @if($resource_type == 6)
                        <td>年份</td>
                        <td>省份</td>
                        <td>城市</td>
                    @endif

                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr class="right-menu" {!!  \App\Helper\Utils::gen_jquery_data($var )  !!} >
                        <td>
                            <input type="checkbox" class="opt-select-item" data-file_id="{{$var["file_id"]}}" data-id="{{$var["resource_id"]}}"/>
                        </td>
                        <td>{{@$var["file_title"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["file_type"]}} </td>
                        <td>{{@$var["file_use_type_str"]}} </td>
                        <td>{{@$var["file_size_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        @if( $resource_type <= 6)
                            <td>{{@$var["tag_one_str"]}} </td>
                        @endif

                        @if( in_array($resource_type,[1,2,9]))
                            <td>{{@$var["tag_two_str"]}}</td>
                        @endif

                        @if( $resource_type == 1 || $resource_type == 3)
                            <td>{{@$var["tag_four_str"]}} </td>
                            @if( $resource_type == 1)
                                <td>{{@$var["tag_five_str"]}} </td>
                            @else
                                <td>{{@$var["tag_three_str"]}} </td>
                            @endif
                        @endif

                        @if($resource_type < 7 && $resource_type > 3)
                            <td>{{@$var["tag_five_str"]}}</td>
                        @endif

                        @if($resource_type == 6)
                            <td>{{@$var["tag_two"]}}</td>
                            <td class="province">{{@$var["tag_three"]}}</td>
                            <td class="city">{{@$var["tag_four"]}}</td>
                        @endif

                        <td>
                            <a class="opt-look btn color-blue" data-file_id="{{$var["file_id"]}}"  title="预览">预览</a>
                            <a class="opt-comment btn color-blue" data-file_id="{{$var["file_id"]}}"  title="评价">
                                @if($var['comment'] > 0 )
                                    评价({{$var['comment']}})
                                @else
                                    评价(0)
                                @endif
                            </a>
                            <a class="opt-error btn color-blue" data-file_id="{{$var["file_id"]}}" data-resource_type="{{$var["resource_type"]}}"  title="报错">报错</a>
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

    <div class="comment hide">
        <div class="comment_item">
            <p class="comment_info">
                <span>质量总评</span>
                <span>人数：<b class="comment_num">100</b>人</span>
                <span>均分：<b class="comment_quality_score">3.9</b>分</span>
            </p>
            <table class="comment_eject comment_quality">
                <tbody>
                    <tr>
                        <td>五星</td>
                        <td>四星</td>
                        <td>三星</td>
                        <td>二星</td>
                        <td>一星</td>
                    </tr>
                    <tr>
                        <td>质量很高</td>
                        <td>质量较高</td>
                        <td>质量一般</td>
                        <td>质量较差</td>
                        <td>质量很差</td>
                    </tr>
                    <tr>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                    </tr>

                </tbody>
                
            </table>
        </div>

        <div class="comment_item">
            <p class="comment_info">
                <span>帮助指数</span>
                <span>人数：<b class="comment_num">100</b>人</span>
                <span>均分：<b class="comment_help_score">3.9</b>分</span>
            </p>
            <table class="comment_eject comment_help">
                <tbody>
                    <tr>
                        <td>五星</td>
                        <td>四星</td>
                        <td>三星</td>
                        <td>二星</td>
                        <td>一星</td>
                    </tr>
                    <tr>
                        <td>帮助极大</td>
                        <td>帮助较高</td>
                        <td>帮助一般</td>
                        <td>帮助较小</td>
                        <td>毫无帮助</td>
                    </tr>
                    <tr>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                    </tr>
                </tbody>                
            </table>
        </div>

        <div class="comment_item">
            <p class="comment_info">
                <span>全面指数</span>
                <span>人数：<b class="comment_num">100</b>人</span>
                <span>均分：<b class="comment_whole_score">3.9</b>分</span>
            </p>
            <table class="comment_eject comment_whole">
                <tbody>
                    <tr>
                        <td>五星</td>
                        <td>四星</td>
                        <td>三星</td>
                        <td>二星</td>
                        <td>一星</td>
                    </tr>
                    <tr>
                        <td>内容很全</td>
                        <td>内容较全</td>
                        <td>一般全面</td>
                        <td>不够全面</td>
                        <td>毫无帮助</td>
                    </tr>
                    <tr>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                    </tr>
                </tbody>                
            </table>
        </div>

        <div class="comment_item">
            <p class="comment_info">
                <span>详细指数</span>
                <span>人数：<b class="comment_num">100</b>人</span>
                <span>均分：<b class="comment_detail_score">3.9</b>分</span>
            </p>
            <table class="comment_eject comment_detail">
                <tbody>
                    <tr>
                        <td>五星</td>
                        <td>四星</td>
                        <td>三星</td>
                        <td>二星</td>
                        <td>一星</td>
                    </tr>
                    <tr>
                        <td>非常详细</td>
                        <td>比较详细</td>
                        <td>一般详细</td>
                        <td>不够详细</td>
                        <td>很不详细</td>
                    </tr>
                    <tr>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                    </tr>
                </tbody>                
            </table>
        </div>

        <div class="comment_half">
            <p class="comment_info">
                <span>文字大小</span>
                <span>人数：<b class="comment_num">100</b>人</span>              
            </p>
            <table class="comment_eject comment_font">
                <tbody>
                    <tr>
                        <td>文字太大</td>
                        <td>文字太小</td>
                        <td>文字大小适中</td>
                    </tr>
                    <tr>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                    </tr>

                </tbody>
                
            </table>
        </div>

        <div class="comment_half">
            <p class="comment_info">
                <span>间距大小</span>
                <span>人数：<b class="comment_num">100</b>人</span>
            </p>
            <table class="comment_eject comment_gap">
                <tbody>
                    <tr>
                        <td>间距太大</td>
                        <td>间距太小</td>
                        <td>间距适中</td>
                    </tr>
                    <tr>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                    </tr>

                </tbody>
                
            </table>
        </div>

        <div class="comment_half">
            <p class="comment_info">
                <span>背景图案</span>
                <span>人数：<b class="comment_num">100</b>人</span>
            </p>
            <table class="comment_eject comment_bg">
                <tbody>
                    <tr>
                        <td>背景太单调</td>
                        <td>背景太浮夸</td>
                        <td>背景风格适中</td>
                    </tr>
                    <tr>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="comment_half">
            <p class="comment_info">
                <span>讲义类型</span>
                <span>人数：<b class="comment_num">0</b>人</span>
            </p>
            <table class="comment_eject comment_type">
                <tbody>
                    <tr>
                        <td>纯知识梳理</td>
                        <td>纯题目练习</td>
                        <td>讲解与练习相结合</td>
                    </tr>
                    <tr>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="comment_half">
            <p class="comment_info">
                <span>答案程度</span>
                <span>人数：<b class="comment_num">0</b>人</span>
            </p>
            <table class="comment_eject comment_answer">
                <tbody>
                    <tr>
                        <td>答案有解题过程</td>
                        <td>答案无解题过程</td>
                        <td>答案有多个解题方法</td>
                    </tr>
                    <tr>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="comment_half">
            <p class="comment_info">
                <span>适宜学生</span>
                <span>人数：<b class="comment_num">0</b>人</span>
            </p>
            <table class="comment_eject comment_student">
                <tbody>
                    <tr>
                        <td>基础，难度较低</td>
                        <td>中等，难度居中</td>
                        <td>提优，难度较高</td>
                    </tr>
                    <tr>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="comment_half comment_test hide">
            <p class="comment_info">
                <span>试听课时长</span>
                <span>人数：<b class="comment_num">0</b>人</span>
            </p>
            <table class="comment_eject comment_test_time">
                <tbody>
                    <tr>
                        <td>30分钟</td>
                        <td>40分钟</td>
                        <td>50分钟</td>
                        <td>60分钟</td>
                        <td>70分钟</td>
                        <td>80分钟</td>
                        <td>其他</td>
                    </tr>
                    <tr>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="comment_half comment_other">
            <p class="comment_info">
                <span>试听课时长</span>
                <span>人数：<b class="comment_num">0</b>人</span>
            </p>
            <table class="comment_eject comment_other_time">
                <tbody>
                    <tr>
                        <td>90分钟</td>
                        <td>100分钟</td>
                        <td>110分钟</td>
                        <td>120分钟</td>
                        <td>130分钟</td>
                        <td>140分钟</td>
                        <td>150分钟</td>
                        <td>160分钟</td>
                        <td>170分钟</td>
                        <td>180分钟</td>
                        <td>其他</td>
                    </tr>
                    <tr>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                        <td>0%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="clear:both"></div>
    </div>

    <div class="error hide">
        <div class="error_info">
            <span class="error_title">全部报错（<b class="error_no">0</b>）</span>
            <div class="error_status_choose">
                <span>状态选择</span>
                <select class="form-control error_choose">
                    <option value="-1">全部</option>
                    <option value="0">未处理</option>
                    <option value="1">同意修改</option>
                    <option value="3">初审驳回</option>
                    <option value="4">复审驳回</option>
                </select>
            </div>
            <div style="clear:both"></div>
        </div>
        <p class="error_upload_info">提示：该文件如果还没有未处理的报错，请处理完再重传</p>
        <div class="error_content">
            <table class="error_detail">
                <thead>
                    <tr>
                        <th width="70%">报错内容</th>
                        <th width="15%">状态</th>
                        <th width="15%">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="error_item">
                        <td>
                            <div class="error_item_content">
                                <p></p>
                                <div class="err_pic_box" style="margin-top:20px">
                                    <a class="look_err_pic btn hide" onclick="show_error_pic(this,event)" link="">图片</a>
                                </div>

                                <div style="margin-top:20px">
                                    <span class="error_type_1"></span>
                                    <span class="error_type_2"></span>
                                    <span class="error_author"></span>
                                    <span class="error_time"></span>
                                </div>
                            </div>
                        </td>
                        <td class="error_deal_box"></td>
                        <td>
                            <p><a class="error_agree btn color-blue" onclick="error_agree(this,event)" >同意修改</a></p>
                            <p><a class="err_first_check btn color-blue" onclick="error_first_check(this,event)" >初审驳回</a></p>
                            <p><a class="err_sec_check btn color-blue" onclick="error_second_check(this,event)" >复审驳回</a></p>
                        </td>
                      
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
