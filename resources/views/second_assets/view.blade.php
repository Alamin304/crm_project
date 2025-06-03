@extends('layouts.app')
@section('title')
    {{ __('messages.assets.details') }}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.assets.details') }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-primary form-btn float-right">
                    {{ __('messages.common.edit') }}
                </a>
                <a href="{{ route('assets.index') }}" class="btn btn-primary form-btn float-right mr-2">
                    {{ __('messages.common.back') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('second_assets.show_fields')
                </div>
            </div>
        </div>
    </section>
@endsection
