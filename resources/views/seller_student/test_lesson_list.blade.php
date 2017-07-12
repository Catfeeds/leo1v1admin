@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <script type="text/javascript" src="/page_js/seller_student/common.js?v=121"></script>

    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>


    <style>
     .input-group{
         width:100%;
     }
     .input-group-w145{
         width:145px !important;
     }
    </style>
    <section class="content">
        <div class="book_filter">

            <div class="row row-query-list" >
                <div class="col-xs-12 col-md-6" data-title="时间段">
                    <div id="id_date_range"> </div>  
                </div>


                <div class="col-md-2">
                    <div class="input-group">
                        <span>分类</span> 
                        <select id="id_from_type" class="opt-change  ">
                            <option value="-2">助教添加</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="input-group">
                        <span>状态</span> 
                        <select id="id_revisit_status" class="opt-change">
                            <option value="-3">所有试听用户</option>
                            <option value="-4">所有试听成功用户</option>
                            <option value="-1">全部</option>
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
                        <span class="input-group-addon">年级</span>
                        <select class="opt-change form-control" id="id_grade" >
                        </select>
                    </div>
                </div>


                <div class="col-md-2" style="display:none;">
                    <div class="input-group">
                        <span class="input-group-addon">来源</span>
                        <input type="text" id="id_origin" class="opt-change"/>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-addon">申请人</span>
                        <input id="id_st_application_nick" class="opt-change  "> </input>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">手机</span>
                        <input type="text" id="id_phone" class="opt-change" />
                    </div>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary" id="id_add_user">助教申请</button>
                </div>
                <div class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <span >渠道EX:</span>
                        <input type="text" id="id_origin_ex" class="opt-change"/>
                    </div>
                </div>
                
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >学生</span>
                        <input type="text" id="id_userid" class="opt-change"/>
                    </div>
                </div>
                
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师</span>
                        <input type="text" id="id_teacherid" class="opt-change"/>
                    </div>
                </div>
                
                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">课时设置</span>
                        <select class="opt-change form-control " id="id_confirm_flag" >
                            <option value=-2>所有无效课程</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >申请分类</span>
                        <select class="opt-change form-control " id="id_require_user_type" >
                            <option value=-1>全部</option>
                            <option value=0>销售</option>
                            <option value=1>助教</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">转介绍</span>
                        <select class="opt-change form-control" id="id_ass_adminid_flag" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">取消</span>
                        <select class="opt-change form-control" id="id_test_lesson_cancel_flag" >
                            <option value="-2">非换时间 </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr>
                        <td style="display:none;">手机号</td>
                        <td >基本信息 </td>
                        <td style="min-width:200px;">申请信息</td>
                        <td style="display:none;">申请时间</td>
                        <td style="display:none;">申请人</td>
                        <td style="display:none;">来源</td>
                        <td style="display:none;">姓名</td>
                        <td style="width:70px">回访状态</td>
                        <td style="display:none;">用户备注</td>
                        <td style="display:none;">年级</td>
                        <td style="display:none;" >科目</td>
                        <td style="display:none;">是否有pad</td>
                        <td style="display:none;">期待试听时间</td>
                        <td style="display:none;">回访记录</td>
                        <td style="display:none;">学校</td>
                        <td >试听需求</td>
                        <td style="display:none;">试卷</td>
                        <td style="min-width:80px">排课信息</td>
                        <td style="display:none;">老师</td>
                        <td style="display:none;">实际上课时间</td>
                        <td style="width:130px" >操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td > {{$var["phone"]}} </td>
                            <td >
                                序号: {{$var["id"]}} <br/>
                                {{$var["phone"]}} <br/>
                                {{$var["phone_location"]}} <br/>
                                分类：{{$var["from_type_str"]}}  <br/>
                                姓名：{{$var["nick"]}}  <br/>
                                年级：{{$var["grade_str"]}}  <br/>
                                科目：{{$var["subject_str"]}}  <br/>
                                PAD：{{$var["has_pad_str"]}}  <br/>
                                Pad测试：{{$var["stu_test_ipad_flag_str"]}}<br/>
                            </td>
                            <td >
                                申请时间： {{$var["st_application_time"]}}<br/>
                                申请人：{{$var["st_application_nick"]}}<br/>
                                来源：{{$var["origin"]}}<br/>
                                学校：{{$var["st_from_school"]}}<br/>
                                教材：{{$var["editionid_str"]}}<br/>
                                试卷：{{$var["st_test_paper_str"]}}<br/>
                                成绩情况: {{$var["stu_score_info"]}} <br/>
                                性格信息: {{$var["stu_character_info"]}} <br/>
                            </td>
                            <td >{{$var["st_application_time"]}}</td>
                            <td > {{$var["st_application_nick"]}}</td>
                            <td class="">{{$var["origin"]}}</td>
                            <td class="">{{$var["nick"]}}</td>
                            <td class="">{{$var["status_str"]}}</td>
                            <td >{{$var["user_desc"]}}</td>
                            <td class="">{{$var["grade_str"]}}</td>
                            <td >{{$var["subject_str"]}}</td>
                            <td >{{$var["has_pad_str"]}}</td>
                            <td >{{$var["st_class_time"]}}</td>
                            <td >{{$var["last_revisit_msg"]}}</td>
                            <td >{{$var["st_from_school"]}}</td>
                            <td >
                                期待时间: {{$var["st_class_time"]}} <br/>
                                期待时间(其它): {!!  $var["stu_request_test_lesson_time_info_str"]!!} <br/>
                                正式上课: {!!  $var["stu_request_lesson_time_info_str"]!!} <br/>
                                试听内容: {{$var["stu_test_lesson_level_str"]}} <br/>
                                试听需求:{{$var["st_demand"]}}</td>
                            <td >{{$var["st_test_paper_str"]}}</td>
                            <td>
                                抢单老师:{{$var["assigned_teacher_nick"]}} <br/>
                                正式老师:{{$var["teacher_nick"]}} <br/>
                                上课时间:{{$var["lesson_time"]}} <br/>
                                有效情况:{{$var["confirm_flag_str"]}} <br/>
                                <br/>
                                @if ( $var["cancel_flag"] )  
                                取消人:{{$var["cancel_admin_nick"]}} <br/>
                                取消时间:{{$var["cancel_time"]}} <br/>
                                取消老师:{{$var["cancel_teacher_nick"]}} <br/>
                                取消标志:{{$var["cancel_flag_str"]}} <br/>
                                取消的上课时间:{{$var["cancel_lesson_start"]}} <br/>
                                取消原因:{{$var["cancel_reason"]}} <br/>
                                @endif
                            </td>

                            <td >{{$var["teacher_nick"]}}</td>
                            <td >{{$var["lesson_time"]}}</td>
                            <td>
                                <div class="opt-div" 
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                    <a href="javascript:;" title="用户信息" class="fa-user opt-user"></a>
                                    <a href="javascript:;" title="修改状态,录入回访信息" class="fa-book opt-update_user_info"></a>
                                    
                                    <a href="javascript:;" title="修改用户信息" class="fa-edit opt-update-news2"></a>
                                    <a href="javascript:;" title="查看回访" class="fa-comments opt-return-back-list"></a>
                                    <a title="上传试卷" id="upload-test-paper-{{$var["id"]}}" class="fa-upload opt-upload-test-paper "></a>
                                    <a title="下载试卷"  class="fa-download opt-download-test-paper "></a>
                                    <a title="设置试听信息" class="fa fa-headphones opt-set-test-lesson-info "></a>
                                    <a title="排课" class="fa-bars opt-set-lesson "> </a>
                                    <a title="绑定试听课程" class="fa-link opt-binding-lesson "> </a>
                                    <a title="查看试听课老师反馈" class="fa fa-bookmark opt-get_stu_performance"></a>
                                    <a title="试听派单" class="opt-assign-teacher">派单</a>

                                    <a class="fa-video-camera  opt-play " title="回放"  ></a>
                                    <a href="javascript:;" class="btn fa fa-gavel opt-confirm" title="确认课时"></a>
                                    <a title="复制例子,换时间,换老师"  class=" fa-copy opt-copy"></a>
                                    <a title="排课-new" class="fa-list opt-set-lesson-new "> </a>
                                    <a title="安排老师(开发中)" class="opt-set-teacher">测试功能 </a>
                                    <a title="用户试听信息" class="fa-list-alt  opt-user-info"> </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>
        <div class="dlg-set-status" style="display:none">
            <div class="row">
                <div class="input-group">
                    <span class="input-group-addon">用户手机</span>
                    <label class="show-user-phone form-control" ></label>
                </div>
            </div>
            <div>
			    <td style="text-align:right; width:30%;">设置状态</td>
			    <td>
                    <select class="update_user_status">
                    </select>
                </td>
	        </div>
	    </div>

        <div class="dlg-add-revisit" style="display:none">
            <div class="row">
                <div class="input-group">
                    <span class="input-group-addon">用户手机</span>
                    <label class="show-user-phone form-control" ></label>
                </div>
            </div>
            <div class="row">
                <div class="input-group">
                    <span class="input-group-addon">回访记录</span>
                    <textarea class="opt-add-record form-control" style="height:200px;"></textarea>
                </div>
            </div>
        </div>

        <div class="dlg-show-revisit" style="display:none">
            <table class="table table-bordered table-striped ">
            </table>
        </div>

        <div class="dlg-add_book_time_next" style="display:none">
            <div class="row">
                <div class="input-group">
                    <span class="input-group-addon">添加下次回访时间</span>
                    <input class="update_book_time_next" type="text"/>
                </div>
            </div>
        </div>

        <div class="dlg-update_user_info" style="display:none">
            <table class="table table-bordered table-striped">
	            <tbody>
		            <tr>
			            <td style="text-align:right; width:30%;">用户手机</td>
			            <td><input value="" class="update_user_phone" type="text"/></td>
		            </tr>
                    <tr>
			            <td style="text-align:right; width:30%;">用户状态</td>
			            <td>
                            <select class="update_user_status">
                            </select>
                        </td>
		            </tr>
                    <tr>
			            <td style="text-align:right; width:30%;">取消状态</td>
			            <td>
                            <select class="update_cancel_status">
                            </select>
                        </td>
		            </tr>

                    <tr>
			            <td style="text-align:right; width:30%;">取消原因</td>
			            <td>
                            <input class="update_cancel_reason"/>
                        </td>
		            </tr>

                    <tr>
			            <td style="text-align:right; width:30%;">用户备注</td>
			            <td><textarea value="" style="height:150px;width:100%;" class="update_user_note" type="text"></textarea></td>
		            </tr>
                    <tr>
			            <td style="text-align:right; width:30%;">回访记录</td>
			            <td><textarea value="" style="height:150px;width:100%" class="update_user_record" type="text"></textarea></td>
		            </tr>

                </tbody>
	        </table>
        </div>
        <div style="display:none;" >
            <select id="update_grade">
                <option value="101">小一</option>
                <option value="102">小二</option>
                <option value="103">小三</option>
                <option value="104">小四</option>
                <option value="105">小五</option>
                <option value="106">小六</option>
                <option value="201">初一</option>
                <option value="202">初二</option>
                <option value="203">初三</option>
                <option value="301">高一</option>
                <option value="302">高二</option>
                <option value="303">高三</option>
            </select>
            <select id="update_subject">
                <option value="1">语文</option>
                <option value="2">数学</option>
                <option value="3">英语</option>
                <option value="4">化学</option>
                <option value="5">物理</option>
                <option value="6">生物</option>
                <option value="7">政治</option>
                <option value="8">历史</option>
                <option value="9">地理</option>
            </select>
            <select id="update_pad">
                <option value="0">没有</option>
                <option value="1">ipad</option>
                <option value="2">安卓pad</option>
                <option value="3">其他pad</option>
            </select>

        </div>
        
    <div style="display:none;" id="id_dlg_set_user_info" >
        <div class="row">
            <div class="col-xs-12 col-md-6  ">
                <div class="row">
                    <div class="col-xs-12 col-md-6  ">
                        <div class="input-group ">
                            <span class="input-group-w145" >本节课内容：</span>
                            <input type="text" class=" form-control "  id="id_stu_lesson_content"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="input-group ">
                            <span class="input-group-w145">大致推荐课时数:</span>
                            <input type="text" id="id_stu_lesson_count" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-w145">学生优点：</span>
                            <input type="text" id="id_stu_advantages"  class="form-control"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-w145">学生缺点：</span>
                            <input type="text" id="id_stu_disadvantages"  class="form-control"  />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-w145">学生课堂状态：</span>
                            <input type="text" id="id_stu_lesson_status"  class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-w145">学生吸收情况：</span>
                            <input type="text" id="id_stu_study_status"  class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="input-group ">
                            <span class="input-group-w145">培训计划（简述）:</span>
                            <input type="text" id="id_stu_lesson_plan" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="input-group ">
                            <span class="input-group-w145">教学方向:</span>
                            <input type="text" id="id_stu_teaching_direction" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-6  ">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="input-group ">
                            <span class="input-group-w145">教材及内容:</span>
                            <input type="text" id="id_stu_textbook_info" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="input-group ">
                            <span class="input-group-w145">教学目标:</span>
                            <input type="text" id="id_stu_teaching_aim" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-w145" >意见、建议等（不少于50字）：</span>
                            <textarea class="form-control" style="height:150px;" id="id_stu_advice"  > </textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    <script src="/js/qiniu/plupload/plupload.full.min.js"></script>

@endsection

