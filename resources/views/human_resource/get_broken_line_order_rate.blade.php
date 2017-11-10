@extends('layouts.app')
@section('content')
  <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>
  <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>
  <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.time.js"></script>
  <script src="/js/chart.js"></script>
  <script type="text/javascript" >

   var g_data_ex_list= <?php  echo json_encode($data_ex_list); ?> ;
  </script>

    <section class="content ">
        <div class="row">
            <div class="col-xs-12 col-md-5">
                <div id="id_date_range"> </div>
            </div>
        </div>

        <hr/>
        <div id="id_pic_user_count" > </div>


        <!-- <canvas id="myChart" width="400" height="400"></canvas>
           -->

    </section>

@endsection
