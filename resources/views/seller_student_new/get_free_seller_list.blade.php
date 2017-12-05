@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/lib/flow.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>

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

    <section class="content ">

        <div class="row">

            <div class="col-xs-12 col-md-5"  data-title="时间段">
                <div  id="id_date_range" >
                </div>
            </div>

            <div class="col-xs-6 col-md-3" data-always_show="1"   >
                <div class="input-group ">
                    <input class="opt-change form-control" style="display:block;" id="id_phone_name" placeholder="学生姓名电话,回车搜索"/>
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
                    <span class="input-group-addon">Pad</span>
                    <select class="opt-change form-control" id="id_has_pad" >
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
                    <span class="input-group-addon">是否试听</span>
                    <select class="opt-change form-control" id="id_test_lesson_count_flag" >
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-4" data-always_show="1"   >
                <div class="input-group ">
                    <span class="input-group-addon">分类</span>
                    <select class="opt-change form-control" id="id_test_lesson_order_fail_flag" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-3" data-always_show="1"   >
                <div class="input-group ">
                    <input class="opt-change form-control" style="display:block;" id="id_phone_location" placeholder="手机号码归属地,回车搜索"/>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">回流公海次数</span>
                    <input class="opt-change form-control" id="id_return_publish_count" placeholder="数字-数字" />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">例子经手CC数</span>
                    <input class="opt-change form-control" id="id_1"  placeholder="不可用"/>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">接通次数</span>
                    <input class="opt-change form-control" id="id_2" placeholder="不可用" />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">未接通次数</span>
                    <input class="opt-change form-control" id="id_3" placeholder="不可用"/>
                </div>
            </div>
            <button class="btn btn-primary" id="id_left_count"  title="可领个数" > {{$left_count}}
        </div>

        <hr/>
        <table   class="table table-bordered table-striped"   >
            <thead>
                <tr>
                    <td>学生 </td>
                    <td>地区</td>
                    <td>年级</td>
                    <td>学科</td>
                    <td>上课设备</td>
                    <td>上次回访时间</td>
                    <td>试听成功时间</td>
                    <td>回流公海时间/回流人</td>
                    <td>未签单原因</td>
                    <td>最后一次备注</td>
                    <td>例子进入时间 </td>
                    <td>电话</td>
                    <td>回流公海次数</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["phone_location"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["has_pad_str"]}} </td>
                        <td>{{@$var["last_revisit_time"]}} </td>
                        <td>{{@$var["lesson_start"]}} </td>
                        <td>{{@$var["free_time"]}}/{{@$var["free_nick"]}} </td>
                        <td>{{@$var["test_lesson_order_fail_flag_str"]}}</td>
                        <td>{{@$var["user_desc"]}} </td>
                        <td>{{@$var["add_time"]}} </td>
                        <td>{{@$var["phone_hide"]}} </td>
                        <td>{{@$var['return_publish_count']}}</td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a title="手机拨打"  class=" btn fa fa-phone  opt-telphone "></a>
                                <a   class=" btn fa  opt-set-self" title="">抢学生 </a>
                                <a title="手机拨打&录入回访信息" class="btn fa fa-phone opt-telphone_new">测试请不要使用</a>
                                <a title="查看回访" class="fa fa-comments show-in-select  opt-return-back-list"></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    <div style="display:none;" id="id_dlg_post_user_info">

        <div class="alert alert-danger note-info" style="margin-bottom:0px" >
            <strong>重要提示:</strong> <span>  xx </span>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-6  ">
                <div class="row">
                    <div class="col-xs-12 col-md-6  ">
                        <div class="input-group ">
                            <span class="input-group-addon">学员姓名：</span>
                            <input type="text" class=" form-control "  id="id_stu_nick"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">家长姓名：</span>
                            <input type="text" class=" form-control "  id="id_par_nick"  />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">学生性别：</span>
                            <select id="id_stu_gender" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">是否有pad：</span>
                            <select id="id_stu_has_pad" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon">家庭住址：</span>
                            <input type="text" id="id_stu_addr"  class="form-control" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">学生年级：</span>
                            <select id="id_stu_grade" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">　　科目：</span>
                            <select id="id_stu_subject" class=" form-control "   >
                            </select>
                        </div>
                    </div>

                </div>
                <div class="row ">

                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">在读学校：</span>
                            <input type="text" id="id_stu_school"  class="form-control"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">教材版本：</span>
                            <select id="id_stu_editionid" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                </div>

                <div class="row ">

                    <div class="col-xs-12 col-md-7 ">
                        <div class="input-group ">
                            <span class="input-group-addon">回访状态：</span>
                            <select id="id_stu_status" class=" form-control "   >
                            </select>
                            <span> &gt </span>
                            <select id="id_seller_student_sub_status" class=" form-control "   >
                            </select>

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-5 ">
                        <a class="btn  " id="id_stu_rev_info" >回访记录</a>
                        <a class="btn  btn-primary " id="id_send_sms" >发短信给家长</a>
                    </div>
                </div>
                <div class="row ">

                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">下次回访：</span>
                            <input id="id_next_revisit_time" class=" form-control " />

                            <div class=" input-group-btn "  >
                                <button class="btn  btn-primary " id="id_stu_reset_next_revisit_time"  title="取消下次回访">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">是否高意向：</span>
                            <select id="id_intention_level" class=" form-control "   >
                                <option value="0">否</option>
                                <option value="1">是</option>
                            </select> 

                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="input-group ">
                            <span >成绩情况:</span>
                            <input type="text" value=""   id="id_stu_score_info"  class="form-control" placeholder="" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="input-group ">
                            <span >性格特点:</span>
                            <input type="text" value=""   id="id_stu_character_info" class="form-control"  placeholder="" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-6  ">
                <div class="row ">
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">试听内容：</span>
                            <select id="id_stu_test_lesson_level" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon"> 连线测试 ：</span>
                            <select id="id_stu_test_ipad_flag" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                </div>
                <div class="row ">
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">试听时间：</span>
                            <input id="id_stu_request_test_lesson_time" class=" form-control "   />
                            <div class=" input-group-btn "  >
                                <button class="btn  btn-primary " id="id_stu_reset_stu_request_test_lesson_time"  title="取消">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <button class="btn  btn-primary " id="id_stu_request_test_lesson_time_info"  title=""> 试听其他时段 </button>
                        <button class="btn  btn-primary " id="id_stu_request_lesson_time_info"  title=""> 正式课时段 </button>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon">试听需求：</span>
                            <textarea class="form-control" style="height:60px;"
                                      id="id_stu_request_test_lesson_demand" > </textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon" >　　备注：</span>
                            <textarea class="form-control" style="height:70px;" id="id_stu_user_desc" > </textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon" >回访信息：</span>
                            <textarea class="form-control" style="height:130px;" id="id_stu_revisite_info"  > </textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="display:none;" id="id_dlg_post_user_info_new">

        <div class="alert alert-danger note-info" style="margin-bottom:0px" >
            <strong>重要提示:</strong> <span>  xx </span>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>基本信息&nbsp<font style="color:red">标记红色星号*的为必填内容</font></span>
            </div>
            <div class="col-xs-12 col-md-12  ">
                <div class="row">
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp学员姓名：</span>
                            <input type="text" class=" form-control "  id="id_stu_nick"  />
                            
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon">家长姓名：</span>
                            <input type="text" class=" form-control "  id="id_par_nick"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp学生性别：</span>
                            <select id="id_stu_gender" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp学生年级：</span>
                            <select id="id_stu_grade" class=" form-control "   >
                            </select>
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon">　　<font style="color:red">*</font>&nbsp科目：</span>
                            <select id="id_stu_subject" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon">在读学校：</span>
                            <input type="text" id="id_stu_school"  class="form-control"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp教材版本：</span>
                            <select id="id_stu_editionid" class=" form-control "   >
                            </select>
                        </div>

                    </div>


                   
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp上课设备：</span>
                            <select id="id_stu_has_pad" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-2">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp省</span>
                            <select class="form-control" id="province" name="province">
                            </select>

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp市</span>
                            <select class="form-control" id="city" name="city">
                            </select>

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp区(县)</span>
                            <select class="form-control" id="area" name="area">
                            </select>

                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 " style="display:none">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp家庭住址：</span>
                            <input type="text" id="id_stu_addr"  class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>学习情况</span>
            </div>
            <div class="col-xs-12 col-md-12  ">
                <div class="row">
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp近期成绩：</span>
                            <input type="text" class=" form-control "  id="id_recent_results"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 " style="display:none">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp是否进步：</span>
                            <select id="id_advice_flag" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp班级排名：</span>
                            <input type="text" class=" form-control "  id="id_class_rank"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon">年级排名：</span>
                            <input type="text" class=" form-control "  id="id_grade_rank"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp升学目标：</span>
                            <select id="id_academic_goal" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>核心诉求</span>
            </div>
            <div class="col-xs-12 col-md-12  ">
                <div class="row">
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp应试压力：</span>
                            <select id="id_test_stress" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp升学学校要求：</span>
                            <select id="id_entrance_school_type" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                    <div class="col-xs-12 col-md-3 " style="display:none">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp趣味培养：</span>
                            <select id="id_interest_cultivation" class=" form-control "   >
                            </select>
                        </div>

                    </div>

                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon">课外提高：</span>
                            <select id="id_extra_improvement" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon">习惯重塑：</span>
                            <select id="id_habit_remodel" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>非智力因素</span>
            </div>
            <div class="col-xs-12 col-md-12  ">
                <div class="row">
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp学习习惯：</span>
                            <input type="text" class=" form-control "  id="id_study_habit"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon">兴趣爱好：</span>
                            <input type="text" class=" form-control "  id="id_interests_hobbies"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon"> <font style="color:red">*</font>&nbsp性格特点：</span>
                            <input type="text" class=" form-control "  id="id_character_type"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp老师要求：</span>
                            <input type="text" class=" form-control "  id="id_need_teacher_style"  />
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>试听需求</span>
            </div>
            <div class="col-xs-12 col-md-9  ">
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon" >　<font style="color:red">*</font>&nbsp　试听内容：</span>
                            <textarea class="form-control" style="height:115px;" id="id_stu_request_test_lesson_demand" > </textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-3  ">
                <div class="row">                  
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp上课意向：</span>
                            <select id="id_intention_level" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp试听时间：</span>
                            <input id="id_stu_request_test_lesson_time" class=" form-control "   />
                            <div class=" input-group-btn "  >
                                <button class="btn  btn-primary " id="id_stu_reset_stu_request_test_lesson_time"  title="取消" >
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>

                        </div>
                    </div>


                 
                    <div class="col-xs-12 col-md-12  ">
                        <div class="input-group ">
                            <span class="input-group-addon">上传试卷：</span>
                            <input type="text" class=" form-control "  id="id_test_paper"   / >
                            <div class=" input-group-btn "  >
                                <button class="btn  btn-primary upload_test_paper"  title="上传" >
                                    上传
                                </button>
                            </div>

                            
                        </div>
                    </div>

                </div>
            </div>

        </div>

       
        <div class="row" id="id_revisit_info_new">
            <div class="col-xs-12 col-md-12  ">
                <span>回访信息</span>
            </div>
            <div class="col-xs-12 col-md-6  ">
                <div class="row ">

                    <div class="col-xs-12 col-md-7 ">
                        <div class="input-group ">
                            <span class="input-group-addon">回访状态：</span>
                            <select id="id_stu_status" class=" form-control "   >
                            </select>
                            <span> &gt </span>
                            <select id="id_seller_student_sub_status" class=" form-control "   >
                            </select>

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-5 ">
                        <a class="btn  " id="id_stu_rev_info" >回访记录</a>
                        <a class="btn  btn-primary " id="id_send_sms" >发短信给家长</a>
                    </div>
                </div>
                <div class="row ">

                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">下次回访：</span>
                            <input id="id_next_revisit_time" class=" form-control " />

                            <div class=" input-group-btn "  >
                                <button class="btn  btn-primary " id="id_stu_reset_next_revisit_time"  title="取消下次回访">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon"> 连线测试 ：</span>
                            <select id="id_stu_test_ipad_flag" class=" form-control "   >
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-xs-12 col-md-6  ">
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon" >　　备注：</span>
                            <textarea class="form-control" style="height:70px;" id="id_stu_user_desc" > </textarea>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>其他</span>
            </div>
            <div class="col-xs-12 col-md-12  ">
                <div class="row">
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp需求急迫性：</span>
                            <select id="id_demand_urgency" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp报价反应：</span>
                            <select id="id_quotation_reaction" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                </div>
            </div>

        </div>
@endsection
