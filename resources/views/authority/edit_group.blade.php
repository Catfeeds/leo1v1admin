@extends('layouts.app')
@section('content')

<section class="content">
    <div class="row">
        <div class="col-xs-3 col-md-3">
            <table class="table table-bordered">
                <tr><th>用户组  </th></tr>
			    @foreach ($grp_list as $var )
				    <tr class="opt-select-group" data-groupid="{{$var["groupid"]}}"><td> {{$var["group_name"]}}</td></tr>
                @endforeach

            </table>
            <button type="button" class="opt-commit btn btn-primary" >提交</button>
        </div>
        <div class="col-xs-7 col-md-7">
            <table class="table table-bordered " >
			    @foreach ($power_define_list as $key  => $var )
				    <tr data-powerid="{{$key}}" class="opt-checkbox-power"  >  <td >{{$key}}-> {{  $var }} </td></tr>
                @endforeach
            </table>

            <button type="button" class="opt-commit btn btn-primary" >提交</button>
        </div>

    </div>

</section>
@endsection
