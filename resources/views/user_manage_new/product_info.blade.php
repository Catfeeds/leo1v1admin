@extends('layouts.app_new2')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax_test.js"></script>
    <script src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>

    <section class="content">
        <div class="row  row-query-list" >
            <div class="col-xs-12 col-md-4"  data-title="时间段">
                <div  id="id_date_range" >
                </div>
            </div>


            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <input type="text" class=" form-control click_on put_name opt-change"  data-field="user_name" id="id_feedback_nick"  placeholder="反馈人姓名/ 回车查找" />

                </div>
            </div>

            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span class="input-group-addon">是否解决</span>
                    <select class="opt-change form-control " id="id_deal_flag" >
                    </select>
                </div>
            </div>


            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">问题类型</span>
                    <select class="opt-change form-control " id="id_lesson_problem" >
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <button type="button" class="btn btn-warning" id="id_submit">添加</button>
                </div>
            </div>
            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <button type="button" class="btn btn-warning" id="id_show">显示/隐藏统计</button>
                </div>
            </div>

        </div>
        <hr/>
        <table   class="common-table"   >
            <thead>
                <tr>
                    <td>问题提出人</td>
                    <td>问题收集人</td>
                    <td>问题录入时间</td>
                    <td>问题描述</td>
                    <td>问题类型</td>
                    <td>课程链接</td>
                    <td>原因</td>
                    <td>解决方案</td>
                    <td>学生姓名</td>
                    <td>学生手机</td>
                    <td>学生设备信息</td>
                    <td>老师信息</td>
                    <td>老师手机</td>
                    <td>老师设备信息</td>
                    <td>解决状态</td>
                    <td>备注</td>
                    <td>备注</td>
               </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td >{{@$var["feedback_nick"]}} </td>
                        <td >{{@$var["record_nick"]}}</td>
                        <td >{{@$var["create_time"]}}</td>
                        <td >{{@$var["describe_msg"]}}</td>
                        <td >{{@$var['lesson_problem_str']}}</td>
                        <td >
                            <a target="_blank" href="{{$var['lesson_url']}}">课程链接</a>
                        </td>
                        <td >{{@$var["reason"]}}</td>
                        <td >{{@$var["solution"]}}</td>
                        <td >
                            <a target="_blank" href="http://admin.leo1v1.com/user_manage/index?user_name={{$var['sid']}}">{{@$var["stu_nick"]}}</a>
                        </td>
                        <td >{{@$var["stu_phone"]}}</td>
                        <td >{{@$var["stu_agent_simple"]}}</td>
                        <td >
                            <a target="_blank" href="http://admin.leo1v1.com/human_resource/teacher_info_new?teacherid={{$var['tid']}}">{{@$var["tea_nick"]}}</a>
                        </td>
                        <td >{{@$var["tea_phone"]}}</td>
                        <td >{{@$var["tea_agent_simple"]}}</td>
                        <td >{!!@$var["deal_flag_str"]!!}</td>
                        <td >{{@$var["remark"]}}</td>
                        <td >
                            <div class="btn-group"
                                 {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa-trash-o opt-del " title="删除" ></a>
                                <a class="fa-pencil-square-o opt-edit " title="编辑" ></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto; display:none"></div>
        <div id="pie_container" style="min-width:400px;height:400px;display:none;"></div>
        @include("layouts.page")
    </section>

    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>


@endsection
