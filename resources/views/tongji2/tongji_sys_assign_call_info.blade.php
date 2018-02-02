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
                        <span >拨打者</span>
                        <input type="text" value=""  class="opt-change"  id="id_adminid"  placeholder=""  />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >学生</span>
                        <input type="text" value=""  class="opt-change"  id="id_userid"  placeholder=""  />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>分配时间</td>
                    <td>例子来源</td>
                    <td>学生</td>
                    <td>电话</td>
                    <td>userid</td>
                    <td>cc</td>
                    <td>拨打次数</td>
                    <td>拨通</td>
                    <td>拨打总时长</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["logtime"]}} </td>
                        <td>{{$var["seller_student_assign_from_type_str"]}} </td>
                        <td>{{$var["student_nick"]}}</td>
                        <td>{{$var["student_nick"]}}</td>
                        <td>{{$var["userid"]}} </td>
                        <td>{{$var["admin_nick"]}} </td>
                        <td>{{$var["call_count"]}} </td>
                        <td>{!!  $var["called_flag_str"]!!} </td>
                        <td>{{$var["call_time"]}} </td>
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
