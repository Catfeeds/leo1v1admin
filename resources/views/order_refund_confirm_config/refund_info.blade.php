@extends('layouts.app')
@section('content')

    <section class="content ">

            <div class="row  row-query-list" >

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >部门</span>
                        <select class="opt-change"  id="id_key1"  >
                            <option value="-1">全部</option>
                            @foreach ($key1_list as $item)
                                <option value="{{$item["key1"]}}">
                                    {{$item["value"]}}
                                </option>

                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >一级原因</span>
                        <select class="opt-change"  id="id_key2"  >
                            <option value="-1">全部</option>
                            @foreach ($key2_list as $item)
                                <option value="{{$item["key2"]}}">
                                    {{$item["value"]}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>



                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >二级原因</span>
                        <select class="opt-change"  id="id_key3"  >
                            <option value="-1">全部</option>
                            @foreach ($key3_list as $item)
                                <option value="{{$item["key3"]}}">
                                    {{$item["value"]}}
                                </option>

                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <button id="submit-refund" type="button" class="btn btn-info">添加退款信息</button>
                </div>
            </div>
        <hr/>
        <table   class=" common-table "    >
            <thead>
                <tr>
                    <td class="remove-for-xs"  >id</td>
                    <td >部门</td>
                    <td  >一级原因</td>
                    <td  >二级原因</td>
                    <td  >三级原因</td>
                    <td  >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td  >{{$var["id"]}} </td>
                        <td  >{{@$var["key1_str"]}} </td>
                        <td  >{{@$var["key2_str"]}} </td>
                        <td  >{{@$var["key3_str"]}} </td>
                        <td  >{{@$var["key4_str"]}} </td>
                        <td  >
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class=" opt-del " title="删除" id-item="{{$var["id"]}}">删除</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>



        @include("layouts.page")
    </section>

@endsection
