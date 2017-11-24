@extends('layouts.app')
@section('content')
    <section class="content ">

        <div class="row">

            <div class="col-xs-12 col-md-5"  data-title="时间段">
                <div  id="id_date_range" >
                </div>
            </div>

            <div class="col-xs-6 col-md-3" data-always_show="1"   >
                <div class="input-group ">
                    <input class="opt-change form-control" style="display:block;" id="id_phone_name" placeholder="学生电话,回车搜索"/>
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
                    <span class="input-group-addon">Pad</span>
                    <select class="opt-change form-control" id="id_has_pad" >
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
                    <span class="input-group-addon">是否试听</span>
                    <select class="opt-change form-control" id="id_test_lesson_count_flag" >
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-4" data-always_show="1"   >
                <div class="input-group ">
                    <span class="input-group-addon">分类</span>
                    <select class="opt-change form-control" id="id_test_lesson_order_fail_flag" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-3" data-always_show="1"   >
                <div class="input-group ">
                    <input class="opt-change form-control" style="display:block;" id="id_phone_location" placeholder="手机号码归属地,回车搜索"/>
                </div>
            </div>
            <button class="btn btn-primary" id="id_left_count"  title="可领个数" > {{$left_count}}
        </div>

        <hr/>
        <table   class="table table-bordered table-striped"   >
            <thead>
                <tr>
                    <td>学生 </td>
                    <td>地区</td>
                    <td>年级</td>
                    <td>学科</td>
                    <td>上课设备</td>
                    <td>上次回访时间</td>
                    <td>试听成功时间</td>
                    <td>回流公海时间/回流人</td>
                    <td>未签单原因</td>
                    <td>最后一次备注</td>
                    <td>例子进入时间 </td>
                    <td>电话</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["phone_location"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["has_pad_str"]}} </td>
                        <td>{{@$var["last_revisit_time"]}} </td>
                        <td>{{@$var["lesson_start"]}} </td>
                        <td>{{@$var["free_time"]}}/{{@$var["free_nick"]}} </td>
                        <td>{{@$var["test_lesson_order_fail_flag_str"]}}</td>
                        <td>{{@$var["user_desc"]}} </td>
                        <td>{{@$var["add_time"]}} </td>
                        <td>{{@$var["phone_hide"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a title="手机拨打" style="display:none;" class=" btn fa fa-phone  opt-telphone   "></a>
                                <a   class=" btn fa  opt-set-self" title="">抢学生 </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
