@extends('layouts.app')
@section('content')
    <script type="text/javascript" >
     var g_data_ex_list= <?php  echo json_encode($table_data_list); ?> ;
    </script>


    <section class="content ">
	      <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>
	      <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>
    <script type="text/javascript" >
     var g_data_ex_list= <?php  echo json_encode($table_data_list); ?> ;
    </script>


        <div>
            <div class="row  " >
                <div class="col-xs-12 col-md-4">
                    <div class="input-group ">
                        <span >时间:</span>
                        <input type="text" id="id_start_time" class="opt-change"/>
                        <span >-</span>
                        <input type="text" id="id_end_time" class="opt-change"/>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <hr/>
        <div style="font-size:22px">全职老师每月CC转化率统计曲线</div>
        <div id="id_pic_cc_transfer" > </div>
         <hr/>
         <div style="font-size:22px"> 全职老师每月常规课耗统计曲线</div>
        <div id="id_pic_lesson_count" > </div>

        <hr/>
        <div style="font-size:22px"> 全职老师所带学生总数统计曲线</div>
        <div id="id_pic_student_num" > </div>

        <hr/>
    </section>

@endsection
