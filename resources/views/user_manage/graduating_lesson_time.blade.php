@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >xx</span>
                        <input type="text" value=""  class="opt-change"  id="id_"  placeholder=""  />
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">剩余值</span>
                        <select class="opt-change form-control" id="id_residual_flag" >
                        </select>
                    </div>
                </div>



            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td rowspan="3" style="vertical-align: middle;">助教 </td>
                    <td rowspan="3" style="vertical-align: middle;">学员 </td>
                    <td rowspan="3" style="vertical-align: middle;">剩余课时 </td>
                    <td colspan="28" style="vertical-align: middle;" align="center">每周课时量</td>
                    <td rowspan="3"  style="vertical-align: middle;">剩余值 </td>
                    <td rowspan="3"  style="vertical-align: middle;">操作  </td>
                </tr>
                <tr>
                    @foreach ( $weeks as $var )
                        <td colspan="2" style="font-size: 10px;">{{$var}}</td>
                    @endforeach
                </tr>
                <tr>
                    @foreach ( $weeks as $var )
                        <td style="font-size: 10px;" >计划</td>
                        <td style="font-size: 10px;" >实际</td>
                    @endforeach
                </tr>

            </thead>
            <tbody>
                @foreach ( $graduating_list['list'] as $index=>$var )
                    <tr>
                        <td>{{@$var["assistant_nick"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var['lesson_count_left']}}</td>
                        @foreach ($total_plan_actual_time[$index] as $item_lesson_time )
                            <td>{{@$item_lesson_time['plan_lesson_time']}}</td>
                            <td>{{@$item_lesson_time['actual_lesson_time']}}</td>
                        @endforeach

                        <td>{{@$var['residual_value']}}</td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>
                                <a class="fa fa-flag opt-plan_lesson_time  btn" title="设置同学的计划课时"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
