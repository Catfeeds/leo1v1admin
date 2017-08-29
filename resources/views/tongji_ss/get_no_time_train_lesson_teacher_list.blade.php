@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row" >                
               
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>teacherid</td>
                    <td>姓名</td>
                    <td>电话</td>
                    <td>微信绑定</td>
                   

                    <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["teacherid"]}} </td>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["wx_flag"]}} </td>
                       
                       

                       
                        <td>
                            <div class="row-data" {!! \App\Helper\Utils::gen_jquery_data($var) !!} >
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

