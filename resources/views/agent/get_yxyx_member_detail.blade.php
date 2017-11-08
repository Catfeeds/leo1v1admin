@extends('layouts.app')
@section('content')

<script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <section class="content ">
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>介绍人</td>
                    <td>加入时间</td>
                    <td>电话</td>
                    <td>姓名</td>
                    <td>年级</td>
                    <td>科目</td>
                    <td>是否试听</td>
                    <td>试听时间</td>
                    <td>最后一次回访时间</td>
                    <td>回访间隔</td>
                    <td>试听需求</td>
                    <td>备注</td>
                    <td>签单失败分类  </td>
                    <td>签单失败特别说明  </td>
                    <td>曾经负责的CC</td>
                    <td>回访次数(电话拨打次数)</td>
                    <td>录入原因（市场操作）</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["nick1"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["test_lesson"]}} </td>
                        <td>{{@$var["lesson_start"]}} </td>
                        <td>{{@$var["revisit_time"]}} </td>
                        <td>{{@$var["last_call_time_space"]}}天</td>
                        <td>{{@$var["stu_request_test_lesson_demand"]}} </td>
                        <td>{{@$var["user_desc"]}} </td>
                        <td>{{@$var["test_lesson_order_fail_flag"]? $var["test_lesson_order_fail_flag_str"]:""}} </td>
                        <td>{{@$var["test_lesson_order_fail_desc"]}} </td>
                        <td>{{@$var["sys_operator"]}} </td>
                        <td>{{@$var["phone_count"]}} </td>
                        <td>{{@$var["add_reason"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a title="回访列表" class="fa-comments opt-return-back-list cc-flag" ></a>
                                <a title="录入回访信息" class="fa-edit opt-edit-new"></a>
                                <a title="录入原因(市场操作)" class="fa-edit opt-add-reason"></a>
                                <a class="fa fa-phone opt-telphone " title="电话列表"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

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
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12  ">
                        <div class="input-group ">
                            <span class="input-group-addon">上传试卷：</span>
                            <input type="text" class=" form-control "  id="id_test_paper"   / >
                            <div class=" input-group-btn "  >
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
                    </div>
                </div>
                <div class="row ">
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">下次回访：</span>
                            <input id="id_next_revisit_time" class=" form-control " />
                            <div class=" input-group-btn "  >
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
    </div>



@endsection
