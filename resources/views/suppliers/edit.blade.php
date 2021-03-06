@extends('layouts.master')
@section('main-header')
    <h1>{{ __('UPDATE SUPPLIER PAGE') }}
        <small></small>
    </h1>
@endsection
@section('main-content')

    @include('flash::message')
    @if(isset($supplier))
        <div class="row center">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{__('Edit Supplier')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="{{ route('suppliers.update', ['id' => $supplier->id])}}" method="post">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $supplier->id }}">
                        <div class="col-md-2"></div>
                        <div class="box-body col-md-8">
                            <div class="form-group col-md-12 {{ $errors->has('name') ? 'has-error' : '' }}">
                                <div class="col-md-2"><label for="name">{{__('Name')}}</label></div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="name" placeholder=""
                                           value="{{ $supplier->name }}">
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-12 {{ $errors->has('description') ? 'has-error' : '' }}">
                                <div class="col-md-2">
                                    <label for="description">{{__('Description')}}</label></div>
                                <div class="col-md-10">
                            <textarea class="form-control"
                                      name="description">{{ $supplier->description }}</textarea>
                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                <div class="col-md-12">
                                    <input type="submit" class="btn btn-primary pull-right" value="{{__('Update')}}">
                                    <input type="reset" class="btn btn-danger pull-left" value="{{__('Reset')}}">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="col-md-2"></div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <h1>{{ __('Nothing to show!') }}</h1>
    @endif
@endsection
