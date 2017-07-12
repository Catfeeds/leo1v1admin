@extends('layouts.app')
@section('content')
    <style>
     .center-title {
         font-size:20px;
         text-align:center;
     }
     .huge {
         font-size: 40px;
     }
     .panel-green {
         border-color: #5cb85c;
     }
     .panel-green .panel-heading {
         background-color: #5cb85c;
         border-color: #5cb85c;
         color: #fff;
     }
     .panel-green a {
         color: #5cb85c;
     }
     .panel-green a:hover {
         color: #3d8b3d;
     }
     .panel-red {
         border-color: #d9534f;
     }
     .panel-red .panel-heading {
         background-color: #d9534f;
         border-color: #d9534f;
         color: #fff;
     }
     .panel-red a {
         color: #d9534f;
     }
     .panel-red a:hover {
         color: #b52b27;
     }
     .panel-yellow {
         border-color: #f0ad4e;
     }
     .panel-yellow .panel-heading {
         background-color: #f0ad4e;
         border-color: #f0ad4e;
         color: #fff;
     }
     .panel-yellow a {
         color: #f0ad4e;
     }
     .panel-yellow a:hover {
         color: #df8a13;
     }


    </style>


    <section class="content " id="id_content" style="max-width:1200px;">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div id="id_date_range" >
                    </div>
                </div>
            </div>
            <hr/>      

            <div class="row">

                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            KPI
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>

                                    <tr>
                                        <td>助教</td>
                                        <td>新接学生</td>
                                        <td>在读学生</td>
                                        <td>首次反馈</td>
                                        <td>实际完成</td>
                                        <td>学情反馈1</td>
                                        <td>实际完成</td>
                                        <td>学情反馈2</td>
                                        <td>实际完成</td>

                                        <td>扩课</td>
                                        <td>转介绍</td>                                       
                                    </tr>
                                </thead>
                                <tbody id="id_lesson_count_list">
                                    @foreach($ret_info as $var)
                                        <tr>
                                            <td> {{@$var["account"]}} </td> 
                                            <td> {{@$var["new_stu"]}} </td> 
                                            <td> {{@$var["read_stu"]}} </td> 
                                            <td> {{@$var["new_stu"]}} </td> 
                                            <td> {{@$var["first_revisit"]}} </td> 
                                            <td> {{@$var["revisit_num"]}} </td> 
                                            <td> {{@$var["xq_revisit_first"]}} </td> 
                                            <td> {{@$var["revisit_num"]}} </td> 
                                            <td> {{@$var["xq_revisit_second"]}} </td> 
                                          
                                            <td> {{@$var["kk_succ"]}}</td> 

                                            <td> {{@$var["tran_num"]}} </td> 
                                        </tr>
                                        
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>               

               

            </div>

            
        </div>
    </section>
    
@endsection



