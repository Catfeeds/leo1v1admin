@extends('layouts.app')
@section('content')
    <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>
    <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>
    <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.time.js"></script>
    <script type="text/javascript" >

     var g_data_ex_list= <?php  echo json_encode($data_ex_list); ?> ;
    </script>

    <section class="content ">
        <div class="row">
            <div class="col-xs-12 col-md-5">
                <div id="id_date_range"> </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">一周</span>
                    <select class="opt-change form-control" id="id_week_flag" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">xmpp</span>
                    <select class="opt-change form-control" id="id_xmpp_value" >
                        <option value="">全部</option>
                        @foreach ($xmpp_list as $val)
                            <option value="{{@$val["server_name"]}}" > {{@$val["server_desc"]}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <input type="button" class="reset_xmpp" value="生成"/>
                </div>
            </div>


        </div>

        <hr/>
        <div id="id_pic_user_count" > </div>


    </section>

@endsection
