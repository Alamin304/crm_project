@extends('layouts.app')
@section('title')
    {{ __('messages.routings.routings') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-end mb-5">
                        <h1>{{ __('messages.routings.routings') }}</h1>
                    </div>
                    <div class="w-100">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>{{ __('messages.routings.routing_code') }}</th>
                                    <th>{{ __('messages.routings.routing_name') }}</th>
                                    <th>{{ __('messages.routings.note') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($routings as $routing)
                                    <tr>
                                        <td>{{ $routing->routing_code }}</td>
                                        <td>{{ $routing->routing_name }}</td>
                                        <td>{!! $routing->note !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
