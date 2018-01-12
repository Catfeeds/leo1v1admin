@extends('layouts.app')
@section('content')
<section class="content">

    <div class="row">
        <div class="col-xs-6 col-md-4">
            <div class="input-group ">
                <span class="input-group-addon"> {{$db_name}}.{{$table_name}}</span>
            </div>
        </div>

        <div class="col-xs-6 col-md-3">
            <div class="input-group ">
                <span class="input-group-addon"> {{$id1_name}}</span>
                <input id="id_id1"/>
            </div>
        </div>
        <div class="col-xs-6 col-md-3" style="{{ $id2_name?"":"display:none;" }}">
            <div class="input-group ">
                <span class="input-group-addon"> {{$id2_name}}</span>
                <input id="id_id2"/>
            </div>
        </div>
        <div class="col-xs-6 col-md-3" ">
            <div class="input-group ">
                <a class ="btn btn-primary" id="id_del"> 删除该数据</a>
            </div>
        </div>
    </div>
    <hr/>
    <table class="common-table">
        <thead>
            <tr>
                <td >Comment</td>
                <td style="width:100px;" >字段</td>
                <td >值</td>
                <td style="min-width:100px">操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach($table_data_list as $var)
                <tr>
                    <td> {{$var["comment"]}} </td>
                    <td> {{$var["k"]}} </td>
                    <td> {{$var["v"]}} </td>
                    <td>
                        <div
                            data-field="{{$var["k"]}}"
                            data-value="{{$var["v"]}}"
                        >
                            <a class="fa-edit opt-field-value " title="修改"></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

