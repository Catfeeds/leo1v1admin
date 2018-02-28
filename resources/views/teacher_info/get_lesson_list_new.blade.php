@extends('layouts.teacher_header')
@section('content')
<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>
<script type="text/javascript" src="/page_js/lib/select_resource_ajax.js"></script>
<script type="text/javascript" src="/js/pdfobject.js"></script>
<script>
 var is_full_time = {{$is_full_time}};

</script>
<style>
 textarea{
     resize:both;
 }
 table {
     font-size :14px;
 }
 .false{
     color:red;
 }
 .bg_train_lesson{
     background-color:#ccc;
 }
 .btn-width{
     width:80px;
 }
</style>
<section class="content li-section" >
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <div class="input-group">
                <span >日期</span>
                <input type="text" id="id_start_date" class="opt-change form-control input-group-addon  "/>
                <span >-</span>
                <input type="text" id="id_end_date" class="opt-change form-control input-group-addon  "/>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">课程类型</span>
                <select class="opt-change form-control" id="id_lesson_type" >
                    <option value="-1">[全部]</option>
                    <option value="0">1对1</option>
                    <option value="2">试听课 </option>
                    <option value="1001">公开课 </option>
                    <option value="3001">小班课</option>
                    <option value="1100">培训课程</option>
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-md-2">
            <div class="input-group">
                <span >学生</span>
                <select id="id_student" class="opt-change">
                    <option value="-1">[全部]</option>
                    @foreach($student_list as $val)
                        <option value="{{$val['userid']}}">{{$val['nick']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="teacherid" style="display:none">
            {{@$teacherid}}
        </div>
    </div>
    <hr/>
    <table class="common-table">
        <thead>
            <tr>
                <td style="min-width:100px">学生</td>
                <td style="min-width:100px">课程信息</td>
                <td style="min-width:100px">课程联系人</td>
                <td style="min-width:100px">试听课学生需求</td>
                <td style="min-width:60px">已传PDF(老师/学生/作业)</td>
                <td style="min-width:60px">已评价</td>
                <td style="min-width:300px">操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $key =>$var)
                <tr>
                    <td>
                        {{@$var["stu_nick"]}}<br/>
                        {{@$var["grade_str"]}}<br/>
                        {{@$var["lesson_time"]}}<br/>
                    </td>
                    <td >
                        课程类型：{{@$var["lesson_type_str"]}}<br/>
                        @if(@$var['lesson_name']!="")
                            课堂名称：{{@$var["lesson_name"]}}<br/>
                        @endif
                        @if(@$var['lesson_intro']!="")
                            知识点：{{@$var["lesson_intro"]}}<br/>
                        @endif
                        @if(@$var['textbook']!="")
                            教材版本：{{@$var["textbook"]}}<br/>
                        @endif
                    </td>
                    <td >
                        @if(@$var['ass_nick']!="")
                            助教：{{@$var["ass_nick"]}}<br/>
                            电话：{{@$var["ass_phone"]}}<br/>
                        @endif
                        @if(@$var['lesson_type']==2 && @$var['cc_account']!="")
                            咨询：{{@$var["cc_account"]}}<br/>
                            电话：{{@$var["cc_phone"]}}
                        @endif
                    </td>
                    <td >{!!@$var["stu_request_test_lesson_demand"]  !!}</td>
                    <td >{!!@$var["pdf_status_str"]!!}</td>
                    <td >{!!@$var["tea_comment_str"]!!}</td>
                    <td >
                        <div class="lesson_data"
                            {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >
                            @if(@$var['lesson_start']>0)
                                <a class="opt-teacher-pdf">讲义上传/查看</a>
                                <a class="opt-teacher-pdf-back">讲义上传/查看(备用上传)  </a>
                                <a class="opt-get_stu_performance" >课堂评价</a>
                                <a class="opt-download-test-paper" title="下载试卷">下载试卷</a>
                                @if(@$var['lesson_type'] == 2)
                                    <a class="fa fa-edit opt-add"  title="申请帮助"> 申请帮助</a>
                                @endif
                            @else
                                <a class="opt-set_lesson_time" title="设置上课时间">立即接课</a>
                            @endif
                            <a class="opt-complaint" title="老师投诉">投诉</a>
                            <a class="opt-change-lesson-time" title="换时间">换时间</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="display:none;" class="dlg_set_stu_performance_for_seller" >
        <table class="table table-bordered table-striped" >
            <tbody>
                <tr>
              <td style="text-align:right;width:30%;">试听情况</td>
                    <td>
                        <select id="stu_lesson_content">
                            <option value="顺利完成">顺利完成</option>
                            <option value="未顺利完成">未顺利完成</option>
                        </select>
                        <input type="text" id="stu_lesson_content_more" class="form-control" style="display:none"/>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;width:30%;">学习态度</td>
                    <td>
                        <select id="stu_lesson_status">
                            <option value="积极配合，兴趣浓厚">积极配合，兴趣浓厚</option>
                            <option value="较好配合，互动较多">较好配合，互动较多</option>
                            <option value="配合度一般，但愿意回答问题">配合度一般，但愿意回答问题</option>
                            <option value="不太愿意配合">不太愿意配合</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;width:30%;">学习基础情况</td>
                    <td>
                        <select id="stu_study_status">
                            <option value="较好，紧跟老师节奏，完美消化所学">较好，紧跟老师节奏，完美消化所学</option>
                            <option value="中等，但可以较好吸收当堂所学">中等，但可以较好吸收当堂所学</option>
                            <option value="一般，部分内容需要再学习">一般，部分内容需要再学习</option>
                            <option value="较差，试听内容基本听不懂">较差，试听内容基本听不懂</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;width:30%;">学生优点(可多选)</td>
                    <td>
                        <div id="stu_advantages">
                            <input name="stu_advantages" type="checkbox" value="理解能力强"/>理解能力强<br/>
                            <input name="stu_advantages" type="checkbox" value="表达能力强"/>表达能力强<br/>
                            <input name="stu_advantages" type="checkbox" value="思路清晰"/>思路清晰<br/>
                            <input name="stu_advantages" type="checkbox" value="自信十足"/>自信十足<br/>
                            <input name="stu_advantages" type="checkbox" value="其他"/>其他<br/>
                        </div>
                        <input type="text" id="stu_advantages_more" class="form-control" style="display:none"/>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;width:30%;">学生有待提高(可多选)</td>
                    <td>
                        <div id="stu_disadvantages" >
                            <input name="stu_disadvantages" type="checkbox" value="思维能力的培养"/>思维能力的培养<br/>
                            <input name="stu_disadvantages" type="checkbox" value="知识系统化学习"/>知识系统化学习<br/>
                            <input name="stu_disadvantages" type="checkbox" value="语言表达能力的提高"/>语言表达能力的提高<br/>
                            <input name="stu_disadvantages" type="checkbox" value="举一反三的能力"/>举一反三的能力<br/>
                            <input name="stu_disadvantages" type="checkbox" value="其他"/>其他<br/>
                        </div>
                        <input type="text" id="stu_disadvantages_more" class="form-control" style="display:none"/>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;width:30%;">培训计划</td>
                    <td>
                        <select id="stu_lesson_plan">
                            <option value="从基础内容学习">从基础内容学习</option>
                            <option value="系统性巩固">系统性巩固</option>
                            <option value="提高学习">提高学习</option>
                            <option value="其他">其他</option>
                        </select>
                        <div class="stu_lesson_plan_select">
                            <select id="stu_lesson_plan_grade">
                                <option value="一">一</option>
                                <option value="二">二</option>
                                <option value="三">三</option>
                                <option value="四">四</option>
                                <option value="五">五</option>
                                <option value="六">六</option>
                                <option value="七">七</option>
                                <option value="八">八</option>
                                <option value="九">九</option>
                                <option value="高一">高一</option>
                                <option value="高二">高二</option>
                                <option value="高三">高三</option>
                            </select>年级
                            <select id="stu_lesson_plan_book">
                                <option value="上">上</option>
                                <option value="下">下</option>
                            </select>册
                        </div>
                        <input type="text" id="stu_lesson_plan_more" class="form-control" style="display:none"/>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;width:30%;">教学方向</td>
                    <td>
                        <select id="stu_teaching_direction">
                            <option value="课内知识">课内知识</option>
                            <option value="课外知识">课外知识</option>
                        </select>
                        <div class="stu_teaching_direction_select">
                            <select id="stu_teaching_direction_grade">
                                <option value="一">一</option>
                                <option value="二">二</option>
                                <option value="三">三</option>
                                <option value="四">四</option>
                                <option value="五">五</option>
                                <option value="六">六</option>
                                <option value="七">七</option>
                                <option value="八">八</option>
                                <option value="九">九</option>
                                <option value="高一">高一</option>
                                <option value="高二">高二</option>
                                <option value="高三">高三</option>
                            </select>年级
                            <select id="stu_teaching_direction_book">
                                <option value="上">上</option>
                                <option value="下">下</option>
                            </select>册
                        </div>
                        <input type="text" id="stu_teaching_direction_more" class="form-control" style="display:none"/>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;width:30%;">意见、建议等 <br/>（不少于50字）</td>
                    <td>
                        <textarea class="form-control" style="height:150px;" id="stu_advice"  > </textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

@endsection

<div class="row">
    <div class="col-xs-6 col-md-2">
    </div>
</div>
<div class="opt-select-file" style="position:absolute;display:none;z-index: 1051;">
    <button class="btn btn-width btn-default opt-local">本地</button><br/>
    <button class="btn btn-width btn-default opt-leo-res">资料库</button><br/>
    <button class="btn btn-width btn-default opt-my-res">我的收藏</button>
</div>
<div class="col-md-12 look-pdf"   style="width:80%;height:95%;position:fixed;right:10%;top:2.5%;border-radius:5px;background:#eee;display:none;z-index:8888;overflow:hidden;">
    <div class="look-pdf-son">
    </div>
</div>


