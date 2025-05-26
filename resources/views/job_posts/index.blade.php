@extends('layouts.app')
@section('title')
    {{ __('messages.job_posts.job_posts') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.job_posts.job_posts') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>
            <div class="float-right d-flex">
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('messages.job_posts.export') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('job-posts.export', ['format' => 'pdf']) }}">
                            {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('job-posts.export', ['format' => 'csv']) }}">
                            {{ __('messages.common.csv') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('job-posts.export', ['format' => 'xlsx']) }}">
                            {{ __('messages.common.excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('job-posts.export', ['format' => 'print']) }}"
                            target="_blank">
                            {{ __('messages.common.print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <div class="float-right">
                    <a href="{{ route('job-posts.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.job_posts.add') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @if (session()->has('flash_notification'))
                        @foreach (session('flash_notification') as $message)
                            <div class="alert alert-{{ $message['level'] }}">
                                {{ $message['message'] }}
                            </div>
                        @endforeach
                    @endif
                    @include('job_posts.table')
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

@section('scripts')
    <script>
        'use strict';

        let jobPostTable = $('#jobPostTable').DataTable({
            oLanguage: {
                'sEmptyTable': Lang.get('messages.common.no_data_available_in_table'),
                'sInfo': Lang.get('messages.common.data_base_entries'),
                sLengthMenu: Lang.get('messages.common.menu_entry'),
                sInfoEmpty: Lang.get('messages.common.no_entry'),
                sInfoFiltered: Lang.get('messages.common.filter_by'),
                sZeroRecords: Lang.get('messages.common.no_matching'),
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: route('job-posts.index'),
            },
            columns: [{
                    data: function(row) {
                        return `
                            <div class="d-flex flex-column">
                                <span class="font-weight-bold">${row.job_title}</span>
                                <small class="text-muted">${row.category.name}</small>
                                <small>${row.no_of_vacancy} {{ __('messages.job_posts.vacancies') }}</small>
                            </div>
                        `;
                    },
                    name: 'job_title',
                    orderable: false,
                },
                {
                    data: 'company_name',
                    name: 'company_name',
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: function(data) {
                        return moment(data).format('YYYY-MM-DD');
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data) {
                        return data ?
                            '<span class="badge badge-success">{{ __('messages.job_posts.published') }}</span>' :
                            '<span class="badge badge-warning">{{ __('messages.job_posts.pending') }}</span>';
                    }
                },
                {
                    data: 'date_of_closing',
                    name: 'date_of_closing',
                    render: function(data) {
                        return moment(data).format('YYYY-MM-DD');
                    }
                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    orderable: false,
                    searchable: false,
                }
            ],
            responsive: true,
            order: [
                [2, 'desc']
            ] // Default sort by posting date (created_at)
        });

        $(document).on('click', '.edit-btn', function(event) {
            let jobPostId = $(event.currentTarget).data('id');
            const url = route('job-posts.edit', jobPostId);
            window.location.href = url;
        });

        $(document).on('click', '.delete-btn', function(event) {
            let jobPostId = $(event.currentTarget).data('id');
            deleteItem(route('job-posts.destroy', jobPostId), '#jobPostTable',
                '{{ __('messages.job_posts.job_posts') }}');
        });

        function renderActionButtons(id) {
            return `
                <a title="{{ __('messages.common.edit') }}" href="${route('job-posts.edit', id)}"
                   class="btn btn-warning action-btn has-icon edit-btn" style="float:right;margin:2px;">
                    <i class="fa fa-edit"></i>
                </a>
                <a title="{{ __('messages.common.view') }}" href="${route('job-posts.view', id)}"
                   class="btn btn-info action-btn has-icon view-btn" style="float:right;margin:2px;">
                    <i class="fa fa-eye"></i>
                </a>
                <a title="{{ __('messages.common.delete') }}" href="#"
                   class="btn btn-danger action-btn has-icon delete-btn" data-id="${id}" style="float:right;margin:2px;">
                    <i class="fa fa-trash"></i>
                </a>
            `;
        }
    </script>
@endsection
