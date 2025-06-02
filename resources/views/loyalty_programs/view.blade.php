@extends('layouts.app')

@section('title')
    {{ __('messages.loyalty_programs.view') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.loyalty_programs.view') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('loyalty-programs.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.loyalty_programs.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            {{ Form::label('name', __('messages.loyalty_programs.name')) }}
                            <p>{{ $loyaltyProgram->name }}</p>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('customer_group', __('messages.loyalty_programs.customer_group')) }}
                            <p>{{ $loyaltyProgram->customer_group }}</p>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('customer', __('messages.loyalty_programs.customer')) }}
                            <p>{{ $loyaltyProgram->customer }}</p>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('start_date', __('messages.loyalty_programs.start_date')) }}
                            <p>{{ \Carbon\Carbon::parse($loyaltyProgram->start_date)->format('Y-m-d') }}</p>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('end_date', __('messages.loyalty_programs.end_date')) }}
                            <p>{{ \Carbon\Carbon::parse($loyaltyProgram->end_date)->format('Y-m-d') }}</p>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('rule_base', __('messages.loyalty_programs.rule_base')) }}
                            <p>{{ $loyaltyProgram->rule_base }}</p>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('minimum_purchase', __('messages.loyalty_programs.minimum_purchase')) }}
                            <p>{{ number_format($loyaltyProgram->minimum_purchase, 2) }}</p>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('account_creation_point', __('messages.loyalty_programs.account_creation_point')) }}
                            <p>{{ $loyaltyProgram->account_creation_point }}</p>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('birthday_point', __('messages.loyalty_programs.birthday_point')) }}
                            <p>{{ $loyaltyProgram->birthday_point }}</p>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('redeem_type', __('messages.loyalty_programs.redeem_type')) }}
                            <p>{{ ucfirst($loyaltyProgram->redeem_type) }}</p>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('minimum_point_to_redeem', __('messages.loyalty_programs.minimum_point_to_redeem')) }}
                            <p>{{ $loyaltyProgram->minimum_point_to_redeem }}</p>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('max_amount_receive', __('messages.loyalty_programs.max_amount_receive')) }}
                            <p>{{ number_format($loyaltyProgram->max_amount_receive, 2) }}</p>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('redeem_in_portal', __('messages.loyalty_programs.redeem_in_portal')) }}
                            <p>{{ $loyaltyProgram->redeem_in_portal ? __('Yes') : __('No') }}</p>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('redeem_in_pos', __('messages.loyalty_programs.redeem_in_pos')) }}
                            <p>{{ $loyaltyProgram->redeem_in_pos ? __('Yes') : __('No') }}</p>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('status', __('messages.common.status')) }}
                            <p>
                                <span
                                    class="badge badge-{{ $loyaltyProgram->status === 'enabled' ? 'success' : 'danger' }}">
                                    {{ ucfirst($loyaltyProgram->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="form-group col-md-12">
                            {{ Form::label('description', __('messages.loyalty_programs.description')) }}
                            <p>{!! $loyaltyProgram->description !!}</p>
                        </div>

                        @if (!empty($loyaltyProgram->rules) && is_array($loyaltyProgram->rules))
                            <div class="form-group col-md-12">
                                {{ Form::label('rules', __('messages.loyalty_programs.rules')) }}
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Rule Name</th>
                                                <th>Point From</th>
                                                <th>Point To</th>
                                                <th>Point Weight</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($loyaltyProgram->rules as $rule)
                                                <tr>
                                                    <td>{{ $rule['rule_name'] ?? '-' }}</td>
                                                    <td>{{ $rule['point_from'] ?? '-' }}</td>
                                                    <td>{{ $rule['point_to'] ?? '-' }}</td>
                                                    <td>{{ $rule['point_weight'] ?? '-' }}</td>
                                                    <td>
                                                        @if (isset($rule['rule_status']))
                                                            <span
                                                                class="badge badge-{{ $rule['rule_status'] == 'enabled' ? 'success' : 'secondary' }}">
                                                                {{ ucfirst($rule['rule_status']) }}
                                                            </span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection

@section('scripts')
    <script src="{{ mix('assets/js/custom/input-price-format.js') }}"></script>
@endsection
