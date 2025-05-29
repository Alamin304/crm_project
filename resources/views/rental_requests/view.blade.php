@extends('layouts.app')

@section('title')
    {{ __('Rental Request Details') }}
@endsection

@section('page_css')
    <style>
        .detail-card {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .detail-header {
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            border-radius: 8px 8px 0 0;
        }

        .detail-body {
            padding: 20px;
        }

        .detail-row {
            display: flex;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
            width: 200px;
            flex-shrink: 0;
        }

        .detail-value {
            flex-grow: 1;
            color: #212529;
        }

        .address-box {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-top: 10px;
        }

        .address-line {
            margin-bottom: 5px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .badge-verified {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-regular {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .note-box {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-top: 10px;
            border-radius: 0 5px 5px 0;
        }

        .action-buttons {
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
@endsection

@section('content')
    <section class="section">
         <div class="section-header item-align-right">
             <h1>{{ __('Rental Request Details') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('rental_requests.index') }}" class="btn btn-primary form-btn">
                     {{ __('List') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card detail-card">
                <div class="detail-header">
                    <h5>Request Information</h5>
                </div>
                <div class="detail-body">
                    <div class="detail-row">
                        <div class="detail-label">Request Number:</div>
                        <div class="detail-value">{{ $rentalRequest->request_number }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Date Created:</div>
                        <div class="detail-value">{{ $rentalRequest->date_created->format('M d, Y H:i') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Status:</div>
                        <div class="detail-value">
                            <span
                                class="status-badge badge-{{ $rentalRequest->verification_status === 'verified' ? 'verified' : 'regular' }}">
                                {{ ucfirst($rentalRequest->verification_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card detail-card">
                <div class="detail-header">
                    <h5>Property Details</h5>
                </div>
                <div class="detail-body">
                    <div class="detail-row">
                        <div class="detail-label">Property Name:</div>
                        <div class="detail-value">{{ $rentalRequest->property_name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Customer:</div>
                        <div class="detail-value">{{ $rentalRequest->customer }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Property Price:</div>
                        <div class="detail-value">
                            {{ $rentalRequest->property_price ? '$' . number_format($rentalRequest->property_price, 2) : 'N/A' }}
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Contract Amount:</div>
                        <div class="detail-value">
                            {{ $rentalRequest->contract_amount ? '$' . number_format($rentalRequest->contract_amount, 2) : 'N/A' }}
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Term:</div>
                        <div class="detail-value">{{ $rentalRequest->term }} months</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Date Range:</div>
                        <div class="detail-value">
                            {{ $rentalRequest->start_date->format('M d, Y') }} to
                            {{ $rentalRequest->end_date->format('M d, Y') }}
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Property Inspected:</div>
                        <div class="detail-value">
                            {{ $rentalRequest->inspected_property ? 'Yes' : 'No' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card detail-card">
                <div class="detail-header">
                    <h5>Address Information</h5>
                </div>
                <div class="detail-body">
                    <div class="detail-row">
                        <div class="detail-label">Bill To:</div>
                        <div class="detail-value">
                            @if ($rentalRequest->bill_to)
                                @php $billTo = json_decode($rentalRequest->bill_to, true) @endphp
                                <div class="address-box">
                                    @if (!empty($billTo['street']))
                                        <div class="address-line">{{ $billTo['street'] }}</div>
                                    @endif
                                    @if (!empty($billTo['city']) || !empty($billTo['state']) || !empty($billTo['zip_code']))
                                        <div class="address-line">
                                            @if (!empty($billTo['city']))
                                                {{ $billTo['city'] }},
                                            @endif
                                            @if (!empty($billTo['state']))
                                                {{ $billTo['state'] }}
                                            @endif
                                            @if (!empty($billTo['zip_code']))
                                                {{ $billTo['zip_code'] }}
                                            @endif
                                        </div>
                                    @endif
                                    @if (!empty($billTo['country']))
                                        <div class="address-line">{{ $billTo['country'] }}</div>
                                    @endif
                                </div>
                            @else
                                Not specified
                            @endif
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Ship To:</div>
                        <div class="detail-value">
                            @if ($rentalRequest->ship_to)
                                @php $shipTo = json_decode($rentalRequest->ship_to, true) @endphp
                                <div class="address-box">
                                    @if (!empty($shipTo['street']))
                                        <div class="address-line">{{ $shipTo['street'] }}</div>
                                    @endif
                                    @if (!empty($shipTo['city']) || !empty($shipTo['state']) || !empty($shipTo['zip_code']))
                                        <div class="address-line">
                                            @if (!empty($shipTo['city']))
                                                {{ $shipTo['city'] }},
                                            @endif
                                            @if (!empty($shipTo['state']))
                                                {{ $shipTo['state'] }}
                                            @endif
                                            @if (!empty($shipTo['zip_code']))
                                                {{ $shipTo['zip_code'] }}
                                            @endif
                                        </div>
                                    @endif
                                    @if (!empty($shipTo['country']))
                                        <div class="address-line">{{ $shipTo['country'] }}</div>
                                    @endif
                                </div>
                            @else
                                Not specified
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card detail-card">
                <div class="detail-header">
                    <h5>Notes</h5>
                </div>
                <div class="detail-body">
                    <div class="detail-row">
                        <div class="detail-label">Client Note:</div>
                        <div class="detail-value">
                            @if ($rentalRequest->client_note)
                                <div class="note-box">{!! $rentalRequest->client_note !!}</div>
                            @else
                                No client note
                            @endif
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Admin Note:</div>
                        <div class="detail-value">
                            @if ($rentalRequest->admin_note)
                                <div class="note-box">{!! $rentalRequest->admin_note !!}</div>
                            @else
                                No admin note
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            {{-- <div class="action-buttons">
                <a href="{{ route('rental_requests.edit', $rentalRequest->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('rental_requests.index') }}" class="btn btn-light ml-2">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div> --}}
        </div>
    </section>
@endsection
