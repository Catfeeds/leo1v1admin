@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>项目</td>
                    <td>总共</td>
                    <td>已上课</td>
                    <td>未上课</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>金额 </td>
                    <td> {{$all_money}} </td>
                    <td> {{$confirm_money}} </td>
                    <td> {{$left_money}} </td>
                </tr>
                <tr>
                    <td>课时</td>
                    <td> {{$lesson_count_all}} </td>
                    <td> {{$lesson_count_confirm}} </td>
                    <td> {{$lesson_count_left}} </td>
                </tr>

            </tbody>
        </table>
    </section>
    
@endsection

