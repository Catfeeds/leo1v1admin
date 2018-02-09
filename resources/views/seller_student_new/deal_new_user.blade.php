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

        <div>
            <div class="row  row-query-list" >


                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <a class="btn btn-danger "  id="id_sync_tq" > 刷新tq </a>
                    </div>

                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <a class="btn btn-danger "  id="id_sync_ytx" > 刷新云通讯 </a>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <a class="btn btn-primary "  id="id_call_phone" > 拨打电话 </a>
                    </div>
                </div>

                <!-- james -->
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <button type="button" id="id_tip_no_call"  class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="至少拨打3次，并未拨通填写!">未拨通电话标注
                        </button>
                    </div>
                </div>

                <!-- 此处为模态框-->
                <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #3c8dbc;">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 style="text-align: center;color:white;" class="modal-title">未拨通电话标注</h4>
                            </div>
                            <div class="modal-body" style="text-align:center;">
                                <p>请设置</p>
                                <div class="" id="">
                                    <select style="width:35%;" class="invalid_type">
                                        <option value="0">请选择状态</option>
                                        <option value="1001">无效-空号</option>
                                        <option value="1002">无效-停机</option>
                                        <option value="1012">无效-屏蔽音</option>
                                        <option value="1004">无效-不接电话</option>
                                    </select>
                                    <p style="color:red;">请至少拨打3次确认状态</p>
                                </div>
                            </div>
                            <div class="modal-footer" style="text-align:center;">
                                <button type="button" class="btn btn-primary submit_tag">提交</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">再想想</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- 此处为模态框-->
                <div class="modal fade confirm-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #3c8dbc;">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 style="text-align: center;color:white;" class="modal-title">未拨通电话标注</h4>
                            </div>
                            <div class="modal-body" style="text-align:center;">
                                <p>是否标注为 <font style="color:red;" class="tip_text">无效-空号？</font></p>
                                <p style="color:red;">提示：如经核验不符，将被罚款！</p>
                            </div>
                            <div class="modal-footer" style="text-align:center;">
                                <button type="button" class="btn btn-primary confirm_tag">确认</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">再想想</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- //t_tq_call_info-->


                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <a class="btn btn-primary "  id="id_edit" >编辑</a>
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <button class="btn btn-primary" id="id_left_count"  title="可抢个数" > {{$count_info["left_count"]}}
                        </button>
                        <a class="btn btn-warning "  id="id_goto_new_list" > 继续抢新例子</a>
                        <a class="btn btn-warning "  id="id_get_new" style="display:none;" >抢新例子</a>
                    </div>
                </div>



            </div>
        </div>
        <hr/>
        @if ( $user_info )
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>项目</td>
                    <td>值</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>电话</td>
                    <td>
                        {{$user_info["phone"] }}
                        @if($user_info['origin'] == '优学优享')
                            /
                            <font color="red">
                                {{$user_info["origin"]}}
                            </font>
                        @endif
                    </td>
                </tr>


                <tr>
                    <td>拨打状态</td>
                    <td>
                        {{$user_info["tq_called_flag_str"] }}
                        @if  ($user_info["tq_called_flag"] ==0 )
                            <font color="red"> 请拨打,并 刷新通话记录 </font>
                        @elseif ( $user_info["tq_called_flag"] ==1  )
                            <font color="blue"> 重新拨打,或 抢新例子 </font>
                            <a class="btn btn-primary "  id="id_get_this_new_user" >认领例子</a>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>销售负责人</td>
                    <td>
                        {{$user_info["admin_revisiter_nick"] }}
                        @if ($user_info["admin_revisiterid"] ==0 )
                            <font color="red"> 还不是你的!! </font>
                        @endif
                    </td>
                    </td>
                </tr>



                <tr>
                    <tr>
                        <td>年级 </td>
                        <td>{{$user_info["grade_str"] }}</td>
                    </tr>

                    <tr>
                        <td>科目 </td>
                        <td>{{$user_info["subject_str"] }}</td>
                    </tr>


                    <tr>
                        <td>设备</td>
                        <td>{{$user_info["has_pad_str"] }}</td>
                    </tr>

            </tbody>
        </table>
        <font size="3" color="#FF3030" >拨通未满60S主动挂断电话:{{$count_new}}次,剩余:{{$left_count_new}}次</font>
        @elseif( is_array( @$errors) )
        <div class="alert alert-danger" style="margin:20px;">
            <strong>未开放</strong>
            <br><br>

            <ul>
                @foreach ($errors as $error)
                    <li>{!!  $error !!}  </li>
                @endforeach
            </ul>
        </div>

        @else
        <div class="row  " >
            <br/>

            <br/>
            <br/>


            <br/>

            <br/>
            <br/>
            <br/>

            <br/>
            <br/>


            <div class="col-xs-12 col-md-12  " >
                <div  style="  text-align:center; color:#3c8dbc;font-size:30px" >请抢新例子 </div>
                    <div  style="  text-align:center;  color:#3c8dbc;font-size:30px" >READY GO ... </div>
                </div>
            </div>

        @endif
    </section>


    <div style="display:none;" id="id_dlg_post_user_info">
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


                    <div class="col-xs-12 col-md-12 ">
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






    </div>




@endsection
