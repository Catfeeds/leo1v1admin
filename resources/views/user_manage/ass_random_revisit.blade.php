@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script src='/page_js/select_teacher_free_time.js?{{@$_publish_version}}'></script>
    <script src='/page_js/set_lesson_time.js?{{@$_publish_version}}'></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>


    <section class="content">
        <div class="row">
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">年级</span>
                    <input class="stu_sel form-control" id="id_grade" />
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td >分配时间</td>
                    <td >id</td>
                    <td >姓名</td>
                    <td >地区</td>
                    <td >学员类型</td>
                    <td >家长姓名</td>
                    <td >助教</td>
                    <td >关系</td>
                    <td >联系电话</td>
                    <td >年级</td>
                    <td >科目数量</td>
                    <td >签约课时</td>
                    <td >剩余课时</td>
                    <td >已上课时</td>
                    <td >每周总课时</td>
                    <td >赞</td>
                    <td >回访  {{@$last_time_str}}/{{@$cur_time_str}}</td>
                    <td >本月成绩记录</td>
                    <td style="display:none">回访 学情/月度</td>
                    <td style="display:none; width:160px;">版本信息</td>
                    <td style="display:none;">版本信息-all</td>
                    <td style="display:none;">最近登录IP</td>
                    <td style="display:none;">最近登录时间</td>
                    <td style="width:220px">操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td  >{{$var["ass_assign_time_str"]}} </td>
                        <td  >{{$var["userid"]}} </td>
                        <td class="user_nick">{{$var["nick"]}}</td>
                        <td >{{$var["location"]}}</td>
                        <td >{{$var["type"]}}</td>
                        <td class="" >{{$var["parent_name"]}}</td>
                        <td class="" >{{$var["assistant_nick"]}}</td>
                        <td class="td-parent-type" data-v="{{$var["parent_type"]}}"></td>
                        <td class="user_phone" >{{$var["phone"]}}</td>
                        <td class="td-grade" data-v="{{$var["grade"]}}"  ></td>
                        <td class="" >{{$var['course_list_total']}}</td>
                        <td >{{$var["lesson_count_all"]}}</td>
                        <td >{{$var["lesson_count_left"]}}</td>
                        <td >{{$var["lesson_count_done"]}}</td>
                        <td >{{$var["lesson_total"]}}</td>
                        <td >{{$var["praise"]}}</td>
                        <td >
                            <font color="{{ $var["last"]?"green":"red" }}">{{ $var["last_str"]  }} </font> /
                            <font color="{{ $var["cur"]?"green":"red" }}">{{ $var["cur_str"]  }} </font>

                        </td>
                        <td >  <font color="{{ $var["status"]?"green":"red" }}">{{$var['status_str']}}</font></td>
                        <td >
                            <font color="{{ $var["ass_revisit_week_flag"]?"green":"red" }}">{{ $var["ass_revisit_week_flag_str"]  }} </font> /
                            <font color="{{ $var["ass_revisit_month_flag"]?"green":"red" }}">{{ $var["ass_revisit_month_flag_str"]  }} </font>
                        </td>

                        <td class="" >{{$var["user_agent_simple"]}}</td>
                        <td >{{$var["user_agent"]}}</td>
                        <td >{{$var["last_login_ip"]}}</td>
                        <td >{{$var["last_login_time"]}}</td>
                        <td >
                            <div
                                data-userid="{{$var["userid"]}}"
                                data-nick="{{$var["nick"]}}"
                                data-autoflag="{{$var["is_auto_set_type_flag"]}}"
                                data-reason="{{$var["stu_lesson_stop_reason"]}}"
                                data-type="{{$var["type_str"]}}"
                                data-phone="{{$var["phone"]}}"
                                data-grade="{{$var["grade"]}}"
                                data-location="{{$var["location"]}}"

                            >
                                <a class="fa-user opt-user " title="个人信息" ></a>
                                <a class="fa-comment opt-return-back-lesson " title="回访-new" ></a>
                                <a class="fa-comments opt-return-back-list " title="回访列表" ></a>
                                <a title="手机拨打" class=" fa-phone  opt-telphone   "></a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")

@endsection
