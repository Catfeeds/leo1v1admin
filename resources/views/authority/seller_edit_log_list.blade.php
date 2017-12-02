@extends('layouts.app')
@section('content')
    <section class="content ">

        <div>
            <!-- <div class="row  row-query-list" >
                 <div class="col-xs-6 col-md-2">
                 <button id="id_add"> 增加</button>
                 </div>
                 </div> -->
        </div>
        <hr/>

        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>id </td>
                    <td>修改人</td>
                    <td>被修改人</td>
                    <td>修改类型</td>
                    <td>修改前数据</td>
                    <td>修改后数据</td>
                    <td>创建时间</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["id"]}} </td>
                        <td>{{@$var["adminid_nick"]}} </td>
                        <td>{{@$var["uid_nick"]}} </td>
                        <td>{{@$var["type"]}} </td>
                        <td>{{@$var["old"]}} </td>
                        <td>{{@$var["new"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <!-- <a class="fa fa-edit opt-edit aaa"  title="编辑"> </a> -->
                                <a class="fa fa-times opt-del" style="display:none;" title="删除"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
