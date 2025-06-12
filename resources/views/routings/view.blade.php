@extends('layouts.app')
@section('title')
    {{ __('messages.routings.view') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.routings.view') }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('routings.index') }}"
                   class="btn btn-primary form-btn float-right">
                    {{ __('messages.routings.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <h5>{{ __('messages.routings.routing_code') }}</h5>
                            <p>{{ $routing->routing_code }}</p>
                        </div>
                        <div class="form-group col-sm-12">
                            <h5>{{ __('messages.routings.routing_name') }}</h5>
                            <p>{{ $routing->routing_name }}</p>
                        </div>
                        <div class="form-group col-sm-12">
                            <h5>{{ __('messages.routings.note') }}</h5>
                            <p>{!! $routing->note !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
