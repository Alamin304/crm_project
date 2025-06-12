@extends('layouts.app')
@section('title')
    {{ __('messages.bills_of_materials.view') }}
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.bills_of_materials.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <a href="{{ route('bills-of-materials.index') }}"
                   class="btn btn-primary form-btn">{{ __('messages.bills_of_materials.list') }}</a>
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
                                        <th>{{ __('messages.bills_of_materials.BOM_code') }}</th>
                                        <td>{{ $billsOfMaterial->BOM_code }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.bills_of_materials.product') }}</th>
                                        <td>{{ $billsOfMaterial->product }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.bills_of_materials.product_variant') }}</th>
                                        <td>{{ $billsOfMaterial->product_variant ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.bills_of_materials.quantity') }}</th>
                                        <td>{{ $billsOfMaterial->quantity }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.bills_of_materials.unit_of_measure') }}</th>
                                        <td>{{ $billsOfMaterial->unit_of_measure }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.bills_of_materials.routing') }}</th>
                                        <td>{{ $billsOfMaterial->routing ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.bills_of_materials.bom_type') }}</th>
                                        <td>{{ $billsOfMaterial->bom_type == 'manufacture' ? 'Manufacture this product' : 'Kit' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.bills_of_materials.manufacturing_readiness') }}</th>
                                        <td>{{ $billsOfMaterial->manufacturing_readiness ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.bills_of_materials.consumption') }}</th>
                                        <td>{{ $billsOfMaterial->consumption ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.bills_of_materials.description') }}</th>
                                        <td>{!! $billsOfMaterial->description ?? 'N/A' !!}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.bills_of_materials.created_at') }}</th>
                                        <td>{{ $billsOfMaterial->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.bills_of_materials.updated_at') }}</th>
                                        <td>{{ $billsOfMaterial->updated_at->format('Y-m-d H:i:s') }}</td>
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
