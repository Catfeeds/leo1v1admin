@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-1 remove-for-xs col-xs-6 " >
            <div>
                <button class="btn btn-primary" id="id_back"> 返回 </button>
            </div>

        </div>

        
    </div>



    <!--定义页面图表容器-->
    <!-- 必须制定容器的大小（height、width） -->
    <div id="container_new" style="width: 90%; height: 600px; padding: 10px;"></div>
    <script type="text/javascript" src="/map_test/js/echarts-all.js"></script>

    
@endsection

