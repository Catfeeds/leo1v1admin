@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>

<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/seller_student/common.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg.js"></script>
<script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
<script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>

<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
<section class="content">
    <div class="row row-query-list">

         <div class="col-xs-6 col-md-6">
             <div id="id_date_range"> </div>
         </div>

        <div class="col-md-2 col-xs-6">
            <div class="input-group">
                <span class="input-group-addon">来源</span>
                <input type="text" id="id_origin" class="opt-change"/>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">主管</span>
                <input class="opt-change form-control" id="id_sub_assign_adminid" />
            </div>
        </div>


        <div class="col-md-2 col-xs-6">
            <div class="input-group">
                <span class="input-group-addon">负责人</span>
                <input id="id_admin_revisiterid" class="opt-change  " />
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
                <span class="input-group-addon">PAD</span>
                <select class="opt-change form-control" id="id_has_pad" >
                </select>
            </div>
        </div>

        <div class="col-md-2 col-xs-6 "">
            <div class="input-group">
                <span class="input-group-addon">手机地址</span>
                <input type="text" id="id_phone_location" class="opt-change"/>
            </div>
        </div>
        <div class="col-md-2 col-xs-6 ">
            <div class="input-group ">
                <span class="input-group-addon">科目</span>
                <select class="opt-change form-control" id="id_subject" >
                </select>
            </div>
        </div>


        <div class="col-md-2 col-xs-6 " >
            <div class="input-group">
                <span>状态</span> 
                <select id="id_revisit_status" class="opt-change  ">
                    <option value="-2"> 已回访</option>
                    <option value="-3">所有试听用户</option>
                </select>
            </div>
        </div>

        <div class="col-md-2 col-xs-6 ">
            <div class="input-group">
                <span class="input-group-addon">手机</span>
                <input type="text" id="id_phone" class="opt-change"/>
            </div>
        </div>
        <div class="col-md-2 col-xs-6 ">
            <div class="input-group">
                <span>每页个数</span> 
                <select id="id_page_count" class="opt-change  ">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>



        
        <div class="col-md-1 remove-for-xs col-xs-6 "" >
            <div> 
                <button class="btn btn-primary" id="id_upload_xls"> 上传xls </button>
            </div>
        </div>
        <div class="col-md-1 col-xs-4 col-xs-6 "">
            <div>
                <button class="btn btn-primary" id="id_add_user"> 添加用户 </button>
            </div>
        </div>
        <div class="col-md-1 col-xs-4">
            <div>
                <button class="btn btn-primary" id="id_sub_assign_adminid_select"> 主管分配</button>
            </div>
        </div>
        <div class="col-md-1 col-xs-4">
            <div>
                <button class="btn btn-primary" id="id_assign_seller_select"> 组员分配 </button>
            </div>
        </div>

        <div class="col-md-1 col-xs-4 ">
            <div>
                <button class="btn btn-primary" id="id_assign_seller_del"> 用户删除</button>
            </div>
        </div>

        <div class="col-md-1 col-xs-6 "  >
            <div>
                <button class="btn btn-primary" id="id_upload_xls_jingxun">上传京讯</button>
            </div>
        </div>

        <div class="col-md-1 col-xs-6 " style="display:none;" >
            <div>
                <button class="btn btn-primary" id="id_upload_xls_jinshuju"> 上传金数据</button>
            </div>
        </div>
        <div class="col-md-1 col-xs-6 " style="display:none;">
            <div>
                <button class="btn btn-primary" id="id_upload_xls_youzan"> 上传有赞</button>
            </div>
        </div>


        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">TQ</span>
                <select class="opt-change form-control" id="id_tq_called_flag" >
                </select>
            </div>
        </div>



        <div class="col-md-2 col-xs-6 ">
            <div class="input-group">
                <span class="input-group-addon">姓名</span>
                <input type="text" id="id_nick" class="opt-change"/>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">资源库</span>
                <select class="opt-change form-control" id="id_seller_resource_type" >
                </select>
            </div>
        </div>


        <div class="col-md-4 col-xs-6 ">
            <div class="input-group">
                <span class="input-group-addon">渠道EX</span>
                <input type="text" id="id_origin_ex" class="opt-change"/>
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
                <span class="input-group-addon">课前取消</span>
                <select class="opt-change form-control" id="id_test_lesson_cancel_flag" >
                    <option value="-2">非换时间 </option>
                    
                </select>
            </div>
        </div>


        <div class="col-xs-6 col-md-2">
            <button class="btn" id="id_unallot" data-value="{{$unallot}}" >{{$unallot}}</button>
            <button class="btn" id="id_unset_admin_revisiterid" data-value="{{$unset_admin_revisiterid}}" >{{$unset_admin_revisiterid}}</button>
        </div>
       

    </div>



    <hr />
    <div class="body">
        <table class="common-table ">
            <thead>
                <tr>
                    <td style="width:10px">
                        <a href="javascript:;" id="id_select_all" title="全选">全</a>
                        <a href="javascript:;" id="id_select_other" title="反选">反</a>
                    </td>
                    <td style="display:none;">手机号</td>
                    <td >基本信息</td>
                    <td style="width:60px">时间</td>
                    <td style="display:none;">登记时间</td>
                    <td style="display:none;">分配销售时间</td>
                    <td style="display:none;">申请试听时间</td>
                    <td style="display:none;">第一次回访时间</td>
                    <td >来源</td>
                    <td style="display:none;" >姓名</td>
                    <td  style="width:70px">回访状态</td>
                    <td >用户备注</td>
                    <td style="display:none;">年级</td>
                    <td style="display:none;">科目</td>
                    <td style="display:none;">是否有pad</td>
                    <td >负责人</td>
                    <td style="display:none;">分配时间</td>
                    <td >回访记录</td>
                    <td style="min-width:80px"  >排课信息</td>
                    <td style="display:none;">老师</td>
                    <td style="display:none;">实际上课时间</td>
                    <td >最后一次回访</td>
                    <td style="display:none;">所有收入</td>
                    <td style="min-width:130px" >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td>
                            <input name="check" type="checkbox" class="opt-select-user" />
                            {{$var["number"]}}
                        </td>
                        <td > {{$var["phone"]}} </td>
                        <td >
                            {{$var["phone"]}} <br/> 
                            {{$var["phone_location"]}}  <br/>
                            姓名：{{$var["nick"]}}  <br/>
                            年级：{{$var["grade_str"]}}  <br/>
                            科目：{{$var["subject_str"]}}  <br/>
                            PAD：{{$var["has_pad_str"]}}  <br/>
                        </td>
                        <td >{{$var["opt_time"]}}</td>
                        <td >{{$var["add_time"]}}</td>
                        <td >{{$var["admin_assign_time"]}}</td>
                        <td >{{$var["st_application_time"]}}</td>
                        <td >{{$var["first_revisite_time"]}}</td>

                        <td class="">
                            {{$var["origin"]}}<br/>
                            助教:{{$var["ass_admin_nick"]}}<br>
                            转介绍人:{{$var["origin_user_nick"]}}
                        </td>
                        <td class="">{{$var["nick"]}}</td>
                        <td class="">{{$var["status_str"]}}<br/><br/>TQ:{{$var["tq_called_flag_str"]}}</td>
                        <td >{{$var["user_desc"]}}</td>
                        <td class="">{{$var["grade_str"]}}</td>
                        <td >{{$var["subject_str"]}}</td>
                        <td class="">{{$var["has_pad_str"]}}</td>
                        <td >
                            主管: {{$var["sub_assign_admin_nick" ]}} /<br/>
                            组员: {{$var["admin_revisiter_nick" ]}} <br/>
                        </td>
                        <td >{{$var["admin_assign_time"]}}</td>
                        <td >{{$var["last_revisit_msg"]}}</td>
                        <td>
                            老师:{{$var["teacher_nick"]}} <br/>
                            时间:{{$var["lesson_time"]}} <br/>
                        </td>
                        <td >{{$var["teacher_nick"]}}</td>
                        <td >{{$var["lesson_time"]}}</td>
                        <td >{{$var["last_revisit_time"]}}</td>
                        <td  >{{$var["money_all"]}}</td>
                        <td  >
                            <div class="opt-div" 

                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a href="javascript:;" title="用户信息" class="  fa-user opt-user"></a>
                                <a href="javascript:;" title="查看回访" class=" fa-comments opt-return-back-list  "></a>
                                <a href="javascript:;" title="修改信息2" class=" fa-edit opt-update-news2"></a>
                                <a href="javascript:;" title="分配销售" class="btn fa fa-user-md opt-alloc-seller"></a>
                                <a  href="javascript:;" title="删除" class="fa fa-trash-o done_t"></a>
                                
                                <a title="下载试卷"  class=" fa-download opt-download-test-paper "></a>
                                <a title="复制例子,用扩课试听,重复试听"  class=" fa-copy opt-copy"></a>
                                <a title="设置试听信息" class="fa fa-headphones   opt-set-test-lesson-info "></a>
                                <a title="查看试听课老师反馈" class="opt-get_stu_performance">试听反馈</a>
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
			        <td style="text-align:right; width:30%;">用户备注</td>
			        <td><textarea value="" style="height:150px;width:100%;" class="update_user_note" type="text"></textarea></td>
		        </tr>
                <tr>
			        <td style="text-align:right; width:30%;">回访记录</td>
			        <td><textarea value="" style="height:150px;width:100%" class="update_user_record" type="text"></textarea></td>
		        </tr>

            </tbody>
	    </table>

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

</section>
    <script src="/js/qiniu/plupload/plupload.full.min.js"></script>

@endsection

