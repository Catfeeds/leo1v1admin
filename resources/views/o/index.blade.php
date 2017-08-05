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
                        <td>空调 </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>编号 </td>
                        <td>{{$id}} </td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>操作</td>
                        <td>
                            <button class="btn btn-primary" id="id_open">开 </button>
                            <button class="btn btn-warning"  id="id_close">关</button>

                        </td>
                        <td></td>
                    </tr>
            </tbody>
        </table>
    </section>

@endsection
