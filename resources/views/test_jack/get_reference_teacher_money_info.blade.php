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
                    <td>科目</td>                   
                    <td>年级</td>                   
                    <td>数量</td>                   
                                   
                                 
                     <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $list as $k=>$var )
                    <tr>
                        <td>{{@$var["subject_str"]}} </td>     
                        
                        <td class="lesson_count">{{@$var["grade_str"]}}  </td>                           
                        <td class="cc_per"> {{@$var["num"]}} </td>                           
                                                
                                          
                        <td>
                            <div class="row-data" data-teacherid="{{$var["num"]}}" >
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

