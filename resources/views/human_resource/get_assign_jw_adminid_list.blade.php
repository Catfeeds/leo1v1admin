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
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>
    <script type="text/javascript" > 
     var g_adminid= "{{$adminid}}";
    </script>

    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacherid"  placeholder="" />
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
                        <span >类型</span>
                        <select id="id_identity" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >接课意愿</span>
                        <select id="id_class_will_type" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >有无试听课</span>
                        <select id="id_have_test_lesson_flag" class ="opt-change" ></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span >有无试听课(分配后的九天内)</span>
                        <select id="id_have_lesson" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="1">有</option>
                            <option value="0">无</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >是否回访</span>
                        <select id="id_revisit_flag" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >有无教材</span>
                        <select id="id_textbook_flag" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="1">有</option>
                            <option value="0">无</option>
                        </select>
                    </div>
                </div>



                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >教务</span>
                        <select id="id_jw_adminid" class ="opt-change" >
                            <option value="-1">全部</option>
                            @foreach($jw_list as $vv)
                                <option value="{{$vv["uid"]}}">{{$vv["account"]}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td >姓名</td>
                    <td >老师类型</td>
                    <td >入职时长</td>
                    <td>手机号</td>                  
                    <td>年级段</td>
                    <td>第一科目</td>
                    <td>第二科目</td>
                    <td>教材版本</td>
                    <td>分配教务</td>
                    <td>分配时间</td>                   
                    <td>最新回访记录</td>                   
                    <td>第一节试听课时间(分配后的九天内)</td>                   
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["identity_str"]}}  </td>
                        <td>{{@$var["work_day"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>
                            @if(@$var["grade_start"]>0)
                                {{@$var["grade_start_str"]}} 至 {{@$var["grade_end_str"]}}
                            @else
                                {{@$var["grade_part_ex_str"]}}
                            @endif

                        </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["second_subject_str"]}} </td>
                        <td>{{@$var["textbook"]}}</td>
                        <td>{{@$var["account"]}}</td>
                        <td>{{@$var["assign_jw_time_str"]}}</td>
                        <td>
                            回访时间:{{@$var["add_time_str"]}}<br>
                            回访内容:{{@$var["record_info"]}}<br>
                            接课意愿:{{@$var["class_will_type_str"]}}<br>
                            详情:{{@$var["class_will_sub_type_str"]}}<br>
                            @if($var["class_will_sub_type"]==2 || $var["class_will_sub_type"]==5)
                                恢复接课时间:{{@$var["recover_class_time_str"]}}<br>
                            @endif
                            负责人:{{@$var["acc"]}}<br>
                        </td>
                        <td>
                            时间:{{@$var["lesson_start_str"]}}<br>
                            科目:{{@$var["l_subject_str"]}}
                        </td>
                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var)  !!} >
                                <a class="fa-comment opt-return-back-new" title="回访信息录入-new" ></a>
                                <a class="fa-comments opt-return-back-list-new" title="回访列表" ></a>
                                <a class="opt-teacher-info" title="老师信息" >老师信息</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

