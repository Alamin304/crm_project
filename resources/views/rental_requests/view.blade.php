@extends('layouts.app')

@section('title')
    {{ __('messages.real_estate_agents.view') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .profile-image-view {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #eee;
            margin-bottom: 20px;
        }

        .social-icon {
            font-size: 18px;
            margin-right: 8px;
            color: #6c757d;
        }

        .social-icon:hover {
            color: #007bff;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
        }

        .info-value {
            color: #212529;
            word-break: break-word;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.property_owners.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('real_estate_agents.index') }}"
                    class="btn btn-primary form-btn">{{ __('messages.real_estate_agents.list') }}</a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-center mb-4">
                           @if ($realEstateAgent->profile_image)
                                <img id="profileImagePreview" src="{{ asset('uploads/' . $realEstateAgent->profile_image) }}"
                                    class="profile-image-preview" alt="Profile Image">
                                <div class="current-image-text">Current Image</div>
                            @else
                                <img id="profileImagePreview" src="{{ asset('assets/img/default-user.png') }}"
                                    class="profile-image-preview" alt="Default Image">
                            @endif
                            <h3 class="mt-2">{{ $realEstateAgent->owner_name }}</h3>
                            <p class="text-muted">{{ $realEstateAgent->code }}</p>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="info-label">{{ __('messages.real_estate_agents.email') }}:</span>
                                <p class="info-value">{{ $realEstateAgent->email ?? '-' }}</p>
                            </div>
                            <div class="form-group">
                                <span class="info-label">{{ __('messages.real_estate_agents.phone') }}:</span>
                                <p class="info-value">{{ $realEstateAgent->phone_number }}</p>
                            </div>
                            <div class="form-group">
                                <span class="info-label">{{ __('messages.real_estate_agents.address') }}:</span>
                                <p class="info-value">{{ $realEstateAgent->address }}</p>
                            </div>
                            <div class="form-group">
                                <span class="info-label">{{ __('messages.real_estate_agents.city') }}:</span>
                                <p class="info-value">{{ $realEstateAgent->city }}</p>
                            </div>
                            <div class="form-group">
                                <span class="info-label">{{ __('messages.real_estate_agents.state') }}:</span>
                                <p class="info-value">{{ $realEstateAgent->state ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="info-label">{{ __('messages.real_estate_agents.zip_code') }}:</span>
                                <p class="info-value">{{ $realEstateAgent->zip_code ?? '-' }}</p>
                            </div>
                            <div class="form-group">
                                <span class="info-label">{{ __('messages.real_estate_agents.country') }}:</span>
                                <p class="info-value">{{ $realEstateAgent->country ?? '-' }}</p>
                            </div>
                            <div class="form-group">
                                <span class="info-label">{{ __('messages.real_estate_agents.vat_number') }}:</span>
                                <p class="info-value">{{ $realEstateAgent->vat_number ?? '-' }}</p>
                            </div>
                            <div class="form-group">
                                <span class="info-label">{{ __('messages.real_estate_agents.status') }}:</span>
                                <p class="info-value">
                                    @if ($realEstateAgent->is_active)
                                        <span class="badge badge-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('Inactive') }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="form-group">
                                <span class="info-label">{{ __('messages.real_estate_agents.created_at') }}:</span>
                                <p class="info-value">{{ $realEstateAgent->created_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="form-group">
                                <span class="info-label">{{ __('Social Links') }}:</span>
                                <div class="d-flex mt-2">
                                    @if ($realEstateAgent->website)
                                        <a href="{{ $realEstateAgent->website }}" target="_blank" class="social-icon"
                                            title="Website">
                                            <i class="fas fa-globe"></i>
                                        </a>
                                    @endif
                                    @if ($realEstateAgent->facebook_url)
                                        <a href="{{ $realEstateAgent->facebook_url }}" target="_blank" class="social-icon"
                                            title="Facebook">
                                            <i class="fab fa-facebook"></i>
                                        </a>
                                    @endif
                                    @if ($realEstateAgent->whatsapp_url)
                                        <a href="{{ $realEstateAgent->whatsapp_url }}" target="_blank" class="social-icon"
                                            title="WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    @endif
                                    @if ($realEstateAgent->instagram_url)
                                        <a href="{{ $realEstateAgent->instagram_url }}" target="_blank" class="social-icon"
                                            title="Instagram">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    @endif
                                    @if (
                                        !$realEstateAgent->website &&
                                            !$realEstateAgent->facebook_url &&
                                            !$realEstateAgent->whatsapp_url &&
                                            !$realEstateAgent->instagram_url)
                                        <p class="info-value">-</p>
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

@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection
