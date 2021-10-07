@extends('admin.layout.master')
@section('title') Edit Plan @stop


@section('page_name')
    Plan Management
    <small>Edit Plan</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.plans', 'Plan Management') !!} </li>
    <li class="active"> {!! link_to_route('admin.plan-assign.new', 'Edit Plan') !!} </li>
@stop

@section('content')
    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--font-brand">
                        <i class="fa fa-plus"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        Edit Plan
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        {!! Form::open(['route' => ['admin.plan-assign.update',$merchants->id], 'role' => 'form', 'method' => 'post', 'id' => "add-Plan"]) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="m-form m-form--fit m-form--label-align-right ">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('merchant_id') ? 'has-error' : '' !!}">
                                <div class="card" style="width: 18rem;">
                                    @if($merchants->profile_pic != '')
                                        <?php
                                        $filepath = public_path('/resources/profile_pic/').$merchants->profile_pic;
                                        ?>
                                        @if (file_exists($filepath))
                                            <img class="card-img-top" style="padding: 20px;" src="{{ url('/resources/profile_pic') }}/{!! $merchants->profile_pic !!}" alt="Card image cap">
                                        @else
                                            <img src="{{ url('backend/dist/img/avatar.png') }}">
                                        @endif
                                    @else
                                        <img src="{{ url('backend/dist/img/avatar.png') }}">
                                    @endif

                                    <div class="card-body">

                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><b>Name :</b> {{$merchants->first_name." ".$merchants->first_name}}</li>
                                        <li class="list-group-item"><b>Email :</b> {{$merchants->m_email}}</li>
                                        <li class="list-group-item"><b>Mobile : {{$merchants->m_mobile}}</b></li>
                                    </ul>
                                </div>
                                <input type="hidden" name="merchant_id" value="{{$merchants->id}}">
                                {!! $errors->first('merchant_id', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                                <div class="row {!! $errors->has('Plan Assign_name') ? 'has-error' : '' !!}">
                                    <label>Delivered Plan Name/s <span class="text-danger">*</span></label>
                                    <select class="form-control m-select2 select2-hidden-accessible" id="m_select2_3" name="plan_id[]" multiple="" tabindex="-1" aria-hidden="true">
                                        <option value="">Please select plan</option>
                                        @forelse($deliveredPlans as $plan)
                                            <option value="{{$plan->id}}" <?php foreach ($deliveredPlansAssign as $assign){
                                                if ($plan->id == $assign->plan_id){
                                                    echo 'selected';
                                                }
                                            }?> >{{$plan->plan_name}}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    {!! $errors->first('Plan Assign_name', '<label class="error_txt_size">:message</label>') !!}
                                </div>
                                <div class="row">
                                    <label>Returned Plan Name/s <span class="text-danger">*</span></label>
                                    <select class="form-control m-select2 select2-hidden-accessible" id="m_select2_3" name="returned_plan_id[]" multiple="" tabindex="-1" aria-hidden="true">
                                        <option value="">Please select plan</option>
                                        @forelse($returnedPlans as $rPlan)
                                            <option value="{{$rPlan->id}}" <?php foreach ($returnedPlansAssign as $assign){
                                                if ($rPlan->id == $assign->plan_id){
                                                    echo 'selected';
                                                }
                                            }?> >{{$rPlan->plan_name}}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    {!! $errors->first('Plan Assign_name', '<label class="error_txt_size">:message</label>') !!}
                                </div>

                            </div>
                        </div>
                    </div><!--END LEFT COL -->
                </div>
            </div>
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions--solid">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offser-2 col-md-offset-2 col-sm-offset-2">
                            <div class="pull-left">
                                <button class="btn btn-default" tabindex="20">
                                    <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                                    <span class="text-success"> Save </span>
                                </button>
                                &nbsp;
                                <span class="btn btn-default reset-form" tabindex="20">
                                    <i class="fa fa-history text-info fa-lg" aria-hidden="true"></i>
                                    <span class="text-success"> Reset </span>
                                </span>
                                &nbsp;
                                <a href="{!! route('admin.merchants') !!}" class="btn btn-default" tabindex="20">
                                    {{-- <span class="glyphicon glyphicon-remove text-danger"></span>&nbsp; <span class="text-danger"> Cancel </span>--}}
                                    <i class="fa fa-remove text-danger fa-lg" aria-hidden="true"></i>&nbsp; <span class="text-danger"> Cancel </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
    <!--end::Form-->
    </div>
    <!--end::Portlet-->

    <script>
        //== Class definition
        var BootstrapSelect = function () {
            //== Private functions
            var demos = function () {
                // minimum setup
                $('.m_m_selectpicker').selectpicker();
                $('.m_m_selectpicker1').selectpicker();
                $('.m_m_selectpicker2').selectpicker();
                $('.m_m_selectpicker3').selectpicker();
                $('.m_m_selectpicker4').selectpicker();
                $('.m_m_selectpicker5').selectpicker();
            }
            return {
                // public functions
                init: function() {
                    demos();
                }
            };
        }();

        jQuery(document).ready(function() {
            BootstrapSelect.init();
        });

        //== Bootstrap select 2
        var Select2 = function() {
            //== Private functions
            var demos = function() {
                // basic
                $('#city_select').select2();
            }
            //== Public functions
            return {
                init: function() {
                    demos();
                }
            };
        }();

        //== Initialization
        jQuery(document).ready(function() {
            Select2.init();
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            $(".reset-form").click(function() {
                $(':input','#add-Plan').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            })
        });
        $(".selectCountry").change(function()
        {
            var country_id = $(this).val();
            if (country_id == '')
                return false;
            $.ajax
            ({
                type: "POST",
                url: "{!! route('admin.passenger.zone') !!}",
                data: {"_token": "{{ csrf_token() }}", "country_id": country_id},
                cache: false,
                beforeSend: function(){
                    $('#loader1').show();
                },
                complete: function(){
                    $('#loader1').hide();
                },
                success: function(html)
                {
                    $("#city_select").html(html);
                }
            });
        });
    </script>
    <script>
        //== Class definition
        var Select2 = function() {
            //== Private functions
            var demos = function() {


                // multi select
                $('#m_select2_3, #m_select2_3_validate').select2({
                    placeholder: "Select a plan",
                });





                // loading remote data

                function formatRepo(repo) {
                    if (repo.loading) return repo.text;
                    var markup = "<div class='select2-result-repository clearfix'>" +
                        "<div class='select2-result-repository__meta'>" +
                        "<div class='select2-result-repository__title'>" + repo.full_name + "</div>";
                    if (repo.description) {
                        markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
                    }
                    markup += "<div class='select2-result-repository__statistics'>" +
                        "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> " + repo.forks_count + " Forks</div>" +
                        "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> " + repo.stargazers_count + " Stars</div>" +
                        "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> " + repo.watchers_count + " Watchers</div>" +
                        "</div>" +
                        "</div></div>";
                    return markup;
                }

                function formatRepoSelection(repo) {
                    return repo.full_name || repo.text;
                }
            }

            var modalDemos = function() {
                $('#m_select2_modal').on('shown.bs.modal', function () {

                    // multi select
                    $('#m_select2_3_modal').select2({
                        placeholder: "Select a state",
                    });

                });
            }

            //== Public functions
            return {
                init: function() {
                    demos();
                    modalDemos();
                }
            };
        }();

        //== Initialization
        jQuery(document).ready(function() {
            Select2.init();
        });
    </script>
@stop