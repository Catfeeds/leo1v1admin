@extends('layouts.app')
@section('content')


    <section class="content ">
        <div class="row">
            <div class="col-xs-12 col-md-5">
                <div id="id_date_range" >
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td >省份</td>
                    <td >未设置</td>
                    <td >语文</td>
                    <td >数学</td>
                    <td >英语</td>
                    <td >化学</td>
                    <td >物理</td>
                    <td >生物</td>
                    <td >政治</td>
                    <td >历史</td>
                    <td >地理</td>
                    <td >科学</td>
                    <td >教育学</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $new_list as $key => $var )
                    <tr>
                        <td > {{@$key}} </td>
                        <td > {{@$var["0"]}} </td>
                        <td > {{@$var["1"]}} </td>
                        <td > {{@$var["2"]}} </td>
                        <td > {{@$var["3"]}} </td>
                        <td > {{@$var["4"]}} </td>
                        <td > {{@$var["5"]}} </td>
                        <td > {{@$var["6"]}} </td>
                        <td > {{@$var["7"]}} </td>
                        <td > {{@$var["8"]}} </td>
                        <td > {{@$var["9"]}} </td>
                        <td > {{@$var["10"]}} </td>
                        <td > {{@$var["11"]}} </td>
                        <td>
                            <div class="opt-div" 
                                 {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td >年级</td>
                    <td >未设置</td>
                    <td >语文</td>
                    <td >数学</td>
                    <td >英语</td>
                    <td >化学</td>
                    <td >物理</td>
                    <td >生物</td>
                    <td >政治</td>
                    <td >历史</td>
                    <td >地理</td>
                    <td >科学</td>
                    <td >教育学</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $list as $key => $var )
                    <tr>
                        <td > {{@$key}} </td>
                        <td > {{@$var["0"]}} </td>
                        <td > {{@$var["1"]}} </td>
                        <td > {{@$var["2"]}} </td>
                        <td > {{@$var["3"]}} </td>
                        <td > {{@$var["4"]}} </td>
                        <td > {{@$var["5"]}} </td>
                        <td > {{@$var["6"]}} </td>
                        <td > {{@$var["7"]}} </td>
                        <td > {{@$var["8"]}} </td>
                        <td > {{@$var["9"]}} </td>
                        <td > {{@$var["10"]}} </td>
                        <td > {{@$var["11"]}} </td>
                        <td>
                            <div class="opt-div" 
                                 {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection
