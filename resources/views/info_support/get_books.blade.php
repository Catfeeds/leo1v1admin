@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script>
    </script>
    <style>
     .up_file,.down_file,.dele_file{ padding: 4px;margin-left: 6px;margin-bottom:5px };
     .hide{ display:none}
     .power_table{ width:1200px}
     .power_table thead tr th,.power_table tbody tr td{ border:1px solid #999;padding:10px 10px}
     .power_table thead tr{ background:#d2d6de}
     .power_table thead{}
     .import_excel input#upfile{ margin-right: 0px;display: inline-block;width: 200px;}
    </style>
    <section class="content">

        <div>
            <div class="row">
                <!-- <div class="row row-query-list"> -->
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">分类</span>
                        <select class="form-control opt-change" id="id_use_type"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">资源类型</span>
                        <select class="form-control opt-change" id="id_resource_type"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="form-control opt-change" id="id_subject"> </select>
                    </div>
                </div>

                <div class="col-xs-2 col-md-1 ">
                    <button class="btn btn-primary opt_add">导入</button>
                </div>

                <div class="col-xs-2 col-md-1 ">
                    <button class="btn btn-info opt_add">添加</button>
                </div>

            </div>   
       
        </div>
        <hr/>
        <table class="power_table" id="menu_mark">
            <thead>
                <tr> 
                    <th>科目</th>
                    <th>省份</th>
                    <th>城市</th>
                    <th>小学</th>
                    <th>初中</th>
                    <th>高中</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr class="right-menu">
                   
                        <td>{{@$var["subject_str"]}} </td>
                        <td province="{{@$var["province"]}}">{{@$var["province_name"]}} </td>
                        <td city="{{@$var["city"]}}">{{@$var["city"]}} </td>
                        <td>
                            @if($var['book_arr']['100'])
                                @foreach( $var['book_arr']['100'] as $gra => $book)
                                    <span>{{$book}}、</span>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if($var['book_arr']['200'])
                                @foreach( $var['book_arr']['200'] as $gra => $book)
                                    <span>{{$book}}、</span>
                                @endforeach
                            @endif

                        </td>
                        <td>
                            @if($var['book_arr']['300'])
                                @foreach( $var['book_arr']['300'] as $gra => $book)
                                    <span>{{$book}}、</span>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            <a class="opt-look btn color-blue" title="预览">预览</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
