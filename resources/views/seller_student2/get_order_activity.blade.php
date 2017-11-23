@extends('layouts.app')
@section('content')
   
    <style type="text/css">
     .row-td-field-name {
         padding-right: 0px;
     }

     .row-td-field-value {
         padding-left:0px;
         padding-right: 0px;
         poisition:relative;
     }

     .row-td-field-name >  span {
         background-color: #eee;
         border: 1px solid #ccc;
         border-collapse: separate;
         color: #555;
         display: table-cell;
         font-size: 14px;
         font-weight: normal;
         line-height: 1;
         padding: 6px 12px;
         text-align: right;
         vertical-align: middle;
         width: 1%;
         height: 26pt;
     }

     .row-td-field-value >  span {
         border: 1px solid #ccc;
         border-collapse: separate;
         color: #555;
         display: inline-block;
         font-size: 14px;
         font-weight: normal;
         line-height: 1;
         padding: 6px 12px;
         height: 26pt;
         line-height: 16pt;
         width: 100%;
         text-align:left ;
         background-color: #FFF;
     }
     .row-td-field-value >  span.time_span{
         width: 45%;
         margin-right: 3%;
     }
     p {margin-left: 20px}
     b.to{
         position:absolute;
         left: 46%;
         top: 15px;
     }
    </style>
    <section class="content">
        <div class="row">
            
            <div class="col-xs-12 col-md-12"   >
                <div style="width:98%" id="id_tea_info"
                     {!!  \App\Helper\Utils::gen_jquery_data($ret_info)  !!}
                >
                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span >活动标题:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span>{{@$ret_info['title']}}</span>
                                </div>
                                <div class="col-xs-1 col-md-1  row-td-field-value">
                                    <button style="margin-left:10px"  id="id_upload_face" type="button" class="btn btn-primary">编辑</button> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span>活动日期:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span class="time_span" id="date_range_start">{{@$ret_info['date_range_start']}}</span>
                                    <b class="to">--</b>
                                    <span class="time_span" id="date_range_start">{{@$ret_info['date_range_end']}}</span>
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span>活动课时区间:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span class="time_span">{{@$ret_info['lesson_times_min']}}</span>
                                    <b class="to">--</b>
                                    <span class="time_span">{{@$ret_info['lesson_times_min']}}</span>
                                </div>
                            </div>
                        </div> 
                    </div>
                     
                    <div class="row">
                        <div class="col-xs-12 col-md-12"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-2 row-td-field-name"  >
                                    <span >json字符串:</span>
                                </div>
                                <div class="col-xs-6 col-md-10  row-td-field-value">
                                    <textarea></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
               

                    <div class="row">
                        <div class="col-xs-6 col-md-2  row-td-field-name"   > <span>操作:</span></div>
                        <div class="col-xs-6 col-md-10  row-td-field-value"  data-teacherid="{{@$tea_info['teacherid']}}">
                     
                            <button style="margin-left:10px"  id="id_upload_face" type="button" class="btn btn-primary">保存</button> 
                            <button style="margin-left:10px"  id="id_read_jianli" type="button" class="btn btn-success" >关闭</button>
                            <button style="margin-left:10px"  id="id_upload_face" type="button" class="btn btn-default">返回</button> 

                        </div>
                    </div>
                </div>
                
            </div>
        </div>

    </section>


@endsection


