@extends('layouts.app')
@section('title')
    {{ __('messages.bills_of_materials.bills_of_materials') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0 font-size-18">{{ __('messages.bills_of_materials.bills_of_materials') }}</h4>
                        <div class="page-title-right">
                            <button class="btn btn-primary" onclick="window.print();">{{ __('Print') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.bills_of_materials.BOM_code') }}</th>
                                            <th>{{ __('messages.bills_of_materials.product') }}</th>
                                            <th>{{ __('messages.bills_of_materials.quantity') }}</th>
                                            <th>{{ __('messages.bills_of_materials.unit_of_measure') }}</th>
                                            <th>{{ __('messages.bills_of_materials.bom_type') }}</th>
                                            <th>{{ __('messages.common.created_at') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($boms as $bom)
                                            <tr>
                                                <td>{{ $bom->BOM_code }}</td>
                                                <td>{{ $bom->product }}</td>
                                                <td>{{ $bom->quantity }}</td>
                                                <td>{{ $bom->unit_of_measure }}</td>
                                                <td>{{ $bom->bom_type == 'manufacture' ? 'Manufacture' : 'Kit' }}</td>
                                                <td>{{ $bom->created_at->format('Y-m-d') }}</td>
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
    </div>
@endsection
