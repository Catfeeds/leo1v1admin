@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">兼职</span>
                <select class="will_change" id="id_is_part_time">
                    <option value="-1">不限</option>
                    <option value="0">仅全职</option>
                    <option value="1">仅兼职</option>
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2" >
            <div class="input-group ">
                <span >老师</span>
                <input type="text" value="" class="click_on put_name for_input" style="display:none;" data-field="tea_name" />
                <select class="will_change" id="id_teacher_list">
          <option value="">全部</option>
                    @foreach ($table_data_list as $var)
            <option value="{{$var["nick"]}}">{{$var["nick"]}}</option>
                    @endforeach
        </select>
            </div>
        </div>

    </div>

    <hr/>

    <table class="table table-bordered table-striped"   >
        <thead>
            <tr>
                <td class="remove-for-xs" width="10%">ID</td>
                <td class="remove-for-xs" width="10%">老师</td>
                <td class="remove-for-xs" width="15%">待批改测评</td>
                <td class="remove-for-xs" width="10%">操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                   <td class="remove-for-xs" >{{$var["teacherid"]}}</td>
                   <td class="remove-for-xs tea_nick" >{{$var["tea_nick"]}}</td>
                   <td class="remove-for-xs quiz_num" >{{$var["quiz_num"]}}</td>
                   <td class="remove-for-xs">
                       <span data-teacherid="{{$var["teacherid"]}}" >
                           <a href="javascript:;" title="查看详情" class="btn done_r fa fa-dedent"></a>
                       </span>
                   </td>
               </tr>
            @endforeach
        </tbody>
    </table>
    @include("layouts.page")
@endsection
