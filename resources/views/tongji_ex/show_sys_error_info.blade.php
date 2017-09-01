@extends('layouts.app')
@section('content')

    <section class="content ">

        <div class="row">

            <div class="col-xs-12 col-md-12">
               来自:{{ @$report_info["report_error_from_type_str"] }}
            </div>
            <div class="col-xs-12 col-md-12">
                时间:{{ @$report_info["add_time"] }}
            </div>
            <div class="col-xs-12 col-md-12">
                分类:{{ @$report_info["report_error_type_str"] }}
            </div>
        </div>

        <hr/>
        <div>
           {!! @$report_info["error_msg"] !!} 
        </div>
    </section>

@endsection
