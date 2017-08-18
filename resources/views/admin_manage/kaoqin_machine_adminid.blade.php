@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">考勤机</span>
                        <select class="opt-change form-control" id="id_machine_id" >
                            <option value="-1"> 全部 </option>
                            @foreach ( $machine_list as $item  )
                                <option value="{{ $item["machine_id"] }}">
                                {{ $item["title"] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">账号</span>
                        <input class="opt-change form-control" id="id_adminid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">管理员</span>
                        <select class="opt-change form-control" id="id_auth_flag" >
                        </select>
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <button  class="btn  btn-primary fa fa-plus " id="id_add"> 账号 </button>
                        <button  class="btn  btn-warning   " id="id_sync"> 同步 </button>
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td> 考勤机 </td>
                    <td> 用户 </td>
                    <td> 管理员 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["title"]}} </td>
                        <td>{{$var["admin_nick"]}} </td>
                        <td>{{$var["auth_flag_str"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
