@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div  id="id_date_range" >
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td>类型</td>
                    <td>渠道</td>
                    <td>报名数</td>
                    <td>试讲数</td>
                    <td>试讲率</td>
                    <td>通过试讲数</td>
                    <td>通过率</td>
                    <td>参与培训数</td>
                    <td>通过培训数</td>
                    <td>培训通过率</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $key=>$var )
                    <tr class="{{$var["level"]}}">
                        <td data-class_name="{{$var["teacher_ref_type_class"]}}" class="teacher_ref_type" >
                            {{@$var['teacher_ref_type_str']}}
                        </td>
                        <td class="teacher_ref_type {{$var["teacher_ref_type_class"]}}  ">
                            {{@$var['realname']}}
                        </td>
                        <td>{{@$var["app_num"]}} </td>
                        <td>{{@$var["interview_num"]}} </td>
                        <td>{{@$var["interview_per"]}}% </td>
                        <td>{{@$var["interview_pass_num"]}} </td>
                        <td>{{@$var["interview_pass_per"]}}% </td>
                        <td>{{@$var["interview_trial_num"]}} </td>
                        <td>{{@$var["interview_trial_pass_num"]}} </td>
                        <td>{{@$var["interview_trial_pass_per"]}}% </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection

