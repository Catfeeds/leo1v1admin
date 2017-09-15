@extends('layouts.app')
@section('content')
   

    <section class="content ">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="panel panel-warning"  >
                    <div class="panel-heading center-title ">
                        概况
                    </div>
                    <div class="panel-body">

                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>老师总数</td>
                                    <td>已反馈老师数</td>
                                    <td>在读学生总数</td>
                                    <td>已反馈学生数</td>

                                   
                                </tr>
                            </thead>
                            <tbody >
                              
                                    <tr>
                                        <td>{{@$all_record_info["teacher_num"]}} </td>
                                        <td>{{@$all_record_info["have_record_tea"]}} </td>
                                        <td>{{@$all_record_info["stu_num"]}} </td>
                                        <td>{{@$all_record_info["have_record_stu"]}} </td>
                                    </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-12">
                <div class="panel panel-warning"  >
                    <div class="panel-heading center-title ">
                        第一次试听反馈
                    </div>
                    <div class="panel-body">

                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>科目</td>
                                    <td>0-40</td>
                                    <td>40-50</td>
                                    <td>50-60</td>
                                    <td>60-65</td>
                                    <td>65-70</td>
                                    <td>70-75</td>
                                    <td>75-80</td>
                                    <td>80-85</td>
                                    <td>85-90</td>
                                    <td>90-95</td>
                                    <td>95-100</td>
                                    <td>总计</td>
                                    
                                </tr>
                            </thead>
                            <tbody >
                                @foreach ( $first_test as $key=> $var )
                                    <tr>
                                        <td>{{@$var["subject_str"]}} </td>
                                        <td>{{@$var["first_score"]}} </td>
                                        <td>{{@$var["second_score"]}} </td>
                                        <td>{{@$var["third_score"]}} </td>
                                        <td>{{@$var["fourth_score"]}} </td>
                                        <td>{{@$var["fifth_score"]}} </td>
                                        <td>{{@$var["sixth_score"]}} </td>
                                        <td>{{@$var["seventh_score"]}} </td>
                                        <td>{{@$var["eighth_score"]}} </td>
                                        <td>{{@$var["ninth_score"]}} </td>
                                        <td>{{@$var["tenth_score"]}} </td>
                                        <td>{{@$var["eleventh_score"]}} </td>
                                        <td>{{@$var["all_num"]}} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            


        </div>

      

        
    </section>
    
@endsection


