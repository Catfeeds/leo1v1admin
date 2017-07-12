@extends('layouts.app')

@section('content')
<section class="content">
    
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <div class="input-group ">
                <span >日期</span>
                <input type="text" id="id_start_date" class="opt-change"/>
                <span >-</span>
                <input type="text" id="id_end_date" class="opt-change"/>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span>登录状态</span>
                <select id="id_authority_flag" class="opt-change" >
                    <option value="-1" >全部</option>
                    <option value="1" >登陆成功</option>
                    <option value="0" >登陆失败</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="input-group col-sm-12">
                <input type="text" value="" class="form-control put_name for_input"  id="id_login_info" placeholder="id，账户名称，登陆状态" />
                <div class=" input-group-btn ">
                    <button id="id_search_login" type="submit"  class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div> 
            </div>
        </div>
    </div>
    
        <hr/>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <td >账户名称</td>
                <td >登陆次数</td>
                <td >登陆成功次数</td>
                <td >登陆失败次数</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)

                <tr>
                    <td >{{$var["account"]}}</td>
                    <td >{{$var["all_count"]}}</td>
                    <td >{{$var["succ"]}}</td>
                    <td >{{$var["fail"]}}</td>
                </tr>
			@endforeach
        </tbody>
    </table>
	@include("layouts.page")

</section>
@endsection
