@extends('layouts.app')
@section('content')

    <section class="content ">
        <div class="row">
            <div class="col-xs-12 col-md-5"  data-title="时间段">
                <div  id="id_date_range" >
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <!-- <span class="input-group-addon">电话</span>
                         <input class="opt-change form-control" id="id_phone" /> -->
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
                <button class="btn" id="id_opt_count" data-value="{{$opt_count}}" >{{$opt_count}}</button>
                <button class="btn" id="id_last_count" data-value="{{$last_count}}" >{{$last_count}}</button>
            </div>

        </div>

        <hr/>
        <table   class="table table-bordered table-striped"   >
            <thead>
                <tr>
                    <td>试听时间 </td>
                    <td>昵称 </td>
                    <td>年级</td>
                    <td>科目 </td>
                    <td>性别</td>
                    <td>电话</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["lesson_start"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["gender_str"]}} </td>
                        <td>{{@$var["phone_hide"]}} </td>

                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a   class=" btn fa  opt-set-self" title="">抢学生 </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

@endsection
