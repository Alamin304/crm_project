<div class="btn-group">
    <a href="{{ route('configuration.membership-card-templates.edit', $template->id) }}" class="btn btn-sm btn-primary">
        <i class="fas fa-edit"></i>
    </a>
    <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $template->id }}">
        <i class="fas fa-trash"></i>
    </button>
</div>

<form id="delete-form-{{ $template->id }}" action="{{ route('configuration.membership-card-templates.destroy', $template->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.delete-btn').click(function() {
            if (confirm('Are you sure you want to delete this template?')) {
                $('#delete-form-' + $(this).data('id')).submit();
            }
        });
    });
</script>
@endpush
