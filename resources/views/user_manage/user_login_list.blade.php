@extends('layouts.app')
@section('content')
    <section class="content">
    <div class="row">

        <div class="col-xs-12 col-md-4" data-title="时间段">
            <div id="id_date_range"> </div>
        </div>

        <div class="col-md-3 col-xs-0" >

            <div class="input-group col-sm-12"  >
                <input  id="id_account" type="text" value="" class="form-control opt-change"  placeholder="输入用户名，回车查找" />
            </div>
        </div>

       
    </div>
    
    <hr/> 

        <table   class="common-table"   >
            <thead>
                <tr>
                    <td >用户</td>
                    <td >登入时间</td>
                    <td >登入ip</td>
                    <td >是否成功</td>  
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
				    <tr>
                        <td >{{$var["account"]}}</td>
                        <td >{{$var["login_time_str"]}}</td>
                        <td >{{$var["ip"]}}</td>
                        <td >{{$var["flag_str"]}}</td> 
                        <td >
                            <div>
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

