@extends('layouts.app')
@section('content')
 <script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>
    <section class="content">
        <div class="book_filter">

           

            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="input-group ">
                        <select id="id_type" class="opt-change input-group-addon" style="width:90px">
                            <option value="1">预约时间</option>
                            <option value="2">回访时间</option>
                        </select>
                        <span >:</span>

                        <input type="text" id="id_start_date" class="opt-change"/>
                        <span >-</span>
                        <input type="text" id="id_end_date" class="opt-change"/>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">手机</span>
                        <input type="text" id="id_book_user" class="opt-change"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">来源</span>
                        <input type="text" id="id_book_origin" class="opt-change"/>
                    </div>
                </div>

                <div class="col-md-1">
                    <button id="id_add" class="btn btn-primary form-control" style="{!! $display_add_str; !!} ">添加</button>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-10">
                    <div class="input-group">
                        <span class="input-group-addon">分类</span>
                        <select id="id_sys_operator_type" class="opt-change " >
                            <option value='0'>全部</option>
                            <option value='1'>我跟踪的</option>
                            <option value='2'>未跟踪的</option>
                        </select>

                        <span class="input-group-addon">排课状态</span>
                        <select id="id_class_time" class="opt-change " >
                        </select>

                        <span class="input-group-addon">年级</span>
                        <select id="id_user_grade" class="opt-change book_grade_list" >
                        </select>
                        <span>回访</span> 
                        <select id="id_revisit_status" class="opt-change  ">
                        </select>
                        <span>课程</span> 
                        <select id="id_trial_type" class="opt-change">
                        </select>
                    </div>


                </div>
                <div class="col-xs-12 col-md-2">
                    <div class="input-group">

                        <button  class="btn btn-primary " id="id_download">去重复 下载</button>
                    </div>


                </div>

            </div>

        </div>
        <hr />
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr>
                        <td >手机号</td>
                        <td >归属地 </td>
                        <td >时间</td>
                        <td style="display:none;" >预约时间</td>
                        <td style="display:none;" >下次回访时间</td>
                        <td >来源</td>
                        <td >姓名</td>
                        <td >用户备注</td>
                        <td  style="{!! $display_td_lesson_time_str !!} "  >上课时间</td>
                        <td >年级</td>
                        <td >科目</td>
                        <td >是否有pad</td>
                        <td  style="{!! $display_sys_operator_str !!} "   >负责人</td>
                        <td style="display:none;" >负责人接受时间</td>
					    <td >预约类型</td>
                        <td   >回访状态</td>
                        <td   >QQ号</td>
                        <td >操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td >
                                {{$var["phone"]}}
                            </td>
                            <td>
                                {{$var["phone_location"]}}
                            </td>
                            <td >{{$var["opt_time"]}}</td>
                            <td >{{$var["book_time"]}}</td>
                            <td >{{$var["book_time_next"]}}</td>
                            <td class="">{{$var["origin"]}}</td>
                            <td class="">{{$var["nick"]}}</td>
                            <td class="user-note">{{$var["consult_desc"]}}</td>
                            <td  >{{$var["class_time"]}}</td>
                            <td class="">{{$var["grade_str"]}}</td>
                            <td class="">{{$var["subject_str"]}}</td>
                            <td class="">{{$var["has_pad_str"]}}</td>
                            <td  class="td-sys_operator"  > {{$var["sys_operator"]}}</td>
                            <td  >{{$var["sys_opt_time"]}}</td>
                            <td class="">{{$var["trial_type_str"]}}</td>
                            <td  >{{$var["status_str"]}}</td>
                            <td  >{{$var["qq"]}}</td>
                            <td  class="td-opt" >
                                <div data-status="{{$var["status"]}}"
                                     data-userid="{{$var["userid"]}}"
                                     data-id="{{$var["id"]}}"
                                     data-phone="{{$var["phone"]}}"
                                     data-sys_operator="{{$var["sys_operator"]}}"
                                     class="opt-div"   >

                                    <a class="show-in-no-select btn  fa fa-legal
                                                                  opt-set_sys_operator
                                                                  " title ="我来处理"></a>
                                    <a title="用户信息" class=" show-in-select fa-user opt-user"></a>
                                    <!--<a href="javascript:;" title="添加回访记录" class=" show-in-select  fa-comment  opt-add-revisit-record"></a> -->
                                    <a title="查看回访" class=" show-in-select  fa-comments  opt-show-revisit-record"></a> 

                                    <a title="录入回访信息" class=" show-in-select fa-book opt-update_user_info"></a> 
                                    <a title="添加下次回访时间" class="show-in-select fa-calendar opt-add_book_time_next"></a>
                                    <a title="设置试听时间" class=" show-in-select btn  fa fa-clock-o opt-lesson-open"></a>
                                    <div   data-<?php echo $var["sys_operator"] == $_account ? "aling" : "";   ?>="kehuguanhai" data-telnumber="{{$var["phone"]}}" ></div>
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
    </section>

@endsection

