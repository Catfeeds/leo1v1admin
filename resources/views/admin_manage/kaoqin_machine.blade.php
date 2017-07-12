@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >xx</span>
                        <input type="text" value=""  class="opt-change"  id="id_"  placeholder=""  />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td> 机器id </td>
                    <td> 序列号 </td>
                    <td> 操作门禁 </td>
                    <td> 最后上报时间  </td>
                    <td>  名字 </td>
                    <td>  说明 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["machine_id"]}} </td>
                        <td>{{$var["sn"]}} </td>
                        <td>{{$var["open_door_flag_str"]}} </td>
                        <td>{{$var["last_post_time"]}} </td>
                        <td>{{$var["title"]}} </td>
                        <td>{{$var["desc"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-unlock opt-unlock"  title="开锁"> </a>
                                <a class="fa fa-power-off opt-reboot"  title="重启"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
