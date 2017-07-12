@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_record.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript" src="/page_js/teacher_freeze_limit_record.js"></script>
    <script type="text/javascript" >
     var tea_right= "{{@$tea_right}}";
    </script>


    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-6 col-md-2" style="display:none">
                    <div class="input-group ">
                        <span class="input-group-addon">学科</span>
                        <select class="opt-change form-control" id="id_subject" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >排课限制</span>
                        <select id="id_limit_plan_lesson_type" class ="opt-change" >
                            <option value="-1"> 全部</option>
                            <option value="0"> 未限制</option>
                            <option value="1"> 一周限排1节</option>
                            <option value="3"> 一周限排3节</option>
                            <option value="5"> 一周限排5节</option>
                            <option value="-2"> 已限制</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >是否反馈</span>
                        <select id="id_is_record_flag" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >是否确认</span>
                        <select id="id_is_do_sth" class ="opt-change" ></select>
                    </div>
                </div>



            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>老师姓名 </td>
                    <td width="100px">分类</td>
                    <td>等级</td>
                    <td width="120px">学校</td>
                    <td>学科</td>
                    <td>入职时长</td>
                    <td width="220px">面试评价</td>
                    <td>试听课时段</td>
                    <td>试听课数</td>
                    <td>已签合同数</td>
                    <td  width="220px" style="display:none">冻结排课</td>
                    <td  width="220px">排课限制</td>
                    <td  width="220px">最新反馈</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["identity_str"]}} </td>
                        <td>{{@$var["level_str"]}} </td>
                        <td>{{@$var["school"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["day"]}} </td>
                        <td>{{@$var["interview_access"]}} </td>
                        <td class="ten_lesson" >{{@$var["time"]}}</td>
                        <td class="ten_lesson" data-teacherid={{$var["teacherid"]}} ><a href="javascript:;" >{{$var["test_lesson_num"]}}</a></td>
                        <td>{{@$var["order_num"]}}</td>
                        <td>
                            @if($var["not_grade_str"])
                                冻结年级:{{$var["not_grade_str"]}}<br>
                                操作人:{{@$var["freeze_adminid_str"]}}
                            @endif
                        </td>
                        <td>
                            @if($var["limit_plan_lesson_type"]>0)
                                限课详情:{{$var["limit_plan_lesson_type_str"]}}<br>
                                操作人:{{$var["limit_plan_lesson_account"]}}<br>
                                操作时间:{{$var["limit_plan_lesson_time_str"]}}<br>
                            @endif
                        </td>                      

                        <td>
                            @if($var["add_time"] > 0)
                                反馈时间:{{$var["add_time_str"]}}<br>
                                反馈内容:{{$var["record_info"]}}<br>
                                操作人:{{$var["acc"]}}
                            @endif
                        </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="opt-teacher-freeze">冻结排课</a>
                                <a class="opt-freeze-list">冻结排课记录</a>
                                <a class="opt-limit-plan-lesson" >限制排课</a>
                                <a class="opt-limit-plan-lesson-list" >限制排课记录</a>                            
                               <a class="opt-set-teacher-record-new">反馈</a>
                               <a class="opt-get-teacher-record">反馈记录</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

