@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="http://www.daimajiayuan.com/download/jquery/jquery-1.10.2.min.js"></script>  
    <script type="text/javascript" src="http://cdn.bootcss.com/bootstrap-select/2.0.0-beta1/js/bootstrap-select.js"></script>    
    <link rel="stylesheet" type="text/css" href="http://cdn.bootcss.com/bootstrap-select/2.0.0-beta1/css/bootstrap-select.css">    
    
    
    
    <!-- 3.0 -->  
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">  
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>  
    <script src="/js/jquery.admin.js?{{@$_publish_version}}" type="text/javascript"></script>
    
    <!-- 2.3.2  
         <link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">  
         <script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.js"></script>  
    -->  
    <script type="text/javascript">  
     $(window).on('load', function () {  
         
         $('.selectpicker').selectpicker({  
             'selectedText': 'cat'  
         });  
         
         // $('.selectpicker').selectpicker('hide');  
     });  
    </script>  
    
   
   

    
    <section class="content ">

        <div>
            <div class="row" >
                <div class="col-xs-6 col-md-2">
                    <button id="id_get_money" class="btn btn-primary">刷新</button>
                </div >
                <div class="col-xs-6 col-md-2">
                    <button id="id_add" class="btn btn-primary">新增</button>
                </div >


            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>所带学生数</td>
                    <td>每周1课时人数</td>
                    <td>每周1.5课时人数</td>
                    <td>每周2课时人数</td>
                    <td>每周2.5课时人数</td>
                    <td>每周3课时人数</td>
                    <td>每周3.5课时人数</td>
                    <td>每周4课时人数</td>
                    <td>每周4.5课时人数</td>
                    <td>每周5课时人数</td>
                    <td>每周5.5课时人数</td>
                    <td>每周6课时人数</td>
                    <td>每周6.5课时人数</td>
                    <td>每周7课时人数</td>
                    <td>本月学生请假课时数</td>
                    <td>本月老师请假课时数</td>                                      

                    <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $list as $k=>$var )
                    <tr>
                        
                        <td class="all_num"> </td>
                        <td class="one_num">  </td>
                        <td class="one_five_num">  </td>
                        <td class="two_num"> </td>
                        <td class="two_five_num"> </td>
                        <td class="three_num">  </td>
                        <td class="three_five_num">  </td>
                        <td class="four_num">  </td>
                        <td class="four_five_num">  </td>
                        <td class="five_num"> </td>
                        <td class="five_five_num"> </td>
                        <td class="six_num"></td>
                        <td class="six_five_num"></td>
                        <td class="other_num">  </td>
                        <td class="stu_leave_num"> </td>
                        <td class="tea_leave_num">  </td>
                        

                        <td>
                            <div class="row-data" data-teacherid="1" >
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
