@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row" >
                <div class="col-xs-6 col-md-3">
                    <div id="id_date_range">
                    </div>
                </div>

                <div class="col-xs-6 col-md-8">
                    <div class="input-group">
                        <span class="input-group">老师总人数</span>
                        <span id="id_teacher_money">{{$info['teacher_num']}}</span>
                        <span class="input-group">老师总积分</span>
                        <span id="id_all_money">{{$info['score']}}</span>
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        @include("layouts.page")
    </section>

@endsection
