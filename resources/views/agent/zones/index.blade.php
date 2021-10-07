@extends('agent.layout.master')
@section('title') Zone List @stop


@section('page_name')
    Zone  Management
    <small>All Zones</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('agent.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('agents', 'Zone  Management') !!} </li>
@stop

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-section">
                <div class="m-section__content">
                    <form method="get" action="{{route('zones.search')}}">
                        <div class="row" style="padding-bottom: 20px;">
                            <div class="col-md-3">
                                <label class="label label-primary m--bg-brand">Country</label>
                                <select name="country" class="form-control" disabled="true">
                                    <option value="18">Bangladesh</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="label label-primary m--bg-brand">Division</label>
                                <select name="division" id="division" class="form-control">
                                    <option value="">select division</option>
                                    @forelse($division as $div)
                                        <option value="{{$div->id}}" {{$div->id == $divi_id ? 'selected' : ''}}>{{$div->name}}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="label label-primary m--bg-brand">District</label>
                                <select name="district" id="district" class="form-control">
                                    <option value="">select district</option>
                                    @forelse($zones as $zn)
                                        <option value="{{$zn->zone_id}}" {{$zn->zone_id == $dist_id ? 'selected' : ''}}>{{$zn->name}}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" style="margin-top: 21px" name="" class="btn btn-brand"><i class="fa fa-search-plus"></i>&nbsp;Search</button>
                            </div>
                        </div>
                    </form>
                    <table class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                        <thead class="">
                            <tr>
                                <th>Sl.</th>
                                <th>Thana/Upazilla</th>
                                <th>District</th>
                                <th>Division</th>
                                <th>Country</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; ?>
                        @forelse($results as $ag)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$ag->upazilla}}</td>
                                <td>{{$ag->division}}</td>
                                <td>{{$ag->zone}}</td>
                                <td>{{$ag->country}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-danger">{{ "No data found" }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div> 
            </div>
        </div>
        <div class="m-portlet__foot clearfix">
            <div class="pull-right">
                {!! $results->render() !!}
            </div>
        </div>
        <!--end::Section-->
    </div>

<!--end::Portlet-->




    <script type="text/javascript">
        // For export data
        $("#division").change(function () {
            $.ajax({
                type: "POST",
                url: "{!! route('zones.division') !!}",
                data: {"_token": "{{ csrf_token() }}", "division_id": $(this).val()},
                cache: false,
                beforeSend: function(){
                    $('#loading-indicator').show();
                },
                complete: function(){
                    $('#loading-indicator').hide();
                },
                success: function (res) {
                    if (res.success === false) {
                        $("#Zone-details")[0].reset();
                    } else {
                        var content ='';
                        $.each(res.result, function (key, val) {
                            console.log(val['zone_id']);
                            content += '<option value="'+val['zone_id']+'">' + val['name'] + '</option>';
                        });
                        $("#district").html(content);
                    }
                }
            });
        });
    </script>
@stop