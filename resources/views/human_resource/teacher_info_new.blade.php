@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time.js"></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time_new.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_record.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>
    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师 </span>
                        <input type="text" class="opt-change"  id="id_teacherid"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >性别</span>
                        <select id="id_gender" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >年级段</span>
                        <select id="id_grade_part_ex" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >第一科目</span>
                        <select id="id_subject" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >第二科目</span>
                        <select id="id_second_subject" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >测试老师</span>
                        <select id="id_is_test_user" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >排课冻结</span>
                        <select id="id_is_freeze" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >排课限制</span>
                        <select id="id_limit_plan_lesson_type" class ="opt-change" >
                            <option value="-1"> 全部</option>
                            <option value="0"> 未限制</option>
                            <option value="1"> 一周限排1节</option>
                            <option value="3"> 一周限排3节</option>
                            <option value="5"> 一周限排5节</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >新入职培训</span>
                        <select id="id_train_through_new" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="0">未通过</option>
                            <option value="1">已通过</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >暂停接试听课</span>
                        <select id="id_lesson_hold_flag" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="0">未暂停</option>
                            <option value="1">暂停</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >是否沉睡老师</span>
                        <select id="id_sleep_teacher_flag" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="0">否</option>
                            <option value="1">是</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-3" style="display:block">
                    <div class="input-group ">
                        <span >空闲时间筛选 </span>
                        <input type="text" value=""  class="opt-change"  id="id_free_time"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <input type="text" value="" class=" form-control click_on put_name opt-change"  data-field="address" id="id_address"  placeholder="所在地、学校、姓名等 回车查找" />
                    </div>
                </div>
                @if(in_array($acc,["zoe","adrian","ivy","jim","abby","夏宏东"]))
                    <div class="col-xs-1 col-md-1">
                        <button class="btn btn-primary" id="id_add_other_teacher">新增招师代理</button>
                    </div>
                @endif
                @if(in_array($acc,["jack","jim","江敏","ted"]))
                    <div class="col-md-6 col-xs-4 "  >
                        @if(in_array($acc,["jack","jim"]))
                            <button class="btn btn-warning" id="id_set_jw_subject">设置教务学科权限</button>
                        @endif
                        <button class="btn btn-danger" id="id_add_teacher_callcard">新增老师名片</button>
                    </div>
                @endif

            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td >id</td>
                    <td >昵称</td>
                    <td >真实姓名</td>
                    <td >工资分类</td>
                    <td >老师身份</td>
                    <td >培训通过时间</td>
                    <td >入库时间</td>
                    <td >入职时长</td>
                    <td >等级</td>
                    <td >老师类型</td>
                    <td >手机号</td>
                    <td style="display:none;">邮箱</td>
                    <td >性别</td>
                    <td >所在地</td>
                    <td >学校</td>
                    <td>年级段</td>
                    <td>第一科目</td>
                    <td style="display:none;">第二科目</td>
                    <td>设备信息</td>
                    <td style="display:none;">擅长教材</td>
                    <td class="tea_textbook">教材版本</td>
                    <td class="tea_textbook" width="220px">冻结情况</td>
                    <td class="tea_textbook"  width="220px">限课情况</td>
                    <td   class="tea_textbook" width="500px">标签</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["teacherid"]}} </td>
                        <td>{{$var["teacherid"]}} <br/>{{$var["nick"]}} </td>
                        <td>{{$var["realname"]}} </td>
                        <td>{{$var["teacher_money_type_str"]}} </td>
                        <td>{{$var["identity_str"]}} </td>
                        <td>{{@$var["train_through_new_time_str"]}} </td>
                        <td>{{@$var["create_time_str"]}} </td>
                        <td>{{@$var["work_day"]}}</td>
                        <td>{{$var["level_str"]}} </td>
                        <td>{{$var["teacher_type_str"]}} </td>
                        <td>{{$var["phone_ex"]}} </td>
                        <td>{{$var["email"]}} </td>
                        <td>{{$var["gender_str"]}} </td>
                        <td>{{$var["address"]}} </td>
                        <td>{{$var["school"]}} </td>
                        <td>
                            @if($var["grade_start"]>0)
                                {{$var["grade_start_str"]}} 至 {{$var["grade_end_str"]}}
                            @else
                                {{$var["grade_part_ex_str"]}}
                            @endif
                        </td>
                        <td>{{$var["subject_str"]}} </td>
                        <td>{{$var["second_subject_str"]}} </td>
                        <td>{{$var["user_agent"]}} </td>
                        <td>{{$var["teacher_textbook"]}} </td>
                        <td>{{$var["textbook_type_str"]}} </td>
                        <td>
                            @if($var["not_grade_str"])
                                冻结年级:{{$var["not_grade_str"]}}<br>
                                操作人:{{@$var["freeze_adminid_str"]}}
                            @endif
                        </td>
                        <td>
                            @if($var["limit_plan_lesson_type"]>0)
                                限课详情:{{$var["limit_plan_lesson_type_str"]}}<br>
                                操作人:{{$var["limit_plan_lesson_account"]}}<br>
                                操作时间:{{$var["limit_plan_lesson_time_str"]}}<br>
                            @endif
                        </td>
                        <td>{!! @$var["teacher_tags"] !!} </td>

                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var)  !!} >
                                <a class="fa-user opt-user-info" href="/teacher_info_admin/index?teacherid={{$var["teacherid"]}}"
                                   target="_blank" title="老师信息"> </a>
                                <a class="opt-show-lessons-new"  title="课程列表-new">课程-new</a>
                                <a  href="/teacher_info_admin/lesson_list?teacherid={{$var["teacherid"]}}" target="_blank" title="跳转到老师课表">课 </a>
                                <a class="div_show" href="/teacher_info_admin/free_time?teacherid={{$var["teacherid"]}}"
                                   target="_blank" title="设置空闲时间">设置空闲时间</a>
                                <a class="opt-get-teacher-lesson-hold div_show">设置暂停接试听课</a>
                                <a class="fa-edit opt-edit"  title="编辑"> </a>
                                @if(in_array($account_role,["8","10","12"]))
                                    <a class="opt-tea_origin_url" title="招师链接">招</a>
                                @endif
                                @if(in_array($acc,["zoe","jim"]) || in_array($account_role,[12]))
                                    <a class="opt-account-number" title="老师账号信息修改功能">账号相关</a>
                                @endif

                                @if(in_array($account_role,["10","12"]))
                                    <a class="fa-shield opt-trial-pass"  title="试讲通过"> </a>
                                    <a class="fa-gratipay opt-test-user" title="设置为测试用户"></a>
                                    <a class="fa-gavel opt-set-tmp-passwd div_show"  title="临时密码"></a>
                                    <a class="opt-old ">旧版</a>
                                @endif
                                @if($var["label_id"]==0)
                                    <a class="opt-set-teacher-label div_show" title="设置标签<">设置标签</a>
                                @elseif($var["label_id"]>0)
                                    <a class="opt-set-teacher-label div_show" title="修改标签<">修改标签</a>
                                @endif
                                @if($elite_flag==1)
                                    @if(in_array($acc,["jack","jim","江敏","ted"]))
                                        <a class="opt-upload-teacher-call-crad" title="上传名片">上传名片</a>
                                    @endif
                                    <a class="opt-show-teacher-call-crad" title="老师名片">老师名片</a>
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
