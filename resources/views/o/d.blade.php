@extends('layouts.app')
@section('content')

    <section class="content ">

        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>项目</td>
                    <td>值</td>
                    <td>td</td>
                </tr>
            </thead>
            <tbody>


                    <tr>
                        <td>设备</td>
                        <td>门禁</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>位置 </td>
                        <td>{{$info["title"]}} </td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>最后一次上报时间</td>
                        <td>{{$info["last_post_time"]}} </td>
                        <td></td>
                    </tr>


                    <tr>
                        <td>操作</td>
                        <td>
                            <button class="btn btn-primary" id="id_open">开 </button>

                        </td>
                        <td></td>
                    </tr>
            </tbody>
        </table>
    </section>

@endsection
