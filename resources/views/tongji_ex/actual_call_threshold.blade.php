@extends('layouts.app')
@section('content')
    <!-- 引入 ECharts 文件 -->
    <script type="text/javascript" src="/page_js/echarts.js"></script>
    <script type="text/javascript" >
     var g_data_ex_list= <?php  echo json_encode($data_ex_list); ?> ;
    </script>
    <section class="content ">
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >xx</span>
                        <input type="text" value=""  class="opt-change"  id="id_"  placeholder=""  />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <!-- 为 ECharts 准备一个具备大小（宽高）的 DOM -->
        <div id="main" style="width: 95%;height:100%;"></div>
    </section>

@endsection
