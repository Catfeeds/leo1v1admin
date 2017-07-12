@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-xs-6 col-md-5">
                    <div  id="id_date_range" >
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>
                        <select id="id_groupid" class="opt-change" style="height:53px;margin-top:-10px;margin-bottom:-10px;width:200px;margin-left:-10px;margin-right:-114px">
                            <option value="-1">全部</option>
                            @foreach ( $group_list as $var )
                                <option value="{{$var['groupid']}}">{{$var['group_name']}}</option>
                            @endforeach
                           

                        </select>
                    </td>
                <td> 当天例子回访数 </td>  <td> 当天例子-首次回访距例子进入时间(分钟) </td> <td> 操作  </td> </tr>
            </thead>
            <tbody>               
                <tr>
                    <td> 平均 </td>
                    <td> {{$count_ave}}</td>
                    <td> {{$avg_call_interval_ave}}  </td>
                    <td>
                        <div
                            {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >

                        </div>
                    </td>
                </tr>

                @foreach ( $table_data_list as $var )
                    <tr>
                        <td> {{$var["admin_nick"]}}  </td>
                        <td> {{$var["user_count"]}}  </td>
                        <td> {{$var["avg_call_interval"]}}  </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

