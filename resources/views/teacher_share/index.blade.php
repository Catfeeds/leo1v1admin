@extends('layouts.app')

@section('content')

    <section class="content ">

        <div>

            <div class="row  " >

                <div class="col-xs-12 col-md-6"  >
                    <div > {{$cur_dir}}   </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>名称</td>
                    <td>大小 </td>
                    <td>创建时间 </td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td class="file_name" >{{@$var["file_name"]}} </td>
                        <td>{{@$var["file_size"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-download opt-download"  title="下载"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
