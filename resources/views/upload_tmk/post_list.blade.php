@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2"  >
                    <button class="btn btn-primary" id="id_add_upload"> 增加批次</button>
                </div>

            </div>

        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>批次id</td>
                    <td>上传者</td>
                    <td>时间 </td>
                    <td>说明</td>
                    <td>例子数</td>
                    <td>有效例子数</td>
                    <td>重复例子数</td>
                    <td>是否已提交</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["postid"]}} </td>
                        <td>{{$var["upload_admin_nick"]}} </td>
                        <td>{{$var["upload_time"]}} </td>
                        <td>{{$var["upload_desc"]}} </td>
                        <td>{{$var["count"]}} </td>
                        <td>{{$var["count"]-$var["old_count"]}} </td>
                        <td>{{$var["old_count"]}} </td>
                        <td>{{$var["post_flag_str"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>
                                <a class="fa fa-list opt-list" title="明细">  </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
