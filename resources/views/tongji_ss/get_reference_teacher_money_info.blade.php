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
                    <td>userid </td>
                    <td>姓名</td>
                    <td>年级</td>
                    <td>老师类型</td>
                    <td>地区</td>
                                     
                   

                    <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var}} </td>
                        <td class="nick"></td>
                        <td class="grade"></td>
                        <td class="identity"></td>
                        <td class="location"></td>
                       
                       
                       

                       
                        <td>
                            <div class="row-data" data-userid="{{$var}}" >
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

