@extends('layouts.teacher_header')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/area/distpicker.data.js"></script>
	<script type="text/javascript" src="/js/area/distpicker.js"></script>
	<script type="text/javascript" src="/js/pdfobject.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script>
     var tag_one = '{{$tag_info['tag_one']['menu']}}';
     var tag_two = '{{$tag_info['tag_two']['menu']}}';
     var tag_three = '{{$tag_info['tag_three']['menu']}}';
     var tag_four = '{{$tag_info['tag_four']['menu']}}';
     var tag_five = '{{$tag_info['tag_five']['menu']}}';
     var tea_sub = {!! @$tea_sub!!} ;
     var tea_gra = {!! @$tea_gra !!};
     var type_list = {!! @$type_list !!};
     var book = {{@$book}};
     var resource_type = {{@$resource_type}};
    </script>
    <style>
     .fl{ float:left }
     .fr{ float:right }
     .clall{ clear:both}
     .comment{ width:750px}
     .hide{ display:none}
     .comment .comment_item{ margin-bottom:15px }
     .comment .comment_title{ width:100px;font-size:14px;font-weight:bold}
     .comment .comment_star{ width:160px}
     .comment .comment_info{ width:380px;font-size:13px;color:#948f8f;padding-top: 3px;}
     .comment .comment_radio{ width:450px;text-align: left; color: black; }
     .comment .comment_radio label{ margin-right: 20px;padding-left: 20px; position: relative;font-weight:normal}
     .comment .comment_radio input{ width:15px;height:15px;border:1px; position: absolute;left: 0px;}

     .error{ width:600px;text-align:left }
     .error .error_type select{ min-width:240px;min-height:30px;margin-right:10px}
     .error .error_detail{ width:500px;height:300px}
     .error .error_pic_info{ color:#948f8f}
     .error .error_pic_box{ margin-right:15px }
     .error .error_pic_box img{ border: 3px solid #a09b9b; }
     .error .error_button{ width: 100px;height: 100px;font-size: 60px;border: 1px dashed #b1b1b1;}
     .error_pic_change{ padding-top: 10px; position: relative; height: 30px;width: 100px;}
     .error_pic_change a{ position: absolute;z-index: 9999; }
     .error_pic_change a:first-child{ left: 5px;}
     .error_pic_change a:last-child{ right: 5px;}
    </style>
    <section class="content li-section">
        <div>
            <!-- <div class="row  row-query-list" >
                 <div class="col-xs-12 col-md-5"  data-title="时间段">
                 <div  id="id_date_range" >
                 </div>
                 </div>
                 </div> -->
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="input-group ">
                        <span class="input-group-addon">资源类型</span>
                        <select class="form-control opt-change" id="id_resource_type"> </select>
                    </div>
                </div>


                <div class="col-xs-6 col-sm-3 col-md-2 col-lg-2">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="form-control opt-change" id="id_subject"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-sm-3 col-md-2 col-lg-2">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <select class="form-control opt-change" id="id_grade"> </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2 {{$tag_info['tag_one']['hide']}}">
                    <div class="input-group ">
                        <span class="input-group-addon">{{$tag_info['tag_one']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_one"> </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2 {{$tag_info['tag_two']['hide']}} ">
                    <div class="input-group ">
                        <span class="input-group-addon">{{$tag_info['tag_two']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_two"> </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2 {{$tag_info['tag_three']['hide']}} ">
                    <div class="input-group ">
                        <span class="input-group-addon">{{$tag_info['tag_three']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_three"> </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 {{$tag_info['tag_four']['hide']}} ">
                    <div class="input-group ">
                        <span class="input-group-addon">{{$tag_info['tag_four']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_four"> </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 {{$tag_info['tag_five']['hide']}} ">
                    <div class="input-group ">
                        <span class="input-group-addon">{{$tag_info['tag_five']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_five"> </select>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table" id="menu_mark">
            <thead>
                <tr>
                    <td>文件名</td>
                    <td>科目</td>
                    <td>年级</td>
                    @if(in_array($resource_type,[1,3,5,6]))
                        <td>教材</td>
                    @endif
                    @if(in_array($resource_type,[1,2]))
                        <td>春暑秋寒</td>
                    @endif
                    <td>修改日期</td>
                    <td>文件格式</td>
                    <td>文件信息</td>
                    <td>文件大小</td>
                    <td>使用次数</td>
                    <td>收藏状态</td>
                    <td> 操作 </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr data-resource_id="{{@$var['resource_id']}}">
                        <td>{{@$var["file_title"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        @if(in_array($resource_type,[1,3,5,6]))
                            <td>{{@$var["tag_one_str"]}}</td>
                        @endif
                        @if(in_array($resource_type,[1,2]))
                            <td>{{@$var["tag_two_str"]}}</td>
                        @endif
                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var["file_type"]}} </td>
                        <td>{{@$var["file_use_type_str"]}} </td>
                        <td>{{@$var["file_size"]}}</td>
                        <td>{{@$var["use_num"]}} </td>
                        <td>
                            @if(@$var['tea_res_id'] == 0)
                                <a class="collect opt-get btn color-red" data-file_id="{{@$var['file_id']}}">未收藏</a>
                            @else
                                <a class="collect btn color-blue" data-id="{{@$var['tea_res_id']}}" data-file_id="{{@$var['file_id']}}">已收藏</a>
                            @endif
                        </td>
                        <td>
                            <a class="opt-look_new btn color-blue"  title="预览" data-file_id="{{@$var['file_id']}}" data-file_type="{{@$var['file_type']}}">预览</a>

                            <a class="opt-error btn color-blue"  title="报错" data-file_id="{{@$var['file_id']}}" data-resource_type="{{@$var['resource_type']}}">报错</a>
                            @if(@$var['is_eval'] > 0)
                            <a class="btn color-blue"  title="评价" data-file_id="{{@$var['file_id']}}"
                             data-resource_type="{{@$var['resource_type']}}">已评价</a>
                            @else
                            <a class="opt-comment btn color-blue"  title="评价" data-file_id="{{@$var['file_id']}}"
                             data-resource_type="{{@$var['resource_type']}}">评价</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    <div class="col-md-12 look-pdf"   style="width:80%;height:95%;position:fixed;right:10%;top:2.5%;border-radius:5px;background:#eee;display:none;z-index:8888;overflow:hidden;">
        <div class="look-pdf-son">
        </div>
    </div>

    
    <div class="comment hide">
        <div class="comment_item" onmouseleave="cancel(this,event)">
            <span class="comment_title fl">质量总评：</span>
            <div class="comment_star comment_quality fl" onmouseover="rate(this,event)" score="0">
                <img src="/img/x1.png" title="1分" />
                <img src="/img/x1.png" title="2分" />
                <img src="/img/x1.png" title="3分" />
                <img src="/img/x1.png" title="4分" />
                <img src="/img/x1.png" title="5分" />
            </div>
            <div class="comment_info fl">
                <span>质量很差/</span>
                <span>质量较差/</span>
                <span>质量一般/</span>
                <span>质量较高/</span>
                <span>质量很高</span>
            </div>
            <div class="clall"></div>
        </div>
        <div class="comment_item" onmouseleave="cancel(this,event)">
            <span class="comment_title fl">帮助指数：</span>
            <div class="comment_star comment_help fl" onmouseover="rate(this,event)" score="0">
                <img src="/img/x1.png" title="1分" />
                <img src="/img/x1.png" title="2分" />
                <img src="/img/x1.png" title="3分" />
                <img src="/img/x1.png" title="4分" />
                <img src="/img/x1.png" title="5分" />
            </div>
            <div class="comment_info fl">
                <span>毫无帮助/</span>
                <span>帮助较小/</span>
                <span>帮助一般/</span>
                <span>帮助较高/</span>
                <span>帮助极大</span>
            </div>
            <div class="clall"></div>
        </div>
        <div class="comment_item" onmouseleave="cancel(this,event)">
            <span class="comment_title fl">全面指数：</span>
            <div class="comment_star comment_whole fl" onmouseover="rate(this,event)" score="0">
                <img src="/img/x1.png" title="1分" />
                <img src="/img/x1.png" title="2分" />
                <img src="/img/x1.png" title="3分" />
                <img src="/img/x1.png" title="4分" />
                <img src="/img/x1.png" title="5分" />
            </div>
            <div class="comment_info fl">
                <span>很不全面/</span>
                <span>不够全面/</span>
                <span>一般全面/</span>
                <span>内容较全/</span>
                <span>内容很全</span>
            </div>
            <div class="clall"></div>
        </div>
        <div class="comment_item" onmouseleave="cancel(this,event)">
            <span class="comment_title fl">详细指数：</span>
            <div class="comment_star comment_detail fl" onmouseover="rate(this,event)" score="0">
                <img src="/img/x1.png" title="1分" />
                <img src="/img/x1.png" title="2分" />
                <img src="/img/x1.png" title="3分" />
                <img src="/img/x1.png" title="4分" />
                <img src="/img/x1.png" title="5分" />
            </div>
            <div class="comment_info fl">
                <span>很不详细/</span>
                <span>不够详细/</span>
                <span>一般详细/</span>
                <span>比较详细/</span>
                <span>非常详细</span>
            </div>
            <div class="clall"></div>
        </div>
        <div class="comment_item">
            <span class="comment_title fl">文字大小：</span>
            <div class="comment_radio comment_font fl">
                <label><input type ="radio" name = "con_font" value ="1">文字太大</label>
                <label><input type ="radio" name = "con_font" value ="2">文字太小</label>
                <label><input type ="radio" name = "con_font" value ="3" checked>文字大小适中</label>
            </div>
            <div class="clall"></div>
        </div>

        <div class="comment_item">
            <span class="comment_title fl">间距大小：</span>
            <div class="comment_radio comment_spacing fl">
                <label><input type ="radio" name = "con_spacing" value ="1">间距太大</label>
                <label><input type ="radio" name = "con_spacing" value ="2">间距太小</label>
                <label><input type ="radio" name = "con_spacing" value ="3" checked>间距适中</label>
            </div>
            <div class="clall"></div>
        </div>

        <div class="comment_item">
            <span class="comment_title fl">背景图案：</span>
            <div class="comment_radio comment_img fl">
                <label><input type ="radio" name = "con_img" value ="1">背景太单调</label>
                <label><input type ="radio" name = "con_img" value ="2">背景太浮夸</label>
                <label><input type ="radio" name = "con_img" value ="3" checked>背景风格适中</label>
            </div>
            <div class="clall"></div>
        </div>

        <div class="comment_item">
            <span class="comment_title fl">讲义类型：</span>
            <div class="comment_radio comment_type fl">
                <label><input type ="radio" name = "con_type" value ="1">纯知识梳理</label>
                <label><input type ="radio" name = "con_type" value ="2">纯题目练习</label>
                <label><input type ="radio" name = "con_type" value ="3" checked>讲解与练习相结合</label>
            </div>
            <div class="clall"></div>
        </div>

        <div class="comment_item">
            <span class="comment_title fl">答案程度：</span>
            <div class="comment_radio comment_answer fl">
                <label><input type ="radio" name = "con_answer" value ="1">答案有解题过程</label>
                <label><input type ="radio" name = "con_answer" value ="2">答案无解题过程</label>
                <label><input type ="radio" name = "con_answer" value ="3" checked>答案有多个解题方法</label>
            </div>
            <div class="clall"></div>
        </div>

        <div class="comment_item">
            <span class="comment_title fl">适宜学生：</span>
            <div class="comment_radio comment_type fl">
                <label><input type ="radio" name = "con_stu" value ="1">基础，难度较低</label>
                <label><input type ="radio" name = "con_stu" value ="2" checked>中等，难度适中</label>
                <label><input type ="radio" name = "con_stu" value ="3">提优，难度较高</label>
            </div>
            <div class="clall"></div>
        </div>
        
        <div class="comment_item comment_test_listen">
            <span class="comment_title fl">试听课时长：</span>
            <div class="comment_radio comment_type fl">
                <label><input type ="radio" name = "con_test_time" value ="30分钟">30分钟</label>
                <label><input type ="radio" name = "con_test_time" value ="40分钟" checked>40分钟</label>
                <label><input type ="radio" name = "con_test_time" value ="50分钟">50分钟</label>
                <label><input type ="radio" name = "con_test_time" value ="60分钟">60分钟</label>
                <label><input type ="radio" name = "con_test_time" value ="70分钟">70分钟</label>
                <label><input type ="radio" name = "con_test_time" value ="80分钟">80分钟</label>
                <label><input type ="radio" name = "con_test_time" value ="其他">其他</label>
            </div>
            <div class="clall"></div>
        </div>

        <div class="comment_item comment_other_listen">
            <span class="comment_title fl">精品课时长：</span>
            <div class="comment_radio comment_type fl">
                <label><input type ="radio" name = "con_time" value ="90分钟">90分钟</label>
                <label><input type ="radio" name = "con_time" value ="100分钟" checked>100分钟</label>
                <label><input type ="radio" name = "con_time" value ="110分钟">110分钟</label>
                <label><input type ="radio" name = "con_time" value ="120分钟">120分钟</label>
                <label><input type ="radio" name = "con_time" value ="130分钟">130分钟</label>
                <label><input type ="radio" name = "con_time" value ="140分钟">140分钟</label>
                <label><input type ="radio" name = "con_time" value ="150分钟">150分钟</label>
                <label><input type ="radio" name = "con_time" value ="160分钟">160分钟</label>
                <label><input type ="radio" name = "con_time" value ="170分钟">170分钟</label>
                <label><input type ="radio" name = "con_time" value ="180分钟">180分钟</label>
                <label><input type ="radio" name = "con_time" value ="其他">其他</label>
            </div>
            <div class="clall"></div>
        </div>

    </div>

    <div class="error hide">
        <h4>错误类型：</h4>
        <div class="error_type">
            <select class="error_type_01" onchange='get_err_sec(this.options[this.options.selectedIndex].value)'>
                @foreach($err_type as $k => $type)
                    <option value="{{$k}}">{{$type}}</option>
                @endforeach
            </select>
            <select class="error_type_02">
                @foreach($err_knowledge as $k => $type)
                    <option value="{{$k}}">{{$type}}</option>
                @endforeach

            </select>

            
        </div>
        <h4>错误详情：</h4>
        <div class="error_detail_box">
            <textarea class="error_detail"></textarea>
        </div>
        <p class="error_pic_info">仅支持jpeg,jpg,png,gif格式图片，大小不超过2M，最多上传5张</p>
        <div class="error_upload">
            <div class="error_pic_box hide fl">
                <img width="100">
                <div class="error_pic_change">
                    <a class="pic_change_01" href="javascript:;">更改</a>
                    <a onclick="dele_upload(this,event)" href="javascript:;">删除</a>
                </div>
            </div>
            <div class="error_pic_box hide fl">
                <img width="100">
                <div class="error_pic_change">
                    <a class="pic_change_02" href="javascript:;">更改</a>
                    <a onclick="dele_upload(this,event)" href="javascript:;">删除</a>
                </div>
            </div>
            <div class="error_pic_box hide fl">
                <img width="100">
                <div class="error_pic_change">
                    <a class="pic_change_03" href="javascript:;">更改</a>
                    <a onclick="dele_upload(this,event)" href="javascript:;">删除</a>
                </div>
            </div>
            <div class="error_pic_box hide fl">
                <img width="100">
                <div class="error_pic_change">
                    <a class="pic_change_04" href="javascript:;">更改</a>
                    <a onclick="dele_upload(this,event)" href="javascript:;">删除</a>
                </div>
            </div>
            <div class="error_pic_box hide fl">
                <img width="100">
                <div class="error_pic_change">
                    <a class="pic_change_05" href="javascript:;">更改</a>
                    <a onclick="dele_upload(this,event)" href="javascript:;">删除</a>
                </div>
            </div>

            <input type="button" class="error_button fl" value="+" >
            <div class="clall"></div>
        </div>
    </div>

    <div class="error_pic_box fl hide">
        <img width="100">
        <div class="error_pic_change">
            <a onclick="change_upload(this,event)" href="javascript:;">更改</a>
            <a onclick="dele_upload(this,event)" href="javascript:;">删除</a>
        </div>
    </div>

    <select class="err_knowledge hide">
        @foreach($err_knowledge as $k => $type)
            <option value="{{$k}}">{{$type}}</option>
        @endforeach
    </select>

    <select class="err_question_answer hide">
        @foreach($err_question_answer as $k => $type)
            <option value="{{$k}}">{{$type}}</option>
        @endforeach
    </select>

    <select class="err_code hide">
        @foreach($err_code as $k => $type)
            <option value="{{$k}}">{{$type}}</option>
        @endforeach
    </select>

    <select class="err_content hide">
        @foreach($err_content  as $k => $type)
            <option value="{{$k}}">{{$type}}</option>
        @endforeach
    </select>

    <select class="err_whole hide">
        @foreach($err_whole as $k => $type)
            <option value="{{$k}}">{{$type}}</option>
        @endforeach
    </select>

    <select class="err_pic hide">
        @foreach($err_pic as $k => $type)
            <option value="{{$k}}">{{$type}}</option>
        @endforeach
    </select>

@endsection
