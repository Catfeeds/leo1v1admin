@extends('layouts.teacher_header')
@section('content')
        <style>
     .center-title {
         font-size:20px;
         text-align:center;
     }
     .huge {
         font-size: 40px;
     }
     .panel-green {
        background-color: #5cb85c;
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
        background-color: #d9534f;
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
         background-color: #f0ad4f;
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
     .panel-blue {
         background-color: #9ff;
     }

     #id_content .panel-body {
         text-align:center;
     }

    </style>


    <section class="content ">
        <div>
            <div class="row">
               <div class="col-xs-12 col-md-4">
            <div class="input-group "  >
                <span >日期</span>
                <input type="text" id="id_start_date" class="opt-change form-control input-group-addon  "/>
                <span >-</span>
                <input type="text" id="id_end_date" class="opt-change form-control input-group-addon  "/>
            </div>
        </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group">
                        <span>培训类型</span>
                        <select id="id_train_type" class="opt-change" >
                        </select>
                    </div>
                </div>
                 <div class="col-xs-6 col-md-2">
                    <div class="input-group">
                        <span>学科</span>
                        <select id="id_subject" class="opt-change" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group">
                        <span>培训状态</span>
                        <select id="id_status" class="opt-change" >
                        </select>
                    </div>
                </div>
                </div>

        </div>
        
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>#</td>
                    <td>创建时间</td>
                    <td>创建人</td>
                    <td>学科</td>
                    <td>培训类型</td>
                    <td>培训状态</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var['num']}}</td>
                        <td>{{@$var['create_admin_nick']}}</td>
                        <td>{{@$var['create_time']}}</td>
                        <td>{{@$var['subject_str']}}</td>
                        <td>{{@$var['train_type_str']}}</td>
                        @if ($var['status'] == 3)
                            <td class="panel-green">{{@$var['train_status_str']}}</td>                   
                        @elseif($var['status'] == 1)
                            <td class="panel-red">{{@$var['train_status_str']}}</td>
                        @else
                            <td class="panel-blue">{{@$var['train_status_str']}}</td>    
                        @endif
                        <td>
                            <div class="opt-div" 
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa  opt-play" title="播放视频">播放视频</a>
                                <a class="fa  opt-test" title="自我评测">自我评测</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

