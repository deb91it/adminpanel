@extends('admin.layout.master')
@section('title') Permission List @stop

@section('page_name')
    Permission Management
	<small>All Permission</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> <a href="{{ url('/admin/permission') }}"> Permission </a> </li>
@stop

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <a href="{{ url('/admin/permission/create') }}" class="btn btn-sm btn-brand m-btn--pill">
                            <span class="la la-plus"></span>&nbsp; New Permission
                        </a>
                    </h3>                 
                </div> 
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-section">
                <div class="m-section__content">
                    <table id="permission-datatable" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover">
                        <thead class="">
                            <tr>
                                <th>Si.</th>
                                <th>Display Name</th>
                                <th>Name</th>
                                <th>Group Name</th>
                                <th><center>Action</center></th>
                            </tr>
                        </thead>
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

    <script type="text/javascript">
        $(function() {
            $('#permission-datatable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                pageLength: 10,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                ajax: {
                        url: '{!! route('admin.datatable.permission') !!}',
                        type: 'GET',
                        data: function( d ) {
                            d._token = "{{ csrf_token() }}";
                        }
                    },
                columns: [
                        {
                            data: 'id',
                            name: 'p.id',
                            searchable: false,
                            render: function(data, type, row, meta){
                                return meta.row + meta.settings._iDisplayStart + 1 ;
                            }
                        },{
                            data: 'display_name',
                            name: 'p.display_name',
                            searchable: true,
                        },{
                            data: 'name',
                            name: 'p.name',
                            searchable: true,
                        },{
                            data: 'group_name',
                            name: 'pg.group_name',
                            searchable: true,
                        },
                        {
                            data: 'action_col',
                            name: 'action_col',
                            searchable: false,
                            orderable: false
                        },
                    ], 
            });
            $('.dataTables_wrapper').removeClass('form-inline'); 
            $('.form-control').removeClass('input-sm'); 
            $('#permission-datatable_length .form-control').addClass('m-bootstrap-select m-bootstrap-select--air filter-select');
            $('.dataTables_processing').addClass('m-loader m-loader--brand'); 
        });

    </script>
@stop