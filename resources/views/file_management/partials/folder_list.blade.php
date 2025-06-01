<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="#" class="breadcrumb-folder" data-section-id="{{ $section->id }}">
                        {{ $section->name }}
                    </a>
                </li>
                @if (isset($currentFolder) && $currentFolder)
                    @foreach ($currentFolder->getBreadcrumbs() as $breadcrumb)
                        <li class="breadcrumb-item">
                            <a href="#" class="breadcrumb-folder" data-section-id="{{ $section->id }}"
                                data-folder-id="{{ $breadcrumb->id }}">
                                {{ $breadcrumb->name }}
                            </a>
                        </li>
                    @endforeach
                    <li class="breadcrumb-item active" aria-current="page">{{ $currentFolder->name }}</li>
                @endif
            </ol>
        </nav>
        <div class="btn-group">
            <button class="btn btn-sm btn-primary me-1 px-2 py-1 add-folder-btn" data-section-id="{{ $section->id }}"
                data-parent-id="{{ $currentFolder->id ?? '' }}">
                <i class="fas fa-folder-plus"></i> {{ __('Add Folder') }}
            </button>
            <button class="btn btn-sm btn-primary px-2 py-1 upload-file-btn" data-section-id="{{ $section->id }}"
                data-folder-id="{{ $currentFolder->id ?? '' }}">
                <i class="fas fa-upload"></i> {{ __('Upload File') }}
            </button>
        </div>

    </div>

    <div class="card-body">
        @if($items->isEmpty())
            <div class="text-center text-muted py-4">
                <i class="fas fa-folder-open fa-3x mb-3"></i>
                <p>{{ __('This folder is empty.') }}</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%"></th>
                            <th width="35%">{{ __('Name') }}</th>
                            <th width="20%">{{ __('Type') }}</th>
                            <th width="20%">{{ __('Date') }}</th>
                            <th width="20%">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr class="{{ $item->type === 'folder' ? 'folder-item' : 'file-item' }}">
                                <td>
                                    <input type="checkbox" class="form-check-input item-checkbox"
                                           data-type="{{ $item->type }}"
                                           data-id="{{ $item->id }}">
                                </td>
                                <td>
                                    @if($item->type === 'folder')
                                        <a href="#" class="folder-link text-decoration-none"
                                           data-section-id="{{ $section->id }}"
                                           data-folder-id="{{ $item->id }}">
                                            <i class="fas fa-folder text-warning"></i>
                                            {{ $item->name }}
                                        </a>
                                    @else
                                        <i class="fas fa-file text-primary"></i>
                                        {{ $item->name }}
                                    @endif
                                </td>
                                <td>{{ $item->type === 'folder' ? __('Folder') : __('File') }}</td>
                                <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                <td class="action-buttons">
                                    <div class="btn-group btn-group-sm">
                                        @if($item->type === 'file')
                                            <a href="{{ route('file_management.download', $item->id) }}"
                                               class="btn btn-outline-primary"
                                               title="{{ __('Download') }}">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-outline-primary download-folder-btn"
                                                    data-item-id="{{ $item->id }}"
                                                    title="{{ __('Download as ZIP') }}">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-outline-secondary edit-item-btn"
                                                data-item-id="{{ $item->id }}"
                                                data-item-name="{{ $item->name }}"
                                                data-item-type="{{ $item->type }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary move-item-btn"
                                                data-item-id="{{ $item->id }}"
                                                data-section-id="{{ $section->id }}"
                                                data-parent-id="{{ $item->parent_id }}">
                                            <i class="fas fa-arrows-alt"></i>
                                        </button>
                                        <button class="btn btn-outline-danger delete-item-btn"
                                                data-item-id="{{ $item->id }}"
                                                data-item-name="{{ $item->name }}"
                                                data-item-type="{{ $item->type }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
