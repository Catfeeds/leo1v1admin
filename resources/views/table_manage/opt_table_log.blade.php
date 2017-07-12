@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="input-group ">
                    <span class="input-group-addon">时间:</span>
                    <input type="text" class=" form-control " id="id_start_time" />
                    <span class="input-group-addon">-</span>
                    <input type="text" class=" form-control "  id="id_end_time" />
                </div>
            </div>

            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">操作者</span>
                    <input type="text" class=" form-control " id="id_adminid" />
                </div>
            </div>
            <div class="col-xs-12 col-md-3">
                <div class="input-group ">
                    <span class="input-group-addon">sql</span>
                    <input type="text" class=" form-control " id="id_sql_str" />
                </div>
            </div>



        </div>

        <hr/>


        <table   class="common-table"   >
            <thead>
                <tr>
                    <td  >id</td>
                    <td  >时间</td>
                    <td >操作者</td>
                    <td >sql</td>
                    <td >影响行数</td>
                    <td  >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
				    <tr>
                        <td >{{$var["id"]}}</td>
                        <td >{{$var["opt_time"]}}</td>
                        <td >{{$var["admin_nick"]}}</td>
                        <td >{{$var["sql_str"]}}</td>
                        <td >{{$var["change_count"]}}</td>
                        <td >
                            <div class="btn-group"
                            >
                            </div>
                        </td>
				    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
        

@endsection

