@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">申请人</span>
                        <input class="opt-change form-control" id="id_require_adminid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">处理人</span>
                        <input class="opt-change form-control" id="id_accept_adminid" />
                    </div>
                </div>

               

            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>编号</td>
                    <td>申请时间</td>
                    <td>申请人</td>
                    <td> 学生 </td>
                    <td> 学生情况</td>
                    <td> 课程信息</td>
                    <td> 备注(特殊需求) </td>
                    <td> 处理方案</td>
                    <td> 处理人</td>
                    <td> 处理时长</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["id_index"]}}</td>
                        <td>{{@$var["add_time_str"]}} </td>
                        <td>{{@$var["account"]}} </td>
                        <td> {{@$var["nick"]}}</td>
                        <td >
                            科目: {{$var["subject_str"]}}<br/>
                            年级: {{$var["grade_str"]}}<br/>
                            教材：{{$var["textbook"]}}<br/>
                            地区: {{$var["phone_location"]}} <br/>
                            学生成绩情况: {{$var["stu_score_info"]}} <br/>
                            性格信息: {{$var["stu_character_info"]}} <br/>
                        </td>
                        <td >
                            期待时间: {{$var["stu_request_test_lesson_time_str"]}} <br/>
                            正式上课: {!!  $var["stu_request_lesson_time_info_str"]!!} <br/>
                            试听需求:{{$var["stu_request_test_lesson_demand"]}}<br/>
                        </td>
                            

                        </td>
                        <td>
                            {{@$var["except_teacher"]}}<br>
                        </td>
                        <td>
                            @if($var["accept_flag"]==1)
                                状态:接受<br>
                                推荐老师:&nbsp&nbsp{{@$var["record_teacher"]}}<br>
                                备注:&nbsp&nbsp&nbsp&nbsp{{@$var["accept_reason"]}}<br>
                                操作时间:&nbsp&nbsp{{@$var["accept_time_str"]}}
                            @elseif($var["accept_flag"]==2)
                                状态:驳回<br>
                                驳回理由:&nbsp&nbsp{{@$var["accept_reason"]}} <br>
                                操作时间:&nbsp&nbsp{{@$var["accept_time_str"]}}
                            @else
                                状态:未处理
                            @endif
                        </td>
                        <td>{{@$var["accept_account"]}}</td>
                        <td>
                            @if($var["accept_time"]>0)
                                {{@$var["deal_time"]}}小时 
                            @endif
                        </td>

                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                               

                                <a class="fa-edit opt-edit"  title="推荐老师"> </a>
                                <a class="fa-edit opt-edit-new"  title="修改申请"> </a>
                                <a class="fa-trash-o opt-del"  title="删除申请"> </a>
                                @if($acc=="jack")
                                    <a class=" opt-del-new"  title="删除申请">删除 </a>
                                @endif
                               

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

