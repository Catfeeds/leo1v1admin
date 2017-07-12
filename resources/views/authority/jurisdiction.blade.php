@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
<section class="content">
    <hr/> 
    <table   class=" common-table "   >
        <thead>
            <tr>
                <td>用户组</td>
                    @foreach ( $power_define_list as $key => $var)
                        <td style="display: {{$key<10?"true":"none" }};">{{$var}}</td> 
                    @endforeach
                <td>opt</td>
            </tr>
            
        </thead>
        <tbody>
            @foreach ($table_data_list as $tvar)
                <tr>
                    <td>{{$tvar["group_name"]}} </td>
                    @foreach ( $power_define_list as $pk => $p)
                        <td > {{@$tvar["l_$pk"] }} </td> 
                    @endforeach
                    <td>
                        <div></div>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
@endsection


