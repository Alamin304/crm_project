
{{-- @section('content') --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <h4 class="card-title p-3">Membership Card</h4>
            <div class="section-header px-4">
                <div class="float-right">
                    <a href="{{ route('configuration.membership-card-templates.create') }}"
                        class="btn btn-primary btn-sm">
                        {{ __('messages.beds.add') }}
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="membership-card-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                {{-- <th>Added By</th> --}}
                                <th>Created At</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @endsection --}}

@push('scripts')
<script>
$(function () {
    $('#membership-card-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('configuration.membership-card-templates.index') }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            // { data: 'added_by', name: 'added_by' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ]
    });
});
</script>
@endpush
