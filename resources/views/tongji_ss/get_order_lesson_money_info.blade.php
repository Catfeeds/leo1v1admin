@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row" >                
                <div class="col-xs-6 col-md-2">
                    <button id="id_get_money" class="btn btn-primary">刷新</button>
                </div > 

            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td rowspan="2">年月</td>
                    <td rowspan="2">当月签约人数 </td>
                    <td rowspan="2">当月新增课时费</td>
                    <td rowspan="2">当月新增课时数</td>
                    <td colspan="22" >课耗时点</td>
                                                                                                                           
                    <td rowspan="2"> 操作</td>
                </tr>
                <tr>
                    @foreach ( $list as $var )
                        <td>{{@$var["month"]}} </td>                          
                    @endforeach
                    
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $list as $var )
                    <tr>

                        <td>{{@$var["month"]}} </td>                          
                        <td>{{@$var["stu_num"]}} </td>                          
                        <td>{{@$var["month"]}} </td>                          
                        <td>{{@$var["month"]}} </td>                          
                        <td>{{@$var["month"]}} </td>                          
                        <td>{{@$var["month"]}} </td>                          
                        <td>{{@$var["month"]}} </td>                          
                        <td>{{@$var["month"]}} </td>                          
                        <td>{{@$var["month"]}} </td>                          
                        <td>{{@$var["month"]}} </td>                          
                        <td>{{@$var["month"]}} </td>                          
                        <td>{{@$var["month"]}} </td>                          
                        <td>{{@$var["month"]}} </td>                          
                        <td>{{@$var["month"]}} </td>                          
                    </tr>

                @endforeach
                    
 
               
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

