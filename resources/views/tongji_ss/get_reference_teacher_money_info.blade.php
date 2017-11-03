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
                    <td>teacherid</td>                   
                    <td>名字</td>                   
                    <td>名字</td>                   
                    <td>名字</td>                   
                    <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $list as $var )
                    <tr>
                        <td>{{@$var["teacherid"]}} </td>                          
                        <td class="stu_num">{{@$var["realname"]}} </td>                          
                        <td class="stu_num">{{@$var["realname"]}} </td>                          
                        <td class="stu_num">{{@$var["realname"]}} </td>                          
                        <td>
                            <div class="row-data" data-teacherid="{{$var["month_start"]}}" >
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

