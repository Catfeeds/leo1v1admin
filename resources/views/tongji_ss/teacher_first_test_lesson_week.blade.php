@extends('layouts.app')
@section('content')
    
    <script type="text/javascript" src="/page_js/lib/select_dlg_record.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript" src="/page_js/teacher_freeze_limit_record.js"></script>
    <section class="content ">
        
        <div>
            <div class="row " >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span class="input-group-addon">学科</span>
                        <select class="opt-change form-control" id="id_subject" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">反馈老师</span>
                        <input class="opt-change form-control" id="id_record_adminid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span class="input-group-addon">是否反馈</span>
                        <select class="opt-change form-control" id="id_record_flag" >
                        </select>
                    </div>
                </div>

   
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>teacherid </td>
                    <td>老师姓名 </td>
                    <td>第一次试听课时间 </td>
                    <td width="100px">分类</td>
                    <td>等级</td>
                    <td width="120px">学校</td>
                    <td>学科</td>
                    <td>年级段</td>
                    <td>入职时长</td>
                    <td>试听课数</td>
                    <td>第一次反馈时间</td>
                    <td>反馈人</td>
                    <td>反馈时长</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["teacherid"]}}</td>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["first_lesson_time_str"]}} </td>
                        <td>{{@$var["identity_str"]}} </td>
                        <td>{{@$var["level_str"]}} </td>
                        <td>{{@$var["school"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["grade_part_ex_str"]}} </td>
                        <td>{{@$var["day"]}} </td>
                        <td class="test_lesson" data-teacherid={{$var["teacherid"]}} ><a href="javascript:;" >{{@$var["all_lesson"]}}</a></td>
                        @if(@$var["add_time"]>0)
                            <td>{{@$var["add_time_str"]}} </td>
                            <td>
                                {{@$var["acc"]}}
                            </td>
                            <td>{{@$var["record_time"]}}天 </td>
                        @else
                            <td></td>
                            <td></td>
                            <td></td>
                        @endif
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >                           
                               <a class="opt-set-teacher-record-new">反馈</a>
                               <a class="opt-get-teacher-record">反馈记录</a>
                               <a class="opt-get-teacher-class-abnormal" >课程异常反馈</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

