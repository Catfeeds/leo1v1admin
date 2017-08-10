@extends('layouts.app')
@section('content')
  <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>
  <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>
  <script language="javascript" type="text/javascript" src="/page_js/lib/select_date_range.js"></script>
  <section class="content ">
      <div class="row">
          <div class="col-xs-12 col-md-4" data-title="时间段">
              <div id="id_date_range"></div>
          </div>
          <br/>
          <hr/>
          <table class="table table-bordered">
              <tr>
                  <td>提交试讲需求老师数</td>
                  <td>提交试讲视频老师数</td>
                  <td>面试通过老师数</td>
                  <td>培训通过老师数</td>
                  <td>第一次试听老师数</td>
                  <td>第五次试听老师数</td>
              </tr>
              <tr>
                  <td>{{$ret["app_total"]}}</td>
                  <td>{{$ret["lec_total"]}}</td>
                  <td>{{$ret["tea_total"]}}</td>
                  <td>{{$ret["tran_total"]}}</td>
                  <td>{{$first_tea_num}}</td>
                  <td>{{$fifth_tea_num}}</td>

              </tr>

          </table>

          <hr>
          <table class="table table-bordered">
              <tr>
                  <td>试讲需求-提交视频时长</td>
                  <td>提交视频-面试通过时长</td>
                  <td>面试通过-培训通过</td>
                  <td>培训通过-第一次试听课时长</td>
                  <td>第一次试听课到第五次试听课</td>
              </tr>
              <tr>
                  <td>{{$app_time}}天</td>
                  <td>{{$lec_time}}天</td>
                  <td>{{$tran_time}}天</td>
                  <td>{{$first_time}}天</td>
                  <td>{{$fifth_time}}天</td>
              </tr>

          </table>
          <hr>
          <table class="table table-bordered">
              <tr>
                  <td>冻结老师数</td>
                  <td>限课老师数</td>
                  <td>限1次</td>
                  <td>限3次</td>
                  <td>限5次</td>
              </tr>
              <tr>
                  <td>{{$tea_limit_info["freeze_num"]}}</td>
                  <td>{{$tea_limit_info["limit_num"]}}</td>
                  <td>{{$tea_limit_info["limit_one"]}}</td>
                  <td>{{$tea_limit_info["limit_three"]}}</td>
                  <td>{{$tea_limit_info["limit_five"]}}</td>
              </tr>

          </table>



  </section>

@endsection
