@extends('layouts.app')
@section('content')
   

    <section class="content ">
        <div class="row">
            <div class="col-xs-12 col-md-2">
                <div class="panel panel-warning"  >
                    <div class="panel-heading center-title ">
                        语文
                    </div>
                    <div class="panel-body">

                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>分数</td>
                                    <td>个数</td>
                                   
                                </tr>
                            </thead>
                            <tbody id="id_ass_group">
                                @foreach ( $list as $key=> $var )
                                    <tr>
                                        <td>{{@$var["record_score"]}} </td>
                                        <td>{{@$var["num"]}} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="panel panel-warning"  >
                    <div class="panel-heading center-title ">
                        数学
                    </div>
                    <div class="panel-body">

                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>分数</td>
                                    <td>个数</td>
                                    
                                </tr>
                            </thead>
                            <tbody id="id_ass_group">
                                @foreach ( $data as $key=> $var )
                                    <tr>
                                        <td>{{@$var["record_score"]}} </td>
                                        <td>{{@$var["num"]}} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="panel panel-warning"  >
                    <div class="panel-heading center-title ">
                        英语
                    </div>
                    <div class="panel-body">

                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>分数</td>
                                    <td>个数</td>
                                    
                                </tr>
                            </thead>
                            <tbody id="id_ass_group">
                                @foreach ( $arr as $key=> $var )
                                    <tr>
                                        <td>{{@$var["record_score"]}} </td>
                                        <td>{{@$var["num"]}} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="panel panel-warning"  >
                    <div class="panel-heading center-title ">
                        物理
                    </div>
                    <div class="panel-body">

                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>分数</td>
                                    <td>个数</td>
                                    
                                </tr>
                            </thead>
                            <tbody id="id_ass_group">
                                @foreach ( $wuli as $key=> $var )
                                    <tr>
                                        <td>{{@$var["record_score"]}} </td>
                                        <td>{{@$var["num"]}} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="panel panel-warning"  >
                    <div class="panel-heading center-title ">
                        化学
                    </div>
                    <div class="panel-body">

                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>分数</td>
                                    <td>个数</td>
                                    
                                </tr>
                            </thead>
                            <tbody id="id_ass_group">
                                @foreach ( $huaxue as $key=> $var )
                                    <tr>
                                        <td>{{@$var["record_score"]}} </td>
                                        <td>{{@$var["num"]}} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>


        </div>

      

        
    </section>
    
@endsection


