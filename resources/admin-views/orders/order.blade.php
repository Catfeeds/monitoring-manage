@extends('admin::layouts.main')

@section('content')

    {{--@include('admin::search.shops-shops')--}}

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">收费列表</h3>

                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>订单号</th>
                            <th>用户名</th>
                            <th>下单归属地</th>
                            <th>产品金额</th>
                            <td>产品有效期</td>
                            <th>订单状态</th>
                            <th>订单创建时间</th>
                            <th>订单完成时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($orders as $order)
                            <tr>

                                <td>{{$order->sn}}</td>
                                <td>{{$order->user->name}}</td>
                                <td>{{$order->school->name}}</td>
                                <td><span class="badge bg-red">{{$order->price}}</span></td>
                                <td>{{$order->charge->time}}(天)</td>
                                <td>@if($order->state==0)<span class="badge bg-red">待支付</span>@else<span class="badge bg-green"> 已完成</span>@endif</td>
                                <td>{{$order->created_at}}</td>
                                <td>{{$order->pay_at}}</td>
                                <td> <a href="javascript:void(0);" data-id="{{ $order->id }}" class="grid-row-delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>


                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $orders->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::orders.index')])
@endsection