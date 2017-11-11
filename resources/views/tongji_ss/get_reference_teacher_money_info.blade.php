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
                    <td>月份</td>                   
                    <td>数量</td>                   
                    <td>课时</td>                   
                    <td>CC转化率</td>                    
                    <td>CR转化率</td>                    
                    <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $list as $k=>$var )
                    <tr>
                        <td>{{@$k}} </td>     
                        <td>{{@$var["tea_num"]}} </td>                           
                        <td>{{@$var["lesson_count"]/100}} </td>                           
                        <td>{{@$var["cc_per"]}} </td>                           
                        <td>{{@$var["cr_per"]}} </td>                           
                        <td>
                            <div class="row-data" data-teacherid="{{$var["tea_num"]}}" >
                                <a class="fa fa-list course_plan" title="按课程包排课"> </a>
                            </div>

                        </td>

                        
                    </tr>

                @endforeach
                
                
                
            </tbody>
        </table>

        
        @include("layouts.page")
    </section>
    
@endsection

