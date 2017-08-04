@extends('layouts.app')
@section('content')
    <section class="content">

        <div class="row">
            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span class="input-group-addon">数据库</span>
                    <select class="opt-change form-control" id="id_db_name" >
                        <option value="db_weiyi">db_weiyi</option>
                        <option value="db_weiyi_admin">db_weiyi_admin</option>
                        <option value="db_tool">db_tool</option>
                        <option value="db_account">db_account</option>

                    </select>

                </div>
            </div>
            <div class="col-xs-6 col-md-1">
                <div class="input-group ">
                    <button id="id_query" class="btn btn-primary"  > 查询 </button>
                </div>
            </div>


        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12">
                <textarea id="id_sql" style="width:100%; height:100px;" > </textarea>
            </div>
        </div>



        <hr/>

        <div  style="overflow: auto;"  >
        <table     class="common-table"  >
            <thead>
                <tr>
                    @foreach( $col_name_list as $col_name  )
                    <td> {{$col_name}}  </td>
                    @endforeach
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody >
                @foreach ( $table_data_list as $var )
                    <tr>
                        @foreach( $col_name_list as $col_name  )
                            <td> {{$var["$col_name"]}}  </td>
                        @endforeach
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
        </div>
        @include("layouts.page")

@endsection
