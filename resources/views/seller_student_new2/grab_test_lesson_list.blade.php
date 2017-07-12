@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
            <div class="row" >
                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <input class="opt-change form-control" id="id_subject" />
                    </div>
                </div>
                <div class="col-xs-3 col-md-2">
                    <div class="input-group">
                        <button class="btn btn-danger" id="id_opt_grab_trial_user_info">生成抢单链接</button>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <div class="body">
            <table class="common-table">
                <thead>
                    <tr>
                        <td style="width:10px">
                            <a href="javascript:;" id="id_select_all" title="全选">全</a>
                            <a href="javascript:;" id="id_select_other" title="反选">反</a>
                        </td>
                        <td >id</td>
                        <td >抢单信息 </td>
                        <td style="width:130px" >操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td>
                                <input type="checkbox" class="opt-select-item "
                                       data-requireid="{{$var["require_id"]}}"
                                       data-lesson_time="{{$var["stu_request_test_lesson_time"]}}"
                                       data-grade="{{$var["grade_str"]}}"
                                       data-subject="{{$var["subject_str"]}}"
                                       data-textbook="{{$var["editionid_str"]}}"
                                />
                            </td>
                            <td>{{@$var['num']}}</td>
                            <td>
                                年级: {{@$var['grade_str']}}
                                科目: {{@$var['subject_str']}}
                                期待时间: {{@$var['stu_request_test_lesson_time_str']}}
                                教材版本: {{@$var['editionid_str']}}
                            </td>

                            <td>
                                <div class="opt-div" {!!  \App\Helper\Utils::gen_jquery_data($var )  !!} >
                                    <a title="用户信息" class="fa-user opt-user"></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
