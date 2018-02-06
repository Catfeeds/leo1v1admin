@extends('layouts.app')
@section('content')
    <script>
     var assistantid    = {{$assistantid}};
     var accept_adminid = {{$accept_adminid}};
    </script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4" >
                    <div id="id_date_range"> </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">老师</span>
                        <input class="opt-change form-control" type="text" id="id_teacherid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">反馈状态</span>
                        <select class="opt-change form-control" id="id_status" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">反馈类型</span>
                        <select class="opt-change form-control" id="id_feedback_type">
                            <option value="-2">非扣款项</option>
                        </select>
                    </div>
                </div>
                @if(in_array($account,["adrian"]))
                    <div class="col-xs-6 col-md-2">
                        <div class="input-group ">
                            <span class="input-group-addon">删除标识</span>
                            <select class="opt-change form-control" id="id_del_flag" >
                            </select>
                        </div>
                    </div>
                @endif
                <div class=" col-xs-6  col-md-2">
                    <div class="input-group col-sm-12">
                       <input type="text" class="form-control for_input" id="id_lesson" placeholder="请输入课程ID 回车查找" />
                       <div class="input-group-btn">
                           <button id="id_search_lesson" type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                       </div> 
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td >老师id</td>
                    <td width="100px">老师信息</td>
                    <td>课程信息</td>
                    <td>反馈类型</td>
                    <td>老师反馈原因</td>
                    <td>添加时间</td>
                    <td>课程扣款</td>
                    <td>检查状态</td>
                    <td>检查原因</td>
                    <td>负责人</td>
                    <td>审核时间</td>
                    <td>处理时长</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["teacherid"]}}</td>
                        <td>
                            {{$var["nick"]}}
                            <br/>
                            {{$var["teacher_money_type_str"]}}
                            <br/>
                            {{$var["level_str"]}}级
                        </td>
                        <td>
                            上课时间:{{$var["lesson_time"]}}
                            <br/>
                            学生：{{$var["stu_nick"]}}
                            <br/>
                            年级：{{$var["grade_str"]}}
                        </td>
                        <td>{{$var["feedback_type_str"]}}</td>
                        <td>{{$var["tea_reason"]}}</td>
                        <td>{{$var["add_time_str"]}}</td>
                        <td>{{$var["lesson_deduct"]}}</td>
                        <td>{{$var["status_str"]}}</td>
                        <td>{{$var["back_reason"]}}</td>
                        <td>{{$var["sys_operator"]}}</td>
                        <td>{{$var["check_time_str"]}}</td>
                        <td>{{$var["processing_time_str"]}}</td>
                        <td>
                            <div
                                {!! \App\Helper\Utils::gen_jquery_data($var)  !!}
                            >
                                @if($var['del_flag']==0 )
                                    @if($var['status']!=4)
                                        <a class="opt-edit" title="审核">审核</a>
                                    @endif
                                    <a class="opt-log-list" title="登录日志">登陆日志</a>
                                    <a class="opt-lesson_info">课堂详情</a>
                                    @if(in_array($account,["adrian","jim","sunny","孙瞿","郭东"]) && $var['show_flag'] )
                                        <a class="opt-change_type" title="更改反馈类型">改</a>
                                        <a class="opt-full_lesson" title="本月所有课程">全</a>
                                        <a class="opt-trial_reward" title="老师的额外奖励">奖</a>
                                        <a class="opt-teacher_money" title="老师上月工资">工</a>
                                        <a class="opt-add_reward_90" title="本节课添加10分钟的课时补偿">90</a>
                                        <a class="opt-check_trial_lesson" title="检测试听签单奖">试</a>
                                        <a class="opt-update-lesson-info" title="修改课程信息">修改课信息</a>
                                        <a class="opt-reset_lesson_money" title="重置课程金额">重置课金额</a>
                                    @endif
                                @endif
                                @if(in_array($account,["adrian","jim","jack"]))
                                    <a class="fa-trash-o opt-delete" title="删除本条记录"></a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")

        <div style="display:none;" >
            <div id="id_lesson_log"  >
                <div class="row">
                    <div class="col-xs-6 col-md-3">
                        <div class="input-group ">
                            <select class="opt-userid form-control" >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-3">
                        <div class="input-group ">
                            <select class="opt-server-type form-control"  >
                                <option value="-1" > 不限 </option>  
                                <option value="1" > webrtc</option>  
                                <option value="2" > xmpp</option>  
                            </select>
                        </div>
                    </div>
                </div>
                <hr/>
                <table   class="table table-bordered "   >
                    <tr>  <th> 时间 <th>角色 <th>用户id <th>服务 <th> 进出 <th> ip </tr>
                        <tbody class="data-body">
                        </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
