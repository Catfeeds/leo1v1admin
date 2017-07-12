@extends('layouts.app')
@section('content')
    <section class="content">
        <table class="common-table"   >
            <thead>
                <tr>
                    <td >助教id</td>
                    <td >助教</td>
                    <td >应回访总数(已/应)</td> 
                    <td >首度回访(已/应)</td> 
                    <td >学情回访(已/应)</td> 
                    <td >月度回访(已/应)</td> 
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
				    <tr>
                        <td >{{$var["assistantid"]}}</td>
                        <td >{{$var["assistant_nick"]}}</td>
                        <td >{{$var["yi_total_revisit"]}}/{{$var["total_revisit"]}}</td>
                        <td >{{$var["yi_first_revisit"]}}/{{$var["first_revisit"]}}</td>
                        <td >{{$var["xq_revisit"]}}/{{$var["yxq_revisit"]}}</td>
                        <td >{{$var["yd_revisit"]}}/{{$var["yyd_revisit"]}}</td>
                        <td >
                            <div class="btn-group"
                                 data-assistantid="{{$var["assistantid"]}}" ;
                                 >
                            </div>
                        </td>
				    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
            
    </section>

    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>


@endsection

