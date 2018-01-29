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
        <div id="main" style="width: 95%;height:600px;"></div>

        <table class="common-table"> 
            <thead>
                <tr>
                    <td>例子 </td>
                    <td>抢单详情 </td>
                    <td>例子进入时间 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var[""]}} </td>
                        <td>{{@$var[""]}} </td>
                        <td>{{@$var[""]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
