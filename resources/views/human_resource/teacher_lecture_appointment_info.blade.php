@extends('layouts.app')
@section('content')
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <style>
     #cal_week th  {
         text-align:center;  
     }
     #cal_week td  {
         text-align:center;  
     }
     #cal_week .select_free_time {
         background-color : red;
     }
     #cal_week .have_lesson {
         background-color : red;
     }
    </style>
    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-12 col-md-4" >
                    <div id="id_date_range"> </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span>推荐人</span>
                        <input id="id_teacherid" type="text" value="" class="opt-change" placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">招师</span>
                        <input class="opt-change form-control" id="id_accept_adminid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">状态</span>
                        <select class="opt-change form-control" id="id_lecture_appointment_status" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <select class="opt-change form-control" id="id_grade" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="opt-change form-control" id="id_subject" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">性别</span>
                        <select class="opt-change form-control" id="id_gender" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">面试类型</span>
                        <select class="opt-change form-control" id="id_interview_type" >
                            <option value="-1">全部</option>
                            <option value="0">无试讲</option>
                            <option value="1">录制试讲</option>
                            <option value="2">面试试讲</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否全职</span>
                        <select class="opt-change form-control" id="id_full_time" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">试讲状态</span>
                        <select class="opt-change form-control" id="id_status" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">回访状态-old</span>
                        <select class="opt-change form-control" id="id_record_status" >
                            <option value="-1">[全部]</option>
                            <option value="0">未回访</option>
                            <option value="1">已回访</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >绑定微信</span>
                        <select id="id_have_wx" class ="opt-change" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >回访状态</span>
                        <select id="id_lecture_revisit_type" class ="opt-change" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >邀约状态</span>
                        <select id="id_lecture_revisit_type_new" class ="opt-change" >
                            <option value="-2">已邀约</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">推荐渠道(多选)</span>
                        <input  placeholder="推荐渠道" id="id_teacher_ref_type" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-3" data-always_show="1">
                    <div class="input-group ">
                        <span class="input-group-addon">全职老师分类</span>
                        <select class="opt-change form-control" id="id_fulltime_teacher_type" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">测试老师</span>
                        <select class="opt-change form-control" id="id_is_test_user" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span class="input-group-addon">二面状态</span>
                        <select class="opt-change form-control" id="id_second_train_status" >
                            <option value="-1">全部</option>
                            <option value='0'>不通过</option>
                            <option value='1'>通过</option>
                            <option value='2'>老师未到</option>
                            <option value='3'>待定</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span class="input-group-addon">入职状态</span>
                        <select class="opt-change form-control" id="id_teacher_pass_type" >
                            <option value="-1">全部</option>
                            <option value='0'>未设置</option>
                            <option value='1'>已入职</option>
                            <option value='2'>未入职</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group">
                        <input type="text" value="" class=" form-control click_on put_name opt-change"  data-field="user_name"
                               id="id_user_name" placeholder="姓名,手机号,QQ,教材,院校,师资 回车查找"/>
                    </div>
                </div>
                <div class="col-md-2 col-xs-6">
                    <div>
                        <button class="btn btn-danger" id="id_add_teacher_lecture_appointment">新增试讲预约</button>
                    </div>
                </div>
                <div class="col-md-1 remove-for-xs col-xs-6 "" >
                    <div>
                        <button class="btn btn-primary" id="id_upload_xls"> 上传xls </button>
                    </div>
                </div>
                <div class="col-md-2 col-xs-6 "  >
                    <div>
                        <button class="btn btn-warning" id="id_update_lecture_appointment_status">批量修改状态</button>
                    </div>
                </div>
                <div class="col-md-2 col-xs-6 "  >
                    <div>
                        <button class="btn btn-warning" id="id_set_zs_work_status">设置招师工作状态</button>
                    </div>
                </div>
                @if(!\App\Helper\Utils::check_env_is_release() || $_account_role==12)
                    <div class="col-md-2 col-xs-6">
                        <div>
                            <button class="btn btn-danger" id="id_add_teacher_lecture_appointment_for_test">快速添加测试老师</button>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-6">
                        <div>
                            <button class="btn btn-danger" id="id_all_through">批量通过</button>
                        </div>
                    </div>
                @endif

            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td style="width:10px">
                        <a id="id_select_all" title="全选">全</a>
                        <a id="id_select_other" title="反选">反</a>
                    </td>
                    <td style="display:none">id</td>
                    <td>教师姓名</td>
                    <td>报名时间</td>
                    <td style="display:none">入职时间</td>
                    <td>电话</td>
                    <td>QQ</td>
                    <td>邮箱</td>
                    <td>绑定微信</td>
                    <td>性别</td>
                    <td>科目</td>
                    <td>年级</td>
                    <td>扩科</td>
                    <td>毕业院校</td>
                    <td>师资</td>
                    @if($show_full_time==0)
                        <td style="width:220px">审核状态</td>
                        <td >推荐人</td>
                        <td>回访状态</td>
                        <td style="display:none">招师</td>
                    @else
                        <td style="width:220px">一面状态</td>
                        <td>一面面试官</td>
                        <td>邀约状态</td>
                        <td>二面状态</td>
                    @endif
                    <td>入职状态</td>
                    <td style="display:none;">测试老师</td>
                    <td style="width:220px;display:none" >客户端版本</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td><input type="checkbox" class="opt-select-item" data-id="{{$var["id"]}}"/></td>
                        <td>{{@$var["id"]}}</td>
                        <td>{{ @$var["name"] }}</td>
                        <td>{{@$var["begin"]}} </td>
                        <td>{{@$var["train_through_new_time_str"]}} </td>
                        <td>
                            <a href="javascript:;" class="show_detail" data-value="{{$var["phone"]}}" >
                                {{$var["phone_ex"]}}
                            </a>
                        </td>
                        <td>
                            <a href="javascript:;" class="show_detail" data-value="{{$var["qq"]}}" >{{$var["qq_ex"]}}</a>
                        </td>
                        <td>
                            <a href="javascript:;" class="show_detail" data-value="{{$var["email"]}}" >{{$var["email_ex"]}}</a>
                        </td>
                        <td>{{@$var["have_wx_flag"]}}</td>
                        <td>{{@$var["gender_str"]}}</td>
                        <td>{{@$var["subject_ex_str"]}} </td>
                        <td>{{@$var["grade_ex_str"]}} </td>
                        <td>{{@$var["trans_subject_ex_str"]}} </td>
                        <td>{{@$var["school"]}} </td>
                        <td>{{@$var["teacher_type_str"]}} </td>
                        <td>
                            {{@$var["status_str"]}}<br><br>
                            @if(!empty($var["reason"]) )
                                原因: {{@$var["reason"]}}
                            @endif
                        </td>
                        @if($show_full_time==0)
                            <td>{{@$var["reference_name"]}} </td>
                            <td>{{@$var["lecture_revisit_type_str"]}} </td>
                            <td>{{@$var["zs_name"]}} </td>
                        @else
                            <td>{{@$var["interviewer_teacher_str"]}}</td>
                            <td>{{@$var["lecture_revisit_type_new_str"]}}<br/>
                                @if ($var['lesson_start']>0)
                                @else
                                    {{@$var['custom']}}</td>
                                @endif
                            <td>
                                {{@$var["full_status_str"]}}<br><br>
                                @if(!empty($var["full_record_info"]) )
                                    原因: {{@$var["full_record_info"]}}
                                @endif
                            </td>
                        @endif
                        <td>
                            @if($var["train_through_new_time"]>0)
                                {{@$var["train_through_new_time_str"]}}
                            @elseif($var["teacher_pass_type"]==2)
                                未入职<br>
                                理由:{{@$var["no_pass_reason"]}}
                            @elseif($var["teacher_pass_type"]==0)
                                未设置
                            @endif
                        </td>
                        <td>{{@$var["is_test_user_str"]}} </td>
                        <td>{{@$var["user_agent"]}} </td>
                        <td>
                            <div {!! \App\Helper\Utils::gen_jquery_data($var) !!} >
                                <a title="手机拨打" class=" fa-phone  opt-telphone"></a>
                                <!-- <a class="fa-edit opt-edit" title="编辑状态"></a> -->
                                <a class="fa-comments opt-return-back-list " title="回访列表" ></a>
                                <a class="opt-more_grade">邮</a>
                                @if($show_full_time==1)
                                    <a class="opt-set-lecture-revisit-type" title="设置邀约状态" >邀约状态</a>
                                    <a class="opt-edit-full_time" title="全职老师审核">二面评价</a>
                                    <a class="opt-set-part-teacher " title="设置为兼职老师" >兼</a>
                                @else
                                    <a class="fa-times opt-del" title="删除"></a>
                                    <a class="opt-set-lecture-revisit-type " title="设置回访状态" >回访状态</a>
                                    <!-- <a class="opt-trans_info" title="设置扩课" >扩</a> -->
                                @endif
                                @if(@$var["hand_flag"]==1 || in_array($acc,["jack","李明玉"]))
                                    <a class="opt-edit-hand" title="修改" >修改</a>
                                @endif
                                <a class="opt-plan-train_lesson">1v1</a>
                                <!-- <a class="opt-1v1-lesson-set-new">1v1-new</a> -->
                                <a class="opt-set-teacher-pass-type" title="修改入职状态">入</a>
                                @if($var['status_str']=="无试讲")
                                    <a class="opt-set-teacher-info" title="老师信息">老师信息</a>
                                @endif
                                @if(!\App\Helper\Utils::check_env_is_release() && $var['train_through_new_time']==0)
                                    <a class="opt-test-through" title="测试环境一键通过老师">一键通过</a>
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
