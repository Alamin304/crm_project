@extends('layouts.app')

@section('title')
    {{ __('messages.asset_maintenance.asset_maintenance_details') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.asset_maintenance.asset_maintenance_details') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('asset-maintenances.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.asset_maintenance.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('asset', __('messages.asset_maintenance.asset')) }}
                            <p>{{ $assetMaintenance->asset->name ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('supplier', __('messages.asset_maintenance.supplier')) }}
                            <p>{{ $assetMaintenance->supplier ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('maintenance_type', __('messages.asset_maintenance.maintenance_type')) }}
                            <p>{{ $assetMaintenance->maintenance_type }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('title', __('messages.asset_maintenance.title')) }}
                            <p>{{ $assetMaintenance->title }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('start_date', __('messages.asset_maintenance.start_date')) }}
                            <p>{{ \Carbon\Carbon::parse($assetMaintenance->start_date)->format('Y-m-d') }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('completion_date', __('messages.asset_maintenance.completion_date')) }}
                            <p>{{ $assetMaintenance->completion_date ? \Carbon\Carbon::parse($assetMaintenance->completion_date)->format('Y-m-d') : 'N/A' }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('warranty_improvement', __('messages.asset_maintenance.warranty_improvement')) }}
                            <p>{{ $assetMaintenance->warranty_improvement ? __('Yes') : __('No') }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('cost', __('messages.asset_maintenance.cost')) }}
                            <p>{{ number_format($assetMaintenance->cost, 2) }}</p>
                        </div>
                        <div class="form-group col-sm-12">
                            {{ Form::label('notes', __('messages.asset_maintenance.notes')) }}
                            <p>{{ $assetMaintenance->notes ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
@endsection

@section('scripts')
    <script src="{{ mix('assets/js/custom/input-price-format.js') }}"></script>
@endsection
