@extends('layouts.master')

@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{trans('category.header-list')}}</h3>
            <a id="btn-add-category" class="btn btn-primary pull-right" href="{{ route('categories.create') }}"
               title="{{trans('category.title.add')}}">
                <span class="glyphicon glyphicon-plus"></span>
            </a>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-striped dataTable table-hover"
                               role="grid"
                               aria-describedby="list-category-info">
                            <thead>
                                <tr role="row">
                                    <th style="width: 1em">{{trans('category.column.id')}}</th>
                                    <th style="width: 7em">{{trans('category.column.name')}}</th>
                                    <th>{{trans('category.column.description')}}</th>
                                    <th style="width: 5em">{{trans('category.column.action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->name  }}</td>
                                    <td>{{ $category->description }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-success btn-edit-item"
                                           href="{{ route('categories.edit', $category->id)}}"
                                           title="{{trans('category.title.edit')}}">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </a> -
                                        <form role="form" class="delete-item pull-left"
                                              action="{{ route('categories.destroy', $category->id)}}"
                                              method="post">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        </form>
                                        <button class="btn-xs btn-danger btn btn-confirm-delete"
                                                data-confirm="{{trans('category.data_confirm')}}"
                                                title="{{trans('category.title.delete')}}">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{ $categories->links() }}
        </div>
    </div>
@endsection
