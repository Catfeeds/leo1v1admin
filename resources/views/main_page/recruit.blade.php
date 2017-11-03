
@extends('layouts.app')
@section('content')

    <style>
     .center-title {
         font-size:20px;
         text-align:center;
     }
     .huge {
         font-size: 35px;
     }
     .subjects {
         font-size: 20px;
         text-align:center;
     }
     .plan_font{
         font-size: 18px;
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

     #id_content .panel-heading {
         font-size:20px;
         text-align:center;
     }

    </style>

    <script type="text/javascript">
     var g_data = <?php echo json_encode(['info'=> $ret_info, 'total' => $total, 't_info' => $type_ret_info, 't_total' => $type_total, 'recruit' => $recruit]);?>;
    </script>

    <section class="content " id="id_content" style="max-width:1600px;">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">历史数据</span>
                        <select class="opt-change form-control" id="id_history_data">
                            <option value="0">是</option>
                            <option value="1">否</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div><a href="javascript:;" id="download_data" class="fa fa-download">导出</a></div>
                </div>

            </div>
            <hr/>
        </div>

 
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            培训至模拟试听通过(科目)
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>科目</td>
                                        <td>面试通过人数</td>
                                        @if ($recruit == 'train')
                                        <td>培训参训率</td>
                                        <td>培训参训新师人数</td>
                                        <td>培训合格率</td>
                                        <td>培训合格新师人数</td>
                                        @endif
                                        <td>模拟试听总排课率</td>
                                        <td>模拟试听总排课人数</td>
                                        <td>模拟试听总上课率</td>
                                        <td>模拟试听总上课人数</td>
                                        <td>模拟试听总通过率</td>
                                        <td>模拟试听总通过人数</td>
                                        <td>入职总人数</td>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td>总计</td>
                                        <td>{{$total['sum']}}</td>
                                        @if ($recruit == 'train')
                                        @if($total['sum'] != 0)
                                            <td>{{round($total['train_tea_sum']/$total['sum'], 2) * 100}}%</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                        <td>{{$total['train_tea_sum']}}</td>
                                        @if($total['train_tea_sum'] != 0)
                                            <td>{{round($total['train_qual_sum']/$total['train_tea_sum'], 2) * 100}}%</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                        <td>{{$total['train_qual_sum']}}</td>
                                        @if($total['train_qual_sum'] != 0)
                                            <td>{{round($total['imit_sum']/$total['train_qual_sum'], 2) * 100}}%</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                        @else
                                        @if($total['sum'] != 0)
                                            <td>{{round($total['imit_sum']/$total['sum'],2) * 100}}%</td>
                                        @else
                                            <td>0</td>
                                            @endif
                                        @endif
                                        <td>{{$total['imit_sum']}}</td>
                                        @if($total['imit_sum'] != 0)
                                            <td>{{round($total['attend_sum']/$total['imit_sum'], 2) * 100}}%</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                        <td>{{$total['attend_sum']}}</td>
                                        @if($total['attend_sum'] != 0)
                                            <td>{{round($total['adopt_sum']/$total['attend_sum'], 2) * 100}}%</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                        <td>{{$total['adopt_sum']}}</td>
                                        <td>{{$total['adopt_sum']}}</td>
                                    </tr>
                                    @foreach($ret_info as $var)
                                        
                                        <tr>
                                            <td>{{@$var['grade_str'].@$var['subject_str']}}</td>
                                            <td>{{$var['sum']}}</td>
                                            @if($recruit == 'train')
                                            @if($var['sum'] != 0)
                                                <td>{{round($var['train_tea_sum']/$var['sum'], 2) * 100}}%</td>
                                            @else
                                                <td>0</td>
                                            @endif
                                            <td>{{$var['train_tea_sum']}}</td>
                                            @if($var['train_tea_sum'] != 0)
                                                <td>{{round($var['train_qual_sum']/$var['train_tea_sum'], 2) * 100}}%</td>
                                            @else
                                                <td>0</td>
                                            @endif
                                            <td>{{$var['train_qual_sum']}}</td>
                                            @if($var['train_qual_sum'] != 0)
                                                <td>{{round($var['imit_sum']/$var['train_qual_sum'], 2) * 100}}%</td>
                                            @else
                                                <td>0</td>
                                            @endif
                                            @else
                                            @if($var['sum'] != 0)
                                                <td>{{round($var['imit_sum']/$var['sum'], 2) * 100}}%</td>
                                            @else
                                                <td>0</td>
                                                @endif
                                            @endif
                                            <td>{{$var['imit_sum']}}</td>
                                            @if($var['imit_sum'] != 0)
                                                <td>{{round($var['attend_sum']/$var['imit_sum'], 2) * 100}}%</td>
                                            @else
                                                <td>0</td>
                                            @endif
                                            <td>{{$var['attend_sum']}}</td>
                                            @if($var['attend_sum'] != 0)
                                                <td>{{round($var['adopt_sum']/$var['attend_sum'], 2) * 100}}%</td>
                                            @else
                                                <td>0</td>
                                            @endif
                                            <td>{{$var['adopt_sum']}}</td>
                                            <td>{{$var['adopt_sum']}}</td>
                                        </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            培训至模拟试听通过(老师类型)
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>老师类型</td>
                                        <td>面试通过人数</td>
                                        @if ($recruit == 'train')
                                        <td>培训参训率</td>
                                        <td>培训参训新师人数</td>
                                        <td>培训合格率</td>
                                        <td>培训合格新师人数</td>
                                        @endif
                                        <td>模拟试听总排课率</td>
                                        <td>模拟试听总排课人数</td>
                                        <td>模拟试听总上课率</td>
                                        <td>模拟试听总上课人数</td>
                                        <td>模拟试听总通过率</td>
                                        <td>模拟试听总通过人数</td>
                                        <td>入职总人数</td>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td>总计</td>
                                        <td>{{$type_total['sum']}}</td>
                                        @if($recruit == 'train')
                                        @if($type_total['sum'] != 0)
                                            <td>{{round($type_total['train_tea_sum']/$type_total['sum'], 2) * 100}}%</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                        <td>{{$type_total['train_tea_sum']}}</td>
                                        @if($type_total['train_tea_sum'] != 0)
                                            <td>{{round($type_total['train_qual_sum']/$type_total['train_tea_sum'], 2) * 100}}%</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                        <td>{{$type_total['train_qual_sum']}}</td>
                                        @if($type_total['train_qual_sum'] != 0)
                                            <td>{{round($type_total['imit_sum']/$type_total['train_qual_sum'], 2) * 100}}%</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                        @else
                                        @if($type_total['sum'] != 0)
                                            <td>{{round($type_total['imit_sum']/$type_total['sum'],2)*100}}%</td>
                                        @else
                                            <td>0</td>
                                            @endif
                                        @endif
                                        <td>{{$type_total['imit_sum']}}</td>
                                        @if($type_total['imit_sum'] != 0)
                                            <td>{{round($type_total['attend_sum']/$type_total['imit_sum'], 2) * 100}}%</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                        <td>{{$type_total['attend_sum']}}</td>
                                        @if($type_total['attend_sum'] != 0)
                                            <td>{{round($type_total['adopt_sum']/$type_total['attend_sum'], 2) * 100}}%</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                        <td>{{$type_total['adopt_sum']}}</td>
                                        <td>{{$type_total['adopt_sum']}}</td>
                                    </tr>
                                    @foreach($type_ret_info as $var)
                                        
                                        <tr>
                                            <td>{{$var['identity_str']}}</td>
                                            <td>{{$var['sum']}}</td>
                                            @if($recruit == 'train')
                                            @if($var['sum'] != 0)
                                                <td>{{round($var['train_tea_sum']/$var['sum'], 2) * 100}}%</td>
                                            @else
                                                <td>0</td>
                                            @endif
                                            <td>{{$var['train_tea_sum']}}</td>
                                            @if($var['train_tea_sum'] != 0)
                                                <td>{{round($var['train_qual_sum']/$var['train_tea_sum'], 2) * 100}}%</td>
                                            @else
                                                <td>0</td>
                                            @endif
                                            <td>{{$var['train_qual_sum']}}</td>
                                            @if($var['train_qual_sum'] != 0)
                                                <td>{{round($var['imit_sum']/$var['train_qual_sum'], 2) * 100}}%</td>
                                            @else
                                                <td>0</td>
                                            @endif
                                            @else
                                            @if($var['sum'] != 0)
                                                <td>{{round($var['imit_sum']/$var['sum'],2) * 100}}%</td>
                                            @else
                                                <td>0</td>
                                            @endif
                                            @endif
                                            <td>{{$var['imit_sum']}}</td>
                                            @if($var['imit_sum'] != 0)
                                                <td>{{round($var['attend_sum']/$var['imit_sum'], 2) * 100}}%</td>
                                            @else
                                                <td>0</td>
                                            @endif
                                            <td>{{$var['attend_sum']}}</td>
                                            @if($var['attend_sum'] != 0)
                                                <td>{{round($var['adopt_sum']/$var['attend_sum'], 2) * 100}}%</td>
                                            @else
                                                <td>0</td>
                                            @endif 
                                            <td>{{$var['adopt_sum']}}</td>
                                            <td>{{$var['adopt_sum']}}</td>
                                        </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
           
@include('layouts.page')
</section>
@endsection
