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
        <div class="col-xs-6 col-md-2" >
            <div class="input-group ">
                <span >助教</span>
                <input id="id_assistantid"  /> 
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">年级</span>
                <input class="stu_sel form-control" id="id_grade" />
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">学员类型</span>
                <select class="stu_sel form-control" id="id_student_type" >
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">回访</span>
                <select class="stu_sel form-control" id="id_revisit_flag" >
                    <option value="-1">全部 </option>
                    <option value="1">学情未回访</option>
                    <option value="2">月度未回访</option>
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">预警学员</span>
                <select class="stu_sel form-control" id="id_warning_stu" >
                    <option value="-1">全部学员 </option>
                    <option value="1">一周</option>
                    <option value="2">二周</option>
                    <option value="3">三周</option>
                    <option value="4">四周</option>
                    <option value="5">八周</option>
                    <option value="6">十二周</option>
                    <option value="7">十六周</option>
                    <option value="8">二十周</option>
                    <option value="9">二十四周</option>
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-3">
            <div class="input-group ">
                <input type="text" value="" class=" form-control click_on put_name for_input"  data-field="user_name" id="id_user_name"  placeholder="学生/家长姓名,userid,手机,地区 回车查找" />
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <button class="btn" id="id_sumweek" data-value="{{$sumweek}}" >{{$sumweek}}</button>
            <button class="btn" id="id_summonth" data-value="{{$summonth}}" >{{$summonth}}</button> 
        </div>
    </div>
    <hr/> 
    <table class="common-table">
        <thead>
            <tr>
                <td >分配时间</td>

                    {!!\App\Helper\Utils::th_order_gen([
                        ["id","userid" ],
                        ["姓名","nick" ],
                        ["地区","location" ],
                        ["学员类型","type" ],
                        ["家长姓名","parent_name" ],
                        ["助教","assistant_nick" ],
                        ["关系","parent_type" ],
                        ["联系电话","phone" ],
                        ["年级","grade"],
                        ["科目数量","course_list_total"],
                        ["签约课时","lesson_count_all" ],
                        ["剩余课时","lesson_count_left" ],
                        ["已上课时","lesson_count_done" ],
                        ["每周总课时","lesson_total" ],
                        ["赞","praise" ],
                       ])  !!}

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
                            <a class=" fa-comment opt-return-back-lesson " title="回访-new" ></a>
                            <a class="fa-comments opt-return-back-list " title="回访列表" ></a>
                            <a class="fa-calendar opt-lesson " title="排课"></a>
                            <a class="fa-gavel opt-modify " title="设置密码"></a>
                            <a class="fa-headphones opt-test-room" title="设置试听"></a>
                            @if($master_adminid >0)
                                <a class="fa-deviantart opt-change-type-new" title="设置学员类型"></a>
                            @endif
                            <a class="fa-list-alt opt-type-change-list" title="学员类型变更列表"></a>
                            <a class="fa-refresh opt-left-time" title="重置课时"></a>
                            <!-- <a class="fa-comment opt-return-back-new" title="回访信息录入-new" ></a>
                               -->
                            <a class="fa-comments opt-return-back-list-new " title="回访列表-new" ></a>
                            <a title="手机拨打" class=" fa-phone  opt-telphone   "></a>
                            <!-- <a class=" opt-change-teacher "  >换老师</a>
                                 <a href="javascript:;" class="opt-require-commend-teacher" title="申请推荐老师">推荐老师</a>
                               -->
                            @if($acc=="jim" || $acc=="jack" || $acc=="michael")
                                <a class="fa-comment opt-return-back-new " title="回访" ></a>
                            @endif

                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include("layouts.page")

    @include("layouts.return_record_add")

    <div class="dlg_set_dynamic_passwd" style="display:none">
        <div class="row ">
            <div class="input-group">
                <label class="stu_nick"> </label>
                <label class="stu_phone"> </label>
            </div>
        </div>
        <div class="row">
            <div class="input-group">
                <span class="input-group-addon">请输入临时密码</span>
                <input type="text" class="dynamic_passwd" />
            </div>
        </div>
    </div>
    <div style="display:none;" class="cl_dlg_change_type">
            <div class="mesg_alertCont">
    	        <table border="0" cellspacing="0" width="100%" style="border-collapse:collapse;" class="stu_tab02">
                    <tr>
                        <td width="30%">是否系统自动更新：</td>
                        <td width="70%" class="align_l"><select id="id_auto_set_flag"></select></td>
                        
                    </tr>

                    <tr>
                        <td width="30%">学员类型：</td>
                        <td width="70%" class="align_l"><select id="id_set_channel"></select></td>
                        
                    </tr>
                    <tr>
                        <td width="30%">停课原因：</td>
                        <td width="70%" class="align_l"><textarea id="id_lesson_stop_reason"></textarea></td>
                        
                    </tr>
                </table>
            </div>
        </div>
@endsection
