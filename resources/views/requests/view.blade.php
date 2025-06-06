@extends('layouts.app')
@section('title')
    {{ __('messages.requests.details') }}
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.requests.details') }}</h1>
            <div class="section-header-breadcrumb float-right">
                {{-- <a href="{{ route('requests.edit', $requestModel->id) }}"
                    class="btn btn-warning form-btn mr-2">{{ __('messages.common.edit') }}</a> --}}
                <a href="{{ route('requests.index') }}"
                    class="btn btn-primary form-btn">{{ __('messages.common.back') }}</a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>{{ __('messages.requests.title') }}</th>
                                        <td>{{ $requestModel->title }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.requests.assets') }}</th>
                                        <td>{{ $requestModel->assets }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.requests.checkout_for') }}</th>
                                        <td>{{ $requestModel->checkout_for }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.requests.status') }}</th>
                                        <td>{{ ucfirst($requestModel->status) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.requests.note') }}</th>
                                        <td>{!! $requestModel->note !!}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
