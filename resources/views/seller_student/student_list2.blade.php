@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>


<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
<script type="text/javascript" src="/page_js/seller_student/common.js"></script>

<script type="text/javascript" src="/page_js/lib/select_dlg_edit.js?v={{@$_publish_version}}"></script>
<script type="text/javascript" src="/page_js/lib/select_date_time_range.js?v={{@$_publish_version}}"></script>
<style>
 .input-group{
     width:100%;
 }
 .input-group-w145{
     width:145px !important;
 }
</style>
 <section class="content">

     <div class="row row-query-list">
         <div class="col-xs-12 col-md-6" data-title="时间段">
             <div id="id_date_range"> </div>
         </div>
         <div class=" col-xs-6 col-md-2">
             <div class="input-group">
                 <span>回访状态</span> 
                 <select id="id_revisit_status" class="opt-change  ">
                     <option value="-2"> 已回访</option>
                     <option value="-3">所有试听用户</option>
                     <option value="-4">所有需要通知上课用户</option>
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
         <div class=" col-xs-6 col-md-2">
             <div class="input-group">
                 <span class="input-group-addon">手机地址</span>
                 <input type="text" id="id_phone_location" class="opt-change"/>
             </div>
         </div>


         <div class=" col-xs-6 col-md-3">
             <div class="input-group">
                 <input type="text" id="id_phone" class="opt-change" placeholder="手机号,姓名,回车查询"/>
             </div>
         </div>

         <div class= " col-xs-6 col-md-2" style="display:none;">
             <div class="input-group">
                 <span class="input-group-addon">来源</span>
                 <input type="text" id="id_origin" class="opt-change"/>
             </div>
         </div>
         <div class=" col-xs-6 col-md-3">
             <div class="input-group">
                 <span class="input-group-addon">来源-EX</span>
                 <input type="text" id="id_origin_ex" class="opt-change"/>
             </div>
         </div>

        <div class="col-xs-6 col-md-2">
            
            <div class="input-group ">
                 <span class="input-group-addon">pad</span>
                <select class="opt-change form-control" id="id_has_pad" >
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
                <span class="input-group-addon">资源库</span>
                <select class="opt-change form-control" id="id_seller_resource_type" >
                </select>
            </div>
        </div>


         <div class=" col-xs-6 col-md-3" >
             <div class="input-group">
                 

                 <button class="btn  " id="id_next_revisit" data-value="{{$next_revisit_count}}" >{{$next_revisit_count}}</button>
                 <button data-value="{{$notify_lesson_info["today"]}}"   class="btn  " id="id_lesson_today"></button>
                 <button data-value="{{$notify_lesson_info["tomorrow"]}}"  class="btn  " id="id_lesson_tomorrow" ></button>
                 <button data-value="{{$return_back_count}}"  class="btn  " id="id_return_back_count" ></button>
                 <button data-value="{{$require_count}}"  class="btn  " id="id_require_count" ></button>
             </div>
         </div>



     </div>


     <hr />
    <div class="body">
         <table class="common-table " id="id_student_table">
             <thead>
                 <tr>
                     <td >手机号</td>
                     <td style="width:60px">时间</td>
                     <td style="display:none;">资源进来时间</td>
                     <td style="display:none;">申请试听时间</td>
                     <td >来源</td>
                     <td >姓名</td>
                     <td >回访状态</td>
                     <td style="display:none;" >用户备注(all)</td>
                     <td >用户备注</td>
                     <td >年级</td>
                     <td >科目</td>
                     <td style="width:60px">是否有pad</td>
                     <td >试卷</td>
                     <td >下次跟进时间</td>

                     <td style="display:none;" >分配时间</td>
                     <td >最后一次回访时间</td>
                     <td >最后一次回访记录</td>
                     <td >老师</td>
                     <td >实际上课时间</td>

                     <td >操作</td>

                 </tr>
             </thead>
             <tbody>
                 @foreach ($table_data_list as $var)
                     <tr>
                         <td >
                             {{$var["phone"]}}
                             <br/>
                             {{$var["phone_location"]}}
                         </td>
                         <td >{{$var["opt_time"]}}</td>
                         <td >{{$var["add_time"]}}</td>
                         <td >{{$var["st_application_time"]}}</td>
                         <td class="">
                            {{$var["origin"]}}<br/>
                            助教:{{$var["ass_admin_nick"]}}<br>
                            转介绍人:{{$var["origin_user_nick"]}}
                         </td>

                         <td >{{$var["nick"]}}</td>
                         <td >{{$var["status_str"]}}<br/><br/>TQ:{{$var["tq_called_flag_str"]}}</td>
                         <td class="user-desc">{{$var["user_desc"]}}</td>
                         <td >{{$var["user_desc_sub"]}}</td>
                         <td >{{$var["grade_str"]}}</td>
                         <td >{{$var["subject_str"]}}</td>
                         <td >{{$var["has_pad_str"]}}</td>
                         <td >{!!  $var["st_test_paper_flag_str"]  !!}</td>
                         <td >{{$var["next_revisit_time"]}}</td>
                         <td >{{$var["admin_assign_time"]}}</td>
                         <td >{{$var["last_revisit_time"]}}</td>
                         <td >{{$var["last_revisit_msg_sub"]}}</td>
                         <td >{{$var["teacher_nick"]}}</td>
                         <td >{{$var["lesson_time"]}} <br/>{!! $var["notify_lesson_flag_str"]!!}</td>
                         <td>
                             <div 
                                 {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                             >
                                 <a title="录入回访信息" class=" show-in-select fa-edit opt-update-infomation-2"></a>
                                 <a href="javascript:;" title="用户信息" class="fa-user opt-user"></a>
                                 <a title="手机拨打&录入回访信息" class=" fa-phone  opt-telphone   "></a>
                                 <a title="查看回访" class=" show-in-select  fa-comments  opt-return-back-list "></a>
                                 <a title="上传试卷" id="upload-test-paper-{{$var["id"]}}" class=" fa-upload opt-upload-test-paper "></a>
                                 <a title="下载试卷"  class=" fa-download opt-download-test-paper "></a>
                                 <a title="设置排课通知家长"  class=" fa-bullhorn opt-notify-lesson"></a>
                                 <a style="display:none;" title="设置试听信息" class="fa fa-headphones opt-set-test-lesson-info "></a>
                                 <a title="查看试听课老师反馈" class="fa fa-bookmark opt-get_stu_performance"></a>
                                 <a title="复制例子,换老师,时间,扩课" class="fa fa-copy opt-copy"></a>
                                 <div data-aling="kehuguanhai" data-telnumber="{{$var["phone"]}}" ></div>
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

     <div class="dlg-show-revisit" style="display:none">
         <table class="table table-bordered table-striped ">
         </table>
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

     
     <div class="dlg_edit_manage" style="display:none">
         <table class="table table-bordered table-striped">
	         <tbody>
		         <tr>
			         <td style="text-align:right; width:30%;">手机号</td>
			         <td><input value="" class="update_phone" type="text"/></td>
		         </tr>
		         <tr>
			         <td style="text-align:right; width:30%;">年级</td>
			         <td><input value="" class="update_grade" type="text"/></td>
		         </tr>

		         <tr>
			         <td style="text-align:right; width:30%;">科目</td>
			         <td><input value="" class="update_subject" type="text"/></td>
		         </tr>
		         <tr>
			         <td style="text-align:right; width:30%;">是否有pad</td>
			         <td><input value="" class="update_pad" type="text"/></td>
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

                         <div class="col-xs-12 col-md-6 ">
                             <div class="input-group ">
                                 <span class="input-group-addon">回访状态：</span>
                                 <select id="id_stu_status" class=" form-control "   >
                                 </select>
                             </div>
                         </div>
                         <div class="col-xs-12 col-md-6 ">
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
                                 <input id="id_st_class_time" class=" form-control "   />
                                 <div class=" input-group-btn "  >
                                     <button class="btn  btn-primary " id="id_stu_reset_st_class_time"  title="取消"> 
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
                                           id="id_st_demand" > </textarea>
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

