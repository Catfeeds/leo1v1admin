@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/lib/flow.js"></script>
    <section class="content ">

        <div>
            <div class="row  " >
               
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>
                        学生
                    </td>
                  
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["nick"]}} </td>
                       
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-list-alt opt-hand-over btn" title="新建交接单"></a>
                                <a class="btn fa fa-th-list opt-hand-over_info" title="交接单详情"></a>
                                

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
