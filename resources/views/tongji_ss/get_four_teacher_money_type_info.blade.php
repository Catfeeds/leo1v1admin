@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

               
                               

            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>老师</td>
                    <td>年级</td>
                    <td>试听课时(cc)</td>
                    <td>签单数(cc)</td>
                    <td>签单率(cc)</td>
                    <td>试听课时(cr)</td>
                    <td>签单数(cr)</td>
                    <td>签单率(cr)</td>
                                
                    <td> 操作</td>
                </tr>
            </thead>
            <tbody>
               

                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                       
                        <td>{{@$var["cc_lesson_count"]}} </td>
                        <td>{{@$var["cc_have_order"]}} </td>
                        <td>{{@$var["cc_per"]}}% </td>
                        <td>{{@$var["cr_lesson_count"]}} </td>
                        <td>{{@$var["cr_have_order"]}} </td>
                        <td>{{@$var["cr_per"]}}% </td>

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

