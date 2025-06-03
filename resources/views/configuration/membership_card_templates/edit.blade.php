<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($template)) @method('PUT') @endif

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Template Name</label>
                <input type="text" class="form-control" id="name" name="name"
                       value="{{ old('name', $template->name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="image">Template Image</label>
                <input type="file" class="form-control-file" id="image" name="image" {{ !isset($template) ? 'required' : '' }}>
                @if(isset($template) && $template->image_path)
                    <small class="form-text text-muted">
                        Current image: <a href="{{ asset('storage/'.$template->image_path) }}" target="_blank">View</a>
                    </small>
                @endif
            </div>

            <div class="form-group">
                <label for="text_color">Text Color</label>
                <input type="color" class="form-control" id="text_color" name="text_color"
                       value="{{ old('text_color', $template->text_color ?? '#000000') }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Display Options</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="show_subject_card"
                                   name="show_subject_card" value="1" {{ old('show_subject_card', $template->show_subject_card ?? false) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="show_subject_card">Show Subject Card</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="show_company_name"
                                   name="show_company_name" value="1" {{ old('show_company_name', $template->show_company_name ?? false) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="show_company_name">Show Company Name</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="show_client_name"
                                   name="show_client_name" value="1" {{ old('show_client_name', $template->show_client_name ?? false) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="show_client_name">Show Client Name</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="show_member_since"
                                   name="show_member_since" value="1" {{ old('show_member_since', $template->show_member_since ?? false) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="show_member_since">Show Member Since</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="show_memberships"
                                   name="show_memberships" value="1" {{ old('show_memberships', $template->show_memberships ?? false) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="show_memberships">Show Memberships</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="show_custom_field"
                                   name="show_custom_field" value="1" {{ old('show_custom_field', $template->show_custom_field ?? false) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="show_custom_field">Show Custom Field</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Save Template</button>
            <a href="{{ route('configuration.membership-card-templates.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</form>
