@extends('layouts.base')
@section('content')


    <section class="content "  style="max-width:1000px" >
        <h1 style="text-align:center" > 协议平台 </h1>
        @if( count( $table_data_list )  >0 )
            <table     class="table table-bordered table-striped "   style="max-width:1000px " >
                <thead>
                    <tr>
                        <td width="50px">命令号</td>
                        <td width="200px">名称</td>
                        <td >说明</td>
                        <td >标签</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $table_data_list as $var )
                        <tr>
                            <td>{{@$var["cmdid"]}} </td>
                            <td  > <a
                                       id="{{@$var["name"]}}_item"
                                       href="#{{@$var["name"]}}_desc"
                                   > {{@$var["name"]}} </td>
                            <td>{{@$var["desc"]}} </td>
                            <td>{{@$var["tags"]}} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif


        @foreach (  $cmd_desc_list as $cmd_item )

            <h4 style="margin-top:30px;margin-bottom:30px;">

                <a
                                       id="{{@$cmd_item["name"]}}_desc"
                                       href="#{{@$cmd_item["name"]}}_item" style="font-size:15px;color:green;padding:30px 0px 30px 50px;" >
                    {{$cmd_item["cmdid"]}} | {{$cmd_item["name"]}}  | {{$cmd_item["desc"]}} </a> </h4>
            <table      class="table table-bordered table-striped " style="margin-left:50px;max-width: 800px; "  >
                <thead>
                    <tr>
                        <td width="40%"> {{$cmd_item["name"]}} <font color="blue">请求包 </font>  字段名</td>
                        <td width="10%">类型</td>
                        <td width="40%">说明</td>
                        <td width="10%">字段id</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $cmd_item["in"] as $var )
                        <tr>
                            <td>{{@$var[2]}} </td>
                            <td>
                                {{@$var["1"]}}
                                {!!  $var[0]=="repeated"?"<font color=red>数组 </font>":"" !!}
                            </td>
                            <td>{{@$var["4"]}} </td>
                            <td>{{@$var["3"]}} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table        class="table table-bordered table-striped " style="margin-left:50px ;max-width: 800px; "  >
                <thead>
                    <tr>
                        <td width="40%"> {{$cmd_item["name"]}} 返回包 字段名</td>
                        <td width="10%">类型</td>
                        <td width="40%">说明</td>
                        <td width="10%">字段id</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $cmd_item["out"] as $var )
                        <tr>
                            <td>{{@$var[2]}} </td>
                            <td>
                                {{@$var["1"]}}
                                {!!  $var[0]=="repeated"?"<font color=red>数组 </font>":"" !!}
                            </td>
                            <td>{{@$var["4"]}} </td>
                            <td>{{@$var["3"]}} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table        class="table table-bordered table-striped " style="margin-left:50px ;max-width: 800px; "  >
                <thead>
                    <tr>
                        <td width="10%"> 错误码值 </td>
                        <td width="10%"> 错误码 </td>
                        <td width="80%">说明</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $cmd_item["cmd_error_list"] as $var )
                        <tr>
                            <td>{{@$var["value"]}} </td>
                            <td>{{@$var["name"]}} </td>
                            <td>{{@$var["desc"]}} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach



        @if (count($error_list) >0 )
            <table
                class="table table-bordered table-striped  " style="max-width:1000px "

            >

                <thead>
                    <tr>
                        <td width="10%">  错误码值 </td>
                        <td width="10%"> 错误码 </td>
                        <td width="80%">说明
                            <font color= "red">全体错误码列表 </font>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $error_list as $var )
                        <tr>
                            <td>{{@$var["value"]}} </td>
                            <td>{{@$var["name"]}} </td>
                            <td>{{@$var["desc"]}} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif


    </section>


@endsection
