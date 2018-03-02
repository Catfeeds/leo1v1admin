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
                    <option value="-1">[全部]</option>
                    <option value="1">学情未回访</option>
                    <option value="2">月度未回访</option>
                    <option value="3">当月退费预警未回访</option>
                    <option value="4">当月退费预警回访<4</option>
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">预警学员</span>
                <select class="stu_sel form-control" id="id_warning_stu" >
                    <option value="-1">[全部学员]</option>
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
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">退费预警级别</span>
                <select class="stu_sel form-control" id="id_refund_warn">
                    <option value="-1">[全部]</option>
                    <option value="3">三级</option>
                    <option value="2">二级</option>
                    <option value="1">一级</option>
                    <option value="4">全部退费预警</option>
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
                    ["退费预警级别", "refund_warning_level"],
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
                    <td  class="userid">{{$var["userid"]}} </td>
                    <td class="user_nick">{{$var["nick"]}}</td>
                    <td >{{$var["location"]}}</td>
                    <td >{{$var["type"]}}</td>
                    @if ($var["refund_warning_level"] == 3)
                        <td><div><span class="refund_warn_reason" style='color:#FF0000;cursor:pointer'>三级</span></div></td>
                    @elseif ($var["refund_warning_level"] == 2)
                        <td><div><span class="refund_warn_reason" style='color:#FFCC33;cursor:pointer'>二级</span></div></td>
                    @elseif ($var["refund_warning_level"] == 1)
                        <td><div><span class="refund_warn_reason" style='color:#0099FF;cursor:pointer'>一级</span></div></td>
                    @else
                        <td><div><span class="refund_warn_reason" style='color:#0000FF;cursor:pointer'>无</span></div></td>
                    @endif
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
                            @if(in_array($acc, ['tom','jim']) || $account_role=12)
                                <a title="试听排课new" class="fa-chevron-up opt-edit-new_new_two"></a>
                            @endif
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
                            <!-- <a class="refund_return_back_new" title="回访-new">退费预警回访</a> -->
                            <!-- <a class="refund_warn_reason" title="退费预警级别详情">详情</a> -->

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




    <div style="display:none;" id="id_dlg_post_user_info_new_two">
        <div class="alert alert-danger note-info" style="margin-bottom:0px;display:none;" >
            <strong>重要提示:</strong> <span>  xx </span>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>其他</span>
            </div>
            <div class="col-xs-12 col-md-12  ">
                <div class="row">
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp分类：</span>
                            <select id="id_ass_test_lesson_type_new_two" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon">&nbsp绿色通道：</span>
                            <input type="text" class=" form-control "  id="id_green_channel_teacherid_new_two" />
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-3 " style="display:none;">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp学情反馈：</span>
                            <textarea class="form-control" style=";height:75px;" id="id_learning_situation_new_two" ></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-3  "  style="display:none;">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp换老师类型：</span>
                            <select id="id_change_teacher_reason_type_new_two" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3  " style="display:none;" >
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp申请原因：</span>
                            <textarea class="form-control" style="height:75px;" id="id_change_reason_new_two" > </textarea>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3  "  style="display:none;margin:2% 0 0 -2%;">
                        <div class="input-group ">
                            <button class="btn  btn-primary upload_change_reason_url"  title="上传" >
                                上传
                            </button>
                            <a id="id_change_reason_url_new_two" style="cursor:pointer;" target="_blank"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>基本信息&nbsp<font style="color:red">标记红色星号*的为必填内容</font></span>
            </div>
            <div class="col-xs-12 col-md-12  ">
                <div class="row">
                    <div class="col-xs-12 col-md-2  ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp学员姓名：</span>
                            <input type="text" class=" form-control "  id="id_stu_nick_new_two" style="width:100px;" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2" style="margin:0 20px 0 30px;">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp性别：</span>
                            <select id="id_stu_gender_new_two" class=" form-control " style="width:120px;" >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2 "  >
                        <div class="input-group " >
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp家长姓名：</span>
                            <input type="text" class=" form-control "  id="id_par_nick_new_two" style="width:88px" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2 " style="margin:0 0.3% 0 0;width:100px;">
                        <div class="input-group " style="margin:0 0 0 -20%;width:80px;">
                            <select id="id_par_type_new_two" class=" form-control " >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp年级：</span>
                            <select id="id_stu_grade_new_two" class=" form-control" >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp科目：</span>
                            <select id="id_stu_subject_new_two" name="subject_score_new_two" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-2 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp教材：</span>
                            <select id="id_stu_editionid_new_two" class=" form-control "  style="width:130px;" >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2 " style="margin:0 20px 0 30px;">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp设备：</span>
                            <select id="id_stu_has_pad_new_two" class=" form-control "  style="width:120px;" >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2 " style="width:290px;margin:0 20px 0 0px;">
                        <div class="input-group ">
                            <span class="input-group-addon">在读学校：</span>
                            <input type="text" id="id_stu_school_new_two"  class="form-control" style="width:166px;" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2  ">
                        <div class="input-group ">
                            <span class="input-group-addon">性格：</span>
                            <input type="text" class=" form-control "  id="id_character_type_new_two"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2  ">
                        <div class="input-group ">
                            <span class="input-group-addon">爱好：</span>
                            <input type="text" class=" form-control "  id="id_interests_hobbies_new_two"  />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-2">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp省</span>
                            <select class="form-control" id="province_new_two" name="province"  style="width:155px">
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2" style="margin:0 20px 0 30px">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red">*</font>&nbsp市</span>
                            <select class="form-control" id="city_new_two" name="city">
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2">
                        <div class="input-group ">
                            <span class="input-group-addon">区(县)</span>
                            <select class="form-control" id="area_new_two" name="area">
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-5 " >
                        <div class="input-group ">
                            <span class="input-group-addon">详细住址：</span>
                            <input type="text" id="id_stu_addr_new_two" placeholder="请输入详细住址" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>学习概况</span>
            </div>
            <div class="col-xs-12 col-md-12  ">
                <div class="row">
                    <div class="col-xs-12 col-md-1" >
                        <div class="input-group ">
                            <span class="input-group-addon" style="height:34px;"><font class="required fields" style="color:red">*</font>&nbsp综合排名：</span>
                        </div>
                    </div>

                    <div class='col-xs-12 col-md-2' style="">
                        <div class='input-group' style='width:118px;'>
                            <input type="text" class=" form-control "  id="id_class_rank_new_two"  placeholder='' />
                        </div>
                    </div>
                    <div class="input-group " style="display:none;">
                        <span class="input-group-addon">年级排名：</span>
                        <input type="text" class=" form-control "  id="id_grade_rank_new_two"  placeholder='年级排名' />
                    </div>



                    <div class="subject_score">
                        <div class='col-xs-12 col-md-1' style='margin:0 0 0 -0.18%'>
                            <div class='input-group'>
                                <span class='input-group-addon' style='height:34px;'><font class="required fields" style='color:red'>*</font>&nbsp科目：</span>
                                <select name='subject_score_new_two' id='id_main_subject_new_two' class='form-control' style='width:70px'>
                                </select>
                            </div>
                        </div>
                        <div class='col-xs-3 col-md-1' style='margin:0 0 0 3.0%'>
                            <div class='input-group' style='width:90px;'>
                                <input type='text' class='form-control' id='id_main_subject_score_one_new_two' name='subject_score_one_new_two' placeholder='' />
                            </div>
                        </div>
                        <div class='col-xs-3 col-md-1' style='width:8px;margin:0.5% 2.5% 0 0%;cursor: pointer;' >
                            <i class='fa fa-plus' onclick='add_subject_score(this)' title='添加科目'></i>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-xs-12 col-md-3  ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font class="required fields" style="color:red">*</font>&nbsp学习目标：</span>
                            <select id="id_test_stress_new_two" class=" form-control " >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 " style="display:none;">
                        <div class="input-group ">
                            <span class="input-group-addon"><font class="required fields" style="color:red">*</font>&nbsp升学目标：</span>
                            <select id="id_academic_goal_new_two" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 " >
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red" class="required fields">*</font>&nbsp升学目标：</span>
                            <select id="id_entrance_school_type_new_two" class=" form-control " >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 " style="margin:0 0 0 -7px;" >
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red" class="required fields">*</font>&nbsp素质培养：</span>
                            <input type="text" class=" form-control "  id="id_cultivation_new_two"  />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-3 " style="display:none;">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red" class="required fields">*</font>&nbsp近期成绩：</span>
                            <input type="text" class=" form-control "  id="id_recent_results_new_two" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 " style="display:none">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red" class="required fields">*</font>&nbsp是否进步：</span>
                            <select id="id_advice_flag_new_two" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 " style="display:none">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red" class="required fields">*</font>&nbsp趣味培养：</span>
                            <select id="id_interest_cultivation_new_two" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3  " style="display:none;">
                        <div class="input-group ">
                            <span class="input-group-addon">课外提高：</span>
                            <select id="id_extra_improvement_new_two" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 " style="display:none;">
                        <div class="input-group ">
                            <span class="input-group-addon">习惯重塑：</span>
                            <select id="id_habit_remodel_new_two" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-3  " style="display:none;">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red" class="required fields">*</font>&nbsp学习习惯：</span>
                            <input type="text" class=" form-control "  id="id_study_habit_new_two" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <div class="row">
            <div class="col-xs-12 col-md-12  ">
                <span>老师要求</span>
            </div>
            <div class="col-xs-12 col-md-12  ">
                <div class="row">
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red" class="required fields">*</font>&nbsp风格性格：</span>
                            <input type="text" class=" form-control "  id="id_teacher_nature_new_two" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red" class="required fields">*</font>&nbsp专业能力：</span>
                            <input type="text" class=" form-control "  id="id_pro_ability_new_two" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red" class="required fields">*</font>&nbsp老师身份：</span>
                            <select id="id_tea_status_new_two" class=" form-control " >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red" class="required fields">*</font>&nbsp年龄段：</span>
                            <select id="id_tea_age_new_two" class=" form-control " >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red" class="required fields">*</font>&nbsp老师性别：</span>
                            <select id="id_tea_gender_new_two" class=" form-control " >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red" class="required fields">*</font>&nbsp课堂气氛：</span>
                            <input type="text" class=" form-control "  id="id_class_env_new_two" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 ">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red" class="required fields">*</font>&nbsp课件要求：</span>
                            <input type="text" class=" form-control "  id="id_courseware_new_two" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 " style="display:block;">
                        <div class="input-group ">
                            <span class="input-group-addon"><font style="color:red" class="required fields">*</font>&nbsp老师类型：</span>
                            <select id="id_teacher_type_new_two" class=" form-control " >
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12  " >
                <span>试听内容</span>
                <span style="margin-left:70px;" id="id_add_tag_new_two"></span>
            </div>
            <div class="col-xs-12 col-md-9  ">
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon" >　<font style="color:red;" class="required fields" >*</font>&nbsp　试听要求：</span>
                            <textarea class="form-control" style="height:115px;" class="class_stu_request_test_lesson_demand_new_two" id="id_stu_request_test_lesson_demand_new_two" ></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-3  "  style="margin:0 0 0 -2%;">
                <div class="row">
                    <div class="col-xs-12 col-md-12 " style="width:310px;">
                        <div class="input-group " >
                            <span class="input-group-addon"><font style="color:red" >*</font>&nbsp试听时间：</span>
                            <input id="id_stu_request_test_lesson_time_new_two" placeholder="开始时间" class=" form-control " style="1"  />
                            <input id="id_stu_request_test_lesson_time_end_new_two" placeholder="结束时间" class=" form-control "   />
                            <div class=" input-group-btn "  >
                                <button class="btn  btn-primary" id="id_stu_reset_stu_request_test_lesson_time_new_two"  title="取消" >
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-12  ">
                        <div class="input-group ">
                            <span class="input-group-addon">上传试卷：</span>
                            <input type="text" class=" form-control "  id="id_test_paper_new_two"   / >
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

@endsection
