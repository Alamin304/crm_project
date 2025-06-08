@extends('layouts.app')
@section('title')
    {{ __('messages.location.details') }}
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.location.details') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-primary form-btn">
                    {{ __('messages.common.edit') }}
                </a>
                <a href="{{ route('locations.index') }}" class="btn btn-primary form-btn ml-2">
                    {{ __('messages.common.back') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.location.location_name') }}:</label>
                                <p>{{ $location->location_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.location.parent') }}:</label>
                                <p>{{ $location->parent ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.location.manager') }}:</label>
                                <p>{{ $location->manager ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.location.location_currency') }}:</label>
                                <p>{{ $location->location_currency ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('messages.location.address') }}:</label>
                                <p>{{ $location->address ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('messages.location.city') }}:</label>
                                <p>{{ $location->city ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('messages.location.state') }}:</label>
                                <p>{{ $location->state ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('messages.location.country') }}:</label>
                                <p>{{ $location->country ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.location.zip_code') }}:</label>
                                <p>{{ $location->zip_code ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.location.image') }}:</label>
                                <div>
                                    @if($location->image)
                                        <img src="{{ asset('storage/'.$location->image) }}" width="150" class="img-thumbnail">
                                    @else
                                        <p>N/A</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
