@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-12 col-md-4" data-title="时间段">
                    <div id="id_date_range"> </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacherid"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >工资分类</span>
                        <select id="id_teacher_money_type" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >等级分类</span>
                        <select id="id_level" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >测试用户</span>
                        <select id="id_is_test_user" class ="opt-change" ></select>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>姓名</td>
                    <td>工资类型</td>
                    <td>等级</td>
                    <td>转化率</td>
                    <td>总课时</td>
                    <td>试听课时</td>
                    <td>常规课时</td>
                    <td>所带学生数</td>
                    <td>所带年级</td>
                    <td>所带科目</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var['realname']}}</td>
                        <td>{{$var['teacher_money_type_str']}}</td>
                        <td>{{$var['level_str']}}</td>
                        <td>{{$var['test_transfor_per']}}</td>
                        <td>{{$var['all_lesson_count']}}</td>
                        <td>{{$var['trial_lesson_count']}}</td>
                        <td>{{$var['normal_lesson_count']}}</td>
                        <td>{{$var['stu_num']}}</td>
                        <td>{{$var['grade_str']}}</td>
                        <td>{{$var['subject_str']}}</td>
                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var)  !!}  >
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection

