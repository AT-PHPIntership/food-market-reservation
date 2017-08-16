@extends('layouts.master')
@section('main-header')
    <h1>{{ __('DETAIL USER') }}
    <small>user's profile and orders history</small>
    <a href="{{ route('users.index') }}" class="pull-right"><span class="fa fa-arrow-left btn btn-primary"></span></a>
    </h1>
@endsection
@section('main-content')
    @if(isset($user))
        <div class="panel-body inf-content">
            <div class="row center">
                <div class="col-md-4">
                    @if(!isset($user->image))
                        <img alt="" title="" class="img-circle img-thumbnail isTooltip"
                             src="https://bootdey.com/img/Content/user-453533-fdadfd.png"
                             data-original-title="Usuario">
                    @else
                        <img alt="" style="width:600px;" title="" class="img-circle img-thumbnail isTooltip"
                             src="/images/users/{{ $user->image }}" data-original-title="Usuario">
                    @endif
                </div>
                <div class="col-md-8">
                    <strong>{{ __('User Information') }}</strong><br>
                    <div class="table-responsive">
                        <table class="table table-condensed table-responsive table-user-information">
                            <tbody>
                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-asterisk text-primary"></span>
                                        {{ __('Identification') }}
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{ $user->id }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-user  text-primary"></span>
                                        {{ __('Full Name') }}
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{ $user->full_name }}
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-bookmark text-primary"></span>
                                        {{ __('Gender') }}
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{ $user->gender == \App\User::MALE ? __('Male') : __('Female') }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-eye-open text-primary"></span>
                                        {{ __('User Type') }}
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{ $user->is_admin == \App\User::ADMIN ? __('Admin') : __('Normal User') }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-envelope text-primary"></span>
                                        {{ __('Email') }}
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{ $user->email }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-calendar text-primary"></span>
                                        {{ __('Birthday') }}
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{ $user->birthday }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-home text-primary"></span>
                                        {{ __('Address') }}
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{ $user->address }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-phone text-primary"></span>
                                        {{ __('Phone Number') }}
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{ $user->phone_number }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-calendar text-primary"></span>
                                        created
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{ $user->created_at }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-calendar text-primary"></span>
                                        {{ __('Updated At') }}
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{ $user->updated_at }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        <span class="glyphicon glyphicon-shopping-cart text-primary"></span>
                                        {{ __('Total Orders') }}
                                    </strong>
                                </td>
                                <td class="text-primary">
                                    {{ $totalOrders }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">{{ __('Total Orders') }}</h3>

                                <div class="box-tools">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" name="table_search" class="form-control pull-right"
                                               placeholder="Search">

                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-default"><i
                                                        class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            @if(!isset($user->orders))
                                <h3>{{ __('No orders') }}</h3>
                            @else
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <tr>
                                            <th>{{ __('ID') }}</th>
                                            <th>{{ __('Ordered at') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Custom Address') }}</th>
                                            <th>{{ __('Payment') }}</th>
                                        </tr>
                                        @foreach($user->orders as $order)
                                            <tr>
                                                <td>{{ $order->id }}</td>
                                                <td>{{ $order->updated_at }}</td>
                                                <td>@if($order->status == 0)
                                                        <span class="label label-danger">Canceled</span>
                                                    @elseif($order->status == 1)
                                                        <span class="label label-warning">Pending</span>
                                                    @else
                                                        <span class="label label-success">Approved</span>
                                                    @endif
                                                </td>
                                                <td>{{ $order->custom_address }}
                                                </td>
                                                <td>{{ $order->payment }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                                <!-- /.box-body -->

                            @endif
                        </div>
                        <!-- /.box -->
                    </div>
                </div>
            </div>
        </div>
    @else
        <h1>{{ __('Nothing to show!') }}</h1>
    @endif
@endsection