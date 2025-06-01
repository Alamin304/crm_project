@extends('layouts.app')

@section('title', __('File Management'))

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .folder-item,
        .file-item {
            transition: all 0.2s;
        }

        .folder-item:hover,
        .file-item:hover {
            background-color: #f8f9fa;
        }

        .breadcrumb {
            background-color: #f8f9fa;
            padding: 0.75rem 1rem;
            border-radius: 0.25rem;
        }

        .file-icon {
            width: 24px;
            height: 24px;
            margin-right: 8px;
        }

        .action-buttons .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
@endsection
@section('content')
    {{-- <div class="container-fluid">
        <div class="row">
            <!-- Left Sidebar: Sections -->
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Sections') }}</span>
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                            data-bs-target="#addSectionModal">+</button>
                    </div>
                    <ul class="list-group list-group-flush" id="sectionList">
                        @foreach ($sections as $section)
                            <li class="list-group-item section-item d-flex justify-content-between align-items-center"
                                data-id="{{ $section->id }}" style="cursor:pointer;">
                                <span>📁 {{ $section->name }}</span>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-danger delete-section"
                                        data-id="{{ $section->id }}" title="Delete Section">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Right Panel: Folders and Files -->
            <div class="col-md-9">
                <div id="sectionContent">
                    <div class="card shadow-sm">
                        <div class="card-body text-center text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3"></i>
                            <p>{{ __('Select a section to view its folders and files.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="container-fluid">
    <div class="row">
        <!-- Left Sidebar: Sections -->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Sections') }}</span>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addSectionModal">+</button>
                </div>
                <ul class="list-group list-group-flush" id="sectionList">
                    @foreach($sections as $section)
                        <li class="list-group-item section-item d-flex justify-content-between align-items-center" data-id="{{ $section->id }}" style="cursor:pointer;">
                            <div class="d-flex align-items-center">
                                <input type="checkbox" class="form-check-input me-2 item-checkbox" data-type="section" data-id="{{ $section->id }}">
                                <span>📁 {{ $section->name }}</span>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-secondary edit-section" data-id="{{ $section->id }}" data-name="{{ $section->name }}" title="Rename Section">
                                    <i class="fas fa-edit"></i>
                                </button>
                                {{-- <button class="btn btn-sm btn-outline-danger delete-section" data-id="{{ $section->id }}" title="Delete Section">
                                    <i class="fas fa-trash"></i>
                                </button> --}}
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Right Panel: Folders and Files -->
        <div class="col-md-9">
            <div class="card shadow-sm mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <button id="downloadSelectedBtn" class="btn btn-sm btn-primary" disabled>
                            <i class="fas fa-download"></i> {{ __('Download') }}
                        </button>
                        <button id="deleteSelectedBtn" class="btn btn-sm btn-primary" disabled>
                            <i class="fas fa-trash"></i> {{ __('Delete') }}
                        </button>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAllCheckbox">
                        <label class="form-check-label" for="selectAllCheckbox">{{ __('Select All') }}</label>
                    </div>
                </div>
            </div>

            <div id="sectionContent">
                <div class="card shadow-sm">
                    <div class="card-body text-center text-muted">
                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                        <p>{{ __('Select a section to view its folders and files.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Modals -->
    <!-- Add Section Modal -->
    <div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="addSectionForm" action="{{ route('file_management.store_section') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSectionModalLabel">{{ __('Add New Section') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('Close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="sectionName" class="form-label">{{ __('Section Name') }}</label>
                            <input type="text" name="name" id="sectionName" class="form-control"
                                placeholder="{{ __('Section Name') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Folder Modal -->
    <div class="modal fade" id="addFolderModal" tabindex="-1" aria-labelledby="addFolderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="addFolderForm" action="{{ route('file_management.store_item') }}" method="POST">
                @csrf
                <input type="hidden" name="file_section_id" id="folderSectionId">
                <input type="hidden" name="type" value="folder">
                <input type="hidden" name="parent_id" id="folderParentId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addFolderModalLabel">{{ __('Add New Folder') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('Close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="folderName" class="form-label">{{ __('Folder Name') }}</label>
                            <input type="text" name="name" id="folderName" class="form-control"
                                placeholder="{{ __('Folder Name') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Upload File Modal -->
    <div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="uploadFileForm" action="{{ route('file_management.store_item') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="file_section_id" id="fileSectionId">
                <input type="hidden" name="parent_id" id="fileFolderId">
                <input type="hidden" name="type" value="file">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadFileModalLabel">{{ __('Upload File') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('Close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="fileName" class="form-label">{{ __('File Name') }}</label>
                            <input type="text" name="name" id="fileName" class="form-control"
                                placeholder="{{ __('File Name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="fileUpload" class="form-label">{{ __('Select File') }}</label>
                            <input type="file" name="file" id="fileUpload" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Upload') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editItemForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" id="editItemType">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editItemModalLabel">{{ __('Rename Item') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('Close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editItemName" class="form-label">{{ __('Name') }}</label>
                            <input type="text" name="name" id="editItemName" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Add Edit Section Modal -->
<div class="modal fade" id="editSectionModal" tabindex="-1" aria-labelledby="editSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editSectionForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSectionModalLabel">{{ __('Rename Section') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editSectionName" class="form-label">{{ __('Section Name') }}</label>
                        <input type="text" name="name" id="editSectionName" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

    <!-- Move Item Modal -->
    <div class="modal fade" id="moveItemModal" tabindex="-1" aria-labelledby="moveItemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="moveItemForm" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="moveItemModalLabel">{{ __('Move Item') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('Close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="moveToFolder" class="form-label">{{ __('Select Destination Folder') }}</label>
                            <select name="parent_id" id="moveToFolder" class="form-select">
                                <option value="">Root (Top Level)</option>
                                <!-- Folders will be populated via JavaScript -->
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Move') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">{{ __('Confirm Deletion') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteConfirmationText">
                        {{ __('Are you sure you want to delete this item? This action cannot be undone.') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <form id="deleteItemForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const sectionItems = document.querySelectorAll('.section-item');
    const sectionContent = document.getElementById('sectionContent');
    const deleteSectionButtons = document.querySelectorAll('.delete-section');
    const downloadSelectedBtn = document.getElementById('downloadSelectedBtn');
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');

    // Modal elements
    const folderSectionIdInput = document.getElementById('folderSectionId');
    const folderParentIdInput = document.getElementById('folderParentId');
    const fileFolderIdInput = document.getElementById('fileFolderId');
    const fileSectionIdInput = document.getElementById('fileSectionId');

    // Load section content when a section is clicked
    sectionItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // Don't trigger if delete button was clicked
            if (e.target.closest('.delete-section') || e.target.closest('.edit-section') || e.target.closest('.item-checkbox')) return;

            const sectionId = this.getAttribute('data-id');
            loadSectionContent(sectionId);
        });
    });

    // Delete section buttons
    deleteSectionButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const sectionId = this.getAttribute('data-id');
            confirmDeleteSection(sectionId);
        });
    });

    // Edit section buttons
    document.querySelectorAll('.edit-section').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const sectionId = this.getAttribute('data-id');
            const sectionName = this.getAttribute('data-name');

            document.getElementById('editSectionName').value = sectionName;
            document.getElementById('editSectionForm').action = `{{ url('file_management/section') }}/${sectionId}`;

            new bootstrap.Modal(document.getElementById('editSectionModal')).show();
        });
    });

    // Select all checkbox
    selectAllCheckbox?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.item-checkbox:not(:disabled)');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedActions();
    });

    // Download selected items
    downloadSelectedBtn?.addEventListener('click', function() {
        const selectedItems = getSelectedItems();
        if (selectedItems.length === 0) return;

        const sectionId = folderSectionIdInput.value || fileSectionIdInput.value;

        // Show loading indicator
        const downloadBtn = this;
        const originalText = downloadBtn.innerHTML;
        downloadBtn.disabled = true;
        downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Preparing...';

        // Prepare the download request
        fetch(`{{ url('file_management/download-selected') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                items: selectedItems,
                section_id: sectionId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Download failed');
            }
            return response.blob();
        })
        .then(blob => {
            // Create download link
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;

            // Set filename based on what's being downloaded
            let filename = 'download.zip';
            if (selectedItems.length === 1) {
                const item = selectedItems[0];
                if (item.type === 'section') {
                    const sectionName = document.querySelector(`.section-item[data-id="${item.id}"] span`)?.textContent.trim() || 'section';
                    filename = `${sectionName}.zip`;
                } else {
                    const itemElement = document.querySelector(`[data-item-id="${item.id}"]`);
                    if (itemElement) {
                        const itemName = itemElement.getAttribute('data-item-name') || 'item';
                        filename = item.type === 'folder' ? `${itemName}.zip` : itemName;
                    }
                }
            } else {
                filename = `selected_items_${new Date().toISOString().slice(0,10)}.zip`;
            }

            a.download = filename;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            a.remove();
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Download Failed',
                text: error.message || 'An error occurred while preparing the download.'
            });
        })
        .finally(() => {
            downloadBtn.disabled = false;
            downloadBtn.innerHTML = originalText;
        });
    });

    // Delete selected items
    deleteSelectedBtn?.addEventListener('click', function() {
        const selectedItems = getSelectedItems();
        if (selectedItems.length === 0) return;

        // Confirmation dialog
        Swal.fire({
            title: 'Confirm Deletion',
            html: `Are you sure you want to delete ${selectedItems.length} selected item(s)?<br><br>
                  <ul class="text-start">${selectedItems.map(item => `<li>${item.name}</li>`).join('')}</ul>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const deleteBtn = this;
                const originalText = deleteBtn.innerHTML;
                deleteBtn.disabled = true;
                deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';

                // Prepare the delete request
                fetch(`{{ url('file_management/delete-selected') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        items: selectedItems
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message || 'Selected items have been deleted.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Reload the current view
                            const sectionId = folderSectionIdInput.value || fileSectionIdInput.value;
                            if (sectionId) {
                                loadSectionContent(sectionId);
                            } else {
                                window.location.reload();
                            }
                        });
                    } else {
                        throw new Error(data.message || 'Deletion failed');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'An error occurred while deleting items.'
                    });
                })
                .finally(() => {
                    deleteBtn.disabled = false;
                    deleteBtn.innerHTML = originalText;
                });
            }
        });
    });

    // Function to load section content
    function loadSectionContent(sectionId) {
        folderSectionIdInput.value = sectionId;
        fileSectionIdInput.value = sectionId;
        sectionContent.innerHTML = '<div class="text-center my-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading...</p></div>';

        fetch(`{{ url('file_management/folder-list') }}/${sectionId}`)
            .then(response => response.text())
            .then(html => {
                sectionContent.innerHTML = updateContentWithCheckboxes(html);
                setupEventListeners();
            })
            .catch(error => {
                console.error('Error:', error);
                sectionContent.innerHTML = '<div class="alert alert-danger">Failed to load content. Please try again.</div>';
            });
    }

    // Setup event listeners for dynamically loaded content
    function setupEventListeners() {
        // Add folder buttons
        document.querySelectorAll('.add-folder-btn').forEach(button => {
            button.addEventListener('click', function() {
                const sectionId = this.getAttribute('data-section-id');
                const parentId = this.getAttribute('data-parent-id') || '';
                folderSectionIdInput.value = sectionId;
                folderParentIdInput.value = parentId;
                new bootstrap.Modal(document.getElementById('addFolderModal')).show();
            });
        });

        // Upload file buttons
        document.querySelectorAll('.upload-file-btn').forEach(button => {
            button.addEventListener('click', function() {
                const folderId = this.getAttribute('data-folder-id');
                fileFolderIdInput.value = folderId;
                new bootstrap.Modal(document.getElementById('uploadFileModal')).show();
            });
        });

        // Edit buttons
        document.querySelectorAll('.edit-item-btn').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                const itemName = this.getAttribute('data-item-name');
                const itemType = this.getAttribute('data-item-type');
                const formAction = `{{ url('file_management/item') }}/${itemId}`;

                document.getElementById('editItemName').value = itemName;
                document.getElementById('editItemType').value = itemType;
                document.getElementById('editItemForm').action = formAction;

                new bootstrap.Modal(document.getElementById('editItemModal')).show();
            });
        });

        // Move buttons
        document.querySelectorAll('.move-item-btn').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                const sectionId = this.getAttribute('data-section-id');
                const currentParentId = this.getAttribute('data-parent-id') || '';
                const formAction = `{{ url('file_management/move') }}/${itemId}`;

                document.getElementById('moveItemForm').action = formAction;

                // Load available folders for this section
                fetch(`{{ url('file_management/folder-options') }}/${sectionId}?exclude=${itemId}`)
                    .then(response => response.json())
                    .then(data => {
                        const select = document.getElementById('moveToFolder');
                        select.innerHTML = '<option value="">Root (Top Level)</option>';

                        data.folders.forEach(folder => {
                            const option = document.createElement('option');
                            option.value = folder.id;
                            option.textContent = folder.name;
                            option.selected = folder.id == currentParentId;
                            select.appendChild(option);
                        });

                        new bootstrap.Modal(document.getElementById('moveItemModal')).show();
                    });
            });
        });

        // Delete buttons
        document.querySelectorAll('.delete-item-btn').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                const itemName = this.getAttribute('data-item-name');
                const itemType = this.getAttribute('data-item-type');
                const formAction = `{{ url('file_management/item') }}/${itemId}`;

                document.getElementById('deleteConfirmationText').textContent =
                    `Are you sure you want to delete this ${itemType} "${itemName}"? This action cannot be undone.`;
                document.getElementById('deleteItemForm').action = formAction;

                new bootstrap.Modal(document.getElementById('deleteConfirmationModal')).show();
            });
        });

        // Folder click to navigate into folder
        document.querySelectorAll('.folder-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const sectionId = this.getAttribute('data-section-id');
                const folderId = this.getAttribute('data-folder-id');
                loadFolderContent(sectionId, folderId);
            });
        });

        // Breadcrumb navigation
        document.querySelectorAll('.breadcrumb-folder').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const sectionId = this.getAttribute('data-section-id');
                const folderId = this.getAttribute('data-folder-id') || '';
                if (folderId) {
                    loadFolderContent(sectionId, folderId);
                } else {
                    loadSectionContent(sectionId);
                }
            });
        });

        // Checkbox handling
        document.querySelectorAll('.item-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectedActions();
            });
        });

        // Download folder buttons
        document.querySelectorAll('.download-folder-btn').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                const itemType = 'folder';
                const sectionId = folderSectionIdInput.value || fileSectionIdInput.value;

                // Simulate clicking the checkbox and download button
                const checkbox = document.querySelector(`.item-checkbox[data-id="${itemId}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                    updateSelectedActions();
                    downloadSelectedBtn.click();
                }
            });
        });
    }

    // Function to load folder content
    function loadFolderContent(sectionId, folderId) {
        sectionContent.innerHTML = '<div class="text-center my-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading...</p></div>';

        fetch(`{{ url('file_management/folder-list') }}/${sectionId}?folder_id=${folderId}`)
            .then(response => response.text())
            .then(html => {
                sectionContent.innerHTML = updateContentWithCheckboxes(html);
                setupEventListeners();
            })
            .catch(error => {
                console.error('Error:', error);
                sectionContent.innerHTML = '<div class="alert alert-danger">Failed to load content. Please try again.</div>';
            });
    }

    // Function to confirm section deletion
    function confirmDeleteSection(sectionId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this! All folders and files in this section will be deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('file_management/section') }}/${sectionId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Deleted!',
                                'The section has been deleted.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Failed to delete section.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error!',
                            'An error occurred while deleting the section.',
                            'error'
                        );
                    });
            }
        });
    }

    // Form submission handling with feedback
    const forms = ['addSectionForm', 'addFolderForm', 'uploadFileForm', 'editItemForm', 'moveItemForm', 'editSectionForm'];
    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = formId === 'uploadFileForm' ? new FormData(form) : new FormData(form);
                const submitButton = form.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;

                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

                fetch(form.action, {
                        method: form.method,
                        body: formData,
                        headers: formId === 'uploadFileForm' ? {} : {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                throw err;
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Close the modal
                            const modal = bootstrap.Modal.getInstance(form.closest('.modal'));
                            modal.hide();

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message || 'Operation completed successfully.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // Reload the appropriate content
                                if (formId === 'addSectionForm' || formId === 'editSectionForm') {
                                    window.location.reload();
                                } else {
                                    const sectionId = folderSectionIdInput.value || fileSectionIdInput.value;
                                    if (sectionId) {
                                        loadSectionContent(sectionId);
                                    }
                                }
                            });
                        } else {
                            throw new Error(data.message || 'Operation failed');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error.message || 'An error occurred. Please try again.'
                        });
                    })
                    .finally(() => {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    });
            });
        }
    });

    // Delete form handling
    const deleteForm = document.getElementById('deleteItemForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitButton = deleteForm.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';

            fetch(deleteForm.action, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Close the modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                        modal.hide();

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message || 'Item deleted successfully.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Reload the appropriate content
                            const sectionId = folderSectionIdInput.value || fileSectionIdInput.value;
                            if (sectionId) {
                                loadSectionContent(sectionId);
                            }
                        });
                    } else {
                        throw new Error(data.message || 'Deletion failed');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: error.message || 'An error occurred while deleting. Please try again.'
                    });
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                });
        });
    }

    // Helper function to update content with checkboxes
    function updateContentWithCheckboxes(html) {
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;

        // Add checkboxes to items
        tempDiv.querySelectorAll('.folder-item, .file-item').forEach(row => {
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'form-check-input me-2 item-checkbox';
            checkbox.setAttribute('data-type', row.classList.contains('folder-item') ? 'folder' : 'file');
            checkbox.setAttribute('data-id', row.querySelector('.edit-item-btn')?.getAttribute('data-item-id') || '');

            const firstTd = row.querySelector('td:first-child');
            if (firstTd) {
                firstTd.insertBefore(checkbox, firstTd.firstChild);
            }
        });

        return tempDiv.innerHTML;
    }

    // Helper function to get selected items
    function getSelectedItems() {
        return Array.from(document.querySelectorAll('.item-checkbox:checked')).map(checkbox => {
            return {
                id: checkbox.getAttribute('data-id'),
                type: checkbox.getAttribute('data-type'),
                name: checkbox.closest('.section-item')
                    ? checkbox.closest('.section-item').querySelector('span').textContent.trim()
                    : checkbox.closest('tr').querySelector('td:nth-child(2)').textContent.trim()
            };
        });
    }

    // Helper function to update action buttons based on selection
    function updateSelectedActions() {
        const selectedCount = document.querySelectorAll('.item-checkbox:checked').length;
        if (downloadSelectedBtn) downloadSelectedBtn.disabled = selectedCount === 0;
        if (deleteSelectedBtn) deleteSelectedBtn.disabled = selectedCount === 0;
        if (selectAllCheckbox) {
            const allCheckboxes = document.querySelectorAll('.item-checkbox:not(:disabled)');
            selectAllCheckbox.checked = selectedCount > 0 && selectedCount === allCheckboxes.length;
        }
    }
});
</script>

