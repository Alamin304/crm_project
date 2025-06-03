@extends('layouts.app')
@section('title')
    {{ __('messages.second_assets.details') }}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.second_assets.details') }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('second-assets.edit', $secondAsset->id) }}"
                   class="btn btn-primary form-btn float-right">{{ __('second-assets.index') }}</a>
                
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            @include('second_assets.show_fields')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
