@extends('layouts.app')
@section('content')
 <script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>
    <section class="content">
        <div class="book_filter">
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="input-group ">
                        <span >上课时间:</span>
                        <input type="text" id="id_start_date" class="opt-change"/>
                        <span >-</span>
                        <input type="text" id="id_end_date" class="opt-change"/>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">手机</span>
                        <input type="text" id="id_phone" class="opt-change"/>
                    </div>
                </div>
            </div>

        </div>
        <hr />
        <div class="body">
            <table class="common-table">
                <thead>
                    <tr>
                        <td >手机号</td>
                        <td style="display:none;" >归属地</td>
                        <td style="display:none;">预约时间</td>
                        <td style="display:none;">回访时间</td>
                        <td >来源</td>
                        <td >姓名</td>
                        <td class="remove-for-xs">用户备注</td>
                        <td class="remove-for-xs">排课时间</td>
                        <td >试听课时间</td>
                        <td >上课老师</td>
                        <td class="remove-for-xs">课总数目</td>
                        <td class="remove-for-xs">年级</td>
                        <td class="remove-for-xs">科目</td>
                        <td >是否有pad</td>
                        <td style="display:none;" >负责人</td>
                        <td style="display:none;" >负责人接受时间</td>
					    <td >预约类型</td>
                        <td >回访状态</td>
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
                            <td >{{$var["phone_location"]}}</td>
                            <td >{{$var["book_time"]}}</td>
                            <td >{{$var["book_time_next"]}}</td>
                            <td >{{$var["origin"]}}</td>
                            <td >{{$var["nick"]}}</td>
                            <td >{{$var["consult_desc"]}}</td>
                            <td >{{$var["class_time"]}}</td>
                            <td >{{$var["order_time"]}}</td>
                            <td >{{$var["tea_nick"]}}</td>
                            <td >{{$var["lesson_num"]}}</td>
                            <td >{{$var["grade_str"]}}</td>
                            <td >{{$var["subject_str"]}}</td>
                            <td >{{$var["has_pad_str"]}}</td>
                            <td >{{$var["sys_operator"]}}</td>
                            <td >{{$var["sys_opt_time"]}}</td>
                            <td >{{$var["trial_type_str"]}}</td>
                            <td >{{$var["status_str"]}}</td>
                            <td class="remove-for-xs">
                                <div data-status="{{$var["status"]}}"
                                     data-userid="{{$var["userid"]}}"
                                     data-phone="{{$var["phone"]}}"
                                     data-sys_operator="{{$var["sys_operator"]}}"
                                     class="opt-div">
                                    <a title="用户信息" class="  fa-user opt-user"></a>
                                    <a href="javascript:;" title="添加合同" class="btn fa fa-male opt-add-contract"></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>
        <div class="dlg-add-revisit" style="display:none">
            <div class="row">
                <div class="input-group">
                    <span class="input-group-addon">用户手机</span>
                    <label class="show-user-phone form-control"></label>
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
        <!-- 新增合同 -->
        <div style="display:none" id="id_dlg_query_user">
            <div class="row">
                <div class="col-xs-12  col-md-8 ">
                    <div class="input-group ">
                        <span class="input-group-addon">注册账号：</span>
                        <input type="text" class=" form-control "  id="id_query_phone"  />
                        <div class=" input-group-btn ">
                            <button id="id_query_student" type="submit"  class="btn btn-primary  ">
                                <i class="fa fa-search"> </i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <ul id="id_add_contrat_user_info" style="text-align:left;">
                <li>账号：<span id="id_user_acc">    </span></li>
                <li>年级：<span id="id_user_grade">     </span></li>
                <li>地区：<span id="id_user_region">    </span></li>
                <li>教材：<span id="id_user_textbook">  </span></li>
            </ul>
        </div>
        <!-- 加合同 -->
        <div style="display:none;" id="id_dlg_add_contract">
            <div class="row">
                <div class="col-xs-12 col-md-6  ">
                    <div class="input-group ">
                        <span class="input-group-addon">学员姓名：</span>
                        <input type="text" class=" form-control "  id="id_user_nick"  />
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 ">
                    <div class="input-group ">
                        <span class="input-group-addon">家长姓名：</span>
                        <input type="text" class=" form-control "  id="id_parent_nick"  />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="input-group ">
                        <span class="input-group-addon">联系电话：</span>
                        <input type="text" id="id_contact_phone"   class=" form-control "  />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 ">
                    <div class="input-group ">
                        <span class="input-group-addon">家庭住址：</span>
                        <input type="text" id="id_user_addr"  class="form-control" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6 ">
                    <div class="input-group ">
                        <span class="input-group-addon">学生年级：</span>
                        <select id="id_stu_grade" class=" form-control "   >
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
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 ">
                    <div class="input-group ">
                        <span class="input-group-addon">所选科目：</span>
                        <select id="id_stu_subject" class=" form-control "   >
                            <!-- 科目 0未设定 1语文 2数学 3英语 4化学 5物理 6生物 7政治 8历史 9地理 --> 
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
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-6 ">
                    <div class="input-group  ">
                        <span class="input-group-addon">合同类型：</span>
                        <select id="id_con_type" class=" form-control "  >
                            <option value="2">试听</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 ">
                    <div class="input-group opt-con-type-div  count_block " style="display:none;">
                        <span class="input-group-addon">购买课次：</span>
                        <input type="text"  id="id_lesson_count"  class=" form-control " />
                    </div>
                    <div class="input-group opt-con-type-div small-class-div" style="display:none;">
                        <span class="input-group-addon">小班课:</span>
                        <input type="text"  id="id_small_class"  class=" form-control " />
                    </div>

                </div>
                <!--<a href="javascript:;" title="设置老师" class="btn fa fa-male opt-alloc-teacher"></a>-->
                <!--  <div class="col-xs-12 col-md-6 ">
                     <div class="input-group ">
                     <span class="input-group-addon" style="cursor:inherit;" >设置老师：</span>
                     <input type="text"  id="id_teacherid"  class=" form-control " />
                     </div>
                     </div>
                   -->
                <div class="col-xs-12 col-md-6 ">
                    <div class="input-group ">
                        <span class="input-group-addon">开具发票：</span>

                        <select id="id_need_receipt" class="form-control" >
                            <option value="0">否</option>
                            <option value="1">是</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="col-xs-12 ">
                    <div class="input-group ">
                        <span class="input-group-addon">　　抬头：</span>
                        <input type="text" id="id_receipt_title"  class="form-control"  />
                    </div>
                </div>
                <br>
                <div class="col-xs-12 ">
                    <div class="input-group ">
                        <span class="input-group-addon">排课要求：</span>
                        <input type="text" id="id_lesson_requirement"  class="form-control"  />
                    </div>
                </div>
                
            <div class="row">
                <div class="col-xs-12 ">
                    <div class="input-group test-listen  "  style="display:none;">
                        <span class="input-group-addon">是否退款：</span>
                        <select id="id_should_refund" class="form-control" >
                            <option value="0">不退款</option>
                            <option value="1">退款</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 ">
                    <div class="input-group test-listen  " style="display:none;">
                        <span class="input-group-addon">赠送原因：</span>
                        <textarea id="id_presented_reason" class="form-control" >
                        </textarea>
                    </div>
                </div>
            </div>

        </div>

        </div>

    </section>
    <script type="text/javascript" src="/page_js/select_course.js"></script>


@endsection

