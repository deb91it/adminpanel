@extends('admin.layout.master')
@section('title') Expense Category List @stop


@section('page_name')
    Expense Category Management
    <small>All Expense Categories</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.expense-categorys', 'Expense Category Management') !!} </li>
@stop

@section('content')

    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <a href="{!! route('admin.expense-category.new') !!}" class="btn btn-sm btn-brand m-btn--pill">
                            <span class="la la-plus"></span>&nbsp; New Expense Category
                        </a>
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-section">
                <div class="m-section__content">
                    <table id="expense-category-datatable" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                        <thead class="text-center">
                        <tr>
                            <th>Sl.</th>
                            <th style="width: 40%">Name</th>
                            <th>Is active</th>
                            <th>Created Date</th>
                            <th><center> Action </center></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $sl = 1; ?>
                        @forelse($exp_cat as $cat)
                        <tr>
                            <td>{{$sl++}}</td>
                            <td style="width: 40%">{{ !empty($cat->name) ? $cat->name : '' }}</td>
                            <td>{!! $cat->status == 1 ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}</td>
                            <td>{{$cat->created_at}}</td>
                            <td>
                                <a href="{{route('admin.expense-category.edit', $cat->id)}}" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"><i class="fa fa-edit"></i></a>
                                <a href="{{route('admin.expense-category.delete', $cat->id)}}" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                            @empty
                        <tr class="text-center">
                            <td style="width: 100%">No Record Found</td>
                        </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="m-portlet__foot clearfix">
            <div class="pull-right">
            </div>
        </div>
        <!--end::Section-->
    </div>

    <!--end::Portlet-->


@stop