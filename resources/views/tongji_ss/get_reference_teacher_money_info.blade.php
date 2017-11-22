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
                    <td>电话</td>                   
                    <td>科目</td>                   
                    <td>入职时间</td>
                    <td>第一次试听课</td>
                    <td>第一次常规课</td>                    
                    <td>CC签单率</td>                    
                    <td>CR签单率</td>                    
                     <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $list as $k=>$var )
                    <tr>
                        <td>{{@$var["teacherid"]}} </td>     
                        <td>{{@$var["realname"]}} </td>     
                        <td>{{@$var["phone"]}} </td>     
                        <td>{{@$var["subject_str"]}} </td>     
                        <td>{{@$var["time_str"]}} </td>      
                        <td class="first_test"> </td>                           
                        <td class="first_normal"></td>                           
                        <td class="cc_per"></td>                           
                        <td class="cr_per"></td>                           
                                          
                        <td>
                            <div class="row-data" data-teacherid="{{$var["teacherid"]}}" >
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

